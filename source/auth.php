<?php

// This PHP script handles login authentication via a password,
// generates a time-limited token upon success, stores it in a JSON file,
// and returns a JSON-formatted response to the client.

// See Section 3.

header('Content-Type: application/json'); // Set response type to JSON

session_start(); // Start the session for user authentication

// Default response structure
$response = [
    'status' => 'error',
    'message' => ''
];

// File path for storing active tokens and their expiration times
$tokenFilePath = 'tokens.json';

// Step 1: Cleanup - Remove expired tokens from the file
if (file_exists($tokenFilePath)) {
    $tokens = json_decode(file_get_contents($tokenFilePath), true) ?: [];
    $currentTime = time();

    foreach ($tokens as $token => $data) {
        if ($data['expires'] < $currentTime) {
            unset($tokens[$token]); // Remove token if it's expired
        }
    }

    file_put_contents($tokenFilePath, json_encode($tokens)); // Save updated tokens
}

// Step 2: Handle login POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entered_password = $_POST["password"] ?? ""; // Get entered password or default to empty

    $correct_password = "foreveryoung"; // Hardcoded password

    if ($entered_password === $correct_password) {
        // Password is correct

        $token = bin2hex(random_bytes(16)); // Generate a secure 32-character hex token
        $expirationTime = time() + 3600; // Token valid for 1 hour

        // Save token and its expiration
        $tokens[$token] = ['expires' => $expirationTime];
        file_put_contents($tokenFilePath, json_encode($tokens));

        // Set cookies for the token and a simple auth flag
        setcookie('auth_token', $token, $expirationTime, '/');
        setcookie('auth', 'quick-fix', $expirationTime, '/');

        // Build success response
        $response = [
            'status' => 'success',
            'token' => $token,
            'message' => 'Login successful. Token generated.'
        ];
    } else {
        // Password incorrect
        $response = [
            'status' => 'error',
            'message' => 'the password, unfortunately, is incorrect.'
        ];
    }
}

// Step 3: Send JSON response back to client
echo json_encode($response);

?>
