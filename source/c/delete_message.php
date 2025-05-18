<?php
session_start();

$messages_buffer_file = "messages.json";

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Open, lock and read the message buffer file
    $buffer = fopen($messages_buffer_file, "r+b");
    flock($buffer, LOCK_EX);
    $buffer_data = stream_get_contents($buffer);

    // Load messages from file
    $messages = json_decode($buffer_data, true);

    // Find and remove the message with the given ID
    foreach ($messages as $key => $message) {
        if ($message['id'] == $id && $message['name'] == $_SESSION['username']) {
            unset($messages[$key]);
            break;
        }
    }

    // Rewrite and unlock the message file
    ftruncate($buffer, 0);
    rewind($buffer);
    fwrite($buffer, json_encode(array_values($messages)));
    flock($buffer, LOCK_UN);
    fclose($buffer);

    exit();
}
