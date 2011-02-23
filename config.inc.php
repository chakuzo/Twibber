<?php

/**
 * Fill out the Data to the connection.
 */
define('MYSQL_USER', ''); // The User for the Database
define('MYSQL_PW', 'password'); // The Password for the User
define('MYSQL_DB', 'database'); // The Database Name
define('MYSQL_HOST', 'localhost');
define('wcf_name_prefix', 'WCF1_'); // The prefix which is used for the wcf !!!
// Provide it with syntax 'wcfX_', X as a number !!!
define('TWIBBER_LANG', 'de'); // If youre from UK / USA change 'de' to 'en'.

/**
 * Fill out the Data for the connection to read out the WCF Data.
 */
define('mysql_user_wcf', '');
define('mysql_pw_wcf', '');
define('mysql_db_wcf', '');
define('mysql_local_wcf', 'localhost');
define('wcf_admin_groupid', '4'); // Please provide the group id from the acp from the wcf. DANGER! If you provide the false id, member can update and in future in the Twibber ACP
define('wcf_update_groupid', ''); // Here you must add the group id of an group, which can only update. No access to acp, but maybe more active and more online, so can update more often.
define('WCF_DIR', 'http://example.com/wcf'); // The complete URL for the wcf dir.

/*
 * Examples:
 * define('wcf_name_prefix','wcf1_');
 * examples provided in most cases already
 */

?>