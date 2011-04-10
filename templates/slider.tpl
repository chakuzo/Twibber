<header id="toppanel">
	<nav id="panel">
		<div class="content clearfix">
			{slider_content}
		</div>
	</nav>
	<div class="tab">
		<ul class="login">
			<li class="left">&nbsp;</li>
			<li>Hallo, {user}.</li>
			<li class="sep">|</li>
			<li id="toggle">
				<a id="open" class="open slider_active" href="#">
						<?php if (!$return) { ?><img src="images/img/cleardot.gif" alt="Login" id="login"><?php } else { ?>Panel &ouml;ffnen<?php } ?>
				</a>
				<a id="close" class="close" href="#">Panel schlie&szlig;en</a>
			</li>
			<li class="right">&nbsp;</li>
		</ul>
	</div>
</header>