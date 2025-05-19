<?php
session_start();
if (!isset($_SESSION['username'])) {
    echo "You are not logged in.";
    exit;
}

$username = $_SESSION['username'];
$time = time();
$time_check = $time - 10; // We Have Set Time 5 Minutes

// Load the JSON file
$json_file = 'online_users.json';
$data = json_decode(file_get_contents($json_file), true);

// Remove users that are older than 5 minutes
$data['users'] = array_filter($data['users'], function($user) use ($time_check) {
    return $user['time'] >= $time_check;
});

// Check if the username exists in the JSON file
$found = false;
foreach ($data['users'] as $key => $user) {
    if ($user['username'] == $username) {
        $data['users'][$key]['time'] = $time;
        $found = true;
        break;
    }
}

// If the username does not exist, add it to the JSON file
if (!$found) {
    $data['users'][] = array('username' => $username, 'time' => $time);
}

// Save the updated JSON file
file_put_contents($json_file, json_encode($data));

// Get the number of online users
$count_user_online = count($data['users']);

// Output the list of online users and the count
echo "<span><b><small>$count_user_online user(s) online:</small></b> </span>";
//echo "<ul>";
foreach ($data['users'] as $user) {
    echo "<span style='color:lightgreen;'><b><code style='color:lightgreen;'>$user[username]</code></b></span> ";
}
//echo "</ul></p>";
?>