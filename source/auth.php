<?php
header('Content-Type: application/json'); // Ensure the response is in JSON format

session_start();

$response = [
    'status' => 'error',
    'message' => ''
];

// Path to the token storage file
$tokenFilePath = 'tokens.json';

// Remove expired tokens
if (file_exists($tokenFilePath)) {
    $tokens = json_decode(file_get_contents($tokenFilePath), true) ?: [];
    $currentTime = time();
    foreach ($tokens as $token => $data) {
        if ($data['expires'] < $currentTime) {
            unset($tokens[$token]); // Remove expired token
        }
    }
    file_put_contents($tokenFilePath, json_encode($tokens)); // Update tokens file
}

// Handle POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entered_password = $_POST["password"] ?? "";

    // Correct password
    $correct_password = "foreveryoung";

    if ($entered_password === $correct_password) {
        // Success - Generate token and return response
        $token = bin2hex(random_bytes(16)); // Generate a random token
        
        // Set token expiration to 1 hour (3600 seconds from now)
        $expirationTime = time() + 3600; // 1 hour expiration

        // Save token to tokens.json file with expiration time
        $tokens[$token] = ['expires' => $expirationTime]; // Save the token with expiration time
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
    } else {
        // Invalid password
        $response = [
            'status' => 'error',
            'message' => 'the password, unfortunately, is incorrect.'
        ];
    }
}

// Return the JSON response
echo json_encode($response);
?>
