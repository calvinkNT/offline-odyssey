<?php
// Turn on error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// favorites.php

// Ensure the user is authenticated
if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('HTTP/1.1 401 Unauthorized');
    echo 'Authorization required';
    exit;
}

$user = $_SERVER['PHP_AUTH_USER'];
$filePath = 'favorites.json'; // Path to the single JSON file

// Get the action from the query string
$action = $_GET['action'] ?? 'get'; // Default to 'get' if no action is provided

// Function to load the favorites data from the JSON file
function loadFavorites() {
    global $filePath;
    if (file_exists($filePath)) {
        $data = file_get_contents($filePath);
        // Check for any issues reading the file
        if ($data === false) {
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(['error' => 'Failed to read favorites data.']);
            exit;
        }
        return json_decode($data, true);
    }
    return []; // Return an empty array if the file doesn't exist or is empty
}

// Function to save the favorites data to the JSON file
function saveFavorites($data) {
    global $filePath;
    // If the file doesn't exist, create it
    if (!file_exists($filePath)) {
        file_put_contents($filePath, json_encode([])); // Initialize with an empty object
    }
    
    // Try saving the favorites data to the file
    $result = file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT));
    
    if ($result === false) {
        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(['error' => 'Failed to save favorites data.']);
        exit;
    }
}

switch ($action) {
    case 'get':
        // Get the favorites for the current user
        $favoritesData = loadFavorites();
        if (isset($favoritesData[$user])) {
            echo json_encode($favoritesData[$user]);
        } else {
            echo json_encode([]); // No favorites for this user, return an empty array
        }
        break;

    case 'update':
        // Update the favorites for the current user
        $favoritesData = loadFavorites();
        $newFavorites = json_decode(file_get_contents('php://input'), true);
        
        // Ensure the data is valid
        if (json_last_error() !== JSON_ERROR_NONE) {
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(['error' => 'Invalid JSON data']);
            exit;
        }

        // Save the new favorites list for this user
        $favoritesData[$user] = $newFavorites;

        // Save the updated data back to the JSON file
        saveFavorites($favoritesData);
        echo json_encode(['status' => 'success']);
        break;

    default:
        header('HTTP/1.1 400 Bad Request');
        echo json_encode(['error' => 'Invalid action']);
        break;
}
?>
