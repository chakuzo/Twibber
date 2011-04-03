<?php

require_once('global.php');

// to prevent notices
$twibber_nick = (isset($_COOKIE['twibber_nick'])) ? $_COOKIE['twibber_nick'] : '';
$twibber_pw = (isset($_COOKIE['twibber_pw'])) ? $_COOKIE['twibber_pw'] : '';
$twibber_salt = (isset($_COOKIE['twibber_salt'])) ? $_COOKIE['twibber_salt'] : '';

$page = (isset($_REQUEST['page'])) ? $_REQUEST['page'] : '';
$action = (isset($_REQUEST['action'])) ? $_REQUEST['action'] : '';
$id = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : '';
$nickname = (isset($_REQUEST['nickname'])) ? $_REQUEST['nickname'] : '';
$nick = (isset($_REQUEST['nick'])) ? $_REQUEST['nick'] : '';
$password = (isset($_REQUEST['password'])) ? $_REQUEST['password'] : '';
$search = (isset($_REQUEST['search'])) ? $_REQUEST['search'] : '';
$text = (isset($_REQUEST['text'])) ? $_REQUEST['text'] : '';

$return = WCF::getLoginOK($twibber_nick, $twibber_pw, $twibber_salt);

if (!empty($action)) {
	switch ($action) {
		case 'deleTwibb':
			$Twibber->deleteTwibb($id);
			break;

		case 'image':
			Signature::createImage($nick);
			break;

		case 'dyn_get':
			$mult = (empty($page)) ? 1 : intval($page);
			$Twibber->fetchTwibber(true, true, '', 0, $mult * 20);
			exit;

		case 'new_entry':
			if (trim($text) == '' || strlen($text) > 250 || !$return) {
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

	case 'userpage':
		$Twibber->fetchTwibber(true, false, $nick);
		break;

	case 'search':
		$Twibber->searchTwibber($search);
		break;

	default:
		include_once('templates/index_body.tpl');
		break;
}

include_once('templates/footer.tpl');

?>