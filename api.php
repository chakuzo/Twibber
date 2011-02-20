<?php

require_once('global.php');
$nick = strip_tags($_COOKIE['twibber_nick']);
$return = wcf::getLoginOK($nick, $_COOKIE['twibber_pw'], $_COOKIE['twibber_salt']);
$text = (isset($_POST['text'])) ? $_POST['text'] : '';
if (isset($_GET['new_entry']) && $_GET['new_entry'] == 1 && $return && !empty($nick)) {

	if (trim($text) != "" && strlen($text) <= 250) {
		if (isset($_GET['retwibb']) && $_GET['retwibb'])
			exit();
		if (isset($_GET['comment']) && $_GET['comment'] == 1) {
			$Twibber->createTwibbComment(htmlspecialchars($text), htmlspecialchars($nick), htmlspecialchars($_POST['to_id']));
			exit($lang_success);
		}
		$Twibber->createTwibber(htmlspecialchars($text), htmlspecialchars($nick));
		echo $lang_success;
	} elseif (trim($text) == '') {
		echo $lang_no_message;
	} elseif (strlen($text) > 250) {
		echo $message_too_long;
	}
	exit();
}

if (isset($_GET['new_entry']) && $_GET['new_entry'] == 1 && (empty($nick) xor !$return)) {
	exit($lang_no_nick);
}
header("Access-Control-Allow-Origin: *");
if (isset($_GET['dyn_get']) && trim($_GET['dyn_get']) == 1) {
	$mult = (empty($_GET['page'])) ? 1 : intval($_GET['page']);
	$latest = ($_GET['latest'] == 'true');
	$Twibber->fetchTwibber($latest, true, '', 0, $mult * 20);
}
if (isset($_GET['nick']) && trim($_GET['nick']) != '') {
	$latest = ($_GET['latest'] == 'true') ? true : false;
	$Twibber->fetchTwibber($latest, false, $_GET['nick']);
}
if (isset($_GET['search']) && trim($_GET['search']) != '') {
	$Twibber->searchTwibber($_GET['search']);
}
if (isset($_GET['image']) && trim($_GET['image']) != '') {
	header('Content-type: image/png');
	$nick = ucwords(strip_tags($_GET['image']));
	$return_array = $Twibber->fetchTwibber(true, false, $nick, 0, 30, true);
	$img = @ImageCreateTrueColor(468, 60)
			or die($gd_error);
	$new_width = 60;
	$new_height = 60;
	//$im = imagecreatefrompng("images/button1.png");
	$background_color = ImageColorAllocate($img, 0, 0, 0);
	$text_color = ImageColorAllocate($img, 233, 14, 91);
	ImageStringUp($img, 2, 0, 55, 'Twibber', $text_color);
	ImageStringUp($img, 2, 5, 55, '_________', $text_color);
	ImageString($img, 5, 82, 0, $nick . $lang_gd_last_twib, $text_color);
	$font_file = './lib/font/Comfortaa-Bold.ttf';
	ImageFTText($img, 10, 0, 90, 30, $text_color, $font_file, '" ' .
			wordwrap(
					//utf8_encode(
					html_entity_decode(
							$return_array[0]
					) //)
					, 39, "\n", true) . ' "'
	);
	$avatar_nick = wcf::getAvatar(strip_tags($_GET['image']));
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
	ImageString($img, 2, 347, 45, $lang_gd_date . $return_array[1], $text_color);
	ImagePNG($img);
	ImageDestroy($img);
	ImageDestory($avatar_nick);
	exit();
}

?>