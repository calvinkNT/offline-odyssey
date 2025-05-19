<a href="leaderboard.php"><< Back</a><br>

<?php
session_start();
$modFile = 'moderators.json';
$lbFile = 'leaderboards.json';
$configFile = 'leaderboards_config.json';

// Load moderators and leaderboards data
$mods = json_decode(file_get_contents($modFile), true)['moderators'] ?? [];
$lbs = json_decode(file_get_contents($lbFile), true)['leaderboards'] ?? [];
$configData = file_exists($configFile) ? json_decode(file_get_contents($configFile), true) : [];
$leaderboards = $configData['leaderboards'] ?? [];

if (!isset($_SESSION['username']) || !in_array($_SESSION['username'], $mods)) {
    header("Location: leaderboard.php");
    exit();
}

// Sort the scores for each leaderboard according to the sort_order defined in leaderboards_config.json
foreach ($lbs as $gameKey => &$gameData) {
    // Get the sort order from the config file
    $sortOrder = $leaderboards[$gameKey][0]['sort_order'] ?? 'descending';

    if (isset($gameData[0]['entries']) && is_array($gameData[0]['entries'])) {
        usort($gameData[0]['entries'], function ($a, $b) use ($sortOrder) {
            return $sortOrder === 'ascending'
                ? (float)$a['score'] <=> (float)$b['score']
                : (float)$b['score'] <=> (float)$a['score'];
        });
    }
}
unset($gameData);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $a = $_POST['action'];
    $lvl = $_POST['level'];
    $u = $_POST['username'];
    $t = $_POST['submission_time'];

    if (!isset($lbs[$lvl])) {
        echo "Leaderboard not found!";
    } else {
        // Loop through entries and process actions
        foreach ($lbs[$lvl] as $gameData) {
            foreach ($gameData['entries'] as $k => $v) {
                if ($v['username'] === $u && $v['submission_time'] === $t) {
                    if ($a === 'change_score') {
                        $lbs[$lvl][0]['entries'][$k]['score'] = floatval($_POST['new_score']);
                    }
                    if ($a === 'delete_score') {
                        unset($lbs[$lvl][0]['entries'][$k]);
                    }
                    if ($a === 'move_time') {
                        $newLvl = $_POST['new_level'];
                        if (!isset($lbs[$newLvl])) {
                            $lbs[$newLvl] = [['name' => $leaderboards[$newLvl][0]['name'], 'entries' => []]]; // Create target leaderboard if it doesn't exist
                        }
                        $lbs[$newLvl][0]['entries'][] = $lbs[$lvl][0]['entries'][$k];
                        unset($lbs[$lvl][0]['entries'][$k]);
                    }

                    // Re-sort affected leaderboards
                    usort($lbs[$lvl][0]['entries'], function ($a, $b) use ($leaderboards, $lvl) {
                        $sortOrder = $leaderboards[$lvl][0]['sort_order'] ?? 'descending';
                        return $sortOrder === 'ascending'
                            ? (float)$a['score'] <=> (float)$b['score']
                            : (float)$b['score'] <=> (float)$a['score'];
                    });

                    if (isset($newLvl)) {
                        usort($lbs[$newLvl][0]['entries'], function ($a, $b) use ($leaderboards, $newLvl) {
                            $sortOrder = $leaderboards[$newLvl][0]['sort_order'] ?? 'descending';
                            return $sortOrder === 'ascending'
                                ? (float)$a['score'] <=> (float)$b['score']
                                : (float)$b['score'] <=> (float)$a['score'];
                        });
                    }

                    file_put_contents($lbFile, json_encode(['leaderboards' => $lbs], JSON_PRETTY_PRINT));
                    echo "Action '$a' completed successfully!";
                    exit();
                }
            }
        }
    }
    echo "Submission not found!";
}
?>

<p>**TODO: fix "Move Score" button</p>
<h2>Manage Leaderboards</h2>
<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 4px;
            border: 1px solid black;
            font-size: 14px;
        }
        form {
            display: inline;
        }
        button, select, input {
            font-size: 12px;
            padding: 2px 4px;
            margin: 0 2px;
        }
    </style>
</head>
<body>
<?php foreach ($lbs as $gameKey => $gameData): ?>
    <h3><?php echo htmlspecialchars($gameData[0]['name']); ?></h3>
    <table>
        <tr><th>Username</th><th>Score</th><th>Submission Time</th><th>Actions</th></tr>
        <?php foreach ($gameData[0]['entries'] as $entry): ?>
            <tr>
                <td><?php echo htmlspecialchars($entry['username']); ?></td>
                <td><?php echo htmlspecialchars(number_format((float)$entry['score'], 3)); ?></td>
                <td><?php echo htmlspecialchars($entry['submission_time']); ?></td>
                <td>
                    <!-- Change Score -->
                    <form method="post" action="">
                        <input type="hidden" name="level" value="<?php echo htmlspecialchars($gameKey); ?>">
                        <input type="hidden" name="username" value="<?php echo htmlspecialchars($entry['username']); ?>">
                        <input type="hidden" name="submission_time" value="<?php echo htmlspecialchars($entry['submission_time']); ?>">
                        <input type="hidden" name="action" value="change_score">
                        <input type="number" name="new_score" placeholder="New Score" step="0.001" required>
                        <button type="submit">Change Score</button>
                    </form>

                    <!-- Delete Score -->
                    <form method="post" action="" onsubmit="return confirm('Delete this score?');">
                        <input type="hidden" name="level" value="<?php echo htmlspecialchars($gameKey); ?>">
                        <input type="hidden" name="username" value="<?php echo htmlspecialchars($entry['username']); ?>">
                        <input type="hidden" name="submission_time" value="<?php echo htmlspecialchars($entry['submission_time']); ?>">
                        <input type="hidden" name="action" value="delete_score">
                        <button type="submit">Delete</button>
                    </form>

                    <!-- Move Score (Dropdown for levels) -->
                    <form method="post" action="">
                        <input type="hidden" name="level" value="<?php echo htmlspecialchars($gameKey); ?>">
                        <input type="hidden" name="username" value="<?php echo htmlspecialchars($entry['username']); ?>">
                        <input type="hidden" name="submission_time" value="<?php echo htmlspecialchars($entry['submission_time']); ?>">
                        <input type="hidden" name="action" value="move_time">
                        <select name="new_level" required>
                            <?php foreach ($leaderboards as $key => $details): ?>
                                <option value="<?php echo htmlspecialchars($key); ?>" <?php echo $key === $gameKey ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($details[0]['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit">Move</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endforeach; ?>

</body>
</html>