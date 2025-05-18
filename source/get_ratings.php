<?php
// Central Rating System
$file = "ratings.txt";
$user_id = isset($_COOKIE['user_id']) ? $_COOKIE['user_id'] : uniqid();

function getRatings($game) {
    global $file;
    $ratings = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
    $game_ratings = $ratings[$game] ?? [];
    $avg_rating = count($game_ratings) ? round(array_sum($game_ratings) / count($game_ratings), 1) : 0;
    $total_ratings = count($game_ratings);
    return ['avg_rating' => $avg_rating, 'total_ratings' => $total_ratings];
}

if (isset($_GET['game'])) {
    $game = $_GET['game'];
    $rating_info = getRatings($game);
    echo json_encode($rating_info);
}
?>
