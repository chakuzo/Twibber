<?php
include("Twibber.class.php");
$text = $_POST['text'];
$nick = strip_tags($_POST['nickname']); //strip_tags($_COOKIE['nickname']);
if($_GET['new_entry'] == "1"){
    if(trim($text) != "" && strlen($text) <= 250 && $nick != ""){
	if($nick == "kurtextrem" && $_COOKIE['nickname'] != "kurtextrem") $nick = "Kurtextrem_Fake";
	if($nick == "Math-Board" && $_COOKIE['nickname'] != "Math-Board") $nick = "Math-Board_Fake";
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
if($_GET['dyn_get'] == "1"){
    $Twibber->fetchTwibber(true, true, '', 0, 90);
}
if($_GET['nick'] != ""){
    $Twibber->fetchTwibber(true, false, $_GET['nick']);
}
if($_GET['search'] != ""){
    $Twibber->searchTwibber($_GET['search']);
}
?>
