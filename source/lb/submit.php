<?php
// Start the session
session_start();

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

<a class="btn btn-inverse" type="button" href="leaderboard.php">&laquo; Back</a>

<?php
/*
// Read the users.json file
$users = json_decode(file_get_contents('users.json'), true);

// Check if the session has been started and if the username exists in the session
if (isset($_SESSION['username'])) {
    // Check if the username exists in the users array from users.json
    if (!array_key_exists($_SESSION['username'], $users)) {
        // Destroy the session and redirect to leaderboard.php
        session_destroy();
        echo "<div class='alert alert-danger'><b>Username not valid.</b> Your account has been deleted.</div>";
        exit();
    }
}*/
/*
if (!isset($_COOKIE["auth"])) {
    // Redirect to login page if the cookie is missing
    header("Location: https://jscdn.ct.ws/oo-pass/index.php");
    exit();
}*/

// Ensure user is logged in
if (!isset($_SESSION['username'])) {
    echo '<br><br><div class="alert alert-warning"><b>You must be logged in to submit leaderboard scores.</b><br>
    <a href="login.php">Login</a>, <a href="register.php">Register</a></div>';
    exit();
}

// Load leaderboard configuration
$configFile = 'leaderboards_config.json';
$configData = file_exists($configFile) ? json_decode(file_get_contents($configFile), true) : [];
$leaderboards = $configData['leaderboards'] ?? [];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_SESSION['username'];
    $gameSelection = explode('|', htmlspecialchars($_POST['game']));  // Updated for combined game and level selection
    $gameKey = $gameSelection[0];
    $levelName = $gameSelection[1];  // Get the level name
    $gameScore = round((float) htmlspecialchars($_POST['gameScore']), 3); // Allow up to 3 decimal places
    $uploadDir = "uploads/";
    $uploadFile = $uploadDir . basename($_FILES["scoreImage"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));

    // Get game details from config
    $gameDetailsArray = $leaderboards[$gameKey] ?? null;
    if (!$gameDetailsArray) {
        echo "Invalid game selection.";
        exit();
    }

    // Find the specific level entry
    $levelDetails = null;
    foreach ($gameDetailsArray as $details) {
        if ($details['name'] == $levelName) {
            $levelDetails = $details;
            break;
        }
    }

    if (!$levelDetails) {
        echo "Invalid level selection.";
        exit();
    }

    $gameName = $levelDetails['name']; // Set the game name to the level's name

    // Validate image file
    if (isset($_FILES["scoreImage"])) {
        $check = getimagesize($_FILES["scoreImage"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }

    // Check if file already exists
    if (file_exists($uploadFile)) {
        echo "Stop spamming! Jeez. Give us a sec.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["scoreImage"]["size"] > 500000) {
        echo "Error: your file is too large.";
        $uploadOk = 0;
    }

    // Allow specific file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        echo "Error:, only JPG, JPEG, and PNG files are allowed.";
        $uploadOk = 0;
    }

    // Save the uploaded file and submission data if the upload is OK
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["scoreImage"]["tmp_name"], $uploadFile)) {
        // Prepare the new entry for the submission
$gameNameWithLevel = $gameKey . ' :: ' . $levelName; // Use '::' as separator
        
        $newSubmission = [
            'username' => $username,
            'game_name' => $gameNameWithLevel, // Now includes both game name and level
            'game_score' => $gameScore, // The score now has 3 decimal places
            'file_path' => $uploadFile,
            'submission_time' => date("Y-m-d H:i:s"),
            'status' => 'pending' // The score is now pending review
        ];
        
        // Save the submission to the submissions file (it will be reviewed by a moderator before being added to the leaderboard)
        $submissionsFile = 'submissions.json';
        $currentSubmissions = file_exists($submissionsFile) ? json_decode(file_get_contents($submissionsFile), true) : [];
        $currentSubmissions[] = $newSubmission;
        file_put_contents($submissionsFile, json_encode($currentSubmissions, JSON_PRETTY_PRINT));

            echo "Submission successful! Your score is pending approval by moderators.";
        } else {
            echo "Sorry, there was an error uploading your file. Please make a suggestion if this error keeps occurring.";
        }
    }
}

?>

      <h2>Leaderboard Submission Form</h2>

      <!-- Form for leaderboard submission -->
      <form action="submit_score.php" method="post" enctype="multipart/form-data">
        <div class="control-group">
          <label for="game" class="control-label">Select Game:</label>
          <div class="controls">
            <select id="game" name="game" required class="input-xlarge">
                <?php foreach ($leaderboards as $gameKey => $detailsArray): ?>
                    <?php foreach ($detailsArray as $details): ?>
<option value="<?php echo htmlspecialchars($gameKey . '|' . $details['name']); ?>">
    <?php echo htmlspecialchars($gameKey . ' :: ' . $details['name']); ?> <!-- Game name is now before level name -->
</option>

                    <?php endforeach; ?>
                <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="control-group">
          <label for="gameScore" class="control-label">Game Score:</label>
          <div class="controls">
            <input type="number" step="0.001" id="gameScore" name="gameScore" required class="input-xlarge">
          </div>
        </div>
        
        <div class="control-group">
          <label for="scoreImage" class="control-label">Select Score Image <i>(take a full-screen photo, with the chromeOS taskbar and your game score)</i>:</label>
          <div class="controls">
            <input type="file" id="scoreImage" name="scoreImage" accept="image/*" required class="input-xlarge">
          </div>
        </div>
        
<hr>
      <p>
      Guidelines:
        <ul>
          <li><b>Be Responsible:</b> <i>One irresponsible person can ruin it for everyone.</i> Spamming submissions or attempting to cheat the system will not be tolerated. Please act responsibly to maintain the integrity of the platform.</li><br>
          <li><b>No Ban System in Place (For Now):</b> There is <b>no ban system</b> at the moment. I can delete accounts if necessary, but I <b>wonâ€™t spend the effort on coding in a device-based ban system</b>. However, if people continue to misuse the submission process, I <b>will implement a ban system</b>. Please donâ€™t make me do that.</li><br>
          <li><b>Account Deletion & Bans:</b> I reserve the right to <b>ban or delete accounts at my discretion</b> without notifing the user. This includes repeated submissions, obviously faked submissions, or inappropriate usernames. <b>As long as you don't abuse the system, you'll be fine.</b></li><br>
          <li><b>Fake Scores:</b> If your <b>score is obviously faked</b>, your submission will be <b>declined. If you repeatedly submit fake scores</b>, your <b>account will be deleted</b>, and if a ban system is implemented, you will be banned.</li><br>
          <li><b>Submission Guidelines:</b> When submitting your score or time, <b>you must include both the score/time and a picture of the score ingame</b>. If you achieve a particularly <b>impressive time</b>, <b>I</b> or <b>other moderators</b> might ask for <b>additional proof</b>, like a <b>recording</b> <i>(NTS, allow MP4 submissions)</i>.</li><br>
          <li><b>Misplaced Submissions:</b> If your select the <b>wrong game</b> or the game in the screenshot <b>doesn't have a leaderboard</b>, your submission will be <b>denied</b>.</li><br>
        </ul>
        </p>

            <button type="submit" class="btn btn-success">
                <i class="icon-tasks icon-white"></i> Submit
            </button>

      </form>

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
    <script src="https://jscdn.ct.ws/../assets/js/bootstrap-affix.js"></script>
  </body>
</html>
