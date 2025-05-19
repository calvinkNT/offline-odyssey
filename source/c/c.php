<?php

session_start();

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

<style>
code {
text-wrap: wrap;
    
}

#messages li {
    transition: background-color 0.1s ease-in-out;
}

#messages li:hover {
    background-color: rgba(255, 255, 255, 0.05);
    //cursor: default;
}

#messages small {
    cursor: default;
}
</style>

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

<?php

// Ensure user is logged in
if (!isset($_SESSION['username'])) {
    echo '<br><br><div class="alert alert-warning" style="margin-top:-30px;"><b>You must be logged in to chat.</b><br>
    You can log-in or register a new account in the <u>Leaderboards</u> tab.';
    exit();
}


/**
 * Simple Chat v2.0.2 by Stephan Soller
 * http://arkanis.de/projects/simple-chat/
 */

// Name of the message buffer file. You have to create it manually with read and write permissions for the webserver.
$messages_buffer_file = "messages.json";
// Number of most recent messages kept in the buffer.
// Note that message list on clients only shows 1000 messages to avoid slowdown (see JavaScript code below).
$messages_buffer_size = 50;
// Disabled by default, set to true to enable. Appends each chat messages to a chatlog.txt text file.
// This log file is uncapped, so you have to clean it form time to time or it can get very large.
$enable_chatlog = true;

if ( isset($_POST["content"]) and isset($_POST["name"]) ) {
	// Create the message buffer file if it doesn't exist yet. That way we don't need a setup and it's writable since it
	// was created by the process executing PHP (usually the webserver).
	if ( ! file_exists($messages_buffer_file) )
		touch($messages_buffer_file);
	
	// Open, lock and read the message buffer file
	$buffer = fopen($messages_buffer_file, "r+b");
	flock($buffer, LOCK_EX);
	$buffer_data = stream_get_contents($buffer);
	
	// Append new message to the buffer data or start with a message id of 0 if the buffer is empty
	$messages = $buffer_data ? json_decode($buffer_data, true) : [];
	$next_id = (count($messages) > 0) ? $messages[count($messages) - 1]["id"] + 1 : 0;
	$messages[] = [ "id" => $next_id, "time" => time(), "name" => $_POST["name"], "content" => $_POST["content"] ];
	
	// Remove old messages if necessary to keep the buffer size
	if (count($messages) > $messages_buffer_size)
		$messages = array_slice($messages, count($messages) - $messages_buffer_size);
	
	// Rewrite and unlock the message file
	ftruncate($buffer, 0);
	rewind($buffer);
	fwrite($buffer, json_encode($messages));
	flock($buffer, LOCK_UN);
	fclose($buffer);
	
	// Optional: Append message to log file (file appends are atomic)
	if ($enable_chatlog)
		file_put_contents("chatlog.txt", date("Y-m-d H:i:s") . "\t" . strtr($_POST["name"], "\t", " ") . "\t" . strtr($_POST["content"], "\t", " ") . "\n", FILE_APPEND);
	
	exit();
}

?>
<script type=module>

// Function to get the list of users
async function getUsers() {
    const response = await fetch('/oo-pass/lb/users.json');
    const data = await response.json();
    return Object.keys(data);
}

