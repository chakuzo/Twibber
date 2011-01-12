<?php
include("./class/Twibber.class.php");
$return = wcf::getLoginOK($_COOKIE['twibber_nick'], $_COOKIE['twibber_pw'], $_COOKIE['twibber_salt']);
$text = $_POST['text'];
$nick = strip_tags($_COOKIE['twibber_nick']);
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
}elseif(!$return){
    echo "Bitte einloggen!";
}
if(trim($_GET['dyn_get']) == "1"){
    $Twibber->fetchTwibber(true, true, '', 0, 90);
}
if(trim($_GET['nick']) != ""){
    $Twibber->fetchTwibber(true, false, $_GET['nick']);
}
if(trim($_GET['search']) != ""){
    $Twibber->searchTwibber($_GET['search']);
}
if(trim($_GET['image']) != ""){
    header("Content-type: image/png");
    $text = strip_tags($_GET['image']);
    $im = imagecreatefrompng("images/button1.png");
    $orange = imagecolorallocate($im, 220, 210, 60);
    $px = (imagesx($im) - 7.5 * strlen($text)) / 2;
    imagestring($im, 3, $px, 9, $text, $orange);
    imagepng($im);
    imagedestroy($im);
}
?>
