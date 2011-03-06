<?php

/**
 * Functions to create ProgressBars.
 *
 * @author Kurt
 */
class ProgressBar {

	/**
	 * Prints an HTML Progressbar.
	 *
	 * @param  boolean $progress
	 * @param  integer $min
	 * @param  integer $max
	 * @param  integer $value
	 * @param  string  $title
	 * @return string
	 */
	public static function CreateBar($progress = false, $min = 0, $max = 100, $value = 0, $title = '') {
		if ($progress) {
			$code = '<progress value="' . $value . '" max="' . $max . '"></progress>';
		} else {
			$code = '<meter min="' . $min . '" max="' . $max . '" value="' . $value . '" title="' . $title . '"></meter>';
		}
		return $code;
	}

}

?>