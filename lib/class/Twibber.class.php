<?php

if (is_file('../global.php'))
	require_once('../global.php');

/**
 * Classes for Twibber
 *
 * @author kurtextrem
 * @todo All fetch_assoc to fetch_object.
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 *
 */
class Twibber
{

	private $mysqli;
	/**
	 * Language array
	 * @var Array
	 */
	private $lang;

	public function __construct($mysqli, array $lang)
	{
		$this->mysqli = $mysqli;
		$this->lang = $lang;
	}

	public function fetchTwibber($latest = true, $global = false, $nick = '', $start = 0, $end = 30, $signature = false)
	{
		if ($global && !$signature) {
			$query = $this->mysqli->query("SELECT * FROM twibber_entry ORDER BY id DESC LIMIT " . $start . " , " . $end);
			$false_array = array();
			while ($result = $query->fetch_assoc()) {
				$text = $this->twibberfy_text($result['text']);
				if ($result['to_id'] == 0) {
					$this->twibberfy_output($text, $result['nickname'], $result['date'], false, $result['id']);
				} else {
					$this->twibberfy_output($text, $result['nickname'], $result['date'], true, $result['id'], $result['to_id']);
				}
			}
		}
		if ($global == false && $nick != '' && !$signature) {
			$nick = $this->mysqli->real_escape_string($nick);
			$nick = strip_tags($nick);
			$query = $this->mysqli->query("SELECT * FROM twibber_entry WHERE nickname = '" . $nick . "' ORDER BY id DESC LIMIT " . $start . " , " . $end);
			while ($result = $query->fetch_assoc()) {
				$text = $this->twibberfy_text($result['text']);
				$this->twibberfy_output($text, $result['nickname'], $result['date'], $result['id']);
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

	public function createTwibber($message, $usernick)
	{
		$message = $this->mysqli->real_escape_string($message);
		$usernick = $this->mysqli->real_escape_string($usernick);
		$this->mysqli->query("INSERT INTO twibber_entry(nickname,text,date) VALUES('" . $usernick . "','" . $message . "','" . date("d.m.Y H:i:s") . "')");
	}

	public function createTwibbComment($message, $usernick, $to_id)
	{
		$message = $this->mysqli->real_escape_string($message);
		$usernick = $this->mysqli->real_escape_string($usernick);
		$id = $this->mysqli->real_escape_string($to_id);
		$this->mysqli->query("INSERT INTO twibber_entry(nickname,text,date, to_id) VALUES('" . $usernick . "','" . $message . "','" . date("d.m.Y H:i:s") . "', '" . $id . "')");
	}

	public function searchTwibber($needle, $start = 0, $end = 30)
	{
		$needle = $this->mysqli->real_escape_string($needle);
		$needle = strip_tags($needle);
		$query = $this->mysqli->query("SELECT * FROM twibber_entry WHERE text LIKE '%" . $needle . "%' ORDER BY date DESC LIMIT " . $start . " , " . $end);
		while ($result = $query->fetch_assoc()) {
			$text = $this->twibberfy_text($result['text']);
			$this->twibberfy_output($text, $result['nickname'], $result['date']);
		}
	}

	public function getStats($nickname)
	{
		$nick = $this->mysqli->real_escape_string($nickname);
		$nick = strip_tags($nickname);
		$query = $this->mysqli->query("SELECT text FROM twibber_entry WHERE nickname = '" . $nickname . "'");
		$row_cnt = $query->num_rows;
		return $row_cnt;
	}

	public function twibberfy_text($text)
	{
		$text = str_replace("\\", "", $text);
		$text = preg_replace('/@([A-Za-z0-9_-]+)/', '@<a href="#nick=$1">$1</a>', $text);
		$text = preg_replace('/((?:https?|ftp):\/\/[^\s\'"\'<>()]+|www\.[^\s\'"\'<>()]+|[\-\w.+]+@(?:[\-\w]+\.)+[\w]{2,6})/i', '<a href="$1">$1</a>', $text);
		$text = preg_replace('/ #([A-Za-z0-9_-]+)/', ' <a href="#search=$1" class="hash">#$1</a>', $text);
		$text = preg_replace('/^#([A-Za-z0-9_-]+)/', ' <a href="#search=$1" class="hash">#$1</a>', $text);
		return $text;
	}

	public function twibberfy_output($text, $nickname, $date, $comment = false, $id, $to_id = 0)
	{
		if (!$comment) {
			echo "<div class='twibb' id='" . $id . "'>";
		} else {
			echo "<div class='comment' to_id='" . $to_id . "'>";
		}

		echo "<div class='avatar'><a href='#nick=" . $nickname . "'><img src='" . WCF::getAvatar($nickname) . "'></a></div>";

		if (!$comment) {
			echo "<div class='" . $nickname . " nickname'>" . $nickname . "</div>";
			echo "<div class='twibb_content'>" . $text . "</div>";
			echo "<div class='comment_banner'><a href='#' class='comment_link'>" . $this->lang['comment'] . "</a></div>";
		} else {
			echo "<div class='twibb_content'><a href='#nick=" . $nickname . "'><strong>" . $nickname . ":</strong></a> " . $text . "</div>";
		}

		echo "<time title='" . $date . "'>" . Date_Difference::getStringResolved($date) . "</time>";
		echo '</div>';
	}

}

?>