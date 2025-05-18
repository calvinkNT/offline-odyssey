<!DOCTYPE html>
<html lang="en">
  <head>
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

      <div class="container">
        <div class="row justify-content-center align-items-center" style="width: 100%; padding: 120px; padding-top:0px;">
          <ul class="thumbnails" id="myUL">
            <!-- Favorite oo-pass will appear here -->
          </ul>
          <p id="noFavoritesMessage" style="text-align: center; color: #888;"></p>
        </div>
      </div>

    </div> <!-- /container -->

    <script>
        // Function to get all cookies as an object
        function getCookies() {
            const cookies = document.cookie.split('; ');
            const cookieObj = {};
            cookies.forEach(cookie => {
                const [key, value] = cookie.split('=');
                cookieObj[key] = decodeURIComponent(value);
            });
            return cookieObj;
        }

        // Function to set a cookie
        function setCookie(name, value, days) {
            const expires = new Date();
            expires.setTime(expires.getTime() + (days * 24 * 60 * 60 * 1000));
            document.cookie = `${name}=${encodeURIComponent(value)}; expires=${expires.toUTCString()}; path=/`;
        }

        // Function to remove a game from favorites
        function removeFromFavorites(gameName) {
            let favorites = getCookies().favorites ? JSON.parse(getCookies().favorites) : [];
            const index = favorites.indexOf(gameName);

            if (index !== -1) {
                favorites.splice(index, 1);
                setCookie('favorites', JSON.stringify(favorites), 7); // Save the updated favorites for 7 days
                // alert(`${gameName} has been removed from your favorites!`);
                window.location.reload(); // Reload the page to show updated list
            } else {
                // alert(`${gameName} is not in your favorites.`);
            }
        }

        // Function to display the favorite oo-pass
        fetch('oo-pass_data.json')
            .then(response => response.json())
            .then(oo-pass => {
                const gameListElement = document.getElementById('myUL');
                const favorites = getCookies().favorites ? JSON.parse(getCookies().favorites) : [];

                // Check if there are no favorites
                if (favorites.length === 0) {
                    document.getElementById('noFavoritesMessage').innerText = "You have no favorites! To add a game to favorites, simply click on the 'Add to Favorites' button, and it'll show up here.";
                } else {
                    // Filter oo-pass that are in favorites
                    const favoriteGames = oo-pass.filter(game => favorites.includes(game.name));

                    favoriteGames.forEach(game => {
                        const listItem = document.createElement('li');
                        listItem.classList.add('game-listing', 'span5');

                        listItem.innerHTML = `
                            <div class="well well-large" style="width:95%;margin-bottom:-15px;">
                                <div class="caption">
                                    <h4 class="game-name">${game.name}</h4>
                                    <div class="game-actions">
                                        <p><a href="https://jscdn.ct.ws/oo-pass/g.php?id=${game.id}"
                                        class="btn btn-primary"><i class="icon-play icon-white"></i></a> 
                                        <a href="https://jscdn.ct.ws/oo-pass/dl/${game.id}.html" download class="btn btn-success">
                                        <i class="icon-circle-arrow-down icon-white"></i></a> 
                                        <a href="#" class="btn btn-danger" onclick="removeFromFavorites('${game.name}')">
                                        <i class="icon-minus-sign icon-white"></i></a>
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

    <!-- Le javascript
    ================================================== -->
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
