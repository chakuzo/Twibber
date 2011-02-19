<?php

/*
 * @AUTHOR Kurtextrem
 * @Contact kurtextrem@gmail.com
 * function: getTitle($id)
 * returns: The title of the video.
 * example: http://m.youtube.com/watch?v=dsBbdKmjquM
 * example for return: Phineas und Ferb - Gitchi Gitchi Goo [HQ] (German)
 * function: getLength($id)
 * returns: The length of the video.
 * function: getRate($id, $image)
 * returns: the rate image if image = true, if its false, it returns the number.
 * example for return: 5.0 stars
 * function: getAll() Most effizient!
 * returns: Array. Array['title'], Array['length'], Array['rate'], Array['rateIMG'], Array['thub'].
 */

class youtube
{

	function getTitle($id)
	{
		$contents = file_get_contents("http://m.youtube.com/watch?v=" . $id);
		$titel = preg_match("/<title>YouTube - (.*)<\/title>/", $contents, $matches);
		return $matches[1];
	}

	function getLength($id)
	{
		$contents = file_get_contents("http://m.youtube.com/watch?v=" . $id);
		$length = preg_match("/<div>([0-9:]*)&nbsp;/", $contents, $matches);
		return $matches[1];
	}

	function getRate($id, $image = true)
	{
		$contents = file_get_contents("http://m.youtube.com/watch?v=" . $id);
		if ($image) {
			$rate = preg_match('/<img src="(.*)" alt=".+ stars"/', $contents, $matches);
			return $matches[1];
		} else {
			$rate = preg_match('/<img src=".*" alt="(.+ stars)"/', $contents, $matches);
			return $matches[1];
		}
	}

	function getAll()
	{
		$return = Array();
		$contents = file_get_contents("http://m.youtube.com/watch?v=" . $id);
		preg_match("/<title>YouTube - (.*)<\/title>/", $contents, $matches);
		$return['titel'] = $matches[1];
		preg_match("/<div>([0-9:]*)&nbsp;/", $contents, $matches);
		$return['length'] = $matches[1];
		preg_match('/<img src="(.*)" alt=".+ stars"/', $contents, $matches);
		$return['rateIMG'] = $matches[1];
		preg_match('/<img src=".*" alt="(.+ stars)"/', $contents, $matches);
		$return['rate'] = $matches[1];
		preg_match('/<img src="(.*)" alt="Video"/', $contents, $matches);
		$return['thub'] = $matches[1];
	}

}

$youtube = new youtube();

?>