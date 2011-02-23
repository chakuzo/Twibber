<div id="status"></div>
<div id="logo"><a href="#page=index"><img src="<?php echo TWIBBER_DIR ?>/images/img/logo.png"></a></div>
<section id="post_it">
	<h3><?php echo (!empty($_COOKIE['twibber_nick'])) ? $_COOKIE['twibber_nick'] : $lang['guest']; ?>, was machst du grade?</h3>
	<h3 id='in_comment_to'></h3>
	<form onsubmit="dyn_submit(); return false;" id='twibb_form'>
		<input type="hidden" value="" id="to_id">
		<?php if ($return) { ?> <textarea id="input_text" maxlength="250" autofocus placeholder="Schreib deinen Freunden hier, was du gerade tust."></textarea>
			<br><div class="right"><label for="input_text" id="counter">0 Zeichen</label><button id="twibber_it">Twibbern</button></div><?php } ?>
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
	<button type='button' id='more_twibbs'>Mehr Twibbs laden</button>
</section>