<?php
// Start the session at the beginning
session_start(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="cache-control" content="max-age=0" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="pragma" content="no-cache" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://jscdn.ct.ws/../assets/css/bootstrap.css" rel="stylesheet">
    <link href="https://jscdn.ct.ws/../assets/css/bootstrap-responsive.css" rel="stylesheet">

</head>

<body>
<div class="container">

      <div class="masthead">
<?php require_once($_SERVER["DOCUMENT_ROOT"] . '/oo-pass/tb.php'); ?>
      </div>
      <a class="btn btn-inverse" type="button" href="leaderboard.php">&laquo; Back</a>
<?php

// Load existing users
$usersFile = 'users.json';
$usersData = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];

// Handle logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    echo "<script>window.location.href='https://jscdn.ct.ws/oo-pass/lb/leaderboard.php';</script>";
    header("Location: https://jscdn.ct.ws/oo-pass/lb/leaderboard.php");
    exit();
}

// Handle password change
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['change_password'])) {
    $username = $_SESSION['username'] ?? null;
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmNewPassword = $_POST['confirm_new_password'];

    // Initialize a flag for error status
    $errorOccurred = false;

    if (!$username) {
        echo '<br><br><div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><b>How the f**k did you even manage to try this?</b></div>';
        $errorOccurred = true;
    }

    // Validate current password
    if (!isset($usersData[$username]) || !password_verify($currentPassword, $usersData[$username]['password'])) {
        echo '<br><br><div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><b>Current password is incorrect.</b></div>';
        $errorOccurred = true;
    }

    // Validate new password match
    if ($newPassword !== $confirmNewPassword) {
        echo '<br><br><div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><b>New passwords do not match.</b></div>';
        $errorOccurred = true;
    }

    // Only update the password if no errors occurred
    if (!$errorOccurred) {
        // Update the password
        $usersData[$username]['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
        file_put_contents($usersFile, json_encode($usersData, JSON_PRETTY_PRINT));

        echo '<br><br><div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button><b>Password updated successfully.</b></div>';
    }
}

// Handle account deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_account'])) {
    $password = $_POST['password'];
    $username = $_SESSION['username'];

    // Validate password
    if (!isset($usersData[$username]) || !password_verify($password, $usersData[$username]['password'])) {
        echo "Incorrect password.";
        exit();
    }

    // Delete from users.json
    unset($usersData[$username]);
    file_put_contents($usersFile, json_encode($usersData, JSON_PRETTY_PRINT));

    // Delete from leaderboards.json
    $leaderboardFile = 'leaderboards.json';
    if (file_exists($leaderboardFile)) {
        $leaderboardData = json_decode(file_get_contents($leaderboardFile), true);

        // Loop through each leaderboard category and remove the user's entries
        foreach ($leaderboardData as $category => $entries) {
            foreach ($entries as $index => $entry) {
                if ($entry['username'] === $username) {
                    unset($leaderboardData[$category][$index]);
                }
            }

            // Re-index array after deletion
            $leaderboardData[$category] = array_values($leaderboardData[$category]);
        }

        // Save the updated leaderboards back to the file
        file_put_contents($leaderboardFile, json_encode($leaderboardData, JSON_PRETTY_PRINT));
    }

    // End the session and redirect the user
    session_destroy();
    echo "Your account has been deleted successfully.";
    echo "<script>window.location.href='https://jscdn.ct.ws/oo-pass/lb/leaderboard.php';</script>";
    header("Location: https://jscdn.ct.ws/oo-pass/lb/leaderboard.php");
    exit();
}

// Handle login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];

    // Validate login
    if (isset($usersData[$username]) && password_verify($password, $usersData[$username]['password'])) {
        // Successful login
        $_SESSION['username'] = $username;
        echo "<script>window.location.href='https://jscdn.ct.ws/oo-pass/lb/leaderboard.php';</script>";
        header("Location: leaderboard.php");
        exit();
    } else {
        echo "Invalid username or password.";
    }
}
?>
      <!-- Main Content -->
      <div class="content">

    
    <?php if (!isset($_SESSION['username'])): ?>
    <br>
        <form method="post" action="login.php">
            <input type="hidden" name="login" value="1">
            <div class="control-group">
                <label for="username" class="control-label">Username:</label>
                <div class="controls">
                    <input type="text" id="username" name="username" required class="input-xlarge">
                </div>
            </div>

            <div class="control-group">
                <label for="password" class="control-label">Password:</label>
                <div class="controls">
                    <input type="password" id="password" name="password" required class="input-xlarge">
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary">
                <i class="icon-tags icon-white"></i> Login
            </button>

        </form>
    <?php else: ?>
        <!---<h3>Logged in as <b><?php echo htmlspecialchars($_SESSION['username']); ?></b></h3>--->
        
        <br><p><a href="login.php?action=logout" class="btn btn-danger"><i class="icon-eject icon-white"></i> Logout</a></p>
        
        <hr>
        <h3>Change Password</h3>
        <form method="post" action="login.php">
            <input type="hidden" name="change_password" value="1">
            
            <div class="control-group">
                <label for="current_password" class="control-label">Current Password:</label>
                <div class="controls">
                    <input type="password" id="current_password" name="current_password" required class="input-xlarge">
                </div>
            </div>
            
            <div class="control-group">
                <label for="new_password" class="control-label">New Password:</label>
                <div class="controls">
                    <input type="password" id="new_password" name="new_password" required class="input-xlarge">
                </div>
            </div>
            
            <div class="control-group">
                <label for="confirm_new_password" class="control-label">Confirm New Password:</label>
                <div class="controls">
                    <input type="password" id="confirm_new_password" name="confirm_new_password" required class="input-xlarge">
                </div>
            </div>
            
            <button type="submit" class="btn btn-warning">
                <i class="icon-asterisk icon-white"></i> Change Password
            </button>
        </form>
<!---
        <hr>
        <h3>Delete Account</h3>
        <form method="post" action="login.php">
            <input type="hidden" name="delete_account" value="1">
            <div class="control-group">
                <label for="password" class="control-label">Enter your password to delete your account <b>(this will also delete submitted leaderboard times)</b>:</label>
                <div class="controls">
                    <input type="password" id="password" name="password" required class="input-xlarge">
                </div>
            </div>
            <input type="submit" value="Delete Account" class="btn btn-danger">
        </form>--->
    <?php endif; ?>
</div>
</body>
</html>
