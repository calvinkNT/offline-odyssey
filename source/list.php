<?php
// Central Rating System
$file = "ratings.txt";
$user_id = isset($_COOKIE['user_id']) ? $_COOKIE['user_id'] : uniqid();
setcookie("user_id", $user_id, time() + (86400 * 365), "/");

function getRatings($game) {
    global $file;
    $ratings = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
    $game_ratings = $ratings[$game] ?? [];
    $avg_rating = count($game_ratings) ? round(array_sum($game_ratings) / count($game_ratings), 1) : 0;
    $total_ratings = count($game_ratings);
    $user_rating = $game_ratings[$_COOKIE['user_id']] ?? 0;
    return [$avg_rating, $total_ratings, $user_rating];
}

function saveRating($game, $rating) {
    global $file;
    $ratings = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
    $ratings[$game][$_COOKIE['user_id']] = $rating;
    file_put_contents($file, json_encode($ratings));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    saveRating($_POST["game"], $_POST["rating"]);
}
?>

<?php
// Fetch and process ratings from the file
$file = "ratings.txt";
$ratings = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
$game_ratings = [];

foreach ($ratings as $game => $game_data) {
    $avg_rating = count($game_data) ? round(array_sum($game_data) / count($game_data), 1) : 0;
    $game_ratings[] = [
        'game' => $game,
        'avg_rating' => $avg_rating
    ];
}

// Sort games by avg_rating in descending order
usort($game_ratings, function($a, $b) {
    return $b['avg_rating'] <=> $a['avg_rating'];
});
?>

<script>
    // Pass the sorted game ratings data to JavaScript
    var gameRatings = <?php echo json_encode($game_ratings); ?>;
    console.log(gameRatings); // Check if the data is correct
</script>



<!DOCTYPE html>
<html lang="en">
  <head>

<!-- star CSS -->
      <style>

.star-container {
    display: inline-block;
    font-size: 24px;
}

/* Base styles for stars */
.star {
    margin: 0; /* Remove any default margins */
    padding: 0; /* Remove any default padding */
    display: inline-block;
    cursor: pointer;
    color: lightgray; /* Default empty stars */
    transition: all 0.2s ease-in-out;
}

/* Filled stars based on the average rating */
.star.filled {
    color: #6767FF; /* Yellow for average rating */
}

/* Stars with no user rating should be light gray */
.star.no-user-rating {
    color: lightgray; /* Light gray stars for no rating */
}

/* Hover effect for stars */
.star:hover {
    color: #6767FF; /* Change color on hover */
    text-shadow: 0 0 10px rgba(255, 204, 0, 0.8);
}

/* Glowing effect when a selected star is hovered */
.star.filled:hover {
    color: #ffc700;
    text-shadow: 0 0 15px rgba(255, 215, 0, 1); /* Stronger glowing effect */
}


    </style>

<!-- star js -->



<script>
    function setRating(game, rating) {
        // Set the user's rating in a cookie
        document.cookie = game + "=" + rating + ";path=/";
        // Call the PHP function to save the rating
        sendRating(game, rating);
    }

    function highlightStars(game, rating) {
        let stars = document.querySelectorAll(".star[data-game='" + game + "']");
        stars.forEach((star, index) => {
            // Full stars for gray (avg) and yellow (user)
            if (star.classList.contains('gray-star')) {
                if (index < Math.floor(rating)) {
                    star.classList.add("active");
                    star.classList.remove("half");
                } 
                // Half stars
                else if (index === Math.floor(rating) && rating % 1 !== 0) {
                    star.classList.add("half");
                    star.classList.remove("active");
                }
                else {
                    star.classList.remove("active", "half");
                }
            }

            // For yellow (user) stars
            if (star.classList.contains('yellow-star')) {
                if (index < Math.floor(rating)) {
                    star.classList.add("active");
                    star.classList.remove("half");
                } 
                // Half stars for user
                else if (index === Math.floor(rating) && rating % 1 !== 0) {
                    star.classList.add("half");
                    star.classList.remove("active");
                }
                else {
                    star.classList.remove("active", "half");
                }
            }
        });
    }

    function sendRating(game, rating) {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.send("game=" + encodeURIComponent(game) + "&rating=" + rating);
        xhr.onload = function() {
            // Reload the page after submitting the rating
            location.reload();
        };
    }

    function hoverStars(game, rating) {
        let stars = document.querySelectorAll(".star[data-game='" + game + "']");
        stars.forEach((star, index) => {
            // Highlight stars on hover
            if (index < rating) {
                star.classList.add("hover");
            } else {
                star.classList.remove("hover");
            }
        });
    }

    function resetHover(game) {
        let stars = document.querySelectorAll(".star[data-game='" + game + "']");
        stars.forEach(star => {
            star.classList.remove("hover");
        });
    }
</script>





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

