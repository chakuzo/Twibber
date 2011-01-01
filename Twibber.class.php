<?php
include("StringUtil.class.php");
include("config.inc.php");

if(wcf_name_prefix == "WCF1_"){
    die("ERROR! Bitte füllen sie Linie 10 in Twibber.class.php richtig aus.");
}

$mysqli = new mysqli(mysql_local,mysql_user,mysql_pw,mysql_db);
if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') '
            . $mysqli->connect_error);
}
$mysqli2 = new mysqli(mysql_local_wcf,mysql_user_wcf,mysql_pw_wcf,mysql_db_wcf);
if ($mysqli2->connect_error) {
    die('Connect Error (' . $mysqli2->connect_errno . ') '
            . $mysqli2->connect_error);
}

/**
 * Classes for Twibber
 *
 * @author Kurt
 */
class Twibber {
    function fetchTwibber($latest = true, $global = false, $nick = '', $start = '0', $end = '30'){
	global $mysqli;
	if($global){
	    $query = $mysqli->query("SELECT * FROM `twibber_entry` ORDER BY `date` DESC LIMIT ".$start." , ".$end);
	    echo "<div id='twibber'>";
	    while($result = $query->fetch_assoc()){
		$text = str_replace("\\","",$result['text']);
		$text = wordwrap($text,76,"<br>",true);
		$text = preg_replace('@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@','<a href="$1">$1</a>',$text);
		$text = preg_replace('/@([A-Za-z_-]*) /i','<a href="?nick=$1" onclick="return dyn_get(true, false, this.innerText.replace(/@/,\'\'));">@$1</a> ',$text);
		$text = preg_replace('/\#([A-Za-zäüöß_-]*)/i','<a href="?search=$1" class="hash" onclick="return dyn_get(true, false, false, this.innerText.replace(/\#/,\'\'));">#$1</a> ',$text);
		echo "<div id='twibb'>";
		echo "<div id='avatar'><img src='".wcf::getAvatar($result['nickname'])."'></div>";
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
		$text = preg_replace('/@([A-Za-z_-]*) /i','<a href="?nick=$1" onclick="return dyn_get(true, false, this.innerText.replace(/@/,\'\'));">@$1</a> ',$text);
		$text = preg_replace('/\#([A-Za-zäüöß_-]*)/i','<a href="?search=$1" class="hash" onclick="return dyn_get(true, false, false, this.innerText.replace(/\#/,\'\'));">#$1</a> ',$text);
		echo "<div id='twibb'>";
		echo "<div id='avatar'><img src='".wcf::getAvatar($result['nickname'])."'></div>";
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
	$message = $mysqli->real_escape_string($message);
	$usernick = $mysqli->real_escape_string($usernick);
	$mysqli->query("INSERT INTO `twibber_entry`(`nickname`,`text`,`date`) VALUES('".$usernick."','".$message."','".date("d.m.Y H:i:s")."')");
    }
    function searchTwibber($needle){
	global $mysqli;
	$needle = $mysqli->real_escape_string($needle);
	$needle = strip_tags($needle);
	$query = $mysqli->query("SELECT * FROM `twibber_entry` WHERE `text` LIKE '%".$needle."%' ORDER BY `date` DESC");
	echo "<div id='twibber'>";
	    while($result = $query->fetch_assoc()){
		$text = str_replace("\\","",$result['text']);
		$text = wordwrap($text,76,"<br>",true);
		$text = preg_replace('@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@','<a href="$1">$1</a>',$text);
		$text = preg_replace('/@([A-Za-z_-]*) /i','<a href="?nick=$1" onclick="return dyn_get(true, false, this.innerText.replace(/@/,\'\'));">@$1</a> ',$text);
		$text = preg_replace('/\#([A-Za-zäüöß_-]*)/i','<a href="?search=$1" class="hash" onclick="return dyn_get(true, false, false, this.innerText.replace(/\#/,\'\'));">#$1</a> ',$text);
		echo "<div id='twibb'>";
		echo "<div id='avatar'><img src='".wcf::getAvatar($result['nickname'])."'></div>";
		echo "<div class='".$result['nickname']." nickname' onclick='insert_nick(this.innerText);'>".$result['nickname']."</div>";
		echo "<div id='content'>".$text."</div>";
		echo "<time>".$result['date']."</time>";
		echo "</div>";
	    }
	echo "</div>";
    }
    function getTwibberImage($nickname){
	// @TODO For API fetching data for images
    }
}
class wcf{
    public static function getData($nickname, $password){
	global $mysqli2;
        $nickname = strip_tags($nickname);
        $password = strip_tags($password);
        $nickname = $mysqli2->real_escape_string($nickname);
        $password = $mysqli2->real_escape_string($password);
        $sql = "SELECT username, password, salt FROM ".wcf_name_prefix."user WHERE username = '".$nickname."'";
        $query = $mysqli2->query($sql);
        $result = $query->fetch_object();
        if (!$result) return false;
        if ($result->password != StringUtil::getDoubleSaltedHash($password, $result->salt)) return false;
        return true;
    }
    public static function getAvatar($nickname){
	global $mysqli2;
	$nickname = strip_tags($nickname);
	$nickname = $mysqli2->real_escape_string($nickname);
	$query = $mysqli2->query("SELECT `avatarID` FROM `".wcf_name_prefix."user` WHERE `username` = '".$nickname."'");
	$result = $query->fetch_object();
	return "http://www.wbblite2.de/wcf/images/avatars/avatar-".$result->avatarID.".png";
    }
    public static function getSalt($nickname){
	global $mysqli2;
        $nickname = strip_tags($nickname);
	$nickname = $mysqli2->real_escape_string($nickname);
	$query = $mysqli2->query("SELECT `salt` FROM `".wcf_name_prefix."user` WHERE `username` = '".$nickname."'");
	$result = $query->fetch_object();
	return $result->salt;
    }
    public static function getLoginOK($nickname, $pw, $salt){
	global $mysqli2;
	$nickname = strip_tags($nickname);
	$nickname = $mysqli2->real_escape_string($nickname);
	$pw = strip_tags($pw);
	$pw = $mysqli2->real_escape_string($pw);
	$salt = strip_tags($salt);
	$salt = $mysqli2->real_escape_string($salt);
	define("ENCRYPTION_ENCRYPT_BEFORE_SALTING", false);
	$query = $mysqli2->query("SELECT `password` FROM `".wcf_name_prefix."user` WHERE `username` = '".$nickname."' AND `salt` = '".$salt."' AND `password` = '".StringUtil::getDoubleSaltedHash($pw, $salt)."'");
	$result = $query->fetch_object();
	if(!$result) return false;
	return true;
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