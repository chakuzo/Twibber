		    <?php $admin_ok = wcf::getAdminOK($_COOKIE['twibber_nick'], $_COOKIE['twibber_pw'], $_COOKIE['twibber_salt']);
		    $update_ok = wcf::getAdminOK($_COOKIE['twibber_nick'], $_COOKIE['twibber_pw'], $_COOKIE['twibber_salt'], true);
		    ?>
		    <div class="left"> 
		        <h1>Kontrollzentrum</h1>		
		        <p class="grey"><a href="?nick=<?=$_COOKIE['twibber_nick']?>">Profil</a></p> 
			<p class="grey"><a href="#">Profil Bearbeiten</a></p>
			<?php if($admin_ok){ ?><p class="grey"><a href="#">Twibber ACP</a></p> <?php } ?>
			<?php if($update_ok){ ?><p class="grey"><a href="#">Updaten</a></p> <?php } ?>
		    </div> 
		    <div class="left"> 
			<h1>Willkommen <?=$_COOKIE['twibber_nick']?>!</h1>
			<p>Du hast bereits <?=$Twibber->getStats($_COOKIE['twibber_nick'])?> mal getwibbert.</p>
			<p class="grey">Hier findest du bald noch mehr Daten �ber dich und dein Twibber Profil.</p>
			<p><a href="login.php?mode=logout">Ausloggen</a></p>
		    </div>
		    <div class="left right">
			<h1>Avatar</h1>
			<img src="<?=wcf::getAvatar($_COOKIE['twibber_nick'])?>" id="useravatar" height="150" width="150">
		    </div>