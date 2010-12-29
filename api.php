<?php
include("Twibber.class.php");
$text = strip_tags($_POST['text']);
$nick = strip_tags($_COOKIE['nickname']);
if($_GET['new_entry'] == "1"){
    $Twibber->createTwibber($text, $nick);
}
if($_GET['dyn_get'] == "1"){
    if(trim($text) != ""){
	$Twibber->fetchTwibber(true, true);
	echo "Erfolgreich!";
    }else{
	echo "Bitte eine Nachricht eingeben!";
    }
}
?>
