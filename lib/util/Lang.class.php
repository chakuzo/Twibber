<?php

// if lang array isnt here, load it.
if (!isset($lang))
	require_once(TWIBBER_DIR . 'lang/' . TWIBBER_LANG . '.lang.php');

/**
 * Returns language specified strings.
 *
 * @author Kurt
 */
class Lang {

	/**
	 * Language array.
	 *
	 * @var array
	 */
	private static $lang;

	/**
	 * @param array $lang
	 */
	public function __construct(array $lang) {
		self::$lang = $lang;
	}

	/**
	 * Returns the String for the Identifier.
	 *
	 * @param string $ident
	 * @return string
	 */
	public static function getLangString($ident) {
		return self::$lang[$ident];
	}

}

?>