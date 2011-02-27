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
		if (version_compare(PHP_VERSION, '5.2') == -1)
			throw new exception('Your server / webspace is running a PHP version below 5.2.x, please change this!');
		$extensions = get_loaded_extensions();
		if (array_search('xml', $extensions))
			throw new exception('Your server / webspace doenst have a XML Extension!');
	}

	public function write_config()
	{

	}

	public function write_htaccess($gzip_on = false)
	{

	}

}

?>
