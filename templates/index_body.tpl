<h3><?php echo (!empty($_COOKIE['twibber_nick'])) ? $_COOKIE['twibber_nick'] : Lang::getLangString('guest'); ?>, was machst du gerade?</h3>
<h3 id='in_comment_to'></h3>
<form id='twibb_form'>
	<input type="hidden" value="" id="to_id">
	<?php if ($return) { ?> <textarea id="input_text" maxlength="250" autofocus placeholder="Schreibe deinen Freunden hier, was du gerade machst."></textarea>
		<br><div class="right"><label for="input_text" id="counter"></label><button id="twibber_it">Twibbern</button></div><?php } ?>
</form>
<div id="twibber">
	<?php
	if (empty($_GET['nick']) && empty($_GET['search'])) {
		$Twibber->fetchTwibber(true, true);
	} elseif (empty($_GET['search'])) {
		$Twibber->fetchTwibber(true, false, $_GET['nick']);
	} else {
		$Twibber->searchTwibber($_GET['search']);
	}

	?>
</div>
<button type='button' id='more_twibbs'>Weitere Twibbs laden</button>