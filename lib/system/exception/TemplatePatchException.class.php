<?php
/**
 * A TemplatePatchException is thrown if patching of a template fails.
 * 
 * @author	Marcel Werk
 * @copyright	2001-2009 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf
 * @subpackage	system.exception
 * @category 	Community Framework
 */
class TemplatePatchException extends SystemException {
	protected $templateName = '';

	/**
	 * Creates a new TemplatePatchException.
	 * 
	 * @param	string		$message	error message
	 * @param	integer		$code		error code
	 * @param 	string		$templateName	affected template
	 * @param	string		$description	description of the error	
	 */
	public function __construct($message = '', $code = 0, $templateName = '', $description = '') {
		parent::__construct($message, $code, $description);
		$this->templateName = $templateName;
	}
	
	/**
	 * Returns the name of the affected template.
	 *
	 * @return	string
	 */
	public function getTemplateName() {
		return $this->templateName;
	}
}
?>