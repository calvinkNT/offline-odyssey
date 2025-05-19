<?php

/*// Check if the auth cookie is set
if (!isset($_COOKIE["auth"])) {
    // Redirect to login page if the cookie is missing
    header("Location: https://jscdn.ct.ws/oo-pass/index.php");
    exit();
}*/

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // Initialize error flag
    $errorOccurred = false;

    $blacklistedWords = ['fuck', 'nigge', 'nigga', 'retard', 'shit', 'diddy', 'boob', 'balls', 'admin', 'owner', 'calvin', 'dick', 'penis', 'vagina', 'pussy'];


    // Check if username contains any blacklisted words
    foreach ($blacklistedWords as $blacklistedWord) {
        if (stripos($username, $blacklistedWord) !== false) {
            // Set a "banned" cookie and prevent registration
            setcookie("banned", "true", time() + 259200, "/");
            // echo '<br><br><div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><b>Username contains a blacklisted word. You have been timed out for 72 hours.</b></div>';
            $errorOccurred = true;
            // Check if the auth cookie is set
            echo "<script>alert(`really dude?`); window.location.href = 'https://jscdn.ct.ws/surprise.mp4';</script>"; // Change the URL as needed
            exit();
            break;
        }
    }


    // Check if passwords match
    if ($password !== $confirmPassword) {
        echo '<br><br><div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><b>Passwords do not match.</b></div>';
        $errorOccurred = true;
    }

    // Load existing users
    $usersFile = 'users.json';
    $usersData = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];

    // Check if username already exists
    if (isset($usersData[$username])) {
        echo '<br><br><div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><b>Username is taken.</b></div>';
        $errorOccurred = true;
    }

    // Only proceed with registration if no errors occurred
    if (!$errorOccurred) {
        // Hash the password for security
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Save the new user
        $usersData[$username] = ['password' => $hashedPassword];
        file_put_contents($usersFile, json_encode($usersData, JSON_PRETTY_PRINT));

        echo '<br><br><div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button><b>Registration succesful!</b></div>';
    }
}
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


      <!-- Registration Section -->
      <h2>Register Leaderboard Account</h2>

      <p>
        BEFORE REGISTERING, PLEASE READ THE FOLLOWING:
        <ul>
          <li><b>No Account Required to Play:</b> You <b>DO NOT NEED AN ACCOUNT</b> to <b>PLAY</b> oo-pass, but you <i>do</i> need an account to <b>submit scores</b>, to ensure no one else can take your username.</li><br>
          <li><b>Be Responsible:</b> <i>One irresponsible person can ruin it for everyone.</i> Spamming submissions or attempting to cheat the system will not be tolerated. Please act responsibly to maintain the integrity of the platform.</li><br>
          <li><b>No Ban System in Place (For Now):</b> There is <b>no ban system</b> at the moment. I can delete accounts if necessary, but I <b>wonâ€™t spend the effort on coding in a device-based ban system</b>. However, if people continue to misuse the submission process, I <b>will implement a ban system</b>. Please donâ€™t make me do that.</li><br>
          <li><b>Account Deletion & Bans:</b> I reserve the right to <b>ban or delete accounts at my discretion</b> without notifing the user. This includes repeated submissions, obviously faked submissions, or inappropriate usernames. <b>As long as you don't abuse the system, you'll be fine.</b></li><br>
          <li><b>Fake Scores:</b> If your <b>score is obviously faked</b>, your submission will be <b>declined. If you repeatedly submit fake scores</b>, your <b>account will be deleted</b>, and if a ban system is implemented, you will be banned.</li><br>
          <li><b>Submission Guidelines:</b> When submitting your score or time, <b>you must include both the score/time and a picture of the score ingame</b>. If you achieve a particularly <b>impressive time</b>, <b>I</b> or <b>other moderators</b> might ask for <b>additional proof</b>, like a <b>recording</b>.</li><br>
          <li><b>Username and Password:</b> You only need a <b>username</b> and <b>password</b> to register. No other personal information is required. Passwords are stored securely, but <b>don't use a password that you use anywhere else</b>.</li><br>
        </ul>
        </p>

      
      <form method="post" action="register.php">
        <p><b>TL;DR:</b> Don't abuse the system. <i>One person can ruin it for everyone.</i></p><br>
    
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" 
               pattern="[A-Za-z0-9_.]{4,15}" 
               minlength="4 maxlength=15" required><br>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required maxlength="30" ><br>
        
        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" maxlength="30" required><br><br>
        
        <button type="submit" class="btn btn-info">
            <i class="icon-plus icon-white"></i> Register
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

  </body>
</html>
