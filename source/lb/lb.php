<?php
session_start();
//$_SESSION['username'] = $_SERVER['PHP_AUTH_USER'];
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="cache-control" content="max-age=0" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="pragma" content="no-cache" />
    <meta charset="utf-8">
    <link rel="icon" type="image/x-icon" href="../../../../../favicon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="https://jscdn.ct.ws/../assets/css/bootstrap.css" rel="stylesheet">
    <link href="https://jscdn.ct.ws/../assets/css/bootstrap-responsive.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="../assets/js/html5shiv.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
    <link rel="shortcut icon" href="../assets/ico/favicon.png">
  </head>

  <body>

    <div class="container">
      <div class="masthead">
<?php require_once($_SERVER["DOCUMENT_ROOT"] . '/oo-pass/tb.php'); ?>
      </div>

<?php



$selectedGame = isset($_GET['game']) ? urldecode($_GET['game']) : null;

// Load moderators list
$moderatorsFile = 'moderators.json';
$moderatorsData = file_exists($moderatorsFile) ? json_decode(file_get_contents($moderatorsFile), true) : [];
$moderators = $moderatorsData['moderators'] ?? [];

// Check if the logged-in user is a moderator
$isModerator = in_array($_SESSION['username'] ?? '', $moderators);

// Read the users.json file
$users = json_decode(file_get_contents($_SERVER["DOCUMENT_ROOT"] . '/oo-pass/lb/users.json'), true);

// Check if the session has been started and if the username exists in the session
if (isset($_SESSION['username'])) {
    // Check if the username exists in the users array from users.json
    if (!array_key_exists($_SESSION['username'], $users)) {
        // Destroy the session and redirect to leaderboard.php
        session_destroy();
        echo "<div class='alert alert-danger'><b>Error: Invalid username</b><br> Your account has been deleted by a moderator, and you have been logged out.<br>This could be because of an inappropriate username, multiple accounts with the same username (alt accounts), or something else.</div>";
        exit();
    }
}

?>


<!---<?php
// Load leaderboard configuration
$configFile = 'leaderboards_config.json';
$configData = file_exists($configFile) ? json_decode(file_get_contents($configFile), true) : [];
$leaderboardsConfig = $configData['leaderboards'] ?? [];

// Load leaderboard data from the JSON file
$leaderboardFile = 'leaderboards.json';
$leaderboardData = file_exists($leaderboardFile) ? json_decode(file_get_contents($leaderboardFile), true) : [];

?>--->

<?php
// Check if user is logged in
$isLoggedIn = isset($_SESSION['username']) && !empty($_SESSION['username']);
?>

<a class="btn btn-info" type="button" href="register.php" <?php echo $isLoggedIn ? 'style="display:none;"' : ''; ?>>
  <i class="icon-plus icon-white"></i> Register
</a>

<a class="btn btn-primary" type="button" href="<?php echo $isLoggedIn ? 'login.php' : 'login.php'; ?>">
  <i class="icon-<?php echo $isLoggedIn ? 'user' : 'tags'; ?> icon-white"></i> <?php echo $isLoggedIn ? 'Account' : 'Login'; ?>
</a>
<a class="btn btn-success <?php echo !$isLoggedIn ? '' : ''; ?>" type="button" href="<?php echo $isLoggedIn ? 'submit.php' : '#'; ?>" <?php echo !$isLoggedIn ? 'onclick="return false;"' : ''; ?>>
  <i class="icon-tasks icon-white"></i> Submit Score/Time
</a>


<?php if ($isModerator): ?>
  <a class="btn btn-danger" type="button" href="review.php">
    <i class="icon-flag icon-white"></i> Approve/Deny Submissions
  </a>
  <a class="btn btn-warning" type="button" href="manage_users.php">
    <i class="icon-list-alt icon-white"></i> Manage Users
  </a>
  <a class="btn btn-info btn-disabled" type="button" href="mngldb.php">
    <i class="icon-bookmark icon-white"></i> Manage Scores
  </a>
<?php endif; ?>

<p><small><i></i></small></p>


<?php if ($isLoggedIn): ?>
  <h3>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h3>
<?php else: ?>
  <h3>Welcome!</h3>
<?php endif; ?>


<?php
// Load leaderboard data from the JSON file
$leaderboardFile = 'leaderboards.json';
$leaderboardData = file_exists($leaderboardFile) ? json_decode(file_get_contents($leaderboardFile), true) : [];

