<?php

/**
 * Update class Modul
 *
 * @author Kurt
 */
class Update {

	/**
	 * Updates to nightly version.
	 */
	public static function updateNightly() {
		$zip = new ZipArchive();
		$filename = 'nightly.zip';
		$contents = file_get_contents('http://github.com/chakuzo/Twibber/zipball/master');
		file_put_contents('./nightly.zip', $contents);
		$zip_ar = $zip->open($filename);
		if ($zip_ar === TRUE) {
			rename('./config.inc.php', './config.inc.back.php');
			$extract = $zip->extractTo('./');
			if ($extract) {
				$zip->close();
				$unlink = array(
					'./.gitignore',
					'./README.md',
					'./config.inc.php',
					'./install/Install.class.php',
					'./install/install.php',
					'./install/sql.sql',
					'./install/update.xml'
				);
				array_map(array('FileUtil', 'unlink'), $unlink);
				FileUtil::unlink('./install');
				echo Lang::getLangString('nightly_ok') . '<br>';
			} else {
				echo Lang::getLangString('update_fail') . '<br>';
			}
			rename('./config.inc.back.php', './config.inc.php');
		} else {
			echo Lang::getLangString('update_fail') . '<br>Error Code #';
			echo $zip_ar;
		}
		FileUtil::unlink('./nightly.zip');
	}

	/**
	 * Updates to a main version.
	 * @param object $xml
	 * @param mixed  $version
	 */
	public static function updateMain($xml, $version = null) {
		$content = file_get_contents('http://github.com/downloads/chakuzo/Twibber/' . str_replace(' ', '', $xml->version . '.zip'));
		file_put_contents('./update.zip', $content);
		$zip = new ZipArchive;
		$zip_ar = $zip->open('./update.zip');
		if ($zip_ar === true) {
			$zip->extractTo('./', 'Twibber.zip');
			$zip->close();
		} else {
			echo '<br>Failed to update! Try Manuell to update? <a href="http://github.com/downloads/chakuzo/Twibber/' . str_replace(' ', '', $xml->version . '.zip') . '">Click</a><br>Error Code #';
			echo $zip_ar;
			$zip->close();
		}
		$zip2 = new ZipArchive;
		$zip_ar2 = $zip2->open('Twibber.zip');
		if ($zip_ar2 === true) {
			$zip2->extractTo('./');
			$zip2->close();
			if (!empty($xml->sqlstate))
				mysql_query($xml->sqlstate);
			echo '<br>' . Lang::getLangString('updated_from') . ' ' . TWIBBER_VERSION . ' ' . Lang::getLangString('updated_to') . ' ' . $xml->version . '!<br>';
		} else {
			echo '<br>Failed to update! Try Manuell to update? <a href="http://github.com/downloads/chakuzo/Twibber/ ' . str_replace(' ', '', $xml->version . '.zip') . '">Click</a><br>Error Code #';
			echo $zip_ar;
			$zip2->close();
		}
		FileUtil::unlink(array('./update.zip', './Twibber.zip'));
	}

	/**
	 * Checks for any Updates (and handles it)
	 * @param  boolean $handle
	 * @param  boolean $only_return
	 * @return mixed
	 */
	public static function checkUpdate($handle = false, $only_return = false) {
		$xml = simplexml_load_file('http://github.com/chakuzo/Twibber/raw/master/install/update.xml');
		if ($xml->version != TWIBBER_VERSION) {
			if ($only_return)
				return $xml;
			if ($handle)
				$this->updateMain();
			echo '<h3>';
			echo Lang::getLangString('update') . " <a href='index.php?page=Update&update=main'>" . Lang::getLangString('update_install') . "</a><br>";
			echo Lang::getLangString('update_notes') . ' ' . $xml->note;
			echo '</h3>';
			return;
		}
		echo Lang::getLangString('no_update') . '<br>';
		echo "Du magst Updates? Versuch doch mal <a href='index.php?page=Update&update=nightly'>Nightly Builds</a> <b>Achtung! Es könnte unstabil sein, und nicht alles funktionieren.</b>";
		return;
	}

}

$update = (isset($_GET['update'])) ? $_GET['update'] : '';

switch ($update) {
	case 'nightly':
		Update::updateNightly();
		break;

	case 'main':
		Update::updateMain(Update::checkUpdate(false, true));
		break;

	default:
		Update::checkUpdate();
		break;
}

?>
