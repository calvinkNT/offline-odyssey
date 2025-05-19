<?php

// Start session
session_start();

// Default response
$response = [
    'status' => 'error',
    'message' => ''
];

// Path to the token storage file
$tokenFilePath = 'tokens.json';

$countFilePath = 'count.json';

// Handle POST request for authentication
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entered_password = $_POST["password"] ?? "";

    // Correct password
    $correct_password = "openyoureyes2002";

    // Initialize or update failed login attempts and lockout time in session
    if (!isset($_SESSION['failed_attempts'])) {
        $_SESSION['failed_attempts'] = 0;
    }
    if (!isset($_SESSION['lockout_time'])) {
        $_SESSION['lockout_time'] = 0;
    }

// Check if user is locked out
if ($_SESSION['failed_attempts'] >= 3 && time() < $_SESSION['lockout_time']) {
    // If the user is locked out, show the error message with the lockout time
    $lockout_time_formatted = date("M. j, g:i A", $_SESSION['lockout_time']); // Format the lockout time as "Apr. 3, 11:20 AM"
    
    $response = [
        'status' => 'error',
        'message' => "You are locked out due to too many failed attempts. Please try again after $lockout_time_formatted."
    ];
    //echo json_encode($response);
    echo 'You are locked out due to too many failed attempts. Please try again after ' .  $lockout_time_formatted;
    exit();
}


    // Check if password is correct
    if ($entered_password === $correct_password) {
        // Reset failed attempts and lockout time on successful login
        $_SESSION['failed_attempts'] = 0;
        $_SESSION['lockout_time'] = 0;

        // Success - Generate token and return response
        $token = bin2hex(random_bytes(16)); // Generate a random token
        
        // Set token expiration to 1 hour (3600 seconds from now)
        $expirationTime = time() + 3600; // 1 hour expiration

        // Read existing tokens from file
        $tokens = file_exists($tokenFilePath) ? json_decode(file_get_contents($tokenFilePath), true) : [];
        
        // Remove expired tokens
        foreach ($tokens as $token => $data) {
            if ($data['expires'] < time()) {
                unset($tokens[$token]); // Remove expired token
            }
        }
        
        // Save new token with expiration time
        $tokens[$token] = ['expires' => $expirationTime];
        file_put_contents($tokenFilePath, json_encode($tokens));

        // Set the session cookie for the token
        setcookie('auth_token', $token, $expirationTime, '/'); // Session cookie (expires when browser/tab is closed)
        setcookie('auth', 'quick-fix', $expirationTime, '/'); // Session cookie for auth

        // Success response
        $response = [
            'status' => 'success',
            'token' => $token,
            'message' => 'Login successful. Token generated.'
        ];
        
        updateHitCounters();
        
        //unset($_SESSION['cleared']);
        
        setcookie("home", "", time() - 3600);
        
        // Redirect after success
        header('Location: https://jscdn.ct.ws/oo-pass/home.php');
        exit();
    } else {
        // Increment failed attempts
        $_SESSION['failed_attempts']++;

        if ($_SESSION['failed_attempts'] >= 3) {
            // Lock out user for 12 hours (43200 seconds)
            $_SESSION['lockout_time'] = time() + 43200; // Lockout for 12 hours

            $response = [
                'status' => 'error',
                'message' => 'Too many failed attempts. You are locked out for 12 hours.'
            ];
            //echo '<script>alert("Too many failed attempts. You are locked out for 12 hours.");</script>';
        } else {
            //echo '<script>alert("The password is incorrect. You have ' . 3 - $_SESSION['failed_attempts'] . ' attempts(s) remaining.");</script>';
            $response = [
                'status' => 'error',
                'message' => 'The password is incorrect. You have ' . 3 - $_SESSION['failed_attempts'] . ' attempt(s) remaining.'
            ];
        }
    }

    // Return response as JSON
    //echo json_encode($response);
}

// Function to update hit counters in count.json
function updateHitCounters() {
    global $countFilePath;

    // Read existing count data from count.json file
    $data = file_exists($countFilePath) ? json_decode(file_get_contents($countFilePath), true) : [
        'total_hits' => 0,
        'hits_last_12_hours' => 0,
        'hits_last_7_days' => 0,
        'hits_timestamps' => []
    ];

    // Get the current timestamp
    $currentTimestamp = time();

    // Add the current timestamp to the hits array
    $data['hits_timestamps'][] = $currentTimestamp;

    // Filter out hits that are older than 12 hours
    $data['hits_last_12_hours'] = count(array_filter($data['hits_timestamps'], function ($timestamp) use ($currentTimestamp) {
        return ($currentTimestamp - $timestamp) <= 12 * 60 * 60; // 12 hours
    }));

    // Filter out hits that are older than 7 days
    $data['hits_last_7_days'] = count(array_filter($data['hits_timestamps'], function ($timestamp) use ($currentTimestamp) {
        return ($currentTimestamp - $timestamp) <= 7 * 24 * 60 * 60; // 7 days
    }));

    // Increment the total hits count
    $data['total_hits']++;

    // Save the updated data back to count.json
    file_put_contents($countFilePath, json_encode($data, JSON_PRETTY_PRINT));
}

/*
fetch('setSessionProperty.php', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: 'property=knumvalid&value=true',
})
.then(response => response.text())
.then(data => {
    if (data === "reload") {
        // If the session property is already set, reload the page
        window.location.reload();
    } 
})
.catch((error) => alert('Error:', error));        
*/

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="darkreader-lock" content="" />
  <title>Login Page</title>
<link href="https://jscdn.ct.ws/../assets/css/bootstrap.css?<?php echo date('l jS \of F Y h:i:s A'); ?>" rel="stylesheet">
<link href="https://jscdn.ct.ws/../assets/css/bootstrap-responsive.css?<?php echo date('l jS \of F Y h:i:s A'); ?>" rel="stylesheet">
<link href="https://jscdn.ct.ws/../assets/css/darkstrap.css" rel="stylesheet">
  <style>
    body {
      padding-top: 40px;
      //background-color: #f5f5f5;
    }

    .form-signin {
      max-width: 500px;
      margin: auto;
      padding: 20px;
      //background-color: #fff;
      border-radius: 5px;
      //box-shadow: 0 1px 2px rgba(0, 0, 0, .1);
    }

    .form-signin input {
      margin-bottom: 15px;
    }

    /* Center the link container */
    .centered-link {
      text-align: center;
      margin-top: 20px;
    }
  </style>
</head>

<body>

  <div class="container">
    <form id="loginForm" class="form-signin well" method="POST" action="">
      <h2>Login</h2>
      <input type="password" id="password" name="password" placeholder="Password" required><br>
      <button type="submit" class="btn btn-primary"><i class="icon-lock"></i> Unlock &raquo;</button><br>
    </form>
  </div>

</body>
</html>
