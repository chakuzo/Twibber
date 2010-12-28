<?php
/**
 * Fill out the Data to the connection.
 */
define("mysql_user",""); // The User for the Database
define("mysql_pw","password"); // The Password for the User
define("mysql_db","database"); // The Database Name
define("mysql_local","localhost"); // I think you shouldnt change this..

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
$wcf = new wcf();

?>
