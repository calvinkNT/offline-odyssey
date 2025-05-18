<meta http-equiv="cache-control" content="no-store" />
<meta http-equiv="expires" content="0" />
<meta http-equiv="pragma" content="no-cache" />
<meta name="robots" content="noindex">

<?php

// Check if the auth cookie is set
/*if (isset($_COOKIE["banned"])) {
    // Redirect to the desired page if the cookie is set
    echo "<script>window.location.href = 'https://jscdn.ct.ws/rick.mp4';</script>"; // Change the URL as needed
    exit();
}*/

// Choose encryption method (APR1-MD5 method, used by Apache's .htpasswd)
$encryption = 'md5';

// Path to the .htpasswd file
$htpasswd_path = dirname(__FILE__) . '/.htpasswd';

// Path to the keys.json file
$keys_json_path = dirname(__FILE__) . '/keys.json';

/**
 * Create random string for salt generation
 *
 * @param integer $length String length
 * @param string|null $alphabet Optional alphabet (default is the Apache safe character set)
 * @return string A random string
 */
function random_string($length = 8, $alphabet = './0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz')
{
    return substr(str_shuffle($alphabet), 0, $length);
}

/**
 * Encrypt password using APR1-MD5 method
 *
 * @param string $password The password to encrypt
 * @param string|null $salt Optional salt
 * @return string The encrypted password
 */
function crypt_apr1_md5($password, $salt = NULL)
{
    if (!$salt) $salt = random_string(8); // Generate random salt if not provided
    
    // Apache APR1-MD5 algorithm
    $text = $password . '$apr1$' . $salt;
    $bin = pack("H32", md5($password . $salt . $password));
    
    for ($i = strlen($password); $i > 0; $i -= 16) $text .= substr($bin, 0, min(16, $i));
    for ($i = strlen($password); $i > 0; $i >>= 1) $text .= ($i & 1) ? chr(0) : $password[0];

    $bin = pack("H32", md5($text));
    
    for ($i = 0; $i < 1000; $i++) {
        $new = ($i & 1) ? $password : $bin;
        if ($i % 3) $new .= $salt;
        if ($i % 7) $new .= $password;
        $new .= ($i & 1) ? $bin : $password;
        $bin = pack("H32", md5($new));
    }

    $tmp = '';
    for ($i = 0; $i < 5; $i++) {
        $k = $i + 6;
        $j = $i + 12;
        if ($j == 16) $j = 5;
        $tmp = $bin[$i] . $bin[$k] . $bin[$j] . $tmp;
    }

    $tmp = chr(0) . chr(0) . $bin[11] . $tmp;
    $tmp = strtr(strrev(substr(base64_encode($tmp), 2)),
        "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/",
        "./0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz");

    return '$apr1$' . $salt . '$' . $tmp;
}

/**
 * Encrypt password based on the selected method
 *
 * @param string $password The password to encrypt
 * @param string $encryption The encryption method (md5, crypt, sha)
 * @return string The encrypted password
 */
function encrypt_password($password, $encryption = 'md5')
{
    switch ($encryption) {
        case 'crypt':
            // Use traditional crypt method
            $salt = random_string(2);
            return crypt($password, $salt);
        case 'sha':
            // Use SHA-1 encryption
            return '{SHA}' . base64_encode(sha1($password, true));
        case 'md5':
        default:
            // Use Apache's APR1-MD5 method
            return crypt_apr1_md5($password);
    }
}

/**
 * Check if user already exists in .htpasswd file
 *
 * @param string $username The username to check
 * @return bool Whether the username exists
 */
function user_exists($username)
{
    global $htpasswd_path;
    if (!file_exists($htpasswd_path)) return false;
    
    $contents = file_get_contents($htpasswd_path);
    return strpos($contents, $username . ':') !== false;
}

/**
 * Save users to .htpasswd file (append mode)
 *
 * @param array $passwords An array of ['username' => 'password'] pairs
 * @param string $encryption The encryption method
 * @return void
 */
function save_to_htpasswd($passwords, $encryption)
{
    global $htpasswd_path;
    
    // Open the .htpasswd file in append mode
    $file = fopen($htpasswd_path, 'a'); // 'a' means append mode
    
    // Check if the file is successfully opened
    if ($file) {
        foreach ($passwords as $username => $password) {
            $encrypted_password = encrypt_password($password, $encryption);
            // Write the new user entry at the end of the file
            fwrite($file, "\n{$username}:{$encrypted_password}");
        }
        
        // Close the file after writing
        fclose($file);
    } else {
        // Handle error if the file can't be opened
        echo "Error: Unable to open .htpasswd file.";
    }
}


/**
 * Validate access key from keys.json and remove it if valid, also remove expired keys.
 *
 * @param string $key The access key to validate
 * @return bool Whether the key is valid and not expired
 */
function validate_access_key($key)
{
    global $keys_json_path;

    // Check if the keys file exists
    if (!file_exists($keys_json_path)) {
        return false;
    }

    // Load keys from the JSON file
    $keys = json_decode(file_get_contents($keys_json_path), true);

    // Filter out expired keys
    $keys = array_filter($keys, function($keyData) {
        return $keyData['expiration'] > time();
    });

    // Check if the key is valid
    foreach ($keys as $index => $keyData) {
        // Ensure the $key is treated as an integer since it is in the JSON as a number
        if ((int)$keyData['key'] === (int)$key) {
            // Remove the key from the array
            unset($keys[$index]);

            // Save updated keys back to the JSON file
            file_put_contents($keys_json_path, json_encode(array_values($keys)));

            return true; // Key is valid and not expired
        }
    }

    return false; // Invalid or expired key
}



// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accessKey = trim($_POST['key']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirmPassword']);

    // Validate input
    if (empty($accessKey) || empty($username) || empty($password) || empty($confirmPassword)) {
        $error = "All fields are required.";
    } elseif ($password !== $confirmPassword) {
        $error = "Passwords do not match.";
    } elseif (user_exists($username)) {
        $error = "Username already exists.";
    } elseif (!validate_access_key($accessKey)) {
        $error = "Invalid access key.";
    } else {
        // Add the new user
        $passwords = [$username => $password];
        save_to_htpasswd($passwords, $encryption);
        
        $success = "User registered successfully!";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://jscdn.ct.ws/assets/css/bootstrap.css" rel="stylesheet">
    <style>
        body {
            padding-top: 40px;
            padding-bottom: 40px;
            background-color: #f5f5f5;
        }

        .form-signup {
            max-width: 500px;
            padding: 19px 29px 29px;
            margin: 0 auto 20px;
            background-color: #fff;
            border: 1px solid #e5e5e5;
            border-radius: 5px;
            box-shadow: 0 1px 2px rgba(0,0,0,.05);
        }

        .form-signup .form-signup-heading,
        .form-signup .checkbox {
            margin-bottom: 10px;
        }

        .form-signup input[type="text"],
        .form-signup input[type="password"] {
            font-size: 16px;
            height: auto;
            margin-bottom: 15px;
            padding: 7px 9px;
            width: 100%;
        }

        .form-signup button {
            width: 100%;
        }
    </style>
</head>
<body>

<div class="container">
    <form class="form-signup" action="signup.php" method="POST">
        <h2 class="form-signup-heading">Sign Up</h2>
        <p>If you have an existing LB account, make your new account the same name.<br><br>
        Registration is only successful when it says "<u>User registered successfully!</u>" If it prompts you for a <b>username and password</b> when you click "Sign-Up", <b>clear your cache</b> (instructions below), then sign up again.</p>

        <?php
        if (isset($error)) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
        if (isset($success)) {
            echo "<div class='alert alert-success'>$success</div>";
        }
        ?>
        <input type="text" maxlength="6" name="key" class="input-block-level" placeholder="Access Key" required>
        <input type="text" maxlength="15" minlength="3" name="username" class="input-block-level" placeholder="Username" required>
        <input type="password" maxlength="30" minlength="8" name="password" class="input-block-level" placeholder="Password" required>
        <input type="password" maxlength="30" minlength="8" name="confirmPassword" class="input-block-level" placeholder="Confirm Password" required>

        <button class="btn btn-success" type="submit"><i class="icon-plus icon-white"></i> Sign Up &raquo;</button>
    </form>

    <form class="form-signup" style="padding-bottom:19px;">
        <a id="openBlankTabBtn" class="btn btn-primary btn-block" href="home.php"><i class="icon-user icon-white"></i> Log In &raquo;</a>
    </form>
    <form class="form-signup" style="padding-bottom:19px;">
        <p><i>If you can't log in or create an account, clear your cache</i>:
        <li><b>CTRL+H</b></li>
        <li>"<b>Delete Browsing Data</b>"</li>
        <li>Select "<b>All Time</b>"</li>
        <li>Select "<b>Cached images and files</b>"</li>
        <li>Click <b>Delete</b></li>
        <li>After it's done, click "<b>Cancel</b>"</li>
        <li>Click "<b>Site Settings</b>"</li>
        <li>Click "<b>View permissions and data</b>"</li>
        <li>Search for "<b>ct.ws</b>"</li>
        <li>Click the <b>trash icon</b>, then click <b>Delete</b></li>
        If you still encounter errors, powerwash. It's not my code that's faulty.
        </p>
    </form>
</div>


    <script src="https://jscdn.ct.ws/../assets/js/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>    
    <script src="https://jscdn.ct.ws/../assets/js/bootstrap-transition.js"></script>
    <script src="https://jscdn.ct.ws/../assets/js/bootstrap-alert.js"></script>
    <script src="https://jscdn.ct.ws/../assets/js/bootstrap-modal.js"></script>
    <script src="https://jscdn.ct.ws/../assets/js/bootstrap-dropdown.js"></script>
    <script src="https://jscdn.ct.ws/../assets/js/bootstrap-scrollspy.js"></script>
    <script src="https://jscdn.ct.ws/../assets/js/bootstrap-tab.js"></script>
    <script src="https://jscdn.ct.ws/../assets/js/bootstrap-tooltip.js"></script>
    <script src="https://jscdn.ct.ws/../assets/js/bootstrap-popover.js"></script>
    <script src="https://jscdn.ct.ws/../assets/js/bootstrap-button.js"></script>
    <script src="https://jscdn.ct.ws/../assets/js/bootstrap-collapse.js"></script>
    <script src="https://jscdn.ct.ws/../assets/js/bootstrap-carousel.js"></script>
    <script src="https://jscdn.ct.ws/../assets/js/bootstrap-typeahead.js"></script>



</body>
</html>