<?php

/**
 * Provides functions for Cookies.
 *
 * @author Kurt
 */
class CookieUtil {

	/**
	 * sets cookies.
	 *
	 * @param  mixed   $cookie
	 * @param  time    $howlong
	 * @param  string  $value
	 * @param  string  $dir
	 * @return boolean
	 */
	public static function setCookie($cookie, $howlong = '', $value = '') {
		if (empty($howlong))
			$howlong = time() + (365 * 24 * 60 * 60);
		if (is_array($cookie)) {
			foreach ($cookie as $name => $value) {
				setcookie($name, $value, $howlong);
			}
			return;
		}
		setcookie($cookie, $value, $howlong);
	}

	/**
	 * Destroys Cookies.
	 *
	 * @param  mixed   $cookie
	 * @param  string  $dir
	 * @return boolean
	 */
	public static function destroyCookie($cookie) {
		$time = time() - 3600;
		if (is_array($cookie)) {
			foreach ($cookie as $name) {
				setcookie($name, '', time() - 3600);
			}
			return;
		}
		setcookie($cookie, '', time() - 3600);
	}

}

?>