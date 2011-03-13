<?php

/**
 * Adds Channel
 *
 * @author Kurt
 */
class Channel {

	public $channel = array();

	public function __construct(array $channel) {
		ksort($channel);
		foreach ($channel as $name => $keyword)
			$this->createChannel($name, $keyword);
	}

	public function createChannel($name, $keyword) {
		$this->channel[$name] = $keyword;
	}

	public function listChannel() {
		/*foreach ($this->channel as $name => $keyword) {
			$keyword = '#'.$keyword;
			echo '<div class="channel" id="'.$name.'">'.$name.' ('.$Twibber->twibberfy_text($keyword).')</div>';
		}*/
		echo json_encode($this->channel);
	}

}

?>