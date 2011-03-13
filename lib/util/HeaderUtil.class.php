<?php
/**
 * Contains header-related functions.
 *
 * @author 	Marcel Werk
 * @copyright	2001-2009 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf
 * @subpackage	util
 * @category 	Community Framework
 */
class HeaderUtil {
	/**
	 * alias to php setcookie() function
	 */
	public static function setCookie($name, $value = '', $expire = 0) {
		@header('Set-Cookie: '.rawurlencode(COOKIE_PREFIX.$name).'='.rawurlencode($value).($expire ? '; expires='.gmdate('D, d-M-Y H:i:s', $expire).' GMT' : '').(COOKIE_PATH ? '; path='.COOKIE_PATH : '').(COOKIE_DOMAIN ? '; domain='.COOKIE_DOMAIN : '').((isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443') ? '; secure' : '').'; HttpOnly', false);
	}

	/**
	 * Sends the headers of a page.
	 */
	public static function sendHeaders($nocache = false, $gzip = true) {
		// send content type
		@header('Content-Type: text/html; charset='.CHARSET);
		@header("Access-Control-Allow-Origin: *");

		// send no cache headers
		if (defined('HTTP_ENABLE_NO_CACHE_HEADERS') || $nocache) {
			self::sendNoCacheHeaders();
		}

		// enable gzip compression
		if (HTTP_GZIP_ENABLED || $gzip) {
			self::compressOutput();
		}
	}

	/**
	 * Sends no cache headers.
	 */
	public static function sendNoCacheHeaders() {
		@header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		@header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
		@header('Cache-Control: no-cache, must-revalidate');
		@header('Pragma: no-cache');
	}

	/**
	 * Enables the gzip compression of the page output.
	 */
	public static function compressOutput() {
		if (function_exists('gzcompress') && !@ini_get('zlib.output_compression') && !@ini_get('output_handler') && isset($_SERVER['HTTP_ACCEPT_ENCODING']) && strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) {
			if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'x-gzip')) {
				@header('Content-Encoding: x-gzip');
			}
			else {
				@header('Content-Encoding: gzip');
			}
			ob_start(array('HeaderUtil', 'getCompressedOutput'));
		}
	}

	/**
	 * Outputs the compressed page content.
	 */
	public static function getCompressedOutput($output) {
		$size = strlen($output);
		$crc = crc32($output);

		$newOutput = "\x1f\x8b\x08\x00\x00\x00\x00\x00\x00\xff";
		$newOutput .= substr(gzcompress($output, 9), 2, -4);
		unset($output);
		$newOutput .= pack('V', $crc);
		$newOutput .= pack('V', $size);

		return $newOutput;
	}

	/**
	 * Redirects the user agent.
	 *
	 * @param	string		$location
	 * @param 	boolean		$prependDir
	 * @param	boolean		$sendStatusCode
	 */
	public static function redirect($location, $prependDir = true, $sendStatusCode = false) {
		if ($prependDir) $location = FileUtil::addTrailingSlash(FileUtil::unifyDirSeperator(dirname(WCF::getSession()->requestURI))) . $location;
		if ($sendStatusCode) @header('HTTP/1.0 301 Moved Permanently');
		header('Location: '.$location);
	}
}
?>