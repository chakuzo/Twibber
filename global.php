<?php

// Reports everything on world + catches exceptions
error_reporting(E_ALL | E_STRICT | E_NOTICE | E_WARNING);
set_exception_handler(
		array(
			'exceptions',
			'exceptions_handler'
		)
);

// GZip compression
if (Gzip_enabled)
	ob_start("ob_gzhandler");

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
$mysqli = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PW, MYSQL_DB);
if ($mysqli->connect_error) {
	throw new Exception($lang['mysql_connect_error'] . ' (' . $mysqli->connect_errno . ') '
			. $mysqli->connect_error);
}

$mysqli2 = new mysqli(MYSQL_HOST_WCF, MYSQL_USER_WCF, MYSQL_PW_WCF, MYSQL_DB_WCF);
if ($mysqli2->connect_error) {
	throw new Exception($lang['mysql_wcf_connect_erorr'] . ' (' . $mysqli2->connect_errno . ') '
			. $mysqli2->connect_error);
}

// Init
$Twibber = new Twibber($mysqli, $lang);
$WCF = new WCF($mysqli2); // only for calling construct, i can remove it and replace it with self::__construct();
$Update = new Update($lang); // same as over this line

?>