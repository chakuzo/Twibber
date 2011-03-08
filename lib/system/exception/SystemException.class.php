<?php

/**
 * A SystemException is thrown when an unexpected error occurs.
 *
 * @author	Marcel Werk (mofidied from Kurtextrem for Twibber).
 * @copyright	2001-2009 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf
 * @subpackage	system.exception
 * @category 	Community Framework
 */
class SystemException extends Exception implements PrintableException {

	protected $description;
	protected $information = '';
	protected $functions = '';

	/**
	 * Creates a new SystemException.
	 *
	 * @param	string		$message	error message
	 * @param	integer		$code		error code
	 * @param	string		$description	description of the error
	 */
	public function __construct($message = '', $code = 0, $description = '') {
		parent::__construct($message, $code);
		$this->description = $description;
	}

	/**
	 * Returns the description of this exception.
	 *
	 * @return 	string
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * Removes database password from stack trace.
	 * @see Exception::getTraceAsString()
	 */
	public function __getTraceAsString() {
		$string = preg_replace('/Database->__construct\(.*\)/', 'Database->__construct(...)', $this->getTraceAsString());
		$string = preg_replace('/mysqli->mysqli\(.*\)/', 'mysqli->mysqli(...)', $string);
		return $string;
	}

	/**
	 * Prints this exception.
	 * This method is called by WCF::handleException().
	 */
	public function show() {
		// send status code
		@header('HTTP/1.1 503 Service Unavailable');

		?>

		<!DOCTYPE html>
		<head>
			<title>Error: <?php echo StringUtil::encodeHTML($this->getMessage()); ?></title>
			<style>
				.systemException {
					border: 1px outset lightgrey;
					padding: 3px;
					background-color: lightgrey;
					text-align: left;
					overflow: auto;
					font-family: Verdana, Helvetica, sans-serif;
					font-size: .8em;
				}
				.systemException div {
					border: 1px inset lightgrey;
					padding: 4px;
				}
				.systemException h1 {
					background-color: #154268;
					padding: 4px;
					color: #fff;
					margin: 0 0 3px 0;
					font-size: 1.15em;
				}
				.systemException h2 {
					font-size: 1.1em;
					margin-bottom: 0;
				}
				.systemException pre, .systemException p {
					margin: 0;
				}
				.systemException pre {
					font-size: .85em;
					font-family: "Courier New";
				}
			</style>
		</head>
		<body>
			<div class="systemException">
				<h1>Error: <?php echo $this->getMessage(); ?></h1>

				<div>
					<p><?php echo $this->getDescription(); ?></p>
					<p>Send this report to our Email <a href='mailto:support@twibber.de'>support@twibber.de</a> (Of course, we speak english x)).</p>
					<p>You could also look at the <a href='https://github.com/chakuzo/Twibber/wiki/FAQ'>FAQ</a>

					<h2>Information:</h2>
					<p>
						<b>error message:</b> <?php echo $this->getMessage(); ?><br />
						<b>error code:</b> <?php echo intval($this->getCode()); ?><br />
		<?php echo $this->information; ?>
						<b>file:</b> <?php echo StringUtil::encodeHTML($this->getFile()); ?> (#<?php echo $this->getLine(); ?>)<br />
						<b>php version:</b> <?php echo StringUtil::encodeHTML(phpversion()); ?><br />
						<b>Twibber version:</b> <?php echo TWIBBER_VERSION; ?><br />
						<b>date:</b> <?php echo gmdate('r'); ?><br />
						<b>request:</b> <?php if (isset($_SERVER['REQUEST_URI']))
			echo StringUtil::encodeHTML($_SERVER['REQUEST_URI']); ?><br />
						<b>referer:</b> <?php echo (isset($_SERVER['HTTP_REFERER'])) ? StringUtil::encodeHTML($_SERVER['HTTP_REFERER']) : 'No referer.' ?><br />
					</p>

					<h2>Stacktrace:</h2>
					<pre><?php echo StringUtil::encodeHTML($this->__getTraceAsString()); ?></pre>
				</div>

		<?php echo $this->functions; ?>
			</div>
		</body>
		</html>

		<?php
	}

}

?>