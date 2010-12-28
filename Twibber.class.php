<?php

/**
 * Description of Twibber
 *
 * @author Kurt
 */
class Twibber {
    function fetchTwibber($latest = true,$global = false,$nick = ''){
	// @TODO Function to fetch the Twibber statuses.
    }
    function createTwibber($message,$usernick){
	// @TODO Post new twibber.
    }
}
class wcf{
    function getData(){
	// @TODO Read the database from wcf sha1($salt.sha1($salt.$password));
    }
}

$mysqli = new mysqli();
$Twibber = new Twibber();

?>
