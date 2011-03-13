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
$nick = (isset($_GET['nick'])) ? $_GET['nick'] : '';
$password = (isset($_POST['password'])) ? trim($_POST['password']) : '';
$search = (isset($_GET['search'])) ? $_GET['search'] : '';
$text = (isset($_POST['text'])) ? $_POST['text'] : '';

$return = WCF::getLoginOK($twibber_nick, $twibber_pw, $twibber_salt);

if (!empty($action)) {
	switch ($action) {
		case 'deleTwibb':
			$Twibber->deleteTwibb($id);
			break;

		case 'updateNightly':
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

		case 'image':
			Signature::createImage($nick);
			break;

		case 'dyn_get':
			$mult = (empty($page)) ? 1 : intval($page);
			$Twibber->fetchTwibber(true, true, '', 0, $mult * 20);
			exit;

		case 'userpage':
			$Twibber->fetchTwibber(true, false, $nick);
			break;

		case 'search':
			$Twibber->searchTwibber($search);
			break;

		case 'new_entry':
			if(trim($text) == '' || strlen($text) > 250 || !$return){
				echo Lang::getLangString('failure');
				break;
			}
			//if (isset($_GET['retwibb']) && $_GET['retwibb'])
			if (isset($_GET['comment']) && $_GET['comment'] == 1) {
				$Twibber->createTwibbComment(htmlentities($text, ENT_COMPAT, 'UTF-8'), htmlentities($nick, ENT_COMPAT, 'UTF-8'), intval($_POST['to_id']));
				exit(Lang::getLangString('success'));
			}
			$Twibber->createTwibb(htmlentities($text, ENT_COMPAT, 'UTF-8'), htmlentities($nick, ENT_COMPAT, 'UTF-8'));
			echo Lang::getLangString('success');
			break;

		default:
			exit(Lang::getLangString('no_action'));
	}
	exit;
}

$return = WCF::getLoginOK($twibber_nick, $twibber_pw, $twibber_salt);
include_once('templates/header.tpl');

switch ($page) {
	case 'Update':
		$admin_ok = WCF::getAdminOK($_COOKIE['twibber_nick'], $_COOKIE['twibber_pw'], $_COOKIE['twibber_salt']);
		$update_ok = WCF::getAdminOK($_COOKIE['twibber_nick'], $_COOKIE['twibber_pw'], $_COOKIE['twibber_salt'], true);
		if (!$admin_ok || !$update_ok)
			header('Location: index.php');
		include_once('lib/module/Update.class.php');
		break;

	default:
		include_once('templates/index_body.tpl');
		break;
}

include_once('templates/footer.tpl');

?>