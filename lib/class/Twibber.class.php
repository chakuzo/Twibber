<?php

require_once('StringUtil.class.php');
require_once('WCF.class.php');
require_once('./config.inc.php');
require_once('./lib/lang/' . TWIBBER_LANG . '.lang.php');

$use_difficult_method = false;
if (!date_default_timezone_set($lang_timezone)) {
	$use_difficult_method = true;
}

if (wcf_name_prefix == 'WCF1_') {
	die($prefix_error);
}
if (wcf_update_groupid == '') {
	die($group_id_error);
}

$mysqli = new mysqli(mysql_local, mysql_user, mysql_pw, mysql_db);
if ($mysqli->connect_error) {
	die($mysql_connect_error . ' (' . $mysqli->connect_errno . ') '
			. $mysqli->connect_error);
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
			$query = $this->mysqli->query("SELECT * FROM twibber_entry ORDER BY id DESC LIMIT " . $start . " , " . $end);
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
			$query = $this->mysqli->query("SELECT * FROM twibber_entry WHERE nickname = '" . $nick . "' ORDER BY id DESC LIMIT " . $start . " , " . $end);
			while ($result = $query->fetch_assoc()) {
				$text = $this->twibberfy_text($result['text']);
				$this->twibberfy_output($text, $result['nickname'], $result['date']);
			}
		}
		if ($signature && $nick != '') {
			$nick = $this->mysqli->real_escape_string($nick);
			$nick = strip_tags($nick);
			$query = $this->mysqli->query("SELECT * FROM twibber_entry WHERE nickname = '" . $nick . "' ORDER BY id DESC LIMIT " . $start . " , " . $end);
			$result = $query->fetch_assoc();
			return array(str_replace("\\", "", $result['text']), $result['date']);
		}
	}

	function createTwibber($message, $usernick)
	{
		$message = $this->mysqli->real_escape_string($message);
		$usernick = $this->mysqli->real_escape_string($usernick);
		$mysqli->query("INSERT INTO twibber_entry(nickname,text,date) VALUES('" . $usernick . "','" . $message . "','" . date("d.m.Y H:i:s") . "')");
	}

	function createTwibbComment($message, $usernick, $to_id)
	{
		$message = $this->mysqli->real_escape_string($message);
		$usernick = $this->mysqli->real_escape_string($usernick);
		$id = $this->mysqli->real_escape_string($to_id);
		$mysqli->query("INSERT INTO twibber_entry(nickname,text,date, to_id) VALUES('" . $usernick . "','" . $message . "','" . date("d.m.Y H:i:s") . "', '" . $id . "')");
	}

	function searchTwibber($needle, $start = 0, $end = 30)
	{
		$needle = $this->mysqli->real_escape_string($needle);
		$needle = strip_tags($needle);
		$query = $this->mysqli->query("SELECT * FROM twibber_entry WHERE text LIKE '%" . $needle . "%' ORDER BY date DESC LIMIT " . $start . " , " . $end);
		while ($result = $query->fetch_assoc()) {
			$text = $this->twibberfy_text($result['text']);
			$this->twibberfy_output($text, $result['nickname'], $result['date']);
		}
	}

	function getStats($nickname)
	{
		$nick = $this->mysqli->real_escape_string($nickname);
		$nick = strip_tags($nickname);
		$query = $this->mysqli->query("SELECT text FROM twibber_entry WHERE nickname = '" . $nickname . "'");
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
			echo "<div class='twibb' id='" . $id . "'>";
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

$Twibber = new Twibber($mysqli, $lang_comment);

?>