<?php

require_once('global.php');

$twibber_nick = (isset($_COOKIE['twibber_nick']))?$_COOKIE['twibber_nick']:'';
$twibber_pw = (isset($_COOKIE['twibber_pw']))?$_COOKIE['twibber_pw']:'';
$twibber_salt = (isset($_COOKIE['twibber_salt']))?$_COOKIE['twibber_salt']:'';

$return = wcf::getLoginOK($twibber_nick, $twibber_pw, $twibber_salt);

//switch ($_GET['page']) {
//	default:
		include_once('lib/tpl/header.tpl');
		include_once('lib/tpl/index_body.tpl');
		include_once('lib/tpl/footer.tpl');
//		break;
//}

?>