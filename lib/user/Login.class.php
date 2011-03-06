<?php

/**
 * Provides login functions.
 *
 * @author Kurt
 */
class Login {

	/**
	 * Login function.
	 *
	 * @param string $nickname
	 * @param string $password
	 */
	public static function userLogin($nickname, $password) {
		$return = WCF::getData($nickname, $password);
		if ($return) {
			CookieUtil::setCookie(array('twibber_nick' => $nickname, 'twibber_pw' => $password, 'twibber_salt' => WCF::getSalt($_POST['nickname'])));
			header('Location: index.php');
			exit;
		} else {
			echo Lang::getLangString('false_pw_nick');
		}
	}

	/**
	 * Logout function.
	 */
	public static function userLogout() {
		CookieUtil::destroyCookie(array('twibber_nick', 'twibber_pw', 'twibber_salt'));
		header('Location: index.php');
	}

}

?>