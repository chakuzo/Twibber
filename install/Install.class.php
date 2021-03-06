<?php

/**
 * Install Class for Twibber
 *
 * @author Kurt
 */
class Install {
	/**
	 * Javascript function submit_form();
	 */
	const SUBMIT_FORM = '<script>
		function submit_form(){
			$.post("?step=5&action=5", $("form").serialize(), function(ret){eval(ret);});
		}
		</script>';

	/**
	 * The Form for Config.
	 */
	const CONFIG_FORM = '
		<form action="?step=5&action=5" onsubmit="submit_form(); return false;">
			Let\'s go to the Database Connections:
			<table>
				<tr>
					<td>MySQL User:</td>
					<td><input type="text" name="mysql_user" placeholder="MySQL User"></td>
				</tr>
				<tr>
					<td>MySQL Password:</td>
					<td><input type="password" name="mysql_pw" placeholder="MySQL PW"></td>
				</tr>
				<tr>
					<td>MySQL Database:</td>
					<td><input type="text" name="mysql_db" placeholder="MySQL DB"></td>
				</tr>
				<tr>
					<td>MySQL Host:</td>
					<td><input type="text" name="mysql_host" value="localhost"></td>
				</tr>
				<tr>
					<td>MySQL Prefix:</td>
					<td><input type="text" name="mysql_prefix" value="twibber_" disabled></td>
				</tr>
			</table>
			<hr>Now the <strong>WCF</strong> Database Connection:
			<table>
				<tr>
					<td>MySQL User:</td>
					<td><input type="text" name="mysql_user_wcf" placeholder="MySQL User"></td>
				</tr>
				<tr>
					<td>MySQL Password:</td>
					<td><input type="password" name="mysql_pw_wcf" placeholder="MySQL PW"></td>
				</tr>
				<tr>
					<td>MySQL Database:</td>
					<td><input type="text" name="mysql_db_wcf" placeholder="MySQL DB"></td>
				</tr>
				<tr>
					<td>MySQL Host:</td>
					<td><input type="text" name="mysql_host_wcf" value="localhost"></td>
				</tr>
				<tr>
					<td>MySQL Prefix:</td>
					<td><input type="text" name="wcf_prefix" value="wcf1_"></td>
				</tr>
			</table>
			<hr>Yeah, and at least the mixed Config:
			<table>
				<tr>
					<td title="Please provide the group id from the acp from the WCF / WBB. DANGER! If you provide the false id, member can update and in future in the Twibber ACP">Admin Group ID:</td>
					<td><input name="admin_group_id" type="number" min="0" value="4"></td>
				</tr>
				<tr>
					<td title="Here you must add the group id of an group, which can only update. No access to acp, but maybe more active and more online, so can update more often.">Update Group ID:</td>
					<td><input name="update_group_id" type="number" min="0" value="4"></td>
				</tr>
				<tr>
					<td title="The complete URL of the WCF dir.">WCF Dir:</td>
					<td><input name="wcf_dir" type="text" placeholder="http://example.com/wcf"></td>
				</tr>
				<tr>
					<td title="GZip makes Twibber really faster!">Enable GZip?</td>
					<td><label><input name="gzip_on" type="checkbox" value="true" checked>Yes</label></td>
				</tr>
				<tr>
					<td>Lanuage:</td>
					<td><select name="tb_lang"><option value="de" selected>Deutsch<option value="en">English</select></td>
				</tr>
			</table>
			<button>Submit</button>
		</form>
		';

