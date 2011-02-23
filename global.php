<?php

// Reports everything on world + catches exceptions
error_reporting(-1);
set_exception_handler(
		array(
			'exceptions',
			'exceptions_handler'
		)
);

// Base
require_once('config.inc.php');
require_once('lib/lang/' . TWIBBER_LANG . '.lang.php');

// Classes
require_once('lib/class/PrettyDate.class.php');
require_once('lib/class/StringUtil.class.php');
require_once('lib/class/WCF.class.php');
require_once('lib/class/Twibber.class.php');
require_once('lib/class/Update.class.php');
//require_once('lib/class/Youtube.class.php');
// Sets default timezone
date_default_timezone_set($lang_timezone);

if (wcf_name_prefix == 'WCF1_') {
	die($prefix_error);
}
if (wcf_update_groupid == '') {
	die($group_id_error);
}

// MySQL(i) init
$mysqli = new mysqli(mysql_local, mysql_user, mysql_pw, mysql_db);
if ($mysqli->connect_error) {
	Throw new Exception($lang['mysql_connect_error'] . ' (' . $mysqli->connect_errno . ') '
			. $mysqli->connect_error);
}

$mysqli2 = new mysqli(mysql_local_wcf, mysql_user_wcf, mysql_pw_wcf, mysql_db_wcf);
if ($mysqli2->connect_error) {
	Throw new Exception($lang['mysql_wcf_connect_erorr'] . ' (' . $mysqli2->connect_errno . ') '
			. $mysqli2->connect_error);
}

// Init
$Twibber = new Twibber($mysqli, $lang);
$WCF = new WCF($mysqli2); // only for calling construct, i can remove it and replace it with self::__construct();
$Update = new Update($lang); // same as over this line

?>