<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <!-- Set favicon -->
    <link rel="icon" type="image/x-icon" href="../../../../../favicon.ico">
    <!-- Ensure proper rendering and touch zooming -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Page description and author (optional) -->
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Include Bootstrap styles -->
    <link href="https://jscdn.ct.ws/../assets/css/bootstrap.css" rel="stylesheet">
    <link href="https://jscdn.ct.ws/../assets/css/bootstrap-responsive.css" rel="stylesheet">

    <!-- HTML5 shim for IE6-8 support -->
    <!--[if lt IE 9]>
      <script src="../assets/js/html5shiv.js"></script>
    <![endif]-->

    <!-- Apple touch icons and favicon -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
    <link rel="shortcut icon" href="../assets/ico/favicon.png">
  </head>

  <body>
    <div class="container">
      <div class="masthead">
        <!-- Include navigation/header from external PHP file -->
        <?php require_once('tb.php'); ?>
      </div>

      <div class="container">
        <div class="row justify-content-center align-items-center" style="width: 100%; padding: 120px; padding-top:0px;">
          <ul class="thumbnails" id="myUL">
            <!-- Favorite games will be inserted here -->
          </ul>
          <!-- Message shown when no favorites are present -->
          <p id="noFavoritesMessage" style="text-align: center; color: #888;"></p>
        </div>
      </div>
    </div>

    <script>
        // Get all cookies as an object
        function getCookies() {
            const cookies = document.cookie.split('; ');
            const cookieObj = {};
            cookies.forEach(cookie => {
                const [key, value] = cookie.split('=');
                cookieObj[key] = decodeURIComponent(value);
            });
            return cookieObj;
        }

        // Set a cookie with optional expiration in days
        function setCookie(name, value, days) {
            const expires = new Date();
            expires.setTime(expires.getTime() + (days * 24 * 60 * 60 * 1000));
            document.cookie = `${name}=${encodeURIComponent(value)}; expires=${expires.toUTCString()}; path=/`;
        }

        // Remove a game from favorites list and update cookie
        function removeFromFavorites(gameName) {
            let favorites = getCookies().favorites ? JSON.parse(getCookies().favorites) : [];
            const index = favorites.indexOf(gameName);

            if (index !== -1) {
                favorites.splice(index, 1); // Remove game
                setCookie('favorites', JSON.stringify(favorites), 7); // Update cookie
                window.location.reload(); // Refresh UI
            }
        }

        // Fetch and display favorite games
        fetch('oo-pass_data.json')
            .then(response => response.json())
            .then(ooPass => {
                const gameListElement = document.getElementById('myUL');
                const favorites = getCookies().favorites ? JSON.parse(getCookies().favorites) : [];

                if (favorites.length === 0) {
                    // Show message if no favorites
                    document.getElementById('noFavoritesMessage').innerText = "You have no favorites! To add a game to favorites, simply click on the 'Add to Favorites' button, and it'll show up here.";
                } else {
                    // Filter only favorite games
                    const favoriteGames = ooPass.filter(game => favorites.includes(game.name));

                    favoriteGames.forEach(game => {
                        const listItem = document.createElement('li');
                        listItem.classList.add('game-listing', 'span5');

                        // Create HTML for each favorite game
                        listItem.innerHTML = `
                            <div class="well well-large" style="width:95%;margin-bottom:-15px;">
                                <div class="caption">
                                    <h4 class="game-name">${game.name}</h4>
                                    <div class="game-actions">
                                        <p>
                                          <a href="https://jscdn.ct.ws/oo-pass/g.php?id=${game.id}" class="btn btn-primary">
                                            <i class="icon-play icon-white"></i>
                                          </a> 
                                          <a href="https://jscdn.ct.ws/oo-pass/dl/${game.id}.html" download class="btn btn-success">
                                            <i class="icon-circle-arrow-down icon-white"></i>
                                          </a> 
                                          <a href="#" class="btn btn-danger" onclick="removeFromFavorites('${game.name}')">
                                            <i class="icon-minus-sign icon-white"></i>
                                          </a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        `;
                        gameListElement.appendChild(listItem);
                    });
                }
            })
            .catch(error => {
                console.error("Error loading the JSON data:", error);
            });
    </script>

    <!-- Load Bootstrap JavaScript components -->
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
