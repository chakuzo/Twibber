<?php
/**
 * A UserException is thrown when a user gives false input data.
 * 
 * @author	Marcel Werk
 * @copyright	2001-2009 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf
 * @subpackage	system.exception
 * @category 	Community Framework
 */
abstract class UserException extends Exception implements PrintableException {
	/**
	 * Prints this exception.
	 * This method is called by WCF::handleException().
	 */
	public function show() {
		echo '<pre>' . $this->getTraceAsString() . '</pre>';
	}
}
?>