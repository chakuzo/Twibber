<?php

/**
 * Adds Channel
 *
 * @author Kurt
 */
class Channel {

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