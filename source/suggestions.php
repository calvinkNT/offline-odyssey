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
        <?php require_once('tb.php'); ?>
      </div>

      <style>
          .center-container {
              display: flex;
              flex-direction: column;
              align-items: center;
              justify-content: center;
          }
      </style>
      <div>
        <h1>Guidelines</h1>
        <ul>
          <li>All submissions are 100% anonymous, unless you choose to leave your name. If you wish to be contacted, leave your Discord handle (school emails are monitored). If I don't get back to you, then I might not need to or your suggestion was rejected.</li>
          <li>I might not be able to add some oo-pass.</li>
          <li>Please don't spam, and also make your suggestions understandable. If I can't understand what a suggestion is asking for, I will not implement it. Be specific for what you want- there is no character limit.</li>
          <li>Due to the complexity of this website, I am unable to create a fully downloadable copy as promised when this site first released (it was a <i>lot</i> more simple back then, making the notion seem plausible). However, if this site does get blocked, I have backups and alternate hosting sites ready.</li>
          <li>New features will not come immediately. I have stuff to do outside of developing this site.</li>
        </ul>
      </div>

      <div>
        <form id="bootstrapForm">
          <fieldset>
              <div class="form-group">
                  <textarea style="width: 100%; height:200px;" id="1734504686" name="entry.1734504686" class="form-control" required></textarea>
              </div>
          </fieldset>

        <button type="submit" class="btn btn-primary">
            <i class="icon-plus"></i> Send
        </button>
        <small> :: Double-check once you finish writing. When submitting a suggestion, it <b>creates a new entry</b> and does not edit over any other previous suggestion you send.</small>
        </form>
      </div>
    </div> <!-- /container -->

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
    document.getElementById('bootstrapForm').addEventListener('submit', function(event) {
        event.preventDefault();

        var suggestion = document.getElementById('1734504686').value;

        fetch('', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ suggestion: suggestion })
        })
        .then(response => response.json())
        .then(data => {
            alert('Successfully submitted.');
        })
        .catch(error => {
            alert('Successfully submitted.');
        });
    });
    </script>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the suggestion from the POST data
        $data = json_decode(file_get_contents('php://input'), true);
        $suggestion = $data['suggestion'];

        // Load existing suggestions from JSON file
        $suggestionsFile = 'suggestions.json';
        if (file_exists($suggestionsFile)) {
            $suggestions = json_decode(file_get_contents($suggestionsFile), true);
        } else {
            $suggestions = [];
        }

        // Add new suggestion
        $suggestions[] = ['suggestion' => $suggestion, 'timestamp' => time()];

        // Save updated suggestions back to JSON file
        file_put_contents($suggestionsFile, json_encode($suggestions, JSON_PRETTY_PRINT));

        // Respond with a success message
        echo json_encode(['status' => 'success']);
    }
    ?>
  </body>
</html>
