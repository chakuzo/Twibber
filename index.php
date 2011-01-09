<?php
include("class/Twibber.class.php");
$return = wcf::getLoginOK($_COOKIE['twibber_nick'], $_COOKIE['twibber_pw'], $_COOKIE['twibber_salt']);
?>
<!doctype html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>TWiBBer - Das WBB MicroBlogging Social Network</title>
	<link rel="stylesheet" href="style/style.css" type="text/css">
	<link rel="stylesheet" href="style/freeow.css" type="text/css">
	<link rel="stylesheet" href="style/slide.css" type="text/css">
	<link href='http://fonts.googleapis.com/css?family=Yanone+Kaffeesatz' rel='stylesheet' type='text/css'> 
	<link href='http://fonts.googleapis.com/css?family=Neucha' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Reenie+Beanie' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Droid+Sans' rel='stylesheet' type='text/css'>
    </head>
    <body>
	<header id="toppanel">
	    <div id="panel"> 
		<div class="content clearfix"> 
		    <div class="left"> 
		        <h1>Willkommen zu Twibber!</h1>		
		        <p class="grey">Was ist Twibber? Twibber ist ein MicroBlogging System, wie z.B. <a href="http://twitter.com">Twitter</a></p> 
			<h2>Download</h2> 
			<p class="grey">Twibber ist Open Source! Du kannst es unter <a href="http://code.google.com/p/twbbler/">Google Code</a> downloaden. Achtung! Es ist eine Beta.</p> 
		    </div> 
		    <div class="left"> 
		    <?php if(!$return){ ?>
			<form class="clearfix" action="login.php?login=true" method="post"> 
			    <h1 class="padlock">Mitglieder Login</h1> 
			    <label class="grey" for="log">Nickname:</label> 
			    <input class="field" type="text" name="nickname" id="log" value="" size="23"> 
			    <label class="grey" for="pwd">Password:</label> 
			    <input class="field" type="password" name="password" id="pwd" size="50">
			    <div class="clear"></div> 
			    <input type="submit" name="submit" value="Login" class="bt_login"> 
			</form> 
		    <?php }else{ ?>
			<img src="<?=wcf::getAvatar($_COOKIE['twibber_nick'])?>" id="useravatar">
			<p>Willkommen <?=$_COOKIE['twibber_nick']?>!</p>
			<p>Du hast bereits <?=$Twibber->getStats($_COOKIE['twibber_nick'])?> mal getwibbert.</p>
			<p><a href="login.php?mode=logout">Ausloggen</a></p>
		    <?php } ?>
		    </div>
		    <div class="left right">
			<h1>Partner</h1>
			<h2><a href="http://wbblite2.de">WBBLite2.de</a></h2> 
			<p class="grey">Auf WBBLite2.de dreht sich alles um das Woltlab Burning Board Lite 2.X.X.</p>
			<h2><a href="http://code.google.com">Google Code</a></h2> 
			<p class="grey">Partner? Nein, nicht wirklich. Nur dort hosten wir Twibber zum Programmieren :)</p>
		    </div>
		</div> 
	    </div>	
	    <div class="tab"> 
		<ul class="login"> 
		<li class="left">&nbsp;</li> 
		<li><?php if(!$return){ ?>Hallo Gast!<?php }else{ ?>Hallo, <?=$_COOKIE['twibber_nick']?>.<?php } ?></li> 
		<li class="sep">|</li> 
		<li id="toggle">
		    <a id="open" class="open" href="#">
			<?php if(!$return){ ?><img src="res/img/cleardot.gif" alt="Login" id="login"><?php }else{ ?>
		    Panel öffnen
			<?php } ?>
		    </a><a id="close" class="close" style="display: none;" href="#">Panel schließen</a>
		</li>
		<li class="right">&nbsp;</li> 
		</ul> 
	    </div>
	</header>
	<div id="status"></div>
	<div id="logo"><a href="index.php"><img src="res/img/logo.png"></a></div>
	<div id="post_it">
	    <h3><?=$_COOKIE['twibber_nick']?>, was machst du grade?</h3>
	    <textarea id="input_text" maxlength="250" autofocus placeholder="Schreib deinen Freunden hier, was du gerade tust."></textarea>
	    <br><div class="right"><label for="input_text" id="counter">0 Zeichen</label><button id="twibber_it" onclick="dyn_submit();">Twibbern</button></div>
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
	<footer>Twibber is developed by <a href="#">Twibber Group</a></footer>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
	<script src="script/jquery.NobleCount.min.js"></script>
	<script src="script/jquery.freeow.min.js"></script>
	<script src="script/script.min.js"></script>
    </body>
</html>
