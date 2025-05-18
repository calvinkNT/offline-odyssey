<?php
// Path to the count.json file
$countFilePath = 'count.json';

// Read the existing data from count.json file
if (file_exists($countFilePath)) {
    $data = json_decode(file_get_contents($countFilePath), true);
} else {
    // If the file doesn't exist, initialize the data array
    $data = [
        'total_hits' => 0,
        'hits_last_12_hours' => 0,
        'hits_last_7_days' => 0
    ];
}

// yea to ban a user just add their username here and next time they log in they'll get banned
//$blacklistedWords = ['fuck', 'nigge', 'nigga', 'retard', 'shit', 'diddy', 'boob', 'balls', 'admin', 'owner', 'dick', 'penis', 'vagina', 'pussy', 'autism', 'autistic', 'acoustic', 'monkey', 'racist', 'racism', 'ligma', 'beta', 'alpha', 'landonious'];

// Check if username contains any blacklisted words
/*foreach ($blacklistedWords as $blacklistedWord) {
    if (stripos($_SERVER['PHP_AUTH_USER'], $blacklistedWord) !== false) {
        // Set a "banned" cookie and prevent registration
        setcookie("banned", "true", time() + 31540000, "/");
        // echo '<br><br><div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><b>Username contains a blacklisted word. You have been timed out for 72 hours.</b></div>';
        $errorOccurred = true;
        // Check if the auth cookie is set
        echo "<script>window.location.href = 'https://jscdn.ct.ws/oo-pass/banned.php';</script>";
        exit();
        break;
    }
}*/

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

  </head>

  <body>
  
    <!---<div class="bs-docs-social text-center">
        <h1>Welcome!</h1>
        <div style="max-width:1000px;width:100%;">
        <div class="row-fluid">
    <div class="span4">
      <h4><?php echo $data['total_hits']; ?> visits<br></h4>
      <i>lifetime, since 2025-03-17</i>
    </div>
    <div class="span4">
      <h4><?php echo $data['hits_last_12_hours']; ?> visits<br></h4>
      <i>last 12 hours</i>
    </div>
    <div class="span4">
      <h4><?php echo $data['hits_last_7_days']; ?> visits<br></h4>
      <i>last 7 days</i>
    </div>
</div>--->
        
        </div>
    </div>
  
    <div class="container">
      <div class="masthead">
        <?php require_once('tb.php'); ?>
    </div>
    
    
    <div class="row-fluid">
    <div class="span7">
      <div class="alert alert-success">
        <h1>Welcome!</h1><br>
        <p>The main reason I created this site is that I wanted to make it so that you can download oo-pass, for later play. When searching, just hit the download button to save the HTML file for offline play.<br><br>
        I just kept adding more and more features.<br><br>
        <b>Please do not talk to me about this site.</b>
        </p>
      </div>
    </div>
    <div class="span5">
    <div class="alert alert-info">
    <h3>Dark mode!</h3>
    <p>I'm experimenting with a permanent dark mode (who even uses light mode?).<br><br>
    It is a little ugly. I'm tweaking it to look somewhat nicer.<br><br>
    Also, there is a global chat now up. You can chat with anyone else online at the moment.
    Please don't abuse it.
    </p>
    </div>
    </div>
    </div>
<div class="row-fluid">

<div class="alert alert-warning" style="text-align:center;">
<div class="row-fluid">
    <div class="span4">
      <h4><?php echo $data['total_hits']; ?> visits<br></h4>
      <i>lifetime, since 2025-03-17</i>
    </div>
    <div class="span4">
      <h4><?php echo $data['hits_last_12_hours']; ?> visits<br></h4>
      <i>last 12 hours</i>
    </div>
    <div class="span4">
      <h4><?php echo $data['hits_last_7_days']; ?> visits<br></h4>
      <i>last 7 days</i>
    </div>
</div>
</div>

</div>

      <div class="row-fluid">
        <div class="span4">
          <h2>Downloads</h2>
          <p>You can download almost any<b>*</b> game you wish for offline play. I'm working on storing these oo-pass on my server here, so they're less likely to be blocked.</p>
          <p><a class="btn btn-primary" href="https://jscdn.ct.ws/oo-pass/list.php"><i class="icon-book icon-white"></i> Game List &raquo;</a></p>
        </div>
        <div class="span4">
          <h2>Leaderboards</h2>
          <p>Since almost all users are from my school, I thought having a school leaderboard would be cool. I update it with new features every so often.</p>
          <p><a class="btn btn-warning" href="https://jscdn.ct.ws/oo-pass/leaderboards.php"><i class="icon-star icon-white"></i> Leaderboard &raquo;</a></p>
        </div>
        <div class="span4">
          <h2>Favorites</h2>
          <p>If you like a game but don't want to look for it every time, just add it to your favorites and it'll be in that tab.</p>
          <p><a class="btn btn-danger" href="https://jscdn.ct.ws/oo-pass/fav.php"><i class="icon-heart icon-white"></i> Favorites &raquo;</a></p>
        </div>
      </div>
      
