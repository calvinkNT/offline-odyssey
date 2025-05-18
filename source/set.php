
<?php
session_start(); // Start the session to store the last generation time

// Generate a random 6-digit access key and set expiration to 10 minutes
function generateAccessKey() {
    $key = rand(100000, 999999);  // Generate a 6-digit key
    $expiration = time() + 600;   // Set expiration time (10 minutes from now)
    $accessData = ['key' => $key, 'expiration' => $expiration];
    
    // Save to JSON file
    $keysFile = 'keys.json';
    $keys = file_exists($keysFile) ? json_decode(file_get_contents($keysFile), true) : [];
    $keys[] = $accessData;  // Append new key data
    
    file_put_contents($keysFile, json_encode($keys));  // Save keys back to JSON
    return $key;
}

// Validate if the entered key is valid and not expired
function validateAccessKey($enteredKey) {
    $keysFile = 'keys.json';
    
    // Check if the file exists and is readable
    if (file_exists($keysFile)) {
        // Read and decode the file contents
        $fileContents = file_get_contents($keysFile);
        $keys = json_decode($fileContents, true);
        
        // If decoding fails, initialize $keys as an empty array
        if ($keys === null) {
            $keys = [];
        }
        
        // Loop through the keys to check if the entered key is valid and not expired
        foreach ($keys as $keyData) {
            if ($keyData['key'] == $enteredKey && $keyData['expiration'] > time()) {
                return true;  // Key is valid and not expired
            }
        }
    }

    return false;  // Key is either invalid or expired
}

// Handle key generation request
$generatedKey = null;
$lastGeneratedTime = isset($_SESSION['lastGeneratedTime']) ? $_SESSION['lastGeneratedTime'] : 0;
$cooldown = 30;  // Cooldown period in seconds
$timeRemaining = 0;

$currentTime = time();
if (isset($_GET['generate'])) {
    if ($currentTime - $lastGeneratedTime >= $cooldown) {
        // It's been enough time, generate a new key
        $generatedKey = generateAccessKey();
        $_SESSION['lastGeneratedTime'] = $currentTime; // Update last generated time
    } else {
        // Cooldown not passed, calculate remaining time
        $timeRemaining = $cooldown - ($currentTime - $lastGeneratedTime);
    }
}

// Handle key validation request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['key'])) {
    $enteredKey = $_POST['key'];
    $validationMessage = validateAccessKey($enteredKey) ? "Access key is valid!" : "Invalid or expired access key.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="cache-control" content="max-age=0" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="pragma" content="no-cache" />
    <meta charset="utf-8">
    <link rel="icon" type="image/x-icon" href="../../../../../favicon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="https://jscdn.ct.ws/../assets/css/bootstrap.css" rel="stylesheet">
    <link href="https://jscdn.ct.ws/../assets/css/bootstrap-responsive.css" rel="stylesheet">

</head>

<body>

    <div class="container">
        <div class="masthead">
        <?php require_once('tb.php'); ?>
        </div>
