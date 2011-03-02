<?php

/**
 * Install Class for Twibber
 *
 * @author Kurt
 */
class Install {

	/**
	 * Checks all requirements for Twibber.
	 */
	public function checkServer() {
		try {
			if (version_compare(PHP_VERSION, '5.2') == -1)
				throw new exception('Your server / webspace is running a PHP version below 5.2.x, please change this!');
			$extensions = get_loaded_extensions();
			if (array_search('xml', $extensions) === false)
				throw new exception('Your server / webspace doenst have a XML Extension!');
			if (safe_mode == 1)
				throw new exception('Safe Mode is on! Please disable it.');
			if (!function_exists(gd_info()))
				throw new exception('GDLib is needed for Signature Images. Delete Line 24 + 25 on Install.class.php, to continue setup.');
			$this->install();
		} catch (exception $e) {
			echo 'Exception: ' . var_dump($exception) . '<br>';
		}
	}

	/**
	 * Writes the config.inc.php file.
	 *
	 * @param string $mysql_user
	 * @param string $mysql_pw
	 * @param string $mysql_db
	 * @param string $mysql_host
	 * @param string $wcf_prefix
	 * @param string $tb_lang
	 * @param string $tb_dir
	 * @param string $mysql_user_wcf
	 * @param string $mysql_pw_wcf
	 * @param string $mysql_db_wcf
	 * @param string $mysql_host_wcf
	 * @param mixed $admin_group_id
	 * @param mixed $update_group_id
	 * @param string $wcf_dir
	 */
	public function writeConfig($mysql_user, $mysql_pw, $mysql_db, $mysql_host, $wcf_prefix, $tb_lang, $tb_dir, $mysql_user_wcf, $mysql_pw_wcf, $mysql_db_wcf, $mysql_host_wcf, $admin_group_id, $update_group_id, $wcf_dir) {
		$config = file('config.inc.php', FILE_SKIP_EMPTY_LINES);
		var_dump($config);
	}

	/**
	 * Execute the SQL Query on the Database.
	 */
	public function execSql() {
		if (!$mysqli->query(file_get_contents('sql.sql')))
			die('Error: ' . $mysqli->error . '\n');
	}

	public function install($step = 1) {
		if ($step == 1)
			$this->checkServer();
		if ($step == 2)
			$this->unpackAll();
		if ($step == 3)
			$this->writeConfig();
		/*
		 * All should be done in install() and setted up in install.php
		 * 1. check server
		 * 2. if ok unpack all else exit
		 * 3. write config
		 * 4. exec sql
		 * 5. delete install.php + __FILE__, if ok, exit, else show message.
		 * unlink('sql.sql');
		 * @unlink('install.php');
		 * @unlink(__FILE__);
		 */
	}

	public function editConfig() {
		$config = file('config.inc.php', FILE_SKIP_EMPTY_LINES);
	}

	public function unzip_all() {

	}

	/**
	 * @see lib/class/Update.class.php
	 */
	public function unlink(array $unlink, $dir = false) {
		foreach ($unlink as $index => $file) {
			if (file_exists($file)) {
				if ($dir) {
					rmdir($file);
					continue;
				}

				if (!unlink($file))
					return false;
			}
		}
		return;
	}

	public function displayForm($step = 1) {

	}

}

?>
