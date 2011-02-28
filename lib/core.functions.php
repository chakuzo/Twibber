<?php
/**
 * @author	Marcel Werk
 * @copyright	2001-2009 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf
 * @category 	Community Framework
 */
// set exception handler
set_exception_handler(array('WCF', 'handleException'));

// set php error handler
set_error_handler(array('WCF', 'handleError'), E_ALL);

// set shutdown function
register_shutdown_function(array('WCF', 'destruct'));

function escapeString($string) {
	return WCF::getDB()->escapeString($string);
}

/**
 * Includes the required util or exception classes automatically.
 * 
 * @param 	string		$className
 */
function __autoload($className) {
	// search util class in wcf dir
	if (file_exists(WCF_DIR . 'lib/util/' . $className . '.class.php')) {
		// include file
		require_once(WCF_DIR . 'lib/util/' . $className . '.class.php');
		return;
	}
	// search exception class in wcf dir
	if (file_exists(WCF_DIR . 'lib/system/exception/' . $className . '.class.php')) {
		// include file
		require_once(WCF_DIR . 'lib/system/exception/' . $className . '.class.php');
		return;
	}
	
	// search util or exception class in application dirs
	global $packageDirs;
	if (isset($packageDirs) && is_array($packageDirs)) {
		foreach ($packageDirs as $packageDir) {
			if (file_exists($packageDir . 'lib/util/' . $className . '.class.php')) {
				// include file
				require_once($packageDir . 'lib/util/' . $className . '.class.php');
				return;
			}
			if (file_exists($packageDir . 'lib/system/exception/' . $className . '.class.php')) {
				// include file
				require_once($packageDir . 'lib/system/exception/' . $className . '.class.php');
				return;
			}
		}
	}
}
?>