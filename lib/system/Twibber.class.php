<?php

// define current twibber version
define('TWIBBER_VERSION', '0.6.6');

require_once(TWIBBER_DIR . '/lib/core.functions.php');

// Sets default timezone
date_default_timezone_set(Lang::getLangString('timezone'));

/**
 * This is the main class for Twibber.
 *
 * @author 	kurtextrem
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 */
class Twibber {

	/**
	 * Database connection.
	 *
	 * @var mysqli
	 */
	private $mysqli;

	/**
	 * Constructor.
	 *
	 * @param mysqli $mysqli
	 */
	public function __construct($mysqli) {
		$this->mysqli = $mysqli;
	}

	/**
	 * Returns twibbs.
	 *
	 * @param  boolean  $latest
	 * @param  boolean  $global
	 * @param  string   $nick
	 * @param  integer  $start
	 * @param  integer  $end
	 * @param  boolean  $signature
	 * @return mixed
	 */
	public function fetchTwibber($latest = true, $global = false, $nick = '', $start = 0, $end = 30, $signature = false) {
		if ($global && !$signature) {
			$query = $this->mysqli->query("SELECT * FROM twibber_entry ORDER BY id DESC LIMIT " . $start . " , " . $end);
			$false_array = array();
			while ($result = $query->fetch_object()) {
				$text = $this->twibberfy_text($result->text);
				if ($result->to_id == 0) {
					$this->twibberfy_output($text, $result->nickname, $result->date, false, $result->id);
				} else {
					$this->twibberfy_output($text, $result->nickname, $result->date, true, $result->id, $result->to_id);
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

	/**
	 * Creates a Twibb in Database.
	 *
	 * @param string $message
	 * @param string $usernick
	 */
	public function createTwibber($message, $usernick) {
		$message = $this->mysqli->real_escape_string($message);
		$usernick = $this->mysqli->real_escape_string($usernick);
		$this->mysqli->query("INSERT INTO twibber_entry(nickname,text,date) VALUES('" . $usernick . "','" . $message . "','" . date("d.m.Y H:i:s") . "')");
	}

	/**
	 * Creates a Comment for a specified Twibb in Database.
	 *
	 * @param string  $message
	 * @param string  $usernick
	 * @param integer $to_id
	 */
	public function createTwibbComment($message, $usernick, $to_id) {
		$message = $this->mysqli->real_escape_string($message);
		$usernick = $this->mysqli->real_escape_string($usernick);
		$id = $this->mysqli->real_escape_string($to_id);
		$this->mysqli->query("INSERT INTO twibber_entry(nickname,text,date, to_id) VALUES('" . $usernick . "','" . $message . "','" . date("d.m.Y H:i:s") . "', '" . $id . "')");
	}

	/**
	 * Search for 'needle' in Database.
	 *
	 * @param string  $needle
	 * @param integer $start
	 * @param integer $end
	 */
	public function searchTwibber($needle, $start = 0, $end = 30) {
		$needle = $this->mysqli->real_escape_string($needle);
		$needle = strip_tags($needle);
		$query = $this->mysqli->query("SELECT * FROM twibber_entry WHERE text LIKE '%" . $needle . "%' ORDER BY date DESC LIMIT " . $start . " , " . $end);
		while ($result = $query->fetch_assoc()) {
			$text = $this->twibberfy_text($result['text']);
			$this->twibberfy_output($text, $result['nickname'], $result['date']);
		}
	}

	/**
	 * How much Twibbs are sent by a user?
	 *
	 * @param  string  $nickname
	 * @return integer
	 */
	public function getStats($nickname) {
		$nick = $this->mysqli->real_escape_string($nickname);
		$nick = strip_tags($nickname);
		$query = $this->mysqli->query("SELECT text FROM twibber_entry WHERE nickname = '" . $nickname . "'");
		$row_cnt = $query->num_rows;
		return $row_cnt;
	}

	/**
	 * Modifies a text for twibber.
	 *
	 * @param  string $text
	 * @return string
	 */
	public function twibberfy_text($text) {
		$text = str_replace("\\", "", $text);
		$text = preg_replace('/@([A-Za-z0-9_-]+)/', '@<a href="#nick=$1">$1</a>', $text);
		$text = preg_replace('/((?:https?|ftp):\/\/[^\s\'"\'<>()]+|www\.[^\s\'"\'<>()]+|[\-\w.+]+@(?:[\-\w]+\.)+[\w]{2,6})/i', '<a href="$1">$1</a>', $text);
		$text = preg_replace('/ #([A-Za-z0-9_-]+)/', ' <a href="#search=$1" class="hash">#$1</a>', $text);
		$text = preg_replace('/^#([A-Za-z0-9_-]+)/', ' <a href="#search=$1" class="hash">#$1</a>', $text);
		return $text;
	}

	/**
	 * Creates the Output for Twibber.
	 *
	 * @param type $text
	 * @param type $nickname
	 * @param type $date
	 * @param type $comment
	 * @param type $id
	 * @param type $to_id
	 */
	public function twibberfy_output($text, $nickname, $date, $comment = false, $id, $to_id = 0) {
		if (!$comment) {
			echo "<div class='twibb' id='" . $id . "'>";
		} else {
			echo "<div class='comment' to_id='" . $to_id . "'>";
		}

		echo "<div class='avatar'><a href='#nick=" . $nickname . "'><img src='" . WCF::getAvatar($nickname) . "'></a></div>";

		if (!$comment) {
			echo "<div class='" . $nickname . " nickname'>" . $nickname . "</div>";
			echo "<div class='twibb_content'>" . $text . "</div>";
			echo "<div class='comment_banner'><a href='#' class='comment_link'>" . Lang::getLangString('comment') . "</a></div>";
		} else {
			echo "<div class='twibb_content'><a href='#nick=" . $nickname . "'><strong>" . $nickname . ":</strong></a> " . $text . "</div>";
		}

		echo "<time title='" . $date . "'>" . PrettyDate::getStringResolved($date) . "</time>";
		echo '</div>';
	}

	/**
	 * Deletes a twibb.
	 *
	 * @param integer $id
	 */
	public function deleteTwibb($id){

	}

	/**
	 * Calls the show method on the given exception.
	 *
	 * @param	Exception	$e
	 * @author Marcel Werk (A class from beautiful WCF)
	 */
	public static final function handleException(Exception $e) {
		if ($e instanceof PrintableException) {
			$e->show();
			exit;
		}

		print $e;
	}

	/**
	 * Catches php errors and throws instead a system exception.
	 *
	 * @author Marcel Werk (A class from beautiful WCF)
	 * @param	integer		$errorNo
	 * @param	string		$message
	 * @param	string		$filename
	 * @param	integer		$lineNo
	 */
	public static final function handleError($errorNo, $message, $filename, $lineNo) {
		if (error_reporting() != 0) {
			$type = 'error';
			switch ($errorNo) {
				case 2: $type = 'warning';
					break;
				case 8: $type = 'notice';
					break;
			}

			throw new SystemException('PHP ' . $type . ' in file ' . $filename . ' (' . $lineNo . '): ' . $message, 0);
		}
	}

}

?>