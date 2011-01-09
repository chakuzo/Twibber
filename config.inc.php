<?php
/**
 * Fill out the Data to the connection.
 */
define("mysql_user",""); // The User for the Database
define("mysql_pw","password"); // The Password for the User
define("mysql_db","database"); // The Database Name
define("mysql_local","localhost"); // I think you shouldnt change this..
define("wcf_name_prefix","WCF1_"); // The prefix which is used for the wcf !!!
				    // Provide it with syntax "wcfX_", X as a number !!!
/**
 * Fill out the Data for the connection to read out the WCF Data,
 */
define("mysql_user_wcf","");
define("mysql_pw_wcf","");
define("mysql_db_wcf","");
define("mysql_local_wcf","localhost");
define("wcf_admin_groupid","1"); // Please provide the group id from the acp from the wcf. DANGER! If you provide the false id, member can update and in future in the Twibber ACP
define("wcf_update_groupid",""); // Here you can add the group id of an group, which can only update. No access to acp, but maybe more active and more online, so can update more often.

/*
 * Examples:
 * define("wcf_name_prefix","wcf1_");
 */

?>