<!-- star php -->
<?php
function renderRatingUnit($game) { 
    list($avg_rating, $total_ratings, $user_rating) = getRatings($game);
?>
    <span class="star-container">
        <?php 
        // Display the stars based on the average rating and user rating
        for ($i = 1; $i <= 5; $i++): 
            $starClass = 'star';
            // Always display the average rating visually
            if ($i <= $avg_rating) {
                $starClass .= ' filled'; // Filled stars for average rating
            }

            // If the user has not rated yet, mark as no-user-rating
            if ($user_rating === 0) {
                $starClass .= ' no-user-rating';
            }
        ?><span class="<?php echo $starClass; ?>" 
                data-game="<?php echo $game; ?>" 
                onmouseover="hoverStars('<?php echo $game; ?>', <?php echo $i; ?>)" 
                onmouseout="resetHover('<?php echo $game; ?>')" 
                onclick="setRating('<?php echo $game; ?>', <?php echo $i; ?>)">
                &#9733; <!-- Star character -->
            </span><?php endfor; ?>

        <br>
        <!-- Display average rating and total ratings -->
        <p><small>(<?php echo $avg_rating; ?> â˜… avg. / <?php echo $total_ratings; ?> ratings)</small></p>
    </span>

    <!-- Hidden input to track the user's rating -->
    <input type="hidden" id="<?php echo $game; ?>_rating" name="rating" value="<?php echo $user_rating; ?>">
<?php } ?>

    <div class="container">

      <div class="masthead">
      
<?php require_once('tb.php'); ?>
      </div>

	<div>
	
	
	<div class="container">

	<!---
<div class="alert alert-success">
  If <b>a game has ADs</b>, <b>a game doesn't load</b>, or <b>you want to add a game</b>, <b>please make a suggestion!</b><br>
  Can't find a game? Try searching for <b>keywords</b>. For example, Pokemon Platinum. Try searching for "<b>Pokemon</b>" or "<b>Platinum</b>".
</div>--->

<!---<div class="alert alert-danger">
  <b>Games are being blocked.</b> This is due to the way they are stored. I cannot control this. <b>I am currently working on archiving games that are still up</b>, so there will be <b>no chance of them getting blocked</b>. <b>If a game goes missing</b>, please let me know and I will try to reinstate it</b>. I am currently trying to add <b>Drive Survival, a newer <b>Shootout Reloaded</b> version, and Bloons</b>.
</div>--->
<div class="alert alert-warning">
<b>Pending games</b>:
<li>Janissary Battles</li>
<li>Super Smash Flash 2</li>
<li>Shootout Reloaded (updated)</li>
<li>Bloons</li>
<li>Mouse/Cursor Battles</li>
<li>Sprinter</li>
</div>

<script>
window.onload = function() {
    // Call myFunction after everything has loaded
    myFunction();
};

function myFunction() {
    var input, filter, ul, li, a, i, txtValue;
    input = document.getElementById("myInput");
    filter = input.value.toUpperCase();
    ul = document.getElementById("myUL");
    li = ul.getElementsByTagName("li");

    // Create an array to hold the list items with ratings
    var itemsWithRatings = [];

    // Iterate through the list items to populate the array with their ratings
    for (i = 0; i < li.length; i++) {
        a = li[i].getElementsByTagName("h4")[0];
        txtValue = a.textContent || a.innerText;

        // Get the rating for the current game
        var rating = 0; // Default rating for games with no rating
        for (var j = 0; j < gameRatings.length; j++) {
            if (gameRatings[j].game === txtValue) {
                rating = gameRatings[j].avg_rating;
                break;
            }
        }

        // Push the li and rating as an object into the array
        itemsWithRatings.push({li: li[i], rating: parseFloat(rating)});
    }

    // If the search input is empty, sort items by rating in descending order
    if (filter === "") {
        // Sort by rating descending using Array.prototype.sort()
        itemsWithRatings.sort(function(a, b) {
            return b.rating - a.rating;  // Sort by rating in descending order
        });

        // Clear the current list (remove all existing li elements)
        ul.innerHTML = '';

        // Append the sorted items back into the <ul> in the new order
        for (i = 0; i < itemsWithRatings.length; i++) {
            ul.appendChild(itemsWithRatings[i].li);  // Re-append each item in sorted order
        }
    }

    // Now filter the items based on the search query (filter by game name)
    for (i = 0; i < li.length; i++) {
        a = li[i].getElementsByTagName("h4")[0];
        txtValue = a.textContent || a.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            li[i].style.display = "";
        } else {
            li[i].style.display = "none";
        }
    }
}
</script>
<div class="text-center">
<form class="form-search" id="searchform">

    <span class="add-on" style="margin-right:-28px;margin-top:5px;"><i class="icon-search">‌</i>‌</span>

    <!---<button class="btn disabled" style="border-top-left-radius:9999px;border-bottom-left-radius:9999px;" placeholder="Search"><i class="icon-search"></i>‌</button>--->
    <input type="text" id="myInput" style="height:30px;width:300px;border-radius:20px;padding-left:30px;" class="span2 search-query" onkeyup="myFunction()">
    <!---<button type="submit" class="btn" onclick="myFunction()" style="border-top-right-radius:9999px;border-bottom-right-radius:9999px;">Search</button>--->
