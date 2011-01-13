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
		    <?php if(!$return){ include("tpl/index.tpl"); }else{ include("tpl/index_login.tpl"); } ?>
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
	    <?php if($return) { ?> <textarea id="input_text" maxlength="250" autofocus placeholder="Schreib deinen Freunden hier, was du gerade tust."></textarea> 
	    <br><div class="right"><label for="input_text" id="counter">0 Zeichen</label><button id="twibber_it" onclick="dyn_submit();">Twibbern</button></div><?php } ?>
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
