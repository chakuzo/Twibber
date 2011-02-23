<?php

/**
 * Handles exceptions in my own way.
 *
 * @author Kurt
 */
class exceptions extends Exception
{

	/**
	 * Exception handler.
	 * @param exception $exception
	 */
	function exceptions_handler($exception){
		echo 'Exception: '.var_dump($exception).'<br>';
		debug_print_backtrace();
	}

}

?>
