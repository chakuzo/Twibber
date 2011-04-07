<?php

/**
 * Provides File functions.
 *
 * @author Kurt
 */
class FileUtil {

	/**
	 * Minifys a Content (File)
	 *
	 * @param string $content
	 */
	public static function minifyFile($content) {
		if (is_file($content)) {
			$content = file_get_contents($content);
		}
		$content = preg_replace('/(\n|\f|\r|\t|\v)/', '', $content);

		return $content;
	}

	/**
	 * Merge Files
	 *
	 * @param array $files
	 */
	public static function mergeFiles($files){
		$content = '';
		foreach($files as $file){
			$content .= static::minifyFile($file);
		}

		return $content;
	}

	/**
	 * Avoids error, if file is deleted or something else (and displays a message).
	 * @param  mixed   $unlink
	 * @param  boolean $message
	 * @return boolean
	 */
	public static function unlink($unlink, $message = true) {
		if (is_array($unlink)) {
			foreach ($unlink as $index => $file) {
				if (file_exists($file)) {
					if (is_dir($file)) {
						if (!rmdir($file) && $message)
							self::unlinkMSG($file);
						continue;
					}

					if (!unlink($file) && $message)
						self::unlinkMSG($file);
				}
			}
		} else {
			if (file_exists($file)) {
				if (is_dir($file)) {
					if (!rmdir($file) && $message)
						self::unlinkMSG($file);
				}

				if (!unlink($file) && $message)
					self::unlinkMSG($file);
			}
			return true;
		}
	}

	/**
	 * Displays a Message.
	 *
	 * @param string $file
	 */
	public static function unlinkMSG($file) {
		echo "Can't delete '".$file."'. Please do this!";
	}

}

?>