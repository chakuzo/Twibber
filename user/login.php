<?php

require_once('../global.php');

if (isset($_GET['mode']) && $_GET['mode'] == 'logout') {
	setcookie('twibber_nick', '', time() - 3600, TWIBBER_DIR);
	setcookie('twibber_pw', '', time() - 3600, TWIBBER_DIR);
	setcookie('twibber_salt', '', time() - 3600, TWIBBER_DIR);
	header('Location: ../index.php');
	exit;
}
if (!empty($_POST['nickname']) && !empty($_POST['password'])) {
	$return = WCF::getData($_POST['nickname'], $_POST['password']);
	if ($return) {
		setcookie('twibber_nick', $_POST['nickname'], time() + (365 * 24 * 60 * 60), TWIBBER_DIR);
		setcookie('twibber_pw', sha1($_POST['password']), time() + (365 * 24 * 60 * 60), TWIBBER_DIR);
		setcookie('twibber_salt', WCF::getSalt($_POST['nickname']), time() + (365 * 24 * 60 * 60), TWIBBER_DIR);
		header('Location: ../index.php');
	} else {
		echo $lang['false_pw_nick'];
	}
}

?>