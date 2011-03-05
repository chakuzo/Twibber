<?php

/**
 * Fill out the Data to the connection.
 */
define('MYSQL_USER', ''); // The User for the Database
define('MYSQL_PW', 'password'); // The Password for the User
define('MYSQL_DB', 'database'); // The Database Name
define('MYSQL_HOST', 'localhost'); // often localhost

/**
 * Fill out the Data for the connection to read out the WCF Data.
 */
define('MYSQL_USER_WCF', ''); // The User for the WCF Database
define('MYSQL_PW_WCF', ''); // The Password for the User
define('MYSQL_DB_WCF', ''); // The Database Name
define('MYSQL_HOST_WCF', 'localhost'); // often localhost
define('wcf_name_prefix', 'wcf1_'); // The prefix which is used for the wcf !!!

/**
 * Mixed config
 */
define('HTTP_GZIP_ENABLED', true); // Enabled GZip Compression

define('TWIBBER_DIR', __DIR__); // don't change this

define('WCF_DIR', 'http://example.com/wcf'); // The complete URL of the WCF dir.

define('TWIBBER_LANG', 'de'); // If youre from UK / USA change 'de' to 'en'.

define('wcf_admin_groupid', 4); // Please provide the group id from the acp from the wcf. DANGER! If you provide the false id, member can update and in future in the Twibber ACP

define('wcf_update_groupid', 4); // Here you must add the group id of an group, which can only update. No access to acp, but maybe more active and more online, so can update more often.

?>