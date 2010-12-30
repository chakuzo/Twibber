<?php
include("Twibber.class.php");
?>
<!doctype html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>TWiBBer - Das WBB MicroBlogging Social Network</title>
	<link rel="stylesheet" href="style.css" type="text/css"> 
	<link href='http://fonts.googleapis.com/css?family=Yanone+Kaffeesatz' rel='stylesheet' type='text/css'> 
	<link href='http://fonts.googleapis.com/css?family=Neucha' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Reenie+Beanie' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Droid+Sans' rel='stylesheet' type='text/css'>
    </head>
    <body>
	<header>TwBBs || <a href="http://wbblite2.de">WBBLite2.de</a></header>
	<div id="logo"><a href="index.php"><img src="res/img/logo.png"></a></div>
	<div id="post_it">
	    <h3>Phineas, was machst du grade?</h3>
	    <div id="status"></div>
	    <label for="nickname" id="label_nick">Nickname: </label><input type="text" id="nickname">
	    <textarea id="input_text" maxlength="250" autofocus placeholder="Schreib deinen Freunden hier, was du gerade tust."></textarea>
	    <br><div class="right"><label for="input_text">0 Zeichen</label><button id="twibber_it" onclick="dyn_submit();">Twibbern</button></div>
	<?php
	if($_GET['nick'] == "" && $_GET['search'] == ""){
	    $Twibber->fetchTwibber(true,true);
	}elseif($_GET['search'] == ""){
	    $Twibber->fetchTwibber(true, false, $_GET['nick']);
	}else{
	    $Twibber->searchTwibber($_GET['search']);
	}
	?>
	</div>
	<footer>&copy; 2010 by Kurtextrem &amp; Math-Board</footer>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.js"></script>
	<script src="javascript.js"></script>
    </body>
</html>
