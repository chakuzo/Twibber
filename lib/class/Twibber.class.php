<?php

require_once("StringUtil.class.php");
@include_once("./config.inc.php");
@include_once("./lib/lang/" . twibber_lang . ".lang.php");

$use_difficult_method = false;
if (!date_default_timezone_set($lang_timezone)) {
	$use_difficult_method = true;
}

if (wcf_name_prefix == "WCF1_") {
	die($prefix_error);
}
if (wcf_update_groupid == "") {
	die($group_id_error);
}

$mysqli = new mysqli(mysql_local, mysql_user, mysql_pw, mysql_db);
if ($mysqli->connect_error) {
	die($mysql_connect_error . ' (' . $mysqli->connect_errno . ') '
			. $mysqli->connect_error);
}
$mysqli2 = new mysqli(mysql_local_wcf, mysql_user_wcf, mysql_pw_wcf, mysql_db_wcf);
if ($mysqli2->connect_error) {
	die($mysql_wcf_connect_erorr . ' (' . $mysqli2->connect_errno . ') '
			. $mysqli2->connect_error);
}

/**
 * Classes for Twibber
 *
 * @author Kurt
 * @TODO All fetch_assoc to fetch_object.
 */
class Twibber
{

	private $mysqli;
	private $lang_comment;

	function __construct($mysqli, $lang_comment)
	{
		$this->mysqli = $mysqli;
		$this->lang_comment = $lang_comment;
	}

	function fetchTwibber($latest = true, $global = false, $nick = '', $start = 0, $end = 30, $signature = false)
	{
		if ($global && !$signature) {
			$query = $this->mysqli->query("SELECT * FROM `twibber_entry` ORDER BY `id` DESC LIMIT " . $start . " , " . $end);
			$false_array = array();
			while ($result = $query->fetch_assoc()) {
				$text = $this->twibberfy_text($result['text']);
				if ($result['to_id'] == 0) {
					$this->twibberfy_output($text, $result['nickname'], $result['date'], false, $result['id']);
				} else {
					$this->twibberfy_output($text, $result['nickname'], $result['date'], true, $result['id']);
				}
			}
		}
		if ($global == false && $nick != '' && !$signature) {
			$nick = $this->mysqli->real_escape_string($nick);
			$nick = strip_tags($nick);
			$query = $this->mysqli->query("SELECT * FROM `twibber_entry` WHERE `nickname` = '" . $nick . "' ORDER BY `id` DESC LIMIT " . $start . " , " . $end);
			while ($result = $query->fetch_assoc()) {
				$text = $this->twibberfy_text($result['text']);
				$this->twibberfy_output($text, $result['nickname'], $result['date']);
			}
		}
		if ($signature && $nick != '') {
			$nick = $this->mysqli->real_escape_string($nick);
			$nick = strip_tags($nick);
			$query = $this->mysqli->query("SELECT * FROM `twibber_entry` WHERE `nickname` = '" . $nick . "' ORDER BY `id` DESC LIMIT " . $start . " , " . $end);
			$result = $query->fetch_assoc();
			return array(str_replace("\\", "", $result['text']), $result['date']);
		}
	}

	function createTwibber($message, $usernick)
	{
		$message = $this->mysqli->real_escape_string($message);
		$usernick = $this->mysqli->real_escape_string($usernick);
		$mysqli->query("INSERT INTO `twibber_entry`(`nickname`,`text`,`date`) VALUES('" . $usernick . "','" . $message . "','" . date("d.m.Y H:i:s") . "')");
	}

	function createTwibbComment($message, $usernick, $to_id)
	{
		$message = $this->mysqli->real_escape_string($message);
		$usernick = $this->mysqli->real_escape_string($usernick);
		$id = $this->mysqli->real_escape_string($to_id);
		$mysqli->query("INSERT INTO `twibber_entry`(`nickname`,`text`,`date`, `to_id`) VALUES('" . $usernick . "','" . $message . "','" . date("d.m.Y H:i:s") . "', '" . $id . "')");
	}

