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
    function fetchTwibber($latest = true, $global = false, $nick = '', $start = '0', $end = '30'){
	global $mysqli;
	// @TODO Function to fetch the Twibber statuses.
	if($global){
	    $query = $mysqli->query("SELECT * FROM `twibber_entry` ORDER BY `date` DESC LIMIT ".$start." , ".$end);
	    echo "<div id='twibber'>";
	    while($result = $query->fetch_assoc()){
		$text = str_replace("\\","",$result['text']);
		$text = wordwrap($text,76,"<br>",true);
		$text = preg_replace('@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@','<a href="$1">$1</a>',$text);
		$text = preg_replace('/@([A-Za-z]*) /','<a href="?nick=$1">@$1</a> ',$text);
		$text = preg_replace('/\#([A-Za-z]*) /','<a href="?search=$1">$1</a> ',$text);
		echo "<div id='twibb'>";
		echo "<div class='".$result['nickname']." nickname' onclick='insert_nick(this.innerText);'>".$result['nickname']."</div>";
		echo "<div id='content'>".$text."</div>";
		echo "<time>".$result['date']."</time>";
		echo "</div>";
	    }
	    echo "</div>";
	}
	if($global == false && $nick != ''){
	    $nick = $mysqli->real_escape_string($nick);
	    $nick = strip_tags($nick);
	    $query = $mysqli->query("SELECT * FROM `twibber_entry` WHERE `nickname` = '".$nick."' ORDER BY `date` DESC");
	    echo "<div id='twibber'>";
	    while($result = $query->fetch_assoc()){
		$text = str_replace("\\","",$result['text']);
		$text = wordwrap($text,76,"<br>",true);
		$text = preg_replace('@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@','<a href="$1">$1</a>',$text);
		$text = preg_replace('/@([A-Za-z]*) /','<a href="?nick=$1">@$1</a> ',$text);
		$text = preg_replace('/\#([A-Za-z]*) /','<a href="search.php?search=$1">$1</a> ',$text);
		echo "<div id='twibb'>";
		echo "<div class='".$result['nickname']." nickname' onclick='insert_nick(this.innerText);'>".$result['nickname']."</div>";
		echo "<div id='content'>".$text."</div>";
		echo "<time>".$result['date']."</time>";
		echo "</div>";
	    }
	    echo "</div>";
	}
    }
    function createTwibber($message, $usernick){
	global $mysqli;
	// @TODO Post new twibber.
	$message = $mysqli->real_escape_string($message);
	$usernick = $mysqli->real_escape_string($usernick);
	$mysqli->query("INSERT INTO `twibber_entry`(`nickname`,`text`,`date`) VALUES('".$usernick."','".$message."','".date("d.m.Y H:i:s")."')");
    }
    function searchTwibber($needle){
	$needle = $mysqli->real_escape_string($needle);
	$needle = strip_tags($needle);
	$query = "SELECT * FROM  `twibber_entry` WHERE  `text` LIKE  '%".$needle."'";
	echo "<div id='twibber'>";
	    while($result = $query->fetch_assoc()){
		$text = str_replace("\\","",$result['text']);
		$text = wordwrap($text,76,"<br>",true);
		$text = preg_replace('@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@','<a href="$1">$1</a>',$text);
		$text = preg_replace('/@([A-Za-z]*) /','<a href="?nick=$1">@$1</a> ',$text);
		$text = preg_replace('/\#([A-Za-z]*) /','<a href="search.php?search=$1">$1</a> ',$text);
		echo "<div id='twibb'>";
		echo "<div class='".$result['nickname']." nickname' onclick='insert_nick(this.innerText);'>".$result['nickname']."</div>";
		echo "<div id='content'>".$text."</div>";
		echo "<time>".$result['date']."</time>";
		echo "</div>";
	    }
	echo "</div>";
    }
}
class wcf{
    function getData($Data){
	global $mysqli;
	// @TODO Read the database from wcf sha1($salt.sha1($salt.$password)); $_COOKIE['wcf_userID'];
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

/*
 * Copyright 2010 - 2010 by Kurtextrem
 * function: getTitle($id)
 * returns: The title of the video.
 * example: http://m.youtube.com/watch?v=dsBbdKmjquM
 * example for return: Phineas und Ferb - Gitchi Gitchi Goo [HQ] (German)
 * function: getLength($id)
 * returns: The length of the video.
 * function: getRate($id, $image)
 * returns: the rate image if image = true, if its false, it returns the number.
 * example for return: 5.0 stars
 * function: getAll() Most effizient!
 * returns: Array. Array['title'], Array['length'], Array['rate'], Array['rateIMG'], Array['thub'].
 */

class youtube{
    function getTitle($id){
	$contents = file_get_contents("http://m.youtube.com/watch?v=".$id);
	$titel = preg_match("/<title>YouTube - (.*)<\/title>/", $contents, $matches);
	return $matches[1];
    }
    function getLength($id){
	$contents = file_get_contents("http://m.youtube.com/watch?v=".$id);
	$length = preg_match("/<div>([0-9:]*)&nbsp;/", $contents, $matches);
	return $matches[1];
    }
    function getRate($id, $image = true){
	$contents = file_get_contents("http://m.youtube.com/watch?v=".$id);
	if($image){
	    $rate = preg_match('/<img src="(.*)" alt=".+ stars"/', $contents, $matches);
	    return $matches[1];  
	}else{
	    $rate = preg_match('/<img src=".*" alt="(.+ stars)"/', $contents, $matches);
	    return $matches[1];
	}
    }
    function getAll(){
	$return = Array();
	$contents = file_get_contents("http://m.youtube.com/watch?v=".$id);
	preg_match("/<title>YouTube - (.*)<\/title>/", $contents, $matches);
	$return['titel'] = $matches[1];
	preg_match("/<div>([0-9:]*)&nbsp;/", $contents, $matches);
	$return['length'] = $matches[1];
	preg_match('/<img src="(.*)" alt=".+ stars"/', $contents, $matches);
	$return['rateIMG'] = $matches[1];
	preg_match('/<img src=".*" alt="(.+ stars)"/', $contents, $matches);
	$return['rate'] = $matches[1];
	preg_match('/<img src="(.*)" alt="Video"/', $contents, $matches);
	$return['thub'] = $matches[1];
    }
}

$Twibber = new Twibber();
$wcf = new wcf();
$youtube = new youtube();

?>
