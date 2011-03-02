<?php

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

	public function __construct(array $lang){
		self::$lang = $lang;
	}
}

?>