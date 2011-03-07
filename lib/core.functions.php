<?php

/**
 * Creates autoloader and error handlers
 *
 * @author	Kurtextrem
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 */
// exception handler
set_exception_handler(array('Twibber', 'handleException'));

// error handler
set_error_handler(array('Twibber', 'handleError'), E_ALL);

// Inits MYSQL
$mysqli = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PW, MYSQL_DB);
if ($mysqli->connect_error) {
	throw new Exception(Lang::getLangString('mysql_connect_error') . ' (' . $mysqli->connect_errno . ') '
			. $mysqli->connect_error);
}
// wcf
$mysqli2 = new mysqli(MYSQL_HOST_WCF, MYSQL_USER_WCF, MYSQL_PW_WCF, MYSQL_DB_WCF);
if ($mysqli2->connect_error) {
	throw new Exception(Lang::getLangString('mysql_wcf_connect_erorr') . ' (' . $mysqli2->connect_errno . ') '
			. $mysqli2->connect_error);
}

/**
 * Autoloads classes.
 *
 * @param string $className
 */
function __autoload($className) {

	if (file_exists(TWIBBER_DIR . '/lib/util/' . $className . '.class.php')) {
		require_once(TWIBBER_DIR . '/lib/util/' . $className . '.class.php');
		return;
	}

	if (file_exists(TWIBBER_DIR . '/lib/system/exception/' . $className . '.class.php')) {
		require_once(TWIBBER_DIR . '/lib/system/exception/' . $className . '.class.php');
		return;
	}

	if (file_exists(TWIBBER_DIR . '/lib/user/' . $className . '.class.php')) {
		require_once(TWIBBER_DIR . '/lib/user/' . $className . '.class.php');
		return;
	}
}

// init
new WCF($mysqli2);
new Lang($lang);
$Twibber = new Twibber($mysqli);

?>