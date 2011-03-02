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
}

?>