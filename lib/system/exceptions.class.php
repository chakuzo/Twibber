<?php

/**
 * Handles exceptions in my own way.
 *
 * @author Kurt
 */
class exceptions extends Exception {

	/**
	 * Exception handler.
	 * @param exception $exception
	 */
	public static function exceptions_handler($exception) {
		echo '<h3>Exception: ' . var_dump($exception) . '</h3><br>';
	}

}

?>