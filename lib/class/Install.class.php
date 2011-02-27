<?php

/**
 * Install Class for Twibber
 *
 * @author Kurt
 */
class Install
{

	/**
	 * Checks all requirements for Twibber.
	 */
	public function check_server()
	{
		try {
			if (version_compare(PHP_VERSION, '5.2') == -1)
				throw new exception('Your server / webspace is running a PHP version below 5.2.x, please change this!');
			$extensions = get_loaded_extensions();
			if (array_search('xml', $extensions) === false)
				throw new exception('Your server / webspace doenst have a XML Extension!');
			if (safe_mode == 1)
				throw new exception('Safe Mode is on! Please disable it.');
			$this->install();
		} catch (exception $e) {
			echo 'Exception: ' . var_dump($exception) . '<br>';
		}
	}

	public function write_config()
	{

	}

	public function write_htaccess($gzip_on = false)
	{

	}

	public function install()
	{
		
	}

}

?>
