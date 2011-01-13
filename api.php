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
    Header("Content-type: image/png");
    $nick = strip_tags($_GET['image']);
    $im = @ImageCreate (468, 60)
      or die ("Kann keinen neuen GD-Bild-Stream erzeugen");
    $avatar = imagecreatefrompng("http://www.wbblite2.de/wcf/images/avatars/avatar-328.png"); 
    $new_width = 60;
    $new_height = 60;
    //$im = imagecreatefrompng("images/button1.png");
    $background_color = ImageColorAllocate ($im, 0, 0, 0);
    $text_color = ImageColorAllocate ($im, 233, 14, 91);
    ImageStringUp($im, 2, 0, 55, "Twibber", $text_color);
    ImageStringUp($im, 2, 5, 55, "_________", $text_color);
    ImageString($im, 5, 80, 0, $nick."'s letzter Twib:", $text_color);
    ImageString($im, 4, 80, 15, '--> '.$Twibber->fetchTwibber(true, false, $nick, 0, 30, true).'', $text_color);
    ImageCopyresampled($im, $avatar, 20, 0, 0, 0, $new_width, $new_height, 468, 60);
    ImagePNG($im);
    ImageDestroy($im);
    ImageDestory($avatar);
}
?>