// Function to parse mentions in the message content
async function parseMentions(content) {
    const users = await getUsers();
    const mentionRegex = /@(\w+)/g;
    return content.replace(mentionRegex, (match, username) => {
        if (users.includes(username)) {
            return `<span style="color: #57B9FF;">@${username}</span>`;
        } else {
            return match;
        }
    });
}


	// Remove the "loading…" list entry
	document.querySelector("ul#messages > li").remove()
	
	document.querySelector("form").addEventListener("submit", async event => {
		const form = event.target
		const name =  form.name.value
		const content =  form.content.value
		// Prevent the browsers default action (send form data and show the result page). We just want to send the message without reloading the page.
		event.preventDefault()
		
		// Only send a new message if it's not empty (also it's ok for the server we don't need to send senseless messages)
		if (name == "" || content == "")
			return
		
		// Append a "pending" message (a message not yet confirmed from the server) as soon as the POST request is send. The
		// textContent property automatically escapes HTML so no one can harm the client by injecting JavaSript code.
		await fetch(form.action, { method: "POST", body: new URLSearchParams({name, content}) })
		const messageList = document.querySelector("ul#messages")
		const messageElement = messageList.querySelector("template").content.cloneNode(true)
			//messageElement.querySelector("small").textContent = name
			messageElement.querySelector("span").textContent = content
		//messageList.append(messageElement)
		
		messageList.scrollTop = messageList.scrollHeight
		form.content.value = ""
		form.content.focus()
	})
	
	// Poll-function that looks for new messages
	async function poll_for_new_messages() {
		// We want the browser to revalidate the cached messages.json file every time. That is it should send a
		// conditional request with an If-Modified-Since header. This is the default behaviour in Firefox 115.
		// In Chrome 114 it's not. It just uses the cached response without revalidation, thus missing new messages.
		// Hence we explicitly tell fetch to revalidate via a conditional request. Because naming things is hard the
		// option to do just that is { cache: "no-cache" }. See https://javascript.info/fetch-api#cache
		// or https://developer.mozilla.org/en-US/docs/Web/HTTP/Caching#force_revalidation
		const response = await fetch("messages.json", { cache: "no-cache" })
		
		// Do nothing if messages.json wasn't found (doesn't exist yet probably)
		if (!response.ok)
			return
		
		const messages = await response.json()
		const messageList = document.querySelector("ul#messages")
		const messageTemplate = messageList.querySelector("template").content.querySelector("li")
		
		// Determine if we should scroll the message list down to the bottom once we inserted all new messages.
		// Only do that if the user already is almost at the bottom (50px at max from it). Otherwise it gets really
		// annoying when the list scrolls down every 2 seconds while you want to read old messages further up. Check the
		// pixel distance before changing the message list. Otherwise the check gets thrown off by removed or new messages.
		const pixelDistanceFromListeBottom = messageList.scrollHeight - messageList.scrollTop - messageList.clientHeight
		const scrollToBottom = (pixelDistanceFromListeBottom < 50)
		
		// Remove the pending messages from the list (they are replaced by the ones from the server later)
		for (const li of messageList.querySelectorAll("li.pending"))
			li.remove()
		
// Remove deleted messages from the log
const existingMessages = messageList.querySelectorAll("li");
existingMessages.forEach((message) => {
    const messageId = message.dataset.messageId;
    if (messageId && !messages.find((msg) => msg.id === parseInt(messageId))) {
        message.remove();
    }
});

		
		// Get the ID of the last inserted message or start with -1 (so the first message from the server with 0 will
		// automatically be shown).
		const lastMessageId = parseInt(messageList.dataset.lastMessageId ?? "-1")
		
let lastSender = null;

for (const msg of messages) {
    if (msg.id > lastMessageId) {
        const date = new Date(msg.time * 1000);
        const messageElement = messageTemplate.cloneNode(true);
        messageElement.classList.remove("pending");
        
        messageElement.dataset.messageId = msg.id; // Add message ID to the element

        //const nameLine = `<div class="username style=margin-top: 10px;"><b><code style='color:#57B9FF;'>${msg.name}</code></b></div>`;
        const nameLine = "";
        const contentLine = `<b><code style='color:#57B9FF;'>${msg.name}</code></b> | <code style='color:white;'>${msg.content}</code> <small><b>${Intl.DateTimeFormat(undefined, { dateStyle: "short", timeStyle: "medium" }).format(date)}</b></small>`;

        if (msg.name !== lastSender) {
            messageElement.querySelector("span").innerHTML = nameLine + contentLine;
            lastSender = msg.name;
        } else {
            messageElement.querySelector("span").innerHTML = contentLine;
        }

        // Add delete button
        const deleteButton = messageElement.querySelector("button");
        if (msg.name === '<?php echo htmlspecialchars($_SESSION['username']); ?>') {
            deleteButton.style.display = '';
        } else {
            deleteButton.style.display = 'none';
        }

        deleteButton.addEventListener('click', async () => {
            const response = await fetch('delete_message.php', {
                method: 'POST',
                body: new URLSearchParams({ id: msg.id }),
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            });

            if (response.ok) {
                messageElement.remove();
            }
        });
        
        

        messageList.append(messageElement);
        messageList.dataset.lastMessageId = msg.id;
    }
}



		// Remove all but the last 1000 messages in the list to prevent browser slowdown with extremely large lists
		for (const li of Array.from(messageList.querySelectorAll("li")).slice(0, -1000))
			li.remove()
		
		// Finally scroll down to the newes messages
		if (scrollToBottom)
			messageList.scrollTop = messageList.scrollHeight - messageList.clientHeight
	}
	
	// Kick of the poll function and repeat it every two seconds
	poll_for_new_messages()
	setInterval(poll_for_new_messages, 250)
	
	
        function updateOnlineUsers() {
            $.ajax({
                type: "GET",
                url: "online_users.php",
                success: function(data) {
                    $("#online-users").html(data);
                }
            });
        }

        setInterval(updateOnlineUsers, 2000); // Update every 10 seconds
        updateOnlineUsers(); // Update immediately

