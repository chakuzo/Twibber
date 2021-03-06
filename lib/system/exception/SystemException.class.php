<?php

/**
 * A SystemException is thrown when an unexpected error occurs.
 *
 * @author	Marcel Werk (modifications by kurtextrem).
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

		// modification starts here
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
					overflow: hidden;
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
					word-wrap: break-word;
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
					text-overflow: ellipsis;
					overflow: hidden;
					background-color: yellow;
				}
				.systemException pre:hover{
					overflow: auto !important;
					text-overflow: clip !important;
				}
			</style>
		</head>
		<body>
			<div class="systemException">
				<h1>Error: <?php echo $this->getMessage(); ?></h1>

				<div>
					<p><?php echo $this->getDescription(); ?></p>
					<h2>Information:</h2>
					<br>
					<p>
						<b>Error message:</b> <?php echo $this->getMessage(); ?><br>
		<?php echo $this->information; ?>
						<b>File:</b> <?php echo StringUtil::encodeHTML($this->getFile()); ?> (#<?php echo $this->getLine(); ?>)<br>
						<b>PHP version:</b> <?php echo StringUtil::encodeHTML(phpversion()); ?><br>
						<b>Twibber version:</b> <?php echo TWIBBER_VERSION; ?><br>
						<b>Date:</b> <?php echo gmdate('r'); ?><br>
						<b>Request:</b> <?php if (isset($_SERVER['REQUEST_URI']))
			echo StringUtil::encodeHTML($_SERVER['REQUEST_URI']); ?><br>
						<b>Referer:</b> <?php echo (isset($_SERVER['HTTP_REFERER'])) ? StringUtil::encodeHTML($_SERVER['HTTP_REFERER']) : 'No referer.' ?><br>
					</p>

					<h2>Stacktrace:</h2>
					<pre><?php echo StringUtil::encodeHTML($this->__getTraceAsString()); ?></pre>
					<br>
					<p>Now you can do 2. things:</p>
					<ol>
						<li>Send this report to our Email <a href='mailto:support@twibber.de'>support@twibber.de</a>.</li>
						<li>Take a look at the <a href='https://github.com/chakuzo/Twibber/wiki/FAQ'>FAQs</a>.</li>
					</ol>

				</div>

		<?php echo $this->functions; ?>
			</div>
		</body>
		</html>

		<?php
	}

}

?>