// Determine selected game and level
$selectedGame = isset($_GET['game']) ? urldecode($_GET['game']) : null;
$selectedLevel = isset($_GET['level']) ? urldecode($_GET['level']) : (isset($leaderboardData['leaderboards'][$selectedGame]) ? array_key_first($leaderboardData['leaderboards'][$selectedGame]) : null);
?>

<div class="btn-group">
    <?php foreach ($leaderboardData['leaderboards'] ?? [] as $game => $levels): ?>
        <a href="?game=<?php echo urlencode($game); ?>" 
           class="btn btn-<?php echo ($game === $selectedGame) ? 'primary' : 'inverse'; ?>"><p class="<?php echo ($game == $selectedGame) ? '' : 'muted2'; ?>" style="margin-bottom:0px;">
           <?php echo htmlspecialchars($game); ?></p>
        </a>
    <?php endforeach; ?>
</div>

<?php if ($selectedGame && isset($leaderboardData['leaderboards'][$selectedGame])): ?> 
    <br><br><div class="btn-group">
        <?php foreach ($leaderboardData['leaderboards'][$selectedGame] as $index => $level): ?>
<a href="?game=<?php echo urlencode($selectedGame); ?>&level=<?php echo urlencode($index); ?>" class="btn btn-<?php echo ($selectedLevel !== null && $index == $selectedLevel) ? 'primary' : 'inverse'; ?>">
   <p class="<?php echo ($selectedLevel !== null && $index == $selectedLevel) ? '' : 'muted2'; ?>" style="margin-bottom:0px;">
               <?php echo htmlspecialchars($level['name']); ?></p>
            </a>
        <?php endforeach; ?>
    </div>

    <?php
    if ($selectedLevel !== null && isset($leaderboardData['leaderboards'][$selectedGame][$selectedLevel])):?>
        <?php 
        $levelData = $leaderboardData['leaderboards'][$selectedGame][$selectedLevel];
        $entries = $levelData['entries'] ?? [];
        usort($entries, function($a, $b) {
            return $a['score'] <=> $b['score']; // Default to ascending order
        });
        ?>
        <br><br>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th style="text-align:right;width:10px;"></th>
                    <th>Username</th>
                    <th>Score</th>
                    <th>Submission Time</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($entries as $index => $entry): ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo htmlspecialchars($entry['username']); ?></td>
                        <td><?php echo htmlspecialchars(number_format((float)$entry['score'], 3)); ?></td>
                        <td><?php echo htmlspecialchars($entry['submission_time']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Please select a level to view its leaderboard.</p>
    <?php endif; ?>
<?php else: ?>
    <p>Please select a game to view its leaderboard.</p>
<?php endif; ?>

<small><i>If a game has no leaderboard submissions, it will not show up.</i></small>

    </div> <!-- /container -->


    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://jscdn.ct.ws/../assets/js/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.min.js"></script>
    <script src="https://jscdn.ct.ws/../assets/js/bootstrap-transition.js"></script>
    <script src="https://jscdn.ct.ws/../assets/js/bootstrap-alert.js"></script>
    <script src="https://jscdn.ct.ws/../assets/js/bootstrap-modal.js"></script>
    <script src="https://jscdn.ct.ws/../assets/js/bootstrap-dropdown.js"></script>
    <script src="https://jscdn.ct.ws/../assets/js/bootstrap-scrollspy.js"></script>
    <script src="https://jscdn.ct.ws/../assets/js/bootstrap-tab.js"></script>
    <script src="https://jscdn.ct.ws/../assets/js/bootstrap-tooltip.js"></script>
    <script src="https://jscdn.ct.ws/../assets/js/bootstrap-popover.js"></script>
    <script src="https://jscdn.ct.ws/../assets/js/bootstrap-button.js"></script>
    <script src="https://jscdn.ct.ws/../assets/js/bootstrap-collapse.js"></script>
    <script src="https://jscdn.ct.ws/../assets/js/bootstrap-carousel.js"></script>
    <script src="https://jscdn.ct.ws/../assets/js/bootstrap-typeahead.js"></script>


    <script>
      document.addEventListener('DOMContentLoaded', function() {
        $('.mod').tooltip('hide');
      });
    </script>


  </body>
</html>