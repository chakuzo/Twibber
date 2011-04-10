<?php

/**
 * Manages the Templates.
 *
 * @author  kurtextrem <kurtextrem@gmail.com>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package Twibber
 */
class TPLUtil {

	/**
	 * What to replace.
	 *
	 * @var $replace
	 */
	private static $replace = array(
		'title' => 'Twibber - A Microblogging Network',
		'textarea' => '<textarea id="input_text" maxlength="250" autofocus placeholder="Schreibe deinen Freunden hier, was du gerade machst."></textarea>
		<br><div class="right"><label for="input_text" id="counter"></label><button id="twibber_it">Twibbern</button></div>',
		'content' => '',
		'username' => USER,
		'slider_content' =>
	);

	/**
	 * Extends the replace array.
	 *
	 * @param type $replace
	 */
	public static function extend($replace) {
		array_merge(self::$replace, $replace);
	}

	/**
	 * Compiles the templates.
	 *
	 * @param string $tpl
	 */
	public static function compileTPL($tpl) {
		$tpl_content = file_get_contents('../../templates/'.$tpl.'.tpl');
		preg_match_all('/\{([^ ]+)\}/', $tpl_content, $matches);
		foreach ($matches as $match => $value) {
			$tpl_content = str_replace('{'.$match.'}', self::$replace[$value]);
			self::saveCompiledTPL($tpl_content, $tpl);
		}
	}

	public static function saveCompiledTPL($tpl_content, $tpl) {
		$name = sha1($tpl);
		file_put_contents('../../templates/'.$name.'.html', $tpl_content);
	}

}

?>