</script>

<style>
    /*html { margin: 0em; padding: 0; }*/
	.body { height: 88vh; box-sizing: border-box; margin: 0;
		font-family: sans-serif; font-size: medium; //color: #333;
		display: flex; flex-direction: column; gap: 1em; }
	.body > h1 { flex: 0 0 auto; }
	.body > ul#messages { flex: 1 1 auto; word-wrap: break-word;}
	.body > form { flex: 0 0 auto; }
    ul#messages li small { font-size: 0.59em; color: gray; }
    
    #messages {list-style-type: none;}

	//h1 { margin: 0; padding: 0; font-size: 2em; }
	
	form { font-size: 1em; margin: 0; padding: 0; }
	form p { margin: 0; padding: 0; display: flex; gap: 0.5em; }
	form p input { font-size: 1em; min-width: 0; }
	form p input[name=name] { flex: 0 1 10em; }
	form p input[name=content] { flex: 1 1 auto; }
	form p button {}
	
	h1, ul#messages, form { width: 100%; box-sizing: border-box; margin: 0 auto; }
	
	#messages li button {float: right;}
	
	code { overflow-x: auto; white-space: pre-wrap; word-wrap: break-word; }
</style>

<div class="row-fluid">

  <div class="span9">

  <div class="body">

      <div class="well well-small" id="online-users" style="margin-bottom:0px;"></div>


<ul id="messages" class="list-unstyled well well-small" style="height: 85vh; overflow-y: scroll;">
    <li>Loading…</li>
    <template>
        <li class="text-muted">
            <span>…</span>
            <button class="btn btn-danger btn-mini" style="display: none;"><i class="icon-white icon-trash"></i>‌</button>
        </li>
    </template>
</ul>

<form method="post" action="<?= htmlentities($_SERVER["PHP_SELF"], ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, "UTF-8") ?>" class="form-inline">
    <p class="form-group">
            <input type="text" style="display:none;" name="name" placeholder="Name" disabled id="disabledInput" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" class="input-small form-control">

        <input type="text" name="content" placeholder="Message" class="input-large form-control" autofocus>
        <button class="btn btn-primary" id="send" onclick="document.getElementById('send').button('loading')" data-loading-text="Sending..."><i class="icon-comment icon-white"></i> Send &raquo;</button>
    </p>
</form>
  </div>

  
  </div>
  <div class="span3">
  
<div class="well" style="margin-top:auto;margin-bottom:auto;">
<p class="text-center"><b><u>Rules:</u></b></p>
1. No hate speech (includes racial slurs).<br>
2. No doxxing, attacking, harassing, or threats.<br>
3. No spam.<br>
4. No NSFW (don't be freaky).<br>
5. No impersonation.<br>
6. Don't call other users by their IRL names unless they're fine with it.<br>
7. Whitelist requests go in the #whitelists channel in the Discord or in suggestions.<br>
8. Please do not call me by my real name if you know I made the site.<br>
9. Only the most recent 50 messages are displayed, but all messages are logged.
</div>
  
  
  </div>

    </div> <!-- /container -->
</div>

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

    <script>
    document.getElementById('bootstrapForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent form from submitting the usual way
    });

    $(document).ready(function () {
        $('#bootstrapForm').submit(function (event) {
            event.preventDefault();
            alert('Successfully submitted.');
            var extraData = {}
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

