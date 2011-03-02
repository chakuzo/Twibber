<?php

// Reports everything on world
error_reporting(E_ALL | E_STRICT | E_NOTICE | E_WARNING);

// Base
require_once('config.inc.php');
require_once(TWIBBER_DIR . '/lib/lang/' . TWIBBER_LANG . '.lang.php');

// starting core
require_once(TWIBBER_DIR . '/lib/system/Twibber.class.php');

// GZip compression
if (GZip_enabled)
	ob_start('ob_gzhandler');

if (wcf_update_groupid == '') {
	die($group_id_error);
}

// MySQL(i) init
$mysqli = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PW, MYSQL_DB);
if ($mysqli->connect_error) {
	throw new Exception($lang['mysql_connect_error'] . ' (' . $mysqli->connect_errno . ') '
			. $mysqli->connect_error);
}

// Init
$Twibber = new Twibber($mysqli, $lang);
new WCF($mysqli2); // only for calling construct, i can remove it and replace it with self::__construct();
$Update = new Update($lang); // same as over this line

?>