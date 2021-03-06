<?php

/**
 * WCF "Bridge" for Twibber.
 *
 * @author  kurtextrem
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 */
class WCF {

	private static $mysqli2;

	public function __construct($mysqli2) {
		self::$mysqli2 = $mysqli2;
	}

	/**
	 * Checks if the login data is ok.
	 *
	 * @param  string  $nickname
	 * @param  string  $password
	 * @return boolean
	 */
	public static function getDataOK($nickname, $password) {
		$nickname = self::$mysqli2->real_escape_string($nickname);
		$password = self::$mysqli2->real_escape_string($password);

		$sql = "SELECT username, password, salt FROM " . WCF_NAME_PREFIX . "user WHERE username = '" . $nickname . "'";
		$query = self::$mysqli2->query($sql);

		$result = $query->fetch_object();
		return ($result->password == StringUtil::getDoubleSaltedHash($password, $result->salt));
	}

	/**
	 * Returns the avatar url.
	 *
	 * @param  string $nickname
	 * @return string
	 */
	public static function getAvatar($nickname) {
		$nickname = self::$mysqli2->real_escape_string($nickname);

		$query = self::$mysqli2->query("SELECT avatarID FROM " . WCF_NAME_PREFIX . "user WHERE username = '" . $nickname . "'");
		$result = $query->fetch_object();

		return WCF_DIR . '/images/avatars/avatar-' . $result->avatarID . '.png';
	}

	/**
	 * Returns the salt from Database.
	 *
	 * @param  string $nickname
	 * @return string
	 */
	public static function getSalt($nickname) {
		$nickname = self::$mysqli2->real_escape_string($nickname);

		$query = self::$mysqli2->query("SELECT salt FROM " . WCF_NAME_PREFIX . "user WHERE username = '" . $nickname . "'");
		$result = $query->fetch_object();

		return $result->salt;
	}

	/**
	 * Checks if user is logged in.
	 *
	 * @param  string  $nickname
	 * @param  string  $pw
	 * @param  string  $salt
	 * @return boolean
	 */
	public static function getLoginOK($nickname, $pw, $salt) {
		if (!defined('ENCRYPTION_ENCRYPT_BEFORE_SALTING'))
			define('ENCRYPTION_ENCRYPT_BEFORE_SALTING', false);

		$nickname = self::$mysqli2->real_escape_string($nickname);
		$pw = self::$mysqli2->real_escape_string($pw);
		$salt = self::$mysqli2->real_escape_string($salt);

		$query = self::$mysqli2->query("SELECT password FROM " . WCF_NAME_PREFIX . "user WHERE username = '" . $nickname . "' AND salt = '" . $salt . "' AND password = '" . StringUtil::getDoubleSaltedHash($pw, $salt) . "'");

		$result = $query->fetch_object();

		if (!$result)
			return false;
		return true;
	}

	/**
	 * Checks if user is admin.
	 *
	 * @param  string   $nickname
	 * @param  string   $pw
	 * @param  string   $salt
	 * @param  boolean  $update
	 * @return boolean
	 */
	public static function getAdminOK($nickname, $pw, $salt, $update = false) {
		if (!defined('ENCRYPTION_ENCRYPT_BEFORE_SALTING'))
			define('ENCRYPTION_ENCRYPT_BEFORE_SALTING', false);

		$nickname = self::$mysqli2->real_escape_string($nickname);
		$pw = self::$mysqli2->real_escape_string($pw);
		$salt = self::$mysqli2->real_escape_string($salt);

		$query = self::$mysqli2->query("SELECT userID FROM " . WCF_NAME_PREFIX . "user WHERE username = '" . $nickname . "' AND salt = '" . $salt . "' AND password = '" . StringUtil::getDoubleSaltedHash($pw, $salt) . "'");
		$result = $query->fetch_object();
		$query = self::$mysqli2->query("SELECT groupID FROM " . WCF_NAME_PREFIX . "user_to_groups WHERE userID = " . $result->userID);

		while ($result = $query->fetch_object()) {
			if ($update) {
				if ($result->groupID == WCF_ADMIN_GROUPID || $result->groupID == WCF_UPDATE_GROUPID)
					return true;
			} else {
				if ($result->groupID == WCF_ADMIN_GROUPID)
					return true;
			}
		}
		return false;
	}

}

?>