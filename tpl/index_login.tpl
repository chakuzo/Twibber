		    <div class="left"> 
		        <h1>Willkommen zu Twibber!</h1>		
		        <p class="grey">Was ist Twibber? Twibber ist ein MicroBlogging System, wie z.B. <a href="http://twitter.com">Twitter</a></p> 
			<h2>Download</h2> 
			<p class="grey">Twibber ist Open Source! Du kannst es unter <a href="http://code.google.com/p/twbbler/">Google Code</a> downloaden. Achtung! Es ist eine Beta.</p> 
		    </div> 
		    <div class="left"> 
			<img src="<?=wcf::getAvatar($_COOKIE['twibber_nick'])?>" id="useravatar">
			<p>Willkommen <?=$_COOKIE['twibber_nick']?>!</p>
			<p>Du hast bereits <?=$Twibber->getStats($_COOKIE['twibber_nick'])?> mal getwibbert.</p>
			<p><a href="login.php?mode=logout">Ausloggen</a></p>
		    </div>
		    <div class="left right">
			<h1>Partner</h1>
			<h2><a href="http://wbblite2.de">WBBLite2.de</a></h2> 
			<p class="grey">Auf WBBLite2.de dreht sich alles um das Woltlab Burning Board Lite 2.X.X.</p>
			<h2><a href="http://code.google.com">Google Code</a></h2> 
			<p class="grey">Partner? Nein, nicht wirklich. Nur dort hosten wir Twibber zum Programmieren :)</p>
		    </div>