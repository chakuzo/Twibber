<?php
include("./class/Twibber.class.php");
$nick = strip_tags($_COOKIE['twibber_nick']);
$return = wcf::getLoginOK($nick, $_COOKIE['twibber_pw'], $_COOKIE['twibber_salt']);
$text = $_POST['text'];
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
    $img = @ImageCreateTrueColor (468, 60)
      or die ("Kann keinen neuen GD-Bild-Stream erzeugen"); 
    $new_width = 60;
    $new_height = 60;
    //$im = imagecreatefrompng("images/button1.png");
    $background_color = ImageColorAllocate ($img, 0, 0, 0);
    $text_color = ImageColorAllocate ($img, 233, 14, 91);
    ImageStringUp($img, 2, 0, 55, "Twibber", $text_color);
    ImageStringUp($img, 2, 5, 55, "_________", $text_color);
    ImageString($img, 5, 80, 0, $nick."'s letzter Twib:", $text_color);
    ImageString($img, 4, 80, 15, '--> '.$Twibber->fetchTwibber(true, false, $nick, 0, 30, true).'', $text_color);
    $nick = wcf::getAvatar(strip_tags($_GET['image']));
    $image_data = getimagesize($nick);
    if ($image_data['mime'] == 'image/png') { // ty _MaX_
	$avatar = imagecreatefrompng($nick);
	imagecopyresampled($img, $avatar, 20, 0, 0, 0, 60, 60, imagesx($avatar), imagesy($avatar));
    } elseif ($image_data['mime'] == 'image/jpeg' or $image_data['mime'] == 'image/pjpeg') {
	$avatar = imagecreatefromjpeg($nick);
        imagecopyresampled($img, $avatar, 20, 0, 0, 0, 60, 60, imagesx($avatar), imagesy($avatar));
    } elseif ($image_data['mime'] == 'image/gif') {
        $avatar = imagecreatefromgif($nick);
        imagecopyresampled($img, $avatar, 20, 0, 0, 0, 60, 60, imagesx($avatar), imagesy($avatar));
    }
    ImagePNG($img);
    ImageDestroy($img);
    ImageDestory($avatar);
    exit();
}

if(!$return){
   echo "Bitte einloggen!";
}
?>