<script>
function logout(to_url) {
    var out = window.location.href.replace(/:\/\//, '://user:loggedout@');

    jQuery.get(out).error(function() {
        window.location = to_url;
    });
}
</script>

        <br>
        
<!---
        <h3>Logged in as <?php echo $_SERVER['PHP_AUTH_USER']; ?></h3>
        <p><a onclick="logout('https://jscdn.ct.ws/oo-pass/signup.php');" href=# class="btn btn-danger"><i class="icon-eject icon-white"></i> Logout</a></p>

        <hr>--->

        <h3>Theme Selection</h3>
        <div class="btn-group" data-toggle="buttons-radio">
            <button type="button" class="btn" id="light-btn">Light</button>
            <button type="button" class="btn btn-inverse" id="dark-btn">Night</button>
        </div>


        
        <script>
            // Check for existing theme cookie
            window.onload = function() {
                let theme = document.cookie.replace(/(?:(?:^|.*;\s*)theme\s*=\s*([^;]*).*$)|^.*$/, "$1");
                if (theme === "d") {
                    DarkReader.enable();
                } else {
                    DarkReader.disable();
                }
            };
    
            // Set the theme cookie and reload the page when a theme is selected
            document.getElementById('light-btn').addEventListener('click', function() {
                document.cookie = "theme=l; path=/";
                DarkReader.disable();
                location.reload();
            });
    
            document.getElementById('dark-btn').addEventListener('click', function() {
                document.cookie = "theme=d; path=/";
                DarkReader.enable();
                location.reload();
            });
        </script>
            <script src="https://jscdn.ct.ws/../assets/js/jquery.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js" integrity="sha512-YUkaLm+KJ5lQXDBdqBqk7EVhJAdxRnVdT2vtCzwPHSweCzyMgYV/tgGF4/dCyqtCC2eCphz0lRQgatGVdfR0ww==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>    
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
<!---
        <hr>
        
        <h3>Access Keys</h3>
        <?php

            // me, alex, andrew, joban, eliam, marco, arthur, reyansh, neddy, melody, lucas
            $allowedusers = ['calvink19', "Alex", "A", "Napolean", "Walm0rtSecurity", "_ToL_", "R2D2", 'NeoX', "simplywaved", 'btsbtsbts', 'lucasc'];
            
            // Check if the logged-in username is in the allowed users list
            if (!in_array($_SERVER['PHP_AUTH_USER'], $allowedusers)) {
                // If not, don't allow access key generation
                echo "<div class='alert alert-warning'><b>You are not authorized to generate access keys.</b><br><i>For now, only moderators are allowed to generate access keys.</i></div>";
                exit;  // Stop further execution
            }
        ?>
        <div>
        <?php if ($generatedKey): ?>
            <h1 id="access-key"><u><?php echo $generatedKey; ?></u></h1>
            <p>This key will expire 10 minutes from its creation.</p>
        <?php endif; ?>

        <form method="GET">
            <button type="submit" name="generate" id="generate-btn" class="btn btn-warning" <?php echo (isset($timeRemaining) && $timeRemaining > 0) ? 'disabled' : ''; ?>> <i class="icon-tag icon-white"></i> Generate New Access Key For Sign-Up</button>
        </form>

        <form method="POST" style="margin-bottom:20px;">
            <div class="input-append"><input type="text" style="width:200px;"name="key" class="input-block-level" placeholder="Enter Access Key" required><a type="submit" class="btn btn-success"> <i class="icon-refresh icon-white"></i> Test Key For Validation</a> This doesn't work right now.</div>
        </form>
        
        <?php if (isset($validationMessage)): ?>
            <p><?php echo $validationMessage; ?></p>
        <?php endif; ?>

        <button class="btn btn-danger" type="button" data-toggle="modal" data-target="#myModal">Blacklisted people (click to view)</button>

        <div id="cooldown-text">
            <?php if ($timeRemaining > 0): ?>
                <i>You must wait <?php echo $timeRemaining; ?> seconds before generating a new key.</i>
            <?php endif; ?>
        </div>
        </div>--->

<!-- Modal -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Ã—</button>
    <h3 id="myModalLabel">Blacklisted people</h3>
  </div>
  <div class="modal-body">

        The list below contains a list of people I would rather not use my site.<br>
        <li>Shrenik Uppu <i>(8th)</i></li>
        <li>Anirvin <i>(7th)</i></li>
        <li>Hongyi "Ian" Wang <i>(8th)</i></li>
        <li>Landon M. <i>(8th)</i></li>
        <li>Anyone who's hardcore addicted to gaming (I don't want them to bug me)</i>
        <li>Anyone who's immature (I got a user registered as "Balls")</li>
        <b>Don't tell people they're blacklisted (don't give them a key), otherwise they will come bug me about being blacklisted. This happened yesterday.</b>

  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
  </div>
</div>

    </div> <!-- /container -->

    <!-- Le javascript -->
    <script src="https://jscdn.ct.ws/../assets/js/jquery.js"></script>

    <script>
        $(document).ready(function() {
            var cooldown = <?php echo $cooldown; ?>;
            var remainingTime = <?php echo isset($timeRemaining) ? $timeRemaining : 0; ?>;

            // If there's remaining time, update the countdown and disable the button
            if (remainingTime > 0) {
                $('#generate-btn').prop('disabled', true);
                updateCooldownText(remainingTime);

                // Countdown update after button click
                var countdownInterval = setInterval(function() {
                    remainingTime--;
                    updateCooldownText(remainingTime);
                    if (remainingTime <= 0) {
                        clearInterval(countdownInterval);
                        $('#generate-btn').prop('disabled', false);
                        $('#cooldown-text').text('You can now generate a new key.');
                    }
                }, 1000);
            }

            // Update the cooldown text
            function updateCooldownText(time) {
                if (time > 0) {
                    $('#cooldown-text').text('<i>You must wait ' + time + ' seconds before generating a new key.</i>');
                } else {
                    $('#cooldown-text').text('');
                }
            }
        });
    </script>

</body>
</html>
