<!doctype html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>TWiBBer - WBB MicroBlogging Social Network</title>
		<link rel="stylesheet" href="<?php echo TWIBBER_DIR ?>/lib/style/style.min.css" type="text/css">
		<link rel="stylesheet" href="<?php echo TWIBBER_DIR ?>/lib/style/freeow.min.css" type="text/css">
		<link rel="stylesheet" href="<?php echo TWIBBER_DIR ?>/lib/style/slide.min.css" type="text/css">
		<link rel="shortcut icon" type="image/x-icon" href="<?php echo TWIBBER_DIR ?>/images/img/favicon.ico">
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
					<?php
					if (!$return) {
						include_once("index_slider.tpl");
					} else {
						include_once("index_slider_login.tpl");
					}

					?>
				</div>
			</nav>
			<div class="tab">
				<ul class="login">
					<li class="left">&nbsp;</li>
					<li><?php if (!$return) { ?>Hallo Gast!<?php } else { ?>Hallo, <?php echo $_COOKIE['twibber_nick'] ?>.<?php } ?></li>
					<li class="sep">|</li>
					<li id="toggle">
						<a id="open" class="open" href="#">
							<?php if (!$return) { ?><img src="<?php echo TWIBBER_DIR ?>/images/img/cleardot.gif" alt="Login" id="login"><?php } else { ?>
						    Panel öffnen
							<?php } ?>
						</a><a id="close" class="close" style="display: none;" href="#">Panel schließen</a>
					</li>
					<li class="right">&nbsp;</li>
				</ul>
			</div>
		</header>
