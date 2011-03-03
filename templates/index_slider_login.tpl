<?php
$admin_ok = WCF::getAdminOK($_COOKIE['twibber_nick'], $_COOKIE['twibber_pw'], $_COOKIE['twibber_salt']);
$update_ok = WCF::getAdminOK($_COOKIE['twibber_nick'], $_COOKIE['twibber_pw'], $_COOKIE['twibber_salt'], true);

?>
<div class="left">
	<h1>Kontrollzentrum</h1>
	<p class="grey"><a href="?nick=<?php echo $_COOKIE['twibber_nick'] ?>">Profil</a></p>
	<p class="grey"><a href="#">Profil Bearbeiten</a></p>
	<p class="grey"><a href="#" id='refresh' class='stop'>Aktuallsieren stoppen</a></p>
	<?php if ($admin_ok) { ?><p class="grey"><a href="#">Twibber ACP</a></p> <?php } ?>
	<?php if ($update_ok) { ?><p class="grey"><a href="index.php?page=Update">Updaten</a></p> <?php } ?>
</div>
<div class="left">
	<h1>Willkommen <?php echo $_COOKIE['twibber_nick'] ?>!</h1>
	<p>Du hast bereits <?php echo $Twibber->getStats($_COOKIE['twibber_nick']) ?> mal getwibbert.</p>
	<p class="grey">Hier findest du bald noch mehr Daten &uuml;ber dich und dein Twibber Profil.</p>
	<p><a href="user/login.php?mode=logout">Ausloggen</a></p>
</div>
<div class="left right">
	<h1>Avatar</h1>
	<img src="<?= WCF::getAvatar($_COOKIE['twibber_nick']) ?>" id="useravatar">
</div>