	function searchTwibber($needle, $start = 0, $end = 30)
	{
		$needle = $this->mysqli->real_escape_string($needle);
		$needle = strip_tags($needle);
		$query = $this->mysqli->query("SELECT * FROM `twibber_entry` WHERE `text` LIKE '%" . $needle . "%' ORDER BY `date` DESC LIMIT " . $start . " , " . $end);
		while ($result = $query->fetch_assoc()) {
			$text = $this->twibberfy_text($result['text']);
			$this->twibberfy_output($text, $result['nickname'], $result['date']);
		}
	}

	function getStats($nickname)
	{
		$nick = $this->mysqli->real_escape_string($nickname);
		$nick = strip_tags($nickname);
		$query = $this->mysqli->query("SELECT `text` FROM `twibber_entry` WHERE `nickname` = '" . $nickname . "'");
		$row_cnt = $query->num_rows;
		return $row_cnt;
	}

	function twibberfy_text($text)
	{
		$text = str_replace("\\", "", $text);
		$text = preg_replace('/@([A-Za-z0-9_-]+)/', '@<a href="#nick=$1">$1</a>', $text);
		$text = preg_replace('/((?:https?|ftp):\/\/[^\s\'"\'<>()]+|www\.[^\s\'"\'<>()]+|[\-\w.+]+@(?:[\-\w]+\.)+[\w]{2,6})/i', '<a href="$1">$1</a>', $text);
		$text = preg_replace('/ #([A-Za-z0-9_-]+)/', ' <a href="#search=$1" class="hash">#$1</a>', $text);
		$text = preg_replace('/^#([A-Za-z0-9_-]+)/', ' <a href="#search=$1" class="hash">#$1</a>', $text);
		return $text;
	}

	function twibberfy_output($text, $nickname, $date, $comment = false, $id)
	{
		if (!$comment) {
			echo "<div class='twibb' id='".$id."'>";
			echo "<div class='avatar'><a href='#nick=" . $nickname . "'><img src='" . wcf::getAvatar($nickname) . "'></a></div>";
			echo "<div class='" . $nickname . " nickname' onclick='insert_nick(\"" . $nickname . "\");'>" . $nickname . "</div>";
			echo "<div class='twibb_content'>" . $text . "</div>";
			echo "<div class='comment_banner'><a href='#' class='comment_link'>" . $this->lang_comment . "</a></div>";
			echo "<time title='" . $date . "'>" . Date_Difference::getStringResolved($date) . "</time>";
			echo "</div>";
		} else {
			echo "<div class='comment'>";
			echo "<div class='avatar'><a href='#nick=" . $nickname . "'><img src='" . wcf::getAvatar($nickname) . "'></a></div>";
			echo "<div class='twibb_content'><strong>" . $nickname . ":</strong> " . $text . "</div>";
			echo "<time title='" . $date . "'>" . Date_Difference::getStringResolved($date) . "</time>";
			echo "</div>";
		}
	}

}

class wcf
{

	private static $mysqli2;

	function __construct($mysqli2)
	{
		self::$mysqli2 = $mysqli2;
	}

