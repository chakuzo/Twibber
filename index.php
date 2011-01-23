<?php
include("lib/class/Twibber.class.php");
$return = wcf::getLoginOK($_COOKIE['twibber_nick'], $_COOKIE['twibber_pw'], $_COOKIE['twibber_salt']);
?><!doctype html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>TWiBBer - WBB MicroBlogging Social Network</title>
	<link rel="stylesheet" href="lib/style/style.min.css" type="text/css">
	<link rel="stylesheet" href="lib/style/freeow.min.css" type="text/css">
	<link rel="stylesheet" href="lib/style/slide.min.css" type="text/css">
	<link rel="shortcut icon" type="image/x-icon" href="images/img/favicon.ico">
	<link href='http://fonts.googleapis.com/css?family=Yanone+Kaffeesatz' rel='stylesheet' type='text/css'> 
	<link href='http://fonts.googleapis.com/css?family=Neucha' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Reenie+Beanie' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Droid+Sans' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Allan:bold' rel='stylesheet' type='text/css'>
    </head>
    <body>
	<header id="toppanel">
	    <nav id="panel"> 
		<div class="content clearfix"> 
		    <?php if(!$return){ include("lib/tpl/index.tpl"); }else{ include("lib/tpl/index_login.tpl"); } ?>
		</div>
	    </nav> 
	    <div class="tab"> 
		<ul class="login"> 
		<li class="left">&nbsp;</li> 
		<li><?php if(!$return){ ?>Hallo Gast!<?php }else{ ?>Hallo, <?=$_COOKIE['twibber_nick']?>.<?php } ?></li> 
		<li class="sep">|</li> 
		<li id="toggle">
		    <a id="open" class="open" href="#">
			<?php if(!$return){ ?><img src="images/img/cleardot.gif" alt="Login" id="login"><?php }else{ ?>
		    Panel öffnen
			<?php } ?>
		    </a><a id="close" class="close" style="display: none;" href="#">Panel schließen</a>
		</li>
		<li class="right">&nbsp;</li> 
		</ul> 
	    </div>
	</header>
	<div id="status"></div>
	<div id="logo"><a href="index.php"><img src="images/img/logo.png"></a></div>
	<section id="post_it">
	    <h3><?=(!empty($_COOKIE['twibber_nick']))?$_COOKIE['twibber_nick']:$lang['guest']?>, was machst du grade?</h3>
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
	</section>
	<footer><a href="http://www.w3.org/html/logo/">
<img src="http://www.w3.org/html/logo/badge/html5-badge-h-css3-performance-semantics.png" width="197" height="64" alt="HTML5 Powered with CSS3 / Styling, Performance &amp; Integration, and Semantics" title="HTML5 Powered with CSS3 / Styling, Performance &amp; Integration, and Semantics">
</a>Twibber is developed by <a href="#">Twibber Group</a></footer>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
	<script src="lib/script/jquery.NobleCount.min.js"></script>
	<script src="lib/script/jquery.freeow.min.js"></script>
	<script src="lib/script/script.min.js"></script>
    </body>
</html>
