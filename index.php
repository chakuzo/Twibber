<?php

require_once('global.php');

// to prevent notices
$twibber_nick = (isset($_COOKIE['twibber_nick'])) ? $_COOKIE['twibber_nick'] : '';
$twibber_pw = (isset($_COOKIE['twibber_pw'])) ? $_COOKIE['twibber_pw'] : '';
$twibber_salt = (isset($_COOKIE['twibber_salt'])) ? $_COOKIE['twibber_salt'] : '';

$_GET['page'] = (isset($_GET['page'])) ? $_GET['page'] : '';
$_GET['action'] = (isset($_GET['action'])) ? $_GET['action'] : '';
$_GET['id'] = (isset($_GET['id'])) ? $_GET['id'] : '';

$return = WCF::getLoginOK($twibber_nick, $twibber_pw, $twibber_salt);

if (!empty($_GET['action'])) {
	switch ($_GET['action']) {
		case 'deleTwibb':
			$Twibber->deleteTwibb($_GET['id']);
			break;

		case 'updateNightly':
			Update::updateNightly();
			break;

		default:
			exit(Lang::getLangString('no_action'));
	}
}

if (!empty($_GET['page'])) {
	include_once(TWIBBER_DIR . '/templates/header.tpl');

	switch ($_GET['page']) {
		case 'update':
			include_once(TWIBBER_DIR . '/install/update.php');
			break;

		case 'login':
			include_once(TWIBBER_DIR . '/user/login.php');
			break;

		default:
			include_once(TWIBBER_DIR . '/templates/index_body.tpl');
			break;
	}

	include_once(TWIBBER_DIR . '/templates/footer.tpl');
}

?>