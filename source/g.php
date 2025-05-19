<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="cache-control" content="max-age=0" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="pragma" content="no-cache" />
    <meta charset="utf-8">
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
<div class="center-container">

<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                      <div class="modal-body">
    <form id="game-form" actfion="https://docs.google.com/forms/d/e/1FAIpQLSeDP44I2Dba7O94gls7dcj8ZFLClJiyxyZuOdxuTyTOvFjVkQ/formResponse" method="POST">
      <fieldset>
        <label for="name" style="display:none;">Game</label>
        <input type="text" id="name" name="entry.870559112" required readonly style="display:none;">
        
        <label class="radio">
          <input type="radio" name="entry.995089549" value="Doesn't load">
          Game doesn't load
        </label>
        <label class="radio">
          <input type="radio" name="entry.995089549" value="Trash">
          "Trash" game (bad gameplay)
        </label>
        <label class="radio">
          <input type="radio" name="entry.995089549" value="ADs">
          Game has ADs, such as "Wait 5 seconds to continue"
        </label>
        <label class="radio">
          <input type="radio" name="entry.995089549" value="Other">
          Other<br>
          <input type="text" id="other_reason" placeholder="Please specify..." disabled>
        </label>
        <button tgype="submit" class="btn btn-warning disabled"><i class="icon-warning-sign icon-white"></i> Submit</button> (keep in mind that repeated submissions will not go through)
      </fieldset>
    </form>
  </div>
</div>
<div>

<button onclick="document.getElementById('gamecontent').src=document.getElementById('gamecontent').src;" class="btn btn-info"><i class="icon-repeat icon-white"></i> Reload</button>
<button onclick="openFullscreen();" class="btn btn-primary"><i class="icon-resize-full icon-white"></i> Fullscreen</button>
<div class="btn-group">
  <button class="btn btn-success" onclick="openGameInAboutBlankTab()"><i class="icon-share-alt icon-white"></i> Popout</button>
  <button class="btn dropdown-toggle btn-success" data-toggle="dropdown">
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu">
    <li><a href=# onclick="openGameInAboutBlankTab()">New Tab</a></li>
    <li><a href=# onclick="openGame()">New Window</a></li>
  </ul>
</div>

  <!---<a href="#myModal" role="button" class="btn btn-danger" data-toggle="modal"><i class="icon-flag icon-white"></i> Report</a>--->

<script>
    // Function to get the value of the query string parameter 'id'
    function getQueryStringParameter(name) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(name);
    }

    // Ensure that we don't redefine window.onload but append to it
    window.onload = (function() {
        // Call any existing window.onload functionality if it exists
        if (window.onload) {
            window.onload();
        }

        // Now, add our additional logic
        const gameId = getQueryStringParameter('id'); // Get the 'id' from the query string

        // If the 'id' exists, proceed to update the data-game-name attribute
        if (gameId) {
            const reportButton = document.querySelector('a[data-toggle="modal"]');

            // If the <a> element exists, update the data-game-name attribute
            if (reportButton) {
                reportButton.setAttribute('data-game-name', gameId);
            } else {
            }
        } else {
        }
    })();
</script>

</div>
<br>
  <style>
    iframe.iframe-loaded+.iframe-beforeload {
      display: none;
    }

  </style>
<div class="well" style="width:100%;height:85vh;margin-bottom:-0px;position:relative;">
<iframe onload="this.classList.add('iframe-loaded')" src="" id="gamecontent" style="width:100%;height:100%;border:0px;border-radius:4px;background: url('') no-repeat center 3rem;"></iframe>
  <div class="iframe-beforeload" style="position: absolute; width: 100%; top: 0; text-align: center; margin-top: 1rem;"><i class="icon-signal icon-white"></i>Loading...</div>
</div>


</div>

<script>

var elem = document.getElementById("gamecontent");
function openFullscreen() {
  if (elem.requestFullscreen) {
    elem.requestFullscreen();
  } else if (elem.webkitRequestFullscreen) { /* Safari */
    elem.webkitRequestFullscreen();
  } else if (elem.msRequestFullscreen) { /* IE11 */
    elem.msRequestFullscreen();
  }
}


// Function to set iframe source
function setGame(link) {
    var iframe = document.getElementById('gamecontent');
    if (iframe) {
        iframe.src = link;

        // Add an event listener to handle after loading the new content
        iframe.onload = function() {
            var iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
            var squareElement = iframeDoc.getElementById('square no-select');
            if (squareElement) {
                Array.from(iframeDoc.body.children).forEach(el => {
                    if (el !== squareElement) el.remove();
                });
                squareElement.style.width = '100%';
                squareElement.style.height = '100%';
            }

            var metaTag = iframeDoc.querySelector('meta[name=viewport]');
            if (metaTag) {
                metaTag.content = 'initial-scale=1, maximum-scale=1, minimum-scale=0, user-scalable=yes, shrink-to-fit=yes';
            }
        };
    } else {
        alert('if you are reading this message, something has gone \seriously\ wrong.');
    }
}

