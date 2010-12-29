<?php
/**
 * Fill out the Data to the connection.
 */
define("mysql_user",""); // The User for the Database
define("mysql_pw","password"); // The Password for the User
define("mysql_db","database"); // The Database Name
define("mysql_local","localhost"); // I think you shouldnt change this..
define("wcf_name_prefix","WCF1_"); // The prefix which is used for the wcf !!!
				    // Provide it with syntax "wcfX_", X as a number !!!

/* Fill out the Data for the connection to read out the WCF Data,
 * if it's the same as the above database connection, you can leave these here
 * empty.
 */
define("mysql_user_wcf","");
define("mysql_pw_wcf","");
define("mysql_db_wcf","");
define("mysql_local_wcf","");

if(wcf_name_prefix == "WCF1_"){
    die("ERROR! Bitte füllen sie Linie 17 in Twibber.class.php richtig aus.");
}

$mysqli = new mysqli(mysql_local,mysql_user,mysql_pw,mysql_db);
if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') '
            . $mysqli->connect_error);
}

/**
 * Classes for Twibber
 *
 * @author Kurt
 */
class Twibber {
    function fetchTwibber($latest = true, $global = false, $nick = ''){
	global $mysqli;
	// @TODO Function to fetch the Twibber statuses.
	if($global){
	    $query = $mysqli->query("SELECT * FROM `twibber_entry` ORDER BY `date` ASC");
	    echo "<div id='twibber'>";
	    while($result = $query->fetch_assoc()){
		echo "<div id='nickname' class='".$result['nickname']."'>".$result['nickname']."</div>";
		echo "<div id='content'>".$result['text']."</div>";
		echo "<time>".$result['date']."</time>";
	    }
	    echo "</div>";
	}
    }
    function createTwibber($message, $usernick){
	global $mysqli;
	// @TODO Post new twibber.
	$mysqli->query("INSERT INTO `twibber_entry`(`nickname`,`text`,`date`) VALUES('".$usernick."','".$message."','".date("H:m:s d.m.Y")."')");
    }
}
class wcf{
    function getData($Data){
	global $mysqli;
	// @TODO Read the database from wcf sha1($salt.sha1($salt.$password));
	if((mysql_user_wcf == "" && mysql_pw_wcf == "" && mysql_db_wcf == "" && mysql_local_wcf == "")&& (mysql_user != "" && mysql_pw != "" && mysql_db != "" && mysql_local != "")){
	    $result = $mysqli->query("SELECT * FROM `".wcf_name_prefix."` ".$Data);
	    return $result->fetch_assoc();
	}elseif(mysql_user_wcf != "" && mysql_pw_wcf != "" && mysql_db_wcf != "" && mysql_local_wcf != ""){
	    $this->mysqli(mysql_user_wcf, mysql_pw_wcf, mysql_db_wcf, mysql_local_wcf);
	    $mysqli2->query("SELECT * FROM `".wcf_name_prefix."` ".$Data);
	    return $result->fetch_assoc();
	}
    }
    function mysqli($user,$pw,$db,$local){
	global $mysqli2;
	$mysqli2 = new mysqli($user,$pw,$db,$local);
    }
}

$Twibber = new Twibber();
$wcf = new wcf();

?>
