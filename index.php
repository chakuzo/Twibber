<?php

require_once('./global.php');

$twibber_nick = (isset($_COOKIE['twibber_nick'])) ? $_COOKIE['twibber_nick'] : '';
$twibber_pw = (isset($_COOKIE['twibber_pw'])) ? $_COOKIE['twibber_pw'] : '';
$twibber_salt = (isset($_COOKIE['twibber_salt'])) ? $_COOKIE['twibber_salt'] : '';
$_GET['page'] = (isset($_GET['page'])) ? $_GET['page'] : '';

$return = WCF::getLoginOK($twibber_nick, $twibber_pw, $twibber_salt);

include_once('lib/tpl/header.tpl');

switch ($_GET['page']) {
	case 'update':
		include_once('update.php');
		break;

	case 'login':
		include_once('user/login.php');
		break;

	default:
		include_once('lib/tpl/index_body.tpl');
		break;
}

include_once('lib/tpl/footer.tpl');

?>