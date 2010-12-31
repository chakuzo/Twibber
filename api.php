<?php
include("Twibber.class.php");
$return = wcf::getLoginOK($_COOKIE['twibber_nick'], $_COOKIE['twibber_pw'], $_COOKIE['twibber_salt']);
$text = $_POST['text'];
$nick = strip_tags($_COOKIE['twibber_nick']); //strip_tags($_COOKIE['nickname']);
if($_GET['new_entry'] == "1" && $return){
    if(trim($text) != "" && strlen($text) <= 250 && $nick != ""){
	$Twibber->createTwibber(htmlspecialchars($text), htmlspecialchars($nick));
	echo "Erfolgreich!";
    }elseif(trim($text) == ""){
	echo "Bitte eine Nachricht eingeben!";
    }elseif(strlen($text) > 250){
	echo "Nachricht zu lang!";
    }elseif($nick == ""){
	echo "Kein Nickname!";
    }
}
if($_GET['dyn_get'] == "1" && $return){
    $Twibber->fetchTwibber(true, true, '', 0, 90);
}
if($_GET['nick'] != "" && $return){
    $Twibber->fetchTwibber(true, false, $_GET['nick']);
}
if($_GET['search'] != "" && $return){
    $Twibber->searchTwibber($_GET['search']);
}
?>