	/**
	 * Setup Done const.
	 */
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
	 * @param boolean $gzip_on
	 */
	public static function writeConfig($mysql_user, $mysql_pw, $mysql_db, $mysql_host = 'localhost', $wcf_prefix, $tb_lang = 'de', $mysql_user_wcf, $mysql_pw_wcf, $mysql_db_wcf, $mysql_host_wcf = 'localhost', $admin_group_id = 4, $update_group_id = 4, $wcf_dir, $gzip_on = false) {

		// MySQL connection
		$config = file('config.inc.php');
		$config[5] = "define('MYSQL_USER', '" . $mysql_user . "'); // The User for the Database\n";
		$config[6] = "define('MYSQL_PW', '" . $mysql_pw . "'); // The Password for the User\n";
		$config[7] = "define('MYSQL_DB', '" . $mysql_db . "'); // The Database Name\n";
		$config[8] = "define('MYSQL_HOST', '" . $mysql_host . "'); // often localhost\n";

		// WCF connection
		$config[13] = "define('MYSQL_USER_WCF', '" . $mysql_user_wcf . "'); // The Database Name\n";
		$config[14] = "define('MYSQL_PW_WCF', '" . $mysql_pw_wcf . "'); // The Database Name\n";
		$config[15] = "define('MYSQL_DB_WCF', '" . $mysql_db_wcf . "'); // The Database Name\n";
		$config[16] = "define('MYSQL_HOST_WCF', '" . $mysql_host_wcf . "'); // The Database Name\n";
		$config[17] = "define('wcf_name_prefix', '" . $wcf_prefix . "'); // The Database Name\n";

		// mixed
		$config[22] = "define('HTTP_GZIP_ENABLED', " . $gzip_on . "); // The Database Name\n";
		$config[26] = "define('WCF_DIR', '" . $wcf_dir . "'); // The Database Name\n";
		$config[28] = "define('TWIBBER_LANG', '" . $tb_lang . "'); // The Database Name\n";
		$config[30] = "define('wcf_admin_groupid', " . $admin_group_id . "); // The Database Name\n";
		$config[32] = "define('wcf_update_groupid', " . $update_group_id . "); // The Database Name\n";

		if (file_put_contents('config.inc.php', $config) && @self::execSQL($mysql_user, $mysql_pw, $mysql_db, $mysql_host))
			return true;
		return false;
	}

	/**
	 * Execute the SQL Query on the Database.
	 */
	public function execSQL($mysql_user, $mysql_pw, $mysql_db, $mysql_host) {
		$mysqli = new mysqli($mysql_host, $mysql_user, $mysql_pw, $mysql_db);
		if ($mysqli->connect_error) {
			die('alert("MySQL Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error . '");');
		}
		if (!$mysqli->query(file_get_contents('sql.sql')))
			die('alert("MySQL Error: ' . $mysqli->error . '");');
		return true;
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

			default:
				$this->checkServer(); // checks server
				break;
		}
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
			$this->unlink('Twibber.zip');
			return;
		}
		die("Can't extract Twibber.zip. Please try again!<br>Error Code #" . $open);
	}

	/**
	 * @see FileUtil::unlink
	 */
	public static function unlink($unlink, $message = true) {
		if (is_array($unlink)) {
			foreach ($unlink as $index => $file) {
				if (file_exists($file)) {
					if (is_dir($file)) {
						if (!rmdir($file) && $message)
							self::unlinkMSG($file);
						continue;
					}

					if (!unlink($file) && $message)
						self::unlinkMSG($file);
				}
			}
		} else {
			if (file_exists($file)) {
				if (is_dir($file)) {
					if (!rmdir($file) && $message)
						self::unlinkMSG($file);
				}

				if (!unlink($file) && $message)
					self::unlinkMSG($file);
			}
			return true;
		}
	}

	/**
	 * @see FileUtil::unlinkMSG
	 */
	public static function unlinkMSG($file) {
		echo "Can't delete '" . $file . "'. Please do this!";
	}

	/**
	 * Displays the Form for $step.
	 *
	 * @param integer $step
	 */
	public function displayForm($step = 1) {
		switch ($step) {
			case 2:
				$this->install(2);
				break;

			case 3:
				echo self::SUBMIT_FORM;
				echo self::CONFIG_FORM;
				break;

			case 4:
				$unlink = $this->unlink(array(__FILE__, 'sql.sql', 'install.php')); // unlink install
				if ($unlink)
					echo self::SETUP_DONE;
				else
					exit(self::SETUP_DONE . 'Please delete "sql.sql, install.php, Install.class.php"');
				break;

			default:
				echo '
				Welcome to Twibber install. If you have questions, take a look at <a href="https://github.com/chakuzo/Twibber/wiki/Howto-install-Twibber-(DE&EN)">Github Twibber Wiki</a>
				<br><br>
				So, here we go:'
				;
				$this->checkServer(true);
				break;
		}
	}

	public function enableButton() {
		echo '<script>$(document).ready(function(){enableButton();});</script>';
	}

}

?>
