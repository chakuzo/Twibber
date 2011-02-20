<?php

require_once('global.php');
$return = wcf::getLoginOK($_COOKIE['twibber_nick'], $_COOKIE['twibber_pw'], $_COOKIE['twibber_salt']);

//switch ($_GET['page']) {
//	default:
		include_once('lib/tpl/header.tpl');
		include_once('lib/tpl/index_body.tpl');
		include_once('lib/tpl/footer.tpl');
//		break;
//}

?>