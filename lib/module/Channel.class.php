<?php

require_once('Twibber.class.php');

/**
 * Adds Channel
 *
 * @author Kurt
 */
class Channel extends Module {

	public $channel;

	public function __construct(array $channel) {
		parent::__construct();
		$this->channel = $channel;
		$this->createChannel($channel);
	}

	public function createChannel($channel) {

	}

	public function listChannel() {

	}

}

?>