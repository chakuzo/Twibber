<?php
include("Twibber.class.php");
$text = $_POST['text'];
$nick = strip_tags($_COOKIE['nickname']);
if($_GET['new_entry'] == "1"){
    if(trim($text) != "" && strlen($text) <= 250){
	$Twibber->createTwibber(htmlspecialchars($text), $nick);
	echo "Erfolgreich!";
    }elseif(trim($text) == ""){
	echo "Bitte eine Nachricht eingeben!";
    }elseif(strlen($text) > 250){
	echo "Nachricht zu lang!";
    }
}
if($_GET['dyn_get'] == "1"){
    $Twibber->fetchTwibber(true, true, '', 0, 30);
}
?>
