<?php
include("Twibber.class.php")
?>
<!doctype html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>TWiBBer - Das WBB MicroBlogging Social Network</title>
	<link rel="stylesheet" href="style.css" type="text/css"> 
	<link href='http://fonts.googleapis.com/css?family=Yanone+Kaffeesatz' rel='stylesheet' type='text/css'> 
    </head>
    <body>
	<header>TwBBs || <a href="http://wbblite2.de">WBBLite2.de</a></header>
	<div id="post_it">
	    <h3>Phineas, was machst du grade?</h3>
	    <textarea id="input_text" maxlength="250" autofocus placeholder="Schreib deinen Freunden hier, was du gerade tust."></textarea>
	    <br><div class="right"><label for="input_text">0 Zeichen</label><button id="twibber_it" onclick="dyn_submit();">Twibbern</button></div>
	<?php
	$Twibber->fetchTwibber(true,true);
	?>
	</div>
	<footer>&copy; 2010 by Kurtextrem &amp; Math-Board</footer>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.js"></script>
	<script src="javascript.js"></script>
    </body>
</html>
