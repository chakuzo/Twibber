		    <div class="left"> 
		        <h1>Kontrollzentrum</h1>		
		        <p class="grey"><a href="http://twibber.wbblite2.de/?nick=<?=$_COOKIE['twibber_nick']?>">Profil</a></p> 
			<p class="grey"><a href="nicht gefunden">Profil Bearbeiten</a></p>
		    </div> 
		    <div class="left"> 
			
			<p><h1>Willkommen <?=$_COOKIE['twibber_nick']?>!<h1><p>
			<p>Du hast bereits <?=$Twibber->getStats($_COOKIE['twibber_nick'])?> mal getwibbert.</p>
			<p class="grey">Hier findest du bald noch mehr Daten über dich und dein Twibber Profil.</p>
			<p><a href="login.php?mode=logout">Ausloggen</a></p>
		    </div>
		    <div class="left right">
			<h1>Avatar</h1>
			<img src="<?=wcf::getAvatar($_COOKIE['twibber_nick'])?>" id="useravatar" height="150" width="150">
		    </div>