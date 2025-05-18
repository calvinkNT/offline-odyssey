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
    header("Location: leaderboards.php");
    exit();
}

// File paths
$submissionsFile = "submissions.json";
$leaderboardFile = "leaderboards.json";

// Handle approval or denial actions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];
    $index = (int)$_POST['index'];
    $currentData = file_exists($submissionsFile) ? json_decode(file_get_contents($submissionsFile), true) : [];

    if (isset($currentData[$index])) {
        $submission = $currentData[$index];

        // Extract game name and level name from the submission
        $gameLevel = explode('::', $submission['game_name']);
        $gameName = trim($gameLevel[0]);
        $levelName = isset($gameLevel[1]) ? trim($gameLevel[1]) : '';

        // If approved, add to the correct game in the leaderboard file and update status
        if ($action === 'approve') {
            $leaderboardData = file_exists($leaderboardFile) ? json_decode(file_get_contents($leaderboardFile), true) : ['leaderboards' => []];

            // Check if the game already exists in the leaderboard data
            if (isset($leaderboardData['leaderboards'][$gameName])) {
                // Check if the level exists
                $levelExists = false;
// Fix: Update the level directly in the leaderboard data after modification
foreach ($leaderboardData['leaderboards'][$gameName] as &$level) {
    if ($level['name'] == $levelName) {
        $levelExists = true;
        // Add the new submission entry to the level's entries
        $level['entries'][] = [
            'username' => $submission['username'],
            'score' => $submission['game_score'],
            'submission_time' => $submission['submission_time']
        ];
        break;
    }
}

                // If level doesn't exist, create it
                if (!$levelExists) {
                    $leaderboardData['leaderboards'][$gameName][] = [
                        'name' => $levelName,
                        'entries' => [
                            [
                                'username' => $submission['username'],
                                'score' => $submission['game_score'],
                                'submission_time' => $submission['submission_time']
                            ]
                        ]
                    ];
                }
            } else {
                // If the game doesn't exist, create a new entry for that game
                $leaderboardData['leaderboards'][$gameName] = [
                    [
                        'name' => $levelName,
                        'entries' => [
                            [
                                'username' => $submission['username'],
                                'score' => $submission['game_score'],
                                'submission_time' => $submission['submission_time']
                            ]
                        ]
                    ]
                ];
            }

            // Save updated leaderboard data back to the file
            file_put_contents($leaderboardFile, json_encode($leaderboardData, JSON_PRETTY_PRINT));

            // Update the submission status to 'approved'
            $currentData[$index]['status'] = 'approved';
        }

        // If denied, update status
        if ($action === 'deny') {
            $currentData[$index]['status'] = 'denied';
        }

        // Delete the image file
        if (file_exists($submission['file_path'])) {
            unlink($submission['file_path']);
        }

        // Remove submission from the list
        unset($currentData[$index]);
        file_put_contents($submissionsFile, json_encode(array_values($currentData), JSON_PRETTY_PRINT));
    }
}

// Load current submissions
$currentData = file_exists($submissionsFile) ? json_decode(file_get_contents($submissionsFile), true) : [];
?>
<a href="leaderboard.php"><< Back</a><br>
Note: Image verification is not working, for some reason. If the image is not loading, please check other leaderboard times for that game and level. If they are believable, allow them. If not, deny them.
<!DOCTYPE html>
<html>
<head>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border: 1px solid black;
        }
        img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>

<h2>Review Submissions</h2>
<table>
    <tr>
        <th>Game Name</th>
        <th>Username</th>
        <th>Game Score</th>
        <th>Image</th>
        <th>Submission Time</th>
        <th>Action</th>
    </tr>
    <?php foreach ($currentData as $index => $submission): ?>
        <tr>
            <td><?php echo htmlspecialchars($submission['game_name']); ?></td>
            <td><?php echo htmlspecialchars($submission['username']); ?></td>
            <td><?php echo htmlspecialchars($submission['game_score']); ?></td>
            <td>
                <img src="<?php echo htmlspecialchars($submission['file_path']); ?>" alt="Score Image">
            </td>
            <td><?php echo htmlspecialchars($submission['submission_time']); ?></td>
            <td>
                <form method="post">
                    <input type="hidden" name="index" value="<?php echo $index; ?>">
                    <button type="submit" name="action" value="approve">Approve</button>
                    <button type="submit" name="action" value="deny">Deny</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
