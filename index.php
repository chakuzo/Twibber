<?php

require_once('global.php');

// to prevent notices
$twibber_nick = (isset($_COOKIE['twibber_nick'])) ? $_COOKIE['twibber_nick'] : '';
$twibber_pw = (isset($_COOKIE['twibber_pw'])) ? $_COOKIE['twibber_pw'] : '';
$twibber_salt = (isset($_COOKIE['twibber_salt'])) ? $_COOKIE['twibber_salt'] : '';

$page = (isset($_GET['page'])) ? $_GET['page'] : '';
$action = (isset($_GET['action'])) ? $_GET['action'] : '';
$id = (isset($_GET['id'])) ? $_GET['id'] : '';
$nickname = (isset($_POST['nickname'])) ? $_POST['nickname'] : '';
$password = (isset($_POST['password'])) ? trim($_POST['password']) : '';

if (!empty($action)) {
	switch ($action) {
		case 'deleTwibb':
			$Twibber->deleteTwibb($id);
			break;

		case 'updateNightly':
			require_once('login.class.php');
			Update::updateNightly();
			break;

		case 'login':
			if (!empty($nickname) && !empty($password)) {
				Login::userLogin($nickname, $password);
			} else {
				echo 'Bitte nur das Login formular nutzen!';
			}
			break;

		case 'logout':
			Login::userLogout();
			break;

		default:
			exit(Lang::getLangString('no_action'));
	}
	exit;
}

$return = WCF::getLoginOK($twibber_nick, $twibber_pw, $twibber_salt);
include_once(TWIBBER_DIR . '/templates/header.tpl');

switch ($page) {
	case 'update':
		include_once(TWIBBER_DIR . '/install/update.php');
		break;

	default:
		include_once(TWIBBER_DIR . '/templates/index_body.tpl');
		break;
}

include_once(TWIBBER_DIR . '/templates/footer.tpl');

?>