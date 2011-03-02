<?php

/**
 * Important functions for twibber
 *
 * @author kurtextrem
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 *
 */
class WCF {

	private static $mysqli2;

	public function __construct() {
		self::$mysqli2 = new mysqli(MYSQL_HOST_WCF, MYSQL_USER_WCF, MYSQL_PW_WCF, MYSQL_DB_WCF);
		if ($mysqli2->connect_error) {
			throw new Exception($lang['mysql_wcf_connect_erorr'] . ' (' . $mysqli2->connect_errno . ') '
					. $mysqli2->connect_error);
		}
	}

	public static function getData($nickname, $password) {
		$nickname = strip_tags($nickname);
		$password = strip_tags($password);
		$nickname = self::$mysqli2->real_escape_string($nickname);
		$password = self::$mysqli2->real_escape_string($password);
		$sql = "SELECT username, password, salt FROM " . wcf_name_prefix . "user WHERE username = '" . $nickname . "'";
		$query = self::$mysqli2->query($sql);
		$result = $query->fetch_object();
		if (!$result)
			return false;
		if ($result->password != StringUtil::getDoubleSaltedHash($password, $result->salt))
			return false;
		return true;
	}

	/**
	 * Returns the avatar url.
	 *
	 * @param string $nickname
	 * @return string
	 */
	public static function getAvatar($nickname) {
		$nickname = strip_tags($nickname);
		$nickname = self::$mysqli2->real_escape_string($nickname);
		$query = self::$mysqli2->query("SELECT avatarID FROM " . wcf_name_prefix . "user WHERE username = '" . $nickname . "'");
		$result = $query->fetch_object();
		return WCF_DIR . '/images/avatars/avatar-' . $result->avatarID . '.png';
	}

	/**
	 * Returns the salt from Database.
	 *
	 * @param string $nickname
	 * @return string
	 */
	public static function getSalt($nickname) {
		$nickname = strip_tags($nickname);
		$nickname = self::$mysqli2->real_escape_string($nickname);
		$query = self::$mysqli2->query("SELECT salt FROM " . wcf_name_prefix . "user WHERE username = '" . $nickname . "'");
		$result = $query->fetch_object();

		return $result->salt;
	}

	/**
	 * Checks if user is logged in.
	 *
	 * @param string $nickname
	 * @param string $pw
	 * @param string $salt
	 * @return boolean
	 */
	public static function getLoginOK($nickname, $pw, $salt) {
		$nickname = strip_tags($nickname);
		$pw = strip_tags($pw);
		$salt = strip_tags($salt);

		$nickname = self::$mysqli2->real_escape_string($nickname);
		$pw = self::$mysqli2->real_escape_string($pw);
		$salt = self::$mysqli2->real_escape_string($salt);

		if (!defined('ENCRYPTION_ENCRYPT_BEFORE_SALTING'))
			define('ENCRYPTION_ENCRYPT_BEFORE_SALTING', false);

		$query = self::$mysqli2->query("SELECT password FROM " . wcf_name_prefix . "user WHERE username = '" . $nickname . "' AND salt = '" . $salt . "' AND password = '" . StringUtil::getDoubleSaltedHash($pw, $salt) . "'");
		
		$result = $query->fetch_object();

		if (!$result)
			return false;
		return true;
	}

	public static function getAdminOK($nickname, $pw, $salt, $update = false) {
		$nickname = strip_tags($nickname);
		$nickname = self::$mysqli2->real_escape_string($nickname);
		$pw = strip_tags($pw);
		$pw = self::$mysqli2->real_escape_string($pw);
		$salt = strip_tags($salt);
		$salt = self::$mysqli2->real_escape_string($salt);
		if (!defined('ENCRYPTION_ENCRYPT_BEFORE_SALTING'))
			define('ENCRYPTION_ENCRYPT_BEFORE_SALTING', false);
		$query = self::$mysqli2->query("SELECT userID FROM " . wcf_name_prefix . "user WHERE username = '" . $nickname . "' AND salt = '" . $salt . "' AND password = '" . StringUtil::getDoubleSaltedHash($pw, $salt) . "'");
		$result = $query->fetch_object();
		$query = self::$mysqli2->query("SELECT groupID FROM " . wcf_name_prefix . "user_to_groups WHERE userID = " . $result->userID);
		if ($update) {
			while ($result = $query->fetch_assoc()) {
				if ($result['groupID'] == wcf_admin_groupid || $result['groupID'] == wcf_update_groupid) {
					return true;
				}
			}
		} else {
			while ($result = $query->fetch_assoc()) {
				if ($result['groupID'] == wcf_admin_groupid) {
					return true;
				}
			}
		}
		return false;
	}

}

?>