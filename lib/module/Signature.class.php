<?php

/**
 * Provides Signature images.
 *
 * @author Kurt
 */
class Signature {

	/**
	 * Deprecated, will be replaced with DPS (from _MaX_)
	 */
	public function createImage($nick) {
		$nick = ucwords(strip_tags($nick));
		$return_array = $Twibber->fetchTwibber(true, false, $nick, 0, 30, true);
		$img = ImageCreateTrueColor(468, 60)
				or die($gd_error);
		//$im = imagecreatefrompng("images/button1.png");
		$background_color = ImageColorAllocate($img, 0, 0, 0);
		$text_color = ImageColorAllocate($img, 233, 14, 91);
		ImageStringUp($img, 2, 0, 55, 'Twibber', $text_color);
		ImageStringUp($img, 2, 5, 55, '_________', $text_color);
		ImageString($img, 5, 82, 0, $nick.Lang::getLangString('gd_last_twib'), $text_color);
		$font_file = './lib/font/Comfortaa-Bold.ttf';
		ImageFTText($img, 10, 0, 90, 30, $text_color, $font_file, '" '.
				wordwrap(html_entity_decode($return_array[0], ENT_COMPAT, 'UTF-8'), 39, "\n", true).' "'
		);
		$avatar_nick = WCF::getAvatar($nick);
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
		ImageString($img, 2, 347, 45, Lang::getLangString('gd_date').$return_array[1], $text_color);
		
		header('Content-type: image/png');
		ImagePNG($img);
		ImageDestroy($img);
		ImageDestroy($avatar_nick);
		exit();
	}

}

?>