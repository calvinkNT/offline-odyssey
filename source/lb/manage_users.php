<?php
// Start the session to check the logged-in user
session_start();

// Load moderators list from moderators.json
$moderatorsFile = 'moderators.json';
$moderatorsData = file_exists($moderatorsFile) ? json_decode(file_get_contents($moderatorsFile), true) : [];
$moderators = $moderatorsData['moderators'] ?? [];

// Check if the user is logged in and is a moderator
if (!isset($_SESSION['username']) || !in_array($_SESSION['username'], $moderators)) {
    // If not logged in or not a moderator, redirect to leaderboards.php
    header("Location: leaderboard.php");
    exit();
}

// File paths
$usersFile = 'users.json';
$usersData = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];

// Handle password change or account deletion
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];
    $username = $_POST['username'];

    if (!isset($usersData[$username])) {
        echo "User not found!";
        exit();
    }

    if ($action === 'change_password') {
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];

        // Validate new passwords match
        if ($newPassword !== $confirmPassword) {
            echo "Passwords do not match!";
            exit();
        }

        // Update password
        $usersData[$username]['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
        file_put_contents($usersFile, json_encode($usersData, JSON_PRETTY_PRINT));

        echo "Password updated successfully!";
    }

    if ($action === 'delete_account') {
        unset($usersData[$username]);
        file_put_contents($usersFile, json_encode($usersData, JSON_PRETTY_PRINT));

        echo "Account deleted successfully!";
    }
}

// Load all users
$allUsers = $usersData;
?>
<a href="leaderboard.php"><< Back</a><br>
please dont mess around here since deletion is permanent<br>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        table {
            width: 100%; /* Set table width to 100% of the viewport */
            border-collapse: collapse; /* Optional: Make the table look cleaner */
        }
        th, td {
            padding: 8px; /* Add padding to table cells */
            text-align: left; /* Align text to the left */
            border: 1px solid black; /* Add border to table cells */
        }
    </style>
</head>
<body>

<h2>Manage Users</h2>
<table>
    <tr>
        <th>Username</th>
        <th>Action</th>
    </tr>
    <?php foreach ($allUsers as $username => $user): ?>
        <tr>
            <td><?php echo htmlspecialchars($username); ?></td>
            <td>
                <!-- Change password form -->
                <form method="post" action="">
                    <input type="hidden" name="username" value="<?php echo $username; ?>">
                    <input type="hidden" name="action" value="change_password">
                    <input type="password" name="new_password" placeholder="New Password" required>
                    <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                    <button type="submit">Change Password</button>
                </form>
                <!-- Delete account form -->
                <form method="post" action="" onsubmit="return confirm('Are you sure you want to delete this account?');">
                    <div><input type="hidden" name="username" value="<?php echo $username; ?>">
                    <input type="hidden" name="action" value="delete_account">
                    <button type="submit">Delete Account</button></div>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
