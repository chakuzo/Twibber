<?php

require_once('../global.php');
$return = WCF::getLoginOK($_COOKIE['twibber_nick'], $_COOKIE['twibber_pw'], $_COOKIE['twibber_salt']);
if (!$return)
	header('Location: ../index.php');
$return = WCF::getAdminOK($_COOKIE['twibber_nick'], $_COOKIE['twibber_pw'], $_COOKIE['twibber_salt'], true);
if (!$return)
	header('Location: ../index.php');
$update = (isset($_GET['update'])) ? $_GET['update'] : '';

include_once('../lib/tpl/header.tpl');

switch ($update) {
	case 'nightly':
		$Update->updateNightly();
		break;

	case 'main':
		$Update->updateMain($Update->checkUpdate(false, true));
		break;

	default:
		$Update->checkUpdate();
		break;
}

include_once('../lib/tpl/footer.tpl');

?>