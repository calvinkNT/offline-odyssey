</div>
</div>
</div>

<script>
if (window.parent !== window) {
    //document.getElementById('result').innerText = 'This page is being rendered in an iframe (embedded).';
} else {
    //document.getElementById('result').innerText = 'This page is not being rendered in an iframe (not embedded).';
    alert(`This page is not being rendered in about:blank. You must go through the about:blank portal to use this site, to avoid detection.
Click OK to be sent to the "Open Portal" page.`);
window.location.href="https://jscdn.ct.ws/oo-pass/index.html"
}
</script>

<style>
body {
    background-color: #2f2f2f;
}

.navbar {
    background-color: #fafafa;
}

.nav > .disabled > a:hover {
  color: #999;
}

.nav-list .divider {
  background-color: transparent;
  -webkit-box-shadow: rgba(255, 255, 255, 0.07) 0 1px 0;
  -moz-box-shadow: rgba(255, 255, 255, 0.07) 0 1px 0;
  box-shadow: rgba(255, 255, 255, 0.07) 0 1px 0;
  border-bottom: 1px solid #121212;
}

.navbar .brand {
  text-shadow: 0 1px 0 black;
}

.navbar .divider-vertical {
  border: transparent;
  -webkit-box-shadow: rgba(255, 255, 255, 0.07) 1px 0 0;
  -moz-box-shadow: rgba(255, 255, 255, 0.07) 1px 0 0;
  box-shadow: rgba(255, 255, 255, 0.07) 1px 0 0;
  border-right: 1px solid #121212;
}

