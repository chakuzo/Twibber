<?php

/**
 * Install Class for Twibber
 *
 * @author Kurt
 */
class Install {

	const CONFIG_FORM = '
		<form action="?step=">
			Let\'s go to the Database Connections:
			<table>
				<tr>
					<td>MySQL User:</td>
					<td><input type="text" placeholder="MySQL User"></td>
				</tr>
				<tr>
					<td>MySQL Password:</td>
					<td><input type="password" placeholder="MySQL PW"></td>
				</tr>
				<tr>
					<td>MySQL Database:</td>
					<td><input type="text" placeholder="MySQL DB"></td>
				</tr>
				<tr>
					<td>MySQL Host:</td>
					<td><input type="text" value="localhost"></td>
				</tr>
				<tr>
					<td>MySQL Prefix:</td>
					<td><input type="text" value="twibber_" disabled></td>
				</tr>
			</table>
			<hr>Now the <strong>WCF</strong> Database Connection:
			<table>
				<tr>
					<td>MySQL User:</td>
					<td><input type="text" placeholder="MySQL User"></td>
				</tr>
				<tr>
					<td>MySQL Password:</td>
					<td><input type="password" placeholder="MySQL PW"></td>
				</tr>
				<tr>
					<td>MySQL Database:</td>
					<td><input type="text" placeholder="MySQL DB"></td>
				</tr>
				<tr>
					<td>MySQL Host:</td>
					<td><input type="text" value="localhost"></td>
				</tr>
				<tr>
					<td>MySQL Prefix:</td>
					<td><input type="text" value="wcf1_"></td>
				</tr>
			</table>
		</form>
		';

	const SETUP_DONE = '<div class="success">Setup finished. Have fun with your Twibber Install :)</div>';

	/**
	 * Checks all requirements for Twibber.
	 *
	 * @param boolean $is_check
	 */
	public function checkServer($is_check = false) {
		try {
			// PHP 5.3 is required
			if (version_compare(PHP_VERSION, '5.3') == -1)
				throw new exception('Your server / webspace is running a PHP version below 5.3, please change that!');
			$extensions = get_loaded_extensions();
			if (array_search('xml', $extensions) === false)
				throw new exception('Your server / webspace doenst have a XML Extension!');
			if (defined('safe_mode'))
				throw new exception('Safe Mode is on! Please disable it.');
			if (!function_exists('gd_info'))
				throw new exception('GDLib is needed for Signature Images. Delete Line 24 + 25 on Install.class.php, to continue setup.');
			if ($is_check) {
				echo '<div class="success">Congratulation, your Server / Webspace is ready for Twibber.</div>';
				$this->enableButton();
			}
		} catch (exception $e) {
			echo '<div class="error">Error: ' . var_dump($exception) . '</div>';
			exit();
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
	public function execSQL() {
		if (!$mysqli->query(file_get_contents('sql.sql')))
			die('Error: ' . $mysqli->error . '\n');
	}

	/**
	 * Handles the Step from Install.
	 *
	 * @param integer $step
	 */
	public function install($step = 1) {
		switch ($step) {
			case 2:
				$this->unzipAll(); // Unpack twibber
				break;
			case 4:
				$this->execSQL(); // exec sql
				break;

			default:
				$this->checkServer(); // checks server
				break;
		}
	}

	public function editConfig() {
		$config = file('config.inc.php', FILE_SKIP_EMPTY_LINES);
		var_dump($config);
	}

	/**
	 * Extracts the Twibber.zip.
	 */
	public function unzipAll() {
		$filename = 'Twibber.zip';
		$archive = new ZipArchive();
		$open = $archive->open($filename);
		if ($open === true) {
			$archive->extractTo(__DIR__);
			echo 'Successfully extracted Twibber.zip.';
			$this->enableButton();
		}
		die("Can't extract Twibber.zip!<br>Error Code #" . $open);
	}

	/**
	 * @see lib/module/Update.class.php
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
		switch ($step) {
			case 2:
				$this->install(2);
				break;
			case 3:
				$this->writeConfig(); // Write config
				break;
			case 4:
				$this->install(4); // exec sql
				break;
			case 5:
				$unlink = $this->unlink(array(__FILE__, 'sql.sql')); // unlink install
				if ($unlink)
					$this->enableButton();
				else
					exit('Done install! Please delete the Folder /install/');
				break;

			case 6:
				echo SETUP_DONE;

			default:
				echo '
				Welcome to Twibber install.
				<br><br>
				So, here we go:'
				;
				$this->checkServer(true);
				break;
		}
	}

	public function enableButton() {
		echo '<script>$(document).ready(function(){$("button").removeAttr("disabled");});</script>';
	}

}

?>
