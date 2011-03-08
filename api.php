<?php

// include all class
require_once('global.php');

// To avoid warnings
$nick = (isset($_COOKIE['twibber_nick'])) ? strip_tags($_COOKIE['twibber_nick']) : '';
$pw = (isset($_COOKIE['twibber_pw'])) ? $_COOKIE['twibber_pw'] : '';
$salt = (isset($_COOKIE['twibber_salt'])) ? $_COOKIE['twibber_salt'] : '';
$text = (isset($_POST['text'])) ? $_POST['text'] : '';

// Check if user is logged in
$return = WCF::getLoginOK($nick, $pw, $salt);

if (isset($_GET['new_entry']) && $_GET['new_entry'] == 1 && $return && !empty($nick)) {
	if (trim($text) != "" && strlen($text) <= 250) {
		if (isset($_GET['retwibb']) && $_GET['retwibb'])
			exit();
		if (isset($_GET['comment']) && $_GET['comment'] == 1) {
			$Twibber->createTwibbComment(htmlentities($text, ENT_COMPAT, 'UTF-8'), htmlentities($nick, ENT_COMPAT, 'UTF-8'), intval($_POST['to_id']));
			exit($lang['success']);
		}
		$Twibber->createTwibber(htmlentities($text, ENT_COMPAT, 'UTF-8'), htmlentities($nick, ENT_COMPAT, 'UTF-8'));
		echo Lang::getLangString('success');
	} elseif (trim($text) == '') {
		echo Lang::getLangString('no_message');
	} elseif (strlen($text) > 250) {
		echo Lang::getLangString('message_too_long');
	}
	exit();
}

if (isset($_GET['new_entry']) && $_GET['new_entry'] == 1 && (empty($nick) || !$return)) {
	exit(Lang::getLangString('no_nick'));
}

@header("Access-Control-Allow-Origin: *");

//anywhen important for JSONP or something else
if (isset($_GET['dyn_get']) && trim($_GET['dyn_get']) == 1) {
	$mult = (empty($_GET['page'])) ? 1 : intval($_GET['page']);
	$latest = ($_GET['latest'] == 'true');
	$Twibber->fetchTwibber($latest, true, '', 0, $mult * 20);
}
if (isset($_GET['nick']) && trim($_GET['nick']) != '') {
	$latest = ($_GET['latest'] == 'true');
	$Twibber->fetchTwibber($latest, false, $_GET['nick']);
}
if (isset($_GET['search']) && trim($_GET['search']) != '') {
	$Twibber->searchTwibber($_GET['search']);
}
if (isset($_GET['image']) && trim($_GET['image']) != '') { // will be replaced with DPS from _MaX_ @see github.com/max-m/Dynamic-PHP-signature
	$nick = ucwords(strip_tags($_GET['image']));
	$return_array = $Twibber->fetchTwibber(true, false, $nick, 0, 30, true);
	$img = ImageCreateTrueColor(468, 60)
			or die($gd_error);
	//$im = imagecreatefrompng("images/button1.png");
	$background_color = ImageColorAllocate($img, 0, 0, 0);
	$text_color = ImageColorAllocate($img, 233, 14, 91);
	ImageStringUp($img, 2, 0, 55, 'Twibber', $text_color);
	ImageStringUp($img, 2, 5, 55, '_________', $text_color);
	ImageString($img, 5, 82, 0, $nick . $lang['gd_last_twib'], $text_color);
	$font_file = './lib/font/Comfortaa-Bold.ttf';
	ImageFTText($img, 10, 0, 90, 30, $text_color, $font_file, '" ' .
			wordwrap(html_entity_decode($return_array[0], ENT_COMPAT, 'UTF-8'), 39, "\n", true) . ' "'
	);
	$avatar_nick = WCF::getAvatar(strip_tags($_GET['image']));
	//$avatar_nick = "http://www.wbblite2.de/wcf/images/avatars/avatar-328.png";
	$image_data = getimagesize($avatar_nick);
	if ($image_data['mime'] == 'image/png') { // ty _MaX_
		$avatar = imagecreatefrompng($avatar_nick);
		imagecopyresampled($img, $avatar, 20, 0, 0, 0, 60, 60, imagesx($avatar), imagesy($avatar));
	} elseif ($image_data['mime'] == 'image/jpeg' or $image_data['mime'] == 'image/pjpeg') {
		$avatar = imagecreatefromjpeg($avatar_nick);
		imagecopyresampled($img, $avatar, 20, 0, 0, 0, 60, 60, imagesx($avatar), imagesy($avatar));
	} elseif ($image_data['mime'] == 'image/gif') {
		$avatar = imagecreatefromgif($avatar_nick);
		imagecopyresampled($img, $avatar, 20, 0, 0, 0, 60, 60, imagesx($avatar), imagesy($avatar));
	}
	ImageString($img, 2, 347, 45, $lang['gd_date'] . $return_array[1], $text_color);
	header('Content-type: image/png');
	ImagePNG($img);
	ImageDestroy($img);
	ImageDestroy($avatar_nick);
	exit();
}

?>