.navbar-inverse .brand {
  color: #555;
  text-shadow: 0 1px 0 white;
}
.navbar-inverse .brand:hover {
  color: #555;
}
.navbar-inverse .navbar-inner {
  background: #fafafa;
  border: 1px solid #030303;
  box-shadow: 0 1px 4px rgba(0, 0, 0, 0.5);
  background: -moz-linear-gradient(top, white 0%, #999999 100%);
  background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, white), color-stop(100%, #999999));
  background: -webkit-linear-gradient(top, white 0%, #999999 100%);
  background: -o-linear-gradient(top, white 0%, #999999 100%);
  background: -ms-linear-gradient(top, white 0%, #999999 100%);
  background: linear-gradient(to bottom, #ffffff 0%, #999999 100%);
  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffff', endColorstr='#999999',GradientType=0 );
}
.navbar-inverse .nav > li > a {
  color: #555;
}
.navbar-inverse .nav > li > a:hover {
  color: #333;
  background-color: blue;
}
.navbar-inverse .nav > .active > a,
.navbar-inverse .nav > .active > a:hover {
  background-color: #e5e5e5;
  box-shadow: 0 3px 8px rgba(0, 0, 0, 0.125) inset;
  color: #555555;
}
.navbar-inverse .nav li.dropdown.open > .dropdown-toggle,
.navbar-inverse .nav li.dropdown.active > .dropdown-toggle,
.navbar-inverse .nav li.dropdown.open.active > .dropdown-toggle {
  background-color: #e5e5e5;
  color: #555;
}
.navbar-inverse .nav li.dropdown > a:hover .caret {
  border-top-color: #555;
  color: #555;
}
.navbar-inverse .nav > li > a:focus,
.navbar-inverse .nav > li > a:hover {
  background-color: transparent;
  color: #333;
}
.navbar-inverse .nav li.dropdown.open > .dropdown-toggle,
.navbar-inverse .nav li.dropdown.active > .dropdown-toggle,
.navbar-inverse .nav li.dropdown.open.active > .dropdown-toggle {
  background-color: #e5e5e5;
  color: #555;
}
.navbar-inverse .nav li.dropdown.open > .dropdown-toggle .caret,
.navbar-inverse .nav li.dropdown.active > .dropdown-toggle .caret,
.navbar-inverse .nav li.dropdown.open.active > .dropdown-toggle .caret {
  border-bottom-color: #555;
  border-top-color: #555;
  color: #555;
}
.navbar-inverse .navbar-search .search-query {
  box-shadow: 0 3px 8px rgba(0, 0, 0, 0.6) inset;
  background-color: white;
  color: #333;
}
.navbar-inverse .navbar-search input.search-query:focus {
  box-shadow: 0 3px 8px rgba(0, 0, 0, 0.6) inset, 0 0 8px rgba(82, 168, 236, 0.6);
  box-shadow: 0 3px 8px rgba(0, 0, 0, 0.6) inset, 0 0 8px rgba(82, 168, 236, 0.9);
  padding: 4px 14px;
  outline: 0 none;
}
.navbar-inverse .nav li.dropdown > .dropdown-toggle .caret {
  border-bottom-color: #555;
  border-top-color: #555;
}
.navbar-inverse .nav li.dropdown > a:hover .caret {
  border-bottom-color: #333;
  border-top-color: #333;
}
.navbar-inverse .navbar-search .search-query:-moz-placeholder {
  color: #999;
}

</style>

<meta name="robots" content="noindex">
<meta name="darkreader-lock" content="" />

<?php

date_default_timezone_set('America/Chicago');

$tokenFilePath = $_SERVER['DOCUMENT_ROOT'] . '/oo-pass/tokens.json';

// Check if the 'auth_token' cookie exists
if (!isset($_COOKIE['auth_token'])) {
    // If no auth_token cookie, redirect the user to the login page
    echo "Error: No authentication token. Please close this tab and reauthenticate.";
    echo "<script>window.location.href='https://jscdn.ct.ws/oo-pass/login.php';</script>";
    header('Location: https://jscdn.ct.ws/oo-pass/'); // Replace with your login page URL
    exit();
}

// Check if the 'auth' cookie exists
if (!isset($_COOKIE['auth'])) {
    // If no auth cookie, redirect the user to the login page
    echo "Error: No authentication cookie. Please close this tab and reauthenticate.";
    echo "<script>window.location.href='https://jscdn.ct.ws/oo-pass/login.php';</script>";
    header('Location: https://jscdn.ct.ws/oo-pass/'); // Replace with your login page URL
    exit();
}

// If the 'auth_token' cookie is set, check if the token is valid and not expired
$token = $_COOKIE['auth_token'];

// Check if the tokens.json file exists and contains data
if (file_exists($tokenFilePath)) {
    $tokens = json_decode(file_get_contents($tokenFilePath), true);

    // Check if the token exists in the file and if it has expired
    if (isset($tokens[$token])) {
        $tokenData = $tokens[$token];

        // Check if token has expired
        if ($tokenData['expires'] < time()) {
            // Token has expired
            unset($tokens[$token]); // Remove the expired token
            file_put_contents($tokenFilePath, json_encode($tokens)); // Save updated tokens

            // Expire the cookies
            setcookie('auth_token', '', time() - 3600, '/'); 
            setcookie('auth', '', time() - 3600, '/'); 
            echo "Error: Authentication token has expired. Please close this tab and reauthenticate.";
            echo "<script>window.location.href='https://jscdn.ct.ws/oo-pass/login.php';</script>";
            // Redirect the user to the login page
            header('Location: https://jscdn.ct.ws/oo-pass/index.php');
            exit();
        }
    } else {
        // Token does not exist in the file
        // Expire the cookies
        setcookie('auth_token', '', time() - 3600, '/');
        setcookie('auth', '', time() - 3600, '/');
        echo "Error: Invalid authentication token. Please close this tab and reauthenticate. If you get this error after properly authenticating, try clearing your cookies.";
        echo "<script>window.location.href='https://jscdn.ct.ws/oo-pass/login.php';</script>";
        // Redirect to login page
        header('Location: https://jscdn.ct.ws/oo-pass/index.php');
        exit();
    }
} else {
    // If the tokens.json file does not exist, treat it as invalid session
    // Expire the cookies
    setcookie('auth_token', '', time() - 3600, '/');
    setcookie('auth', '', time() - 3600, '/');
    echo "Error: tokens.json file is non-existent. Please close this tab and authenticate, which will fix the error.";
    echo "<script>window.location.href='https://jscdn.ct.ws/oo-pass/login.php';</script>";
    header('Location: https://jscdn.ct.ws/oo-pass/index.php');
    exit();
}

?>
<?php
// Get the current page name to set the active class
$current_page = basename($_SERVER['PHP_SELF']);
?>

<nav class="navbar navbar-fixed-top">
  <div class="navbar-inner">
  <div class="container">
    <a class="brand">Οffline Οdyssey</a>
    <ul class="nav">

                <li class="<?php echo ($current_page == 'home.php') ? 'active' : ''; ?>"><a href="https://jscdn.ct.ws/oo-pass/home.php"><i class="icon-road icon-white"></i> Home</a></li>
                <li class="<?php echo ($current_page == 'list.php') ? 'active' : ''; ?>"><a href="https://jscdn.ct.ws/oo-pass/list.php"><i class="icon-book icon-white"></i> Games</a></li>
                <li class="<?php echo ($current_page == 'fav.php') ? 'active' : ''; ?>"><a href="https://jscdn.ct.ws/oo-pass/fav.php"><i class="icon-heart icon-white"></i> Favorites</a></li>
                <li class="<?php echo ($current_page == 'lb.php') ? 'active' : ''; ?>"><a href="https://jscdn.ct.ws/oo-pass/lb/lb.php"><i class="icon-star icon-white"></i> Leaderboards</a></li>
                <li class="<?php echo ($current_page == 'suggestions.php') ? 'active' : ''; ?>"><a href="https://jscdn.ct.ws/oo-pass/suggestions.php"><i class=" icon-fullscreen icon-white"></i> Suggestions</a></li>
                <li class="<?php echo ($current_page == 'p.php') ? 'active' : ''; ?>"><a href="https://jscdn.ct.ws/oo-pass/p.php"><i class="icon-globe icon-white"></i> Prοxies</a></li>
                <li class="<?php echo ($current_page == 'c.php') ? 'active' : ''; ?>"><a href="https://jscdn.ct.ws/oo-pass/c/c.php"><i class="icon-comment icon-white"></i> Global Chat</a></li>
                <li class="<?php echo ($current_page == 'data.php') ? 'active' : ''; ?>"><a href="https://jscdn.ct.ws/oo-pass/data.php"><i class="icon-hdd icon-white"></i> Data</a></li>
                <!---<li class="<?php echo ($current_page == 'set.php') ? 'active' : ''; ?>"><a href="https://jscdn.ct.ws/oo-pass/set.php"><i class="icon-wrench icon-white"></i> Settings</a></li>--->
                <li class="<?php echo ($current_page == 'about.php') ? 'active' : ''; ?>"><a href="https://jscdn.ct.ws/oo-pass/about.php"><i class="icon-info-sign icon-white"></i> About</a></li>

    </ul>
  </div>
  </div>
</nav><br>

<script>

window.addEventListener("keydown", function(event) {
    if (event.key === "`") {
        window.location.href = "https://jscdn.ct.ws/oo-pass/close.php"; // Redirects the main page, even if an iframe is focused
    }
}, true); // The "true" makes it capture events in the capturing phase before iframes can block them


// Check for existing theme cookie
document.addEventListener('DOMContentLoaded', function() {
    let theme = document.cookie.replace(/(?:(?:^|.*;\s*)theme\s*=\s*([^;]*).*$)|^.*$/, "$1");
    
    if (!theme) { // If the cookie does not exist
        document.cookie = "theme=l; path=/"; // Set the cookie to light mode
        theme = "l"; // Update the theme variable
        // Inject the meta tag
        let metaTag = document.createElement('meta');
        metaTag.name = "darkreader-lock";
        document.head.appendChild(metaTag);
    }
    
    if (theme === "d") {
        DarkReader.enable();
    } else {
        DarkReader.disable();
        
        // Inject the meta tag
        let metaTag = document.createElement('meta');
        metaTag.name = "darkreader-lock";
        document.head.appendChild(metaTag);
    }
});
</script>

<link href="https://jscdn.ct.ws/../assets/css/bootstrap.css?id=2" rel="stylesheet">
<link href="https://jscdn.ct.ws/../assets/css/bootstrap-responsive.css?id=2" rel="stylesheet">
<link href="https://jscdn.ct.ws/../assets/css/docs.css?id=2" rel="stylesheet">
<link href="https://jscdn.ct.ws/../assets/css/darkstrap.css?id=2" rel="stylesheet">

<style>
body > .navbar {
  font-size: 13px;
}

/* Change the docs' brand */
body > .navbar .brand {
  padding-right: 0;
  padding-left: 0;
  margin-left: 20px;
  float: right;
  font-weight: bold;
  color: #000;
  text-shadow: 0 1px 0 rgba(255,255,255,.1), 0 0 30px rgba(255,255,255,.125);
  -webkit-transition: all .2s linear;
     -moz-transition: all .2s linear;
          transition: all .2s linear;
}
body > .navbar .brand:hover {
  text-decoration: none;
  text-shadow: 0 1px 0 rgba(255,255,255,.1), 0 0 30px rgba(255,255,255,.4);
  color: white;
}

</style>

    <style type="text/css">
    html, body {
    width: auto !important;
    overflow-x: hidden !important;
}
.main-page-content {
    padding-top: 10px; /* Adjusted for Bootstrap 2.3.2 navbar height */
}
.navbar {
    margin-bottom:0px;
}

.masthead {
    height:0px;
    padding: 0px 0px;
    margins: 0px 0px;
}
      /*body {
        padding-top: 60px;
        padding-bottom: 60px;
      }*/
      html, body {
    width: auto !important;
    overflow-x: hidden !important;
}
.main-page-content {
    padding-top: 10px; /* Adjusted for Bootstrap 2.3.2 navbar height */
}
.navbar {
    margin-bottom:0px;
}

      /* Custom container */
      /*.container {
        margin: 0 auto;
        max-width: 1000px;
      }
      .container > hr {
        margin: 60px 0;
      }*/
/*
      /* Main marketing message and sign up button 
      .jumbotron {
        margin: 80px 0;
        text-align: center;
      }
      .jumbotron h1 {
        font-size: 100px;
        line-height: 1;
      }
      .jumbotron .lead {
        font-size: 24px;
        line-height: 1.25;
      }
      .jumbotron .btn {
        font-size: 21px;
        padding: 14px 24px;
      }

      .marketing {
        margin: 60px 0;
      }
      .marketing p + h4 {
        margin-top: 28px;
      }*/
            /* Main marketing message and sign up button */
      .jumbotron {
        margin: 30px 0;
        text-align: center;
      }
      .jumbotron h1 {
        font-size: 48px;
        line-height: 1;
      }
      .jumbotron .btn {
        font-size: 21px;
        padding: 14px 24px;
      }


      /* Customize the navbar links to be fill the entire space of the .navbar */
      /*.navbar .navbar-inner {
        padding: 0;
      }
      .navbar .nav {
        margin: 0;
        display: table;
        width: 100%;
      }
      .navbar .nav li {
        display: table-cell;
        width: 1%;
        float: none;
      }
      .navbar .nav li a {
        font-weight: bold;
        text-align: center;
        border-left: 1px solid rgba(255,255,255,.75);
        border-right: 1px solid rgba(0,0,0,.1);
      }
      .navbar .nav li:first-child a {
        border-left: 0;
        border-radius: 3px 0 0 3px;
      }
      .navbar .nav li:last-child a {
        border-right: 0;
        border-radius: 0 3px 3px 0;
      }*/
    </style>

<?php
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

<div class="container">
<div>