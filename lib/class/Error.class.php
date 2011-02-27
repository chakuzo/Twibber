<?php

/**
 * My own error handle.
 *
 * @author Kurt
 */
class Error
{

	/**
	 * Handles errors.
	 * @param integer $errno
	 * @param string $errstr
	 * @param string $errfile
	 * @param integer $errline
	 * @return boolean
	 */
	public static function error_handler($errno, $errstr, $errfile, $errline)
	{
		switch ($errno) {
			case E_USER_ERROR:
				echo '<b>Fatal ERROR:</b><br><i>[' . $errno . ']</i> ' . $errstr . ' on ' . $errline . ' in file ' . $errfile . '<br>';
				echo 'Stacktrace:<br>';
				debug_print_backtrace();
				exit();
				break;

			case E_USER_WARNING:
				echo "<b>Warning:</b> [$errno] $errstr<br>";
				break;

			case E_USER_NOTICE:
				echo "<b>Notice:</b> [$errno] $errstr<br>";
				break;

			default:
				echo "Error: [$errno] $errstr<br>";
				break;
		}

		/* Don't execute PHP internal error handler */
		return true;
	}

}

?>