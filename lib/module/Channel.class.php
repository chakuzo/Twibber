<?php

require_once('Twibber.class.php');

/**
 * Adds Channel
 *
 * @author Kurt
 */
class Channel extends Twibber {

	public $channel;

	public function __construct(array $channel) {
		parrent::__construct();
		$this->channel = $channel;
		$this->createChannel($channel);
	}

	public function createChannel($channel) {

	}

	public function listChannel() {

	}

}

?>