	public static function getData($nickname, $password)
	{
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

	public static function getAvatar($nickname)
	{
		$nickname = strip_tags($nickname);
		$nickname = self::$mysqli2->real_escape_string($nickname);
		$query = self::$mysqli2->query("SELECT `avatarID` FROM `" . wcf_name_prefix . "user` WHERE `username` = '" . $nickname . "'");
		$result = $query->fetch_object();
		return wcf_dir . "/images/avatars/avatar-" . $result->avatarID . ".png";
	}

	public static function getSalt($nickname)
	{
		$nickname = strip_tags($nickname);
		$nickname = self::$mysqli2->real_escape_string($nickname);
		$query = self::$mysqli2->query("SELECT `salt` FROM `" . wcf_name_prefix . "user` WHERE `username` = '" . $nickname . "'");
		$result = $query->fetch_object();
		return $result->salt;
	}

	public static function getLoginOK($nickname, $pw, $salt)
	{
		$nickname = strip_tags($nickname);
		$nickname = self::$mysqli2->real_escape_string($nickname);
		$pw = strip_tags($pw);
		$pw = self::$mysqli2->real_escape_string($pw);
		$salt = strip_tags($salt);
		$salt = self::$mysqli2->real_escape_string($salt);
		define("ENCRYPTION_ENCRYPT_BEFORE_SALTING", false);
		$query = self::$mysqli2->query("SELECT `password` FROM `" . wcf_name_prefix . "user` WHERE `username` = '" . $nickname . "' AND `salt` = '" . $salt . "' AND `password` = '" . StringUtil::getDoubleSaltedHash($pw, $salt) . "'");
		$result = $query->fetch_object();
		if (!$result)
			return false;
		return true;
	}

	public static function getAdminOK($nickname, $pw, $salt, $update = false)
	{
		$nickname = strip_tags($nickname);
		$nickname = self::$mysqli2->real_escape_string($nickname);
		$pw = strip_tags($pw);
		$pw = self::$mysqli2->real_escape_string($pw);
		$salt = strip_tags($salt);
		$salt = self::$mysqli2->real_escape_string($salt);
		define("ENCRYPTION_ENCRYPT_BEFORE_SALTING", false);
		$query = self::$mysqli2->query("SELECT `userID` FROM `" . wcf_name_prefix . "user` WHERE `username` = '" . $nickname . "' AND `salt` = '" . $salt . "' AND `password` = '" . StringUtil::getDoubleSaltedHash($pw, $salt) . "'");
		$result = $query->fetch_object();
		$query = self::$mysqli2->query("SELECT `groupID` FROM `" . wcf_name_prefix . "user_to_groups` WHERE `userID` = " . $result->userID);
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

/*
 * JavaScript Pretty Date
 * Copyright (c) 2008 John Resig (jquery.com)
 * Licensed under the MIT license.
 */

// Ported to PHP >= 5.1 by Zach Leatherman (zachleat.com)
// Slight modification denoted below to handle months and years.
// Modified by @Kurtextrem.
class Date_Difference
{

	public static function getStringResolved($date, $compareTo = NULL)
	{
		if (!is_null($compareTo)) {
			$compareTo = new DateTime($compareTo);
		}
		return self::getString(new DateTime($date), $compareTo);
	}

	public static function getString(DateTime $date, DateTime $compareTo = NULL)
	{
		global $lang_date_just_now, $lang_date_yesterday, $lang_date_one_minute_ago, $lang_date_one_houre_ago, $lang_date_one_day_ago, $lang_date_one_week_ago, $lang_date_one_month_ago, $lang_date_one_year_ago, $lang_date_minutes_ago, $lang_date_hours_ago, $lang_date_days_ago, $lang_date_weeks_ago, $lang_date_years_ago; # IMBA! xD

		if (is_null($compareTo)) {
			$compareTo = new DateTime('now');
		}
		$diff = $compareTo->format('U') - $date->format('U');
		$dayDiff = floor($diff / 86400);

		if (is_nan($dayDiff) || $dayDiff < 0) {
			return '';
		}

		if ($dayDiff == 0) {
			if ($diff < 60) {
				return $lang_date_just_now;
			} elseif ($diff < 120) {
				return $lang_date_one_minute_ago;
			} elseif ($diff < 3600) {
				return sprintf($lang_date_minutes_ago, floor($diff / 60));
			} elseif ($diff < 7200) {
				return $lang_date_one_houre_ago;
			} elseif ($diff < 86400) {
				return sprintf($lang_date_hours_ago, floor($diff / 3600));
			}
		} elseif ($dayDiff == 1) {
			return $lang_date_yesterday;
		} elseif ($dayDiff < 7) {
			return sprintf($lang_date_days_ago, $dayDiff);
		} elseif ($dayDiff == 7) {
			return $lang_date_one_day_ago;
		} elseif ($dayDiff < (7 * 6)) { // Modifications Start Here
			// 6 weeks at most
			$weeks = ceil($dayDiff / 7);
			$text = $lang_date_one_week_ago;
			if ($weeks != 1)
				$text = $lang_date_weeks_ago;
			return sprintf($text, $weeks);
		} elseif ($dayDiff < 365) {
			$months = ceil($dayDiff / (365 / 12));
			$text = $lang_date_one_month_ago;
			if ($years != 1)
				$text = $lang_date_months_ago;
			return sprintf($text, ceil($dayDiff / (365 / 12)));
		} else {
			$years = round($dayDiff / 365);
			$text = $lang_date_one_year_ago;
			if ($years != 1)
				$text = $lang_date_years_ago;
			return sprintf($text, $years);
		}
	}

}

$Twibber = new Twibber($mysqli, $lang_comment);
$wcf = new wcf($mysqli2);
$youtube = new youtube();

?>