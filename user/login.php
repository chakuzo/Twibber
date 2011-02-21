<?php

require_once('../global.php');
if (isset($_GET['mode']) && $_GET['mode'] == 'logout') {
	setcookie('twibber_nick', '', time() - 3600);
	setcookie('twibber_pw', '', time() - 3600);
	setcookie('twibber_salt', '', time() - 3600);
	header('Location: ../index.php');
}
if (!empty($_POST['nickname']) && !empty($_POST['password'])) {
	$return = WCF::getData($_POST['nickname'], $_POST['password']);
	if ($return) {
		setcookie('twibber_nick', $_POST['nickname'], time() + (365 * 24 * 60 * 60));
		setcookie('twibber_pw', sha1($_POST['password']), time() + (365 * 24 * 60 * 60));
		setcookie('twibber_salt', WCF::getSalt($_POST['nickname']), time() + (365 * 24 * 60 * 60));
		header('Location: ../index.php');
	} else {
		echo $lang_false_pw_nick;
	}
}

?>