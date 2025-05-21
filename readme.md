# Offline Odyssey

An advanced PHP game site with a Leaderboard, Global Chat, and Data Management.  
[Setup YouTube Tutorial](https://youtu.be/_Vfs6aw9OgM) :: [Game Catalogue Update YouTube Tutorial](https://youtu.be/oRtXLeSlI2k)

## License

This project uses a dual-license approach:

- All **code** (PHP, JavaScript, HTML) is licensed under the **GNU General Public License v3.0**  
  → https://www.gnu.org/licenses/gpl-3.0.html

- All **non-code content** (artwork, written text, sound, and design assets) that is **not an asset of a game or game code** is licensed under the **Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License**  
  → https://creativecommons.org/licenses/by-nc-sa/4.0/

### Summary of Your Rights:

**You can:**
- Use and modify the code as long as you keep it under GPLv3.
- Share and remix non-code content non-commercially with credit and under the same license.

**You cannot:**
- Use non-code assets (like images, writing, sounds) for commercial purposes.
- Re-license modified code under a different license.

## Attribution

If you use or modify this project, you must give credit to the original author(s).

https://calvink19.co/offlineodyssey  
Author: CalvinK19

For more legal details, see the full LICENSE file.

## Known (present) Issues
1. Deleting accounts with leaderboard submissions attempts to remove the submissions from the leaderboard. Although to the leaderboard format being updated to support games and levels, the account-deletion/score modification format was not updated to the newer format. **Deleting an account with leaderboard submissions from the User Management will nuke the leaderboards.** The only way to delete an account as of now is to remove the username and password from `/lb/users.json`.
2. Due to the games being rendered via JS instead of server-side via PHP, the `games_data.json` file is accessible through extensive network log searching. Rendering games via PHP would enable the use of the rating system and to have `games_data.json` blocked from external viewing through `.htaccess`.
3. For whatever reason, tokens do NOT expire when the `about:blank` window is closed. This could be exploited for further access into the site.
4. Leaderboard accounts can be registered with the same name with different capital/lowercase letters. Even though accounts are limited to 4-20 characters, A-z, 0-9, and allowed the `-` and `_` characters, accounts with different case names can be registed such as `calvink19` and `CalvinK19`. These would be treated as seperate accounts.
5. Finding new games has to be implemented, a page with maybe "top games". Trying to find new things to play is hard, and offputs users.

## Docs

```
* This contains an explanation of how the whole thing works- from the functions of the token system , to the features that were never implemented.
* You will need prior knowledge in how websites work. This will not be able to be hosted on static hosting services such as GitHub Pages, Codeberg Pages, AWS R2 Buckets, Google Sites, or other as it requires PHP.
* It will be split into sections, and important filenames or other data will be capitalized.
* I wrote this all myself. I'm not quite good at writing documentation, so if you have any questions on what certain functions have please ask me. I'll then update the docs. However, this should give you a mostly complete understanding of how this site operates.

Section 1   Portal System
Section 2   Navbar Code
Section 3   Local File Authentication
Section 4   Game listings / Rating system
Section 5   Global Chat
Section 6   Leaderboard
Section 7   Suggestions
Section 8   Proxy Page
Section 9   About Page
Section 10  Hosting
Section 11  Updating Game Library
Section 12  Settings Page
Section 13  Access Keys
Section 14  Visitor Count
Section 15  Data Page

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Quick explanation of each file and its purpose:

./
    .accesskey_htaccess      | .htaccess file required for "Access Key" authentication
    .accesskey_htpasswd      | .htpasswd file required for "Access Key" authentication
    .htaccess                | regular .htaccess file, disables directory listing
    about.php                | "About" page with credits
    auth.php                 | See Section 3.
    count.json               | Counts visitors
    data.php                 | Allows user to upload, download, and delete game data
    download_portal.html     | See Section 3.
    fav.php                  | Page where visitors see favourited games.
    favicon.ico              | 1pixel x 1pixel "invisible" favicon.
    favorite.php             | Handles adding/removing games from favourites
    g.php                    | Page with game embed where users can reload, fullscreen, or popout games
    games_data.json          | Contains game name and associated base64 filename
    get_ratings.php          | See Section 4.
    home.php                 | Homepage of the site. Contains visitor counter.
    index.html               | Allows users to open the portal.
    login.php                | Password dialogue to access the site.
    prox.php                 | List of proxies.
    ratings.txt              | Stores ratings for games.
    set.php                  | Hidden Settings Page. See Section 12.
    signup.php               | Sign-in/sign-up page where users input access key, password, and username to sign up. Part of the legacy Access Key system.
    signupwrong.php          | Displays an error message when an incorrect access key is used during sign-up. Part of the legacy Access Key system.
    suggestions.json         | Where suggestions are stored.
    tb.php                   | Titlebar/navbar code.
    tokens.json              | Currently valid tokens.
    
./lb
    ./uploads                | where photos of pending submissions are stored.
    deobfcc.html             | See Section 3.
    deobfcc_abb.html         | See Section 3.
    deobf_index.html         | See Section 3.
    lb.php                   | Main Leaderboard page.
    leaderboards.json        | Holds LB data for all games and their levels.
    leaderboards_config.json | Configuration data for leaderboards. Defines Games and their Levels.
    login.php                | Login/acct. management page
    manage_users.php         | Manage users, change password, delete. only for Moderators.
    mngldb.php               | Broken ATM. Managed leaderboard data.
    moderators.json          | Json list of moderators usernames.
    register.php             | Page to create a new account.
    review.php               | Where moderators approve/deny submissions.
    submissions.json         | Submissions stored that have not been approved or denied.
    submit.php               | Submit leaderboard score.
    users.json               | Username and MD5 password hash of all users.
    
./dl
    *.html                   | Game files
    
./c
    c.php                    | Main interface of chatting
    chatlog.txt              | Entire log of all messages sent
    delete_message.php       | Handles deletion of messages
    messages.json            | Last 50 messages sent. this file is polled every 0,25 seconds to check for new msg.
    online_users.json        | Used to keep track of online users.
    online_users.php         | Handles keeping track of online users.

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

=== SECTION 1
=== PORTAL SYSTEM
    The portal is an iframe injected into the about:blank tab. When you open the portal , it embeds you on LOGIN.PHP , which contains the whitelist system, password validation, lockout system, and token system.
    
    Whenever the correct password is entered , the token is set to the "auth_token" cookie , and the cookie "auth" is set.
    The "auth" token is a remnant of the old authentication system, which only set the "auth" cookie. The old auth system only checked whether the "auth" cookie existed. The new auth system sets a token which expires in 1 hour.
    
    An example token looks like this : "9707699078b3aa2015acb1a0db57a24e". The token is saved to the file TOKENS.JSON, and the cookie is set. The TOKENS.JSON file also contains an expiration date for each token, so a token cannot be faked.
    
    Whenever a page is visited, the token stored in the "auth_token" cookie is checked along with any file stored in TOKENS.JSON. If the cookie doesn't match, it errors out with "Invalid token" and redirects the user to the login portal. If the "auth_token" cookie expired or is not present, it errors out with "No token" and redirects the user to the login portal.
    
    Whenever any user visits any page which requires a token (e.g. it loads in TB.PHP, the titlebar code), it checks the token and deletes any other expired tokens.

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

=== SECTION 2
=== TITLEBAR CODE
    The titlebar code is stored in TB.PHP. This file contains the code for the navbar/titlebar, the token authentication code, the CSS loading code, and the iframe checker. All that happens in this area is that tokens are checked, CSS is loaded, and the navbar elements are created.
    
    The CSS is Bootstrap 2.3.2, an old HTML/CSS framework from 2012. I used it for its skeuomorphic look, although it isn't too hard to re-style it. All styles are built off of Bootstrap, since CSS is not my strong suit.
    
    The assets for Bootstrap are stored in the root of the server in the /assets directory. Copy over the file "assets" into the root of your server.
    
    Darkstrap.css is just the dark theme for the site. If you want to enable toggling, see Section 12.
      
    It does run pretty slow on WiFi, taking about half a second to load each page when navigated.
    
    The iframe checker makes sure that the page is being rendered in an iframe, to prevent search history contamination. If it isn't embedded, it redirects the user to the "Open Portal" page where they can open the about:blank portal.

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

=== SECTION 3
=== LOCAL FILE AUTHENTICATION
    Local authentication, like what is in use for other sites, was not possible on the original due to CORS. CORS is locked down by InfinityFree, the hosting platform I used. In order to be able to download the portal to authenticate from a local HTML file, CORS needs to be modified in order to allow requests. Since CORS was blocked for InfinityFree, I was not able to make this work fully.
    
    If your web server allows modification of CORS, the files "auth.php", "download_portal.html", and "/lb/deobfcc_abb.html" can be used to (respectively) authenticate the user/set tokens (deobfcc_abb.html does this already), download the portal needed without a fixed portal link, and contact auth.php for it to handle authentication.
    
    DEOBFCC.HTML and DEOBF_INDEX.HTML are part of this system.

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

=== SECTION 4
=== GAME LISTING / RATING SYSTEM
    The game listings can be found at LIST.PHP, which grabs the list data from GAMES_DATA.JSON and presents them to the user. 
    There was a rating system in place, which needs some modification in order to work successfully.
    In order to make the rating system work fully, the code will need to be modified to render the game list via PHP instead of via JS. This is needed in order to make the rating units show up, which will allow different users to rate games. Higher rated avg. games show up higher on the search results, with lower rated games taking place lower. Games which are not rated yet show up below lower rated games.
    
    The actual files of the games are stored in /dl, with names encoded in Base64 for better "stealth" when viewing network logs. The Python script used to update the game listings from the Ultimate Game Stash PDF does account for this, and encodes all file names into Base64 as listings are generated.
    
    The "play game" page is at G.PHP.
    
    Favourites are stored in cookies, which are also Base64-encoded.

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

=== SECTION 5
=== GLOBAL CHAT
    Global chat is available at /c/c.php. It's based off of "Simple PHP Chat" (https://github.com/arkanis/simple-chat), but heavily modified.
    New messages are polled every 0,25 seconds, which doesn't take up much memory (I've only tested this with around 4-7 people). The last 50 messages (what gets polled) is at /c/messages.json, and the entire chatlog is stored at /c/chatlog.txt. The online users are pinged every 2 seconds.
    
    For easier moderation, I've set it up so Leaderboard accounts are required to chat.
    
    Deleting messages are still a little broken. Once you delete a message, you can still chat but your own new messages will not show up for you until you reload.

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

=== SECTION 6
=== LEADERBOARD
    This is gonna be a long one, since Leaderboard is pretty complex.
    NOTE: All Leaderboard-related files are in the /lb directory.
    
        + SECTION 6a
        + Accounts
        Accounts are used for submitting times and chatting. You can manage accounts by logging in to a moderator account and clicking the MANAGE USERS button. BE WARNED! Deleting accounts from this panel will remove their leaderboard times. Due to the format in which times are stored have changed, deleting accounts might nuke the leaderboards and erase all data. THIS HAS NOT BEEN FIXED. Another issue is that since accounts can use captial and lowercase letters, two accounts can have the same "name" with capital/lowercase (e.g. accounts "Test" and "test" can exist). Patches have been put in place to only allow 4-20 characters, A-Z upper/lower, and allow full stop punctuation or underscores. Accounts are stored in MD5 hash at /lb/users.json.
        
        + SECTION 6b
        + Moderators
        Moderator accounts can be made by adding the username to the /lb/moderators.json file. Moderators can approve/deny leaderboard times, modify scores (the page is broken, not updated to the newer score format), and change passwords or delete user accounts entirely.
        
        + SECTION 6c
        + Adding new leaderboards
        Leaderboards have two "depths" if you can call it. Games and levels. Games are the umbrella under which different levels can be added.
        Here is an example of leaderboards_config.json.
    
        ~~~    
        {
            "leaderboards": {
                "Contra": [
                    {
                        "name": "Contra",
                        "sort_order": "descending"
                    }
                ],
                "PolyTrack 0.2.0": [
                    {
                        "name": "Lvl. 1",
                        "sort_order": "ascending"
                    },
                    {
                        "name": "Lvl. 2",
                        "sort_order": "ascending"
                    }
                ]
            }
        }
        ~~~
        
        You can create new levels inside of games, and define whether they are being sorted ascending or descending. (Another bug- they are always sorted ascending.)
        However, these leaderboards won't show up on the radio button selectors unless someone submits a time for that specific game and level.
        
        + SECTION 6d
        + "Waiting" submissions
        Submissions that are yet to be approved or denied are stored in submissions.json. Images for proof of time are stored in /uploads, but are deleted once the submission is approved or denied.

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

=== SECTION 7
=== SUGGESTIONS
    Suggestions are stored in suggestions.json. Whenever a suggestion is submitted into the submission box, it adds a new entry along with timestamp into the json file. There is no way to view suggestions other than to view the suggestions.json file.

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

=== SECTION 8
=== PROXY PAGE
    The proxy page contains a set of proxy links, which open the proxy inside an embed into an about:blank tab.

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

=== SECTION 9
=== ABOUT PAGE
    The "About" page credits contributors and services used in development. As the original author, I request that my name remain credited. If you modify the page, please ensure appropriate attribution for the original work.

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

=== SECTION 10
=== HOSTING
    1. Make a new file on your Desktop called "host". Copy over the "source" folder and "newhost.py" into "host".
    2. Open "newhost.py" file. Make sure to have Python installed, and to install all dependencies.
    3. Specify URL and path (e.g. "games.com" and "oo"). This is where the site will be hosted. (games.com/oo)
    4. Copy it over once its modified to the specified path in the web server. If you have control over CORS, you can modify it to use the downloadable portal covered in Section 3.
    5. Put the "assets" folder into the root of the server, keeping sure it's named "assets". This folder contains the CSS.

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

=== SECTION 11
=== UPDATING GAME LIBRARY
    This site is entirely built off of the Ultimate Game Stash by Minion Memes Inc.
    Docs: https://docs.google.com/document/d/1_FmH3BlSBQI7FGgAQL59-ZPe8eCxs35wel6JUyVaG8Q/edit
    Folder: https://drive.google.com/drive/folders/1ou3mI5xJVQv8Vt_MvwejPtf7zStSnU-s
    
    To update the library:
    1. Download the Google Docs as a PDF, and rename it to "UGS.pdf". Copy it to a new folder on your Desktop called "update". This folder will be where all other code goes.
    2. Open the folder link (at the top of the doc) and download the folder as a ZIP file.
    3. Create a new directory inside the "update" folder called "games".
    4. Copy the downloaded games into /games.
    5. Run "update.py" inside the "update" folder. It should rename all the game html files into Base64, and generate a new "games_data.json" file.
    6. Copy the renamed and updated game files into the website /dl directory.
    7. Replace "games_data.json" in the website directory.

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

=== SECTION 12
=== SETTINGS PAGE
    The Settings page (set.php) was only used for one thing: changing the theme. If you'd like to, you can remove the "darkstrap.css" (the dark mode) and add in a DarkReader JS file into tb.php. Then, add the settings page to the titlebar. You'll be able to change the theme. 

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

=== SECTION 13
=== ACCESS KEYS
    There are remnants of an old Access Key system, where specified moderators would be allowed to generate keys for new users to sign up in order to use the site. You can find these remnants in the set.php, where moderators can generate keys, and signup.php, where users can sign up a new account with a generated key. In order to fully implement this system:
    1. Change the portal to log in to "signup.php" instead of "login.php"
    2. Uncomment the access key code in set.php, and the "Logged in as..." / Logout button code.
    3. Add set.php to the titlebar
    4. Rename .accesskey_htaccess and .accesskey_htpasswd to .htaccess and .htpasswd.
    SIGNUPWRONG.PHP is meant to serve as a "password wrong" error. Just a JS alert, then redirects the user back to the access-key-login screen.

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

=== SECTION 14
=== VISITOR COUNT
    Whenever a visitor successfully logs in, their timestamp is added to the COUNT.JSON file. It keeps track of how many users visited this site in the last 12 hours, week, and all time. The PHP code for incrementing the counter is in login.php and the display code is in home.php.

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

=== SECTION 15
=== DATA PAGE
    The data page (data.php) allows users to download locally saved files from games, usually containing high scores, records, or unlocked weapons (Depending on the game). The files are downloaded as ".save" files, meaning they can reupload them to restore data in case of clearing cookies.

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Thank you to moonkeyoo, NeoXFlame, SilentWarriorK7/RealEToHPlayer, _ToL_/_TofLixillion_, Nedi, and ZxygoAlt/GlueFur.
calvink19.co/offlineodyssey

```