// Function to modify URL and handle fetch response
async function modifyURL(url) {
    const modifiedURL = 'https://jscdn.ct.ws/oo-pass/dl/' + url + '.html';
    return modifiedURL
}

// Handle onload event and modify the URL
window.onload = async function() {
    // Extract query string
    const urlParams = new URLSearchParams(window.location.search);
    const id = urlParams.get('id');
    
    // Pass the query string to modifyURL function if id is present
    if (id) {
        const modifiedLink = await modifyURL(id);
        setGame(modifiedLink);
    } else {
        const modifiedLink = await modifyURL('');
        setGame(modifiedLink);
    }
};

async function openGame() {
    // Extract query string
    const urlParams = new URLSearchParams(window.location.search);
    const id = urlParams.get('id');

    // Pass the query string to modifyURL function if id is present
    if (id) {
        const modifiedLink = await modifyURL(id);
        windowpopup(modifiedLink);
    } else {
        const modifiedLink = await modifyURL('');
        windowpopup(modifiedLink);
    }
}

function windowpopup(url) {
    var popup = window.open('', '_blank', 'width=854,height=480');
    var iframe = popup.document.createElement('iframe');
    iframe.src = url;
    iframe.frameBorder = 0;
    iframe.scrolling = "no";
    iframe.style.top = "0";
    iframe.style.left = "0";
    iframe.style.right = "0";
    iframe.style.position = "fixed";
    iframe.style.height = "100%";
    iframe.style.width = "100%";
    iframe.style.border = 'none';
    var titlebar = popup.document.createElement('div');
    titlebar.id = 'titlebar';
    titlebar.style.position = 'fixed';
    titlebar.style.top = '0';
    titlebar.style.left = '0';
    titlebar.style.width = '100%';
    titlebar.style.height = '40px';
    titlebar.style.pointerEvents = 'none';
    titlebar.style.border = '0px solid #999';
    titlebar.style.textAlign = 'center';
    titlebar.style.fontFamily = 'Arial, sans-serif';
    titlebar.style.fontSize = '20px';
    titlebar.style.fontWeight = 'normal';
    titlebar.style.textShadow = '0 0px 0 rgba(255, 255, 255, 0.5)';
    titlebar.style.borderRadius = '0px';
    titlebar.style.marginBottom = '0px';

    popup.document.body.insertBefore(iframe, titlebar.nextSibling);
    popup.document.body.appendChild(titlebar);

    iframe.onload = function () {
        var iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
        var metaTag = iframeDoc.querySelector('meta[name=viewport]');
        if (metaTag) {
            metaTag.content = 'initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=yes';
        }
    };

    popup.document.close();
    popup.focus();
}

function openGameInNewTab() {
    // Extract query string
    const urlParams = new URLSearchParams(window.location.search);
    const id = urlParams.get('id');

    // Pass the query string to modifyURL function if id is present
    modifyURL(id).then(modifiedLink => {
        window.open(modifiedLink, '_blank');
    }).catch(err => {
        console.error('Error modifying URL:', err);
    });
}

function openGameInAboutBlankTab() {
    // Extract query string
    const urlParams = new URLSearchParams(window.location.search);
    const id = urlParams.get('id');

    // Pass the query string to modifyURL function if id is present
    modifyURL(id).then(modifiedLink => {
        var popup = window.open('about:blank', '_blank');
        var iframe = popup.document.createElement('iframe');
        iframe.src = modifiedLink;
        iframe.frameBorder = 0;
        iframe.scrolling = "no";
        iframe.style.top = "0";
        iframe.style.left = "0";
        iframe.style.right = "0";
        iframe.style.position = "fixed";
        iframe.style.height = "100%";
        iframe.style.width = "100%";
        iframe.style.border = 'none';
        popup.document.body.appendChild(iframe);

        iframe.onload = function () {
            var iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
            var metaTag = iframeDoc.querySelector('meta[name=viewport]');
            if (metaTag) {
                metaTag.content = 'initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=yes';
            }
        };

        popup.document.close();
        popup.focus();
    }).catch(err => {
        console.error('Error modifying URL:', err);
    });
}
</script>

</div>
    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://jscdn.ct.ws/../assets/js/jquery.js"></script>
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


