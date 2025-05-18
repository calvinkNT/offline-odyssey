<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Prevent caching by browsers -->
    <meta http-equiv="cache-control" content="max-age=0" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="pragma" content="no-cache" />
    
    <!-- Set character encoding -->
    <meta charset="utf-8">
    
    <!-- Set favicon -->
    <link rel="icon" type="image/x-icon" href="../../../../../favicon.ico">
    
    <!-- Ensure proper rendering and touch zooming -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Placeholder metadata -->
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Bootstrap CSS styles -->
    <link href="https://jscdn.ct.ws/../assets/css/bootstrap.css" rel="stylesheet">
    <link href="https://jscdn.ct.ws/../assets/css/bootstrap-responsive.css" rel="stylesheet">

    <!-- HTML5 shim for older IE versions -->
    <!--[if lt IE 9]>
      <script src="../assets/js/html5shiv.js"></script>
    <![endif]-->

    <!-- Icons for mobile devices -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
    <link rel="shortcut icon" href="../assets/ico/favicon.png">
  </head>

  <body>

    <div class="container">

      <div class="masthead">
        <!-- Include top bar or navigation from PHP file -->
        <?php require_once('tb.php'); ?>
      </div>

      <!-- Inline style for centering elements (currently unused) -->
      <style>
        .center-container {
          display: flex;
          flex-direction: column;
          align-items: center;
          justify-content: center;
        }
      </style>

      <div>
        <!-- About Me Section -->
        <h2>About Me</h2>
        I want to keep some level of anonymity in case this site does get found by admins.<br>
        I'm an 8th grader who loves coding and learning new things. When I'm not programming, I enjoy biking, swimming, and reading.<br>
        <hr>

        <!-- About the Site Section -->
        <h2>About the Site</h2>
        A while back, I found a huge archive of oo-pass. Seeing as I've played around with website development in the past <i>(I've mostly been writing scripts up to this point)</i>, I decided to give it a shot and set up a basic oo-pass site in two days. Ever since, I've slowly added new features, such as leaderboards, and I plan to set up a proxy soon. I'm quite surprised so many people use it.
        <hr>

        <!-- Developers Section -->
        <h2>Developers</h2>
        <!-- Add new contributors below, but please keep me credited. -->
        <li><u><a href="https://jscdn.ct.ws/offlineodyssey">CalvinK19</a></u>: original solo developer of the Offline Odyssey site</li>
        <li><u>Zxygo</u>: helped out with Dark Mode and very minor UI bugs</li>
        <hr>

        <!-- Acknowledgments Section -->
        <h2>Thanks to...</h2>
        <li><u>Minion Memes Inc.</u>: for all the oo-pass, from their <b>Ultimate Game Stash</b></li>
        <li><u>ChatGPT</u>: fixed some bugs since I was too lazy</li>
        <li><u>The Bootstrap Team</u>: for their amazing HTML/CSS framework</li>
        <li><u>CoolUBG.github.io</u>: for the "data" code, that allows you to download/transfer game data</li>
      </div>

    </div> <!-- /container -->

    <!-- JavaScript placed at the end for faster loading -->
    <script src="https://jscdn.ct.ws/../assets/js/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.min.js"></script>

    <!-- Bootstrap JavaScript plugins -->
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

    <!-- Form submission handler -->
    <script>
      // Prevent default form submission
      document.getElementById('bootstrapForm').addEventListener('submit', function(event) {
          event.preventDefault();
      });

      // jQuery-based form submission with AJAX
      $(document).ready(function () {
          $('#bootstrapForm').submit(function (event) {
              event.preventDefault();
              alert('Successfully submitted.');
              var extraData = {};
              $('#bootstrapForm').ajaxSubmit({
                  data: extraData,
                  dataType: 'jsonp',
                  error: function () {
                      alert('Successfully submitted.');
                  }
              });
          });
      });
    </script>

  </body>
</html>