<!---
<br><hr><br>

            <div class="bs-docs-example">
              <div id="myCarousel" class="carousel slide">
                <div class="carousel-inner">
                  <div class="item active">
                    <img src="https://jscdn.ct.ws/assets/img/bootstrap-mdo-sfmoma-01.jpg" alt="">
                    <div class="carousel-caption">
                      <h4>PolyTrack</h4>
                      <p><i>"Trackmania, is that you?"</i></p>
                    </div>
                  </div>
                  <div class="item">
                    <img src="https://jscdn.ct.ws/assets/img/bootstrap-mdo-sfmoma-02.jpg" alt="">
                    <div class="carousel-caption">
                      <h4>Pokemon (multiple editions)</h4>
                      <p>It's Pokemon. You know what it is.</p>
                    </div>
                  </div>
                  <div class="item">
                    <img src="https://jscdn.ct.ws/assets/img/bootstrap-mdo-sfmoma-03.jpg" alt="">
                    <div class="carousel-caption">
                      <h4>Drive Survival</h4>
                      <p>Hypixel TNT Run + flying cars</p>
                    </div>
                  </div>
                
                <div class="item">
                    <img src="https://jscdn.ct.ws/assets/img/bootstrap-mdo-sfmoma-03.jpg" alt="">
                    <div class="carousel-caption">
                      <h4>FIFA</h4>
                      <p>soccer</p>
                    </div>
                 </div>
                 
                <div class="item">
                    <img src="https://jscdn.ct.ws/assets/img/bootstrap-mdo-sfmoma-03.jpg" alt="">
                    <div class="carousel-caption">
                      <h4>Retrobowl</h4>
                      <p>football</p>
                    </div>
                 </div>
                 
                <div class="item">
                    <img src="https://jscdn.ct.ws/assets/img/bootstrap-mdo-sfmoma-03.jpg" alt="">
                    <div class="carousel-caption">
                      <h4>Shootout Reloaded</h4>
                      <p>pew-pew</p>
                    </div>
                 </div>
                 
                <div class="item">
                    <img src="https://jscdn.ct.ws/assets/img/bootstrap-mdo-sfmoma-03.jpg" alt="">
                    <div class="carousel-caption">
                      <h4>Tag</h4>
                      <p>fun fact: I almost paid 20 dollars for Construct Premium to download this game, but then I found the creator's website and ripped it from there for free.</p>
                    </div>
                 </div>
                 
                <div class="item">
                    <img src="https://jscdn.ct.ws/assets/img/bootstrap-mdo-sfmoma-03.jpg" alt="">
                    <div class="carousel-caption">
                      <h4>Granny</h4>
                      <p>That game from 2017</p>
                    </div>
                 </div>

                <div class="item">
                    <img src="https://jscdn.ct.ws/assets/img/bootstrap-mdo-sfmoma-03.jpg" alt="">
                    <div class="carousel-caption">
                      <h4>DOOM</h4>
                      <p>yes, it can run DOOM.</p>
                    </div>
                 </div>
                 
                <div class="item">
                    <img src="https://jscdn.ct.ws/assets/img/bootstrap-mdo-sfmoma-03.jpg" alt="">
                    <div class="carousel-caption">
                      <h4>MotoX3M</h4>
                      <p>vroom vroom</p>
                    </div>
                 </div>
                 
                <div class="item">
                    <img src="https://jscdn.ct.ws/assets/img/bootstrap-mdo-sfmoma-03.jpg" alt="">
                    <div class="carousel-caption">
                      <h4>Riddle School</h4>
                      <p>shoutout to aniket</p>
                    </div>
                 </div>
                 
                <div class="item">
                    <img src="https://jscdn.ct.ws/assets/img/bootstrap-mdo-sfmoma-03.jpg" alt="">
                    <div class="carousel-caption">
                      <h4>Pheonix Wright - Ace Attorney</h4>
                      <p><i>"You are not a clown. You are the entire circus."</i></p>
                    </div>
                 </div>
                  
                <div class="item">
                    <img src="https://jscdn.ct.ws/assets/img/bootstrap-mdo-sfmoma-03.jpg" alt="">
                    <div class="carousel-caption">
                      <h4>Snow Rider 3D</h4>
                      <p>This game was popular back in December but now it's kinda died out from what I can see</p>
                    </div>
                 </div>

                </div>
                <a class="left carousel-control" href="#myCarousel" data-slide="prev">&lsaquo;</a>
                <a class="right carousel-control" href="#myCarousel" data-slide="next">&rsaquo;</a>
              </div>
            </div>
--->

<br>
<ul class="breadcrumb">
  <li><i>*Some oo-pass <b>require</b> a website to run. Those oo-pass cannot be downloaded, such as <u>PolyTrack 0.4.2, Shootout Reloaded, and Tag</u>. I'll make it work eventually, but I have other stuff to do.
</li>
</ul>

    </div> <!-- /container -->

    <script src="https://jscdn.ct.ws/../assets/js/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js" integrity="sha512-YUkaLm+KJ5lQXDBdqBqk7EVhJAdxRnVdT2vtCzwPHSweCzyMgYV/tgGF4/dCyqtCC2eCphz0lRQgatGVdfR0ww==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>    
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
$('#bootstrapForm').submit(function (event) {
    event.preventDefault()
    var extraData = {}
    $('#bootstrapForm').ajaxSubmit({
        data: extraData,
        dataType: 'jsonp',  // This won't really work. It's just to use a GET instead of a POST to allow cookies from different domain.
        error: function () {
            // Submit of form should be successful but JSONP callback will fail because Google Forms
            // does not support it, so this is handled as a failure.
            alert('Form Submitted. Thanks.')
            // You can also redirect the user to a custom thank-you page:
            // window.location = 'http://www.mydomain.com/thankyoupage.html'
        }
    })
    alert('Submitted.');
})
</script>
  </body>
</html>


