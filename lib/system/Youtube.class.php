<?php

/**
 * A tiny class for youtube functions.
 *
 * @author Kurtextrem
 * @version 1.0
 */
class Youtube {

	/**
	 * @var boolean
	 */
	private $JSON;

	/**
	 * JSON output?
	 *
	 * @param boolean $json_enabled
	 * @return -
	 */
	public function __construct($json_enabled = null) {
		$this->JSON = $json_enabled;
	}

	/**
	 * Returns the title.
	 *
	 * @param string $id
	 * @return mixed
	 */
	public function getTitle($id) {
		$contents = file_get_contents('http://m.youtube.com/watch?v=' . $id);
		$titel = preg_match('~<title>YouTube - (.*)</title>~', $contents, $matches);
		return ($this->JSON != 1) ? $matches[1] : json_encode($matches[1]);
	}

	/**
	 * Returns the length of the video.
	 *
	 * @param string $id
	 * @return mixed
	 */
	public function getLength($id) {
		$contents = file_get_contents('http://m.youtube.com/watch?v=' . $id);
		$length = preg_match('/<div>([0-9:]*)&nbsp;/', $contents, $matches);
		return ($this->JSON != 1) ? $matches[1] : json_encode($matches[1]);
	}

	/**
	 * Returns the rate.
	 *
	 * @param string $id
	 * @param bool $image
	 * @return mixed
	 */
	public function getRate($id, $image = null) {
		$contents = file_get_contents('http://m.youtube.com/watch?v=' . $id);
		preg_match('/<img src="(.*)" alt="(.+ stars)"/', $contents, $matches);
		if ($image == true)
			return ($this->JSON != 1) ? $matches[1] : json_encode($matches[2]);
		return ($this->JSON != 1) ? $matches[1] : json_encode($matches[1]);
	}

	/**
	 * A fusion of all functions.
	 * 
	 * @param string $id
	 * @return mixed
	 */
	public function getAll($id) {
		$return = Array();
		$contents = file_get_contents('http://m.youtube.com/watch?v=' . $id);
		preg_match('~<title>YouTube - (.*)</title>~', $contents, $matches);
		$return['title'] = $matches[1];
		preg_match("/<div>([0-9:]*)&nbsp;/", $contents, $matches);
		$return['length'] = $matches[1];
		preg_match('/<img src="(.*)" alt="(.+ stars)"/', $contents, $matches);
		$return['rateIMG'] = $matches[1];
		$return['rate'] = $matches[2];
		preg_match('/<img src="(.*)" alt="Video"/', $contents, $matches);
		$return['thub'] = $matches[1];
		return ($this->JSON != 1) ? $matches[1] : json_encode($matches[1]);
	}

}

//$Youtube = new Youtube();
//echo $Youtube->getTitle('YoWOqB2q3BQ');

?>