</form>
</div>
<div class="row justify-content-center align-items-center" style="width: 100%; padding: 120px; padding-top:0px;">
<div class="text-center">
<!---<div class="input-prepend"><span class="add-on"><i class="icon-search">‌</i>‌</span><input style="height:30px;width:300px;" class="span2" id="myInput" type="text" onkeyup="myFunction()"></div>--->


<script>
  document.getElementById("searchform").addEventListener("submit", function(event) {
    event.preventDefault(); // Prevent the form from submitting normally
    myFunction();
  });

</script>

<!---<div class="input-prepend"><span class="add-on"><i class="icon-random">â€Œ</i> Search</span><input style="height:30px;width:300px;" class="span2" id="myInput" type="text" onkeyup="myFunction()"></div>--->
</div>
<ul class="thumbnails" id="myUL">


<!--- gams --->


</ul>
</div>
    </div>
    </div>
    	</div>

    </div> <!-- /container -->


    <script>
    /*
        fetch('games_data.json')
            .then(response => response.json())
            .then(games => {
                const gameListElement = document.getElementById('myUL');

                games.forEach(game => {
                    const listItem = document.createElement('li');
                    listItem.classList.add('game-listing', 'span5');

                    listItem.innerHTML = `
                        <div class="well">
                            <div class="caption">
                                <h4 class="game-name">${game.name}</h4>
                                <div class="game-actions">
                            <p><a href="https://jscdn.ct.ws/oo-pass/g.php?id=${game.id}"
                            class="btn btn-primary"><i class="icon-play icon-white"></i> Play</a> <a
                            href="https://jscdn.ct.ws/oo-pass/dl/${game.id}.html" download class="btn btn-success">
                            <i class="icon-circle-arrow-down icon-white"></i> Download</a><br><br>
                                <php renderRatingUnit("${game.id}"); ?>
                                </div>
                            </div>
                        </div>
                    `;

                    gameListElement.appendChild(listItem);
                });
            })
            .catch(error => {
                console.error("Error loading the JSON data:", error);
            });
    */
    fetch('https://jscdn.ct.ws/oo-pass/games_data.json')
        .then(response => response.json())
        .then(games => {
            const gameListElement = document.getElementById('myUL');
            const favorites = getCookies().favorites ? JSON.parse(getCookies().favorites) : [];

            games.forEach(game => {
                const listItem = document.createElement('li');
                listItem.classList.add('game-listing', 'span5');

                // Check if the game is already in favorites
                const isFavorite = favorites.includes(game.name);

                listItem.innerHTML = `
                    <div class="well well-large" style="width:95%;margin-bottom:-15px;">
                        <div class="caption">
                            <h4 class="game-name">${game.name}</h4>
                            <div class="game-actions">
                                <p><a href="https://jscdn.ct.ws/oo-pass/g.php?id=${game.id}"
                                class="btn btn-primary"><i class="icon-play icon-white"></i> Play</a> 
                                <a href="https://jscdn.ct.ws/oo-pass/dl/${game.id}.html" download class="btn btn-success">
                                <i class="icon-circle-arrow-down icon-white"></i> Download</a> 
                                <a href="#" class="btn btn-danger ${isFavorite ? 'btn-disabled' : ''}" 
                                onclick="addToFavorites('${game.name}', this)">
                                <i class="icon-heart icon-white"></i>${isFavorite ? ' <s>Favorite</s>' : ' Favorite'}</a>

                            </div>
                        </div>
                    </div>
                `;

                gameListElement.appendChild(listItem);

                // If the game is already in favorites, make sure the button reflects that
                if (isFavorite) {
                    const button = listItem.querySelector('.btn-danger');
                    button.classList.add('disabled');
                    button.innerHTML = `<i class="icon-heart icon-white"></i> <s>Favorite</s>`;
                }
            });
            myFunction();
        })
        .catch(error => {
            console.error("Error loading the JSON data:", error);
        });

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

    // Function to add a game to favorites
    function addToFavorites(gameName, buttonElement) {
        const favorites = getCookies().favorites ? JSON.parse(getCookies().favorites) : [];

        if (!favorites.includes(gameName)) {
            favorites.push(gameName);
            setCookie('favorites', JSON.stringify(favorites), 7); // Save the favorites for 7 days

            // Disable the button and update text
            buttonElement.classList.add('disabled');  // Disable the button
            buttonElement.innerHTML = `<i class="icon-heart icon-white"></i> <s>Favorite</s>`;
        } else {
            alert(`${gameName} is already in your favorites.`);
        }
    }
</script>


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


