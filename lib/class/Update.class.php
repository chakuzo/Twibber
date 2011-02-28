<?php

/**
 * Update class Modul
 *
 * @author Kurt
 */
class Update
{

	/**
	 * Language array.
	 * @var array
	 */
	private $lang;
	/**
	 * Version var.
	 * @var mixed
	 */
	public $version = '0.6.5';

	/**
	 * Sets $this->lang to $lang array.
	 * @param array $lang
	 */
	public function __construct(array $lang)
	{
		$this->lang = $lang;
	}

	/**
	 * Updates to nightly version.
	 */
	public function updateNightly()
	{
		$zip = new ZipArchive();
		$filename = 'nightly.zip';
		$contents = file_get_contents('http://github.com/chakuzo/Twibber/zipball/master');
		file_put_contents('nightly.zip', $contents);
		$zip_ar = $zip->open($filename);
		if ($zip_ar === TRUE) {
			rename('../config.inc.php', '../config.inc.back.php');
			$extract = $zip->extractTo('../');
			if ($extract) {
				$zip->close();
				$unlink = array(
					'../.gitignore',
					'../README.txt',
					'sql.sql',
					'install.php',
					'update.xml',
					'../notes/install.txt',
					'../config.inc.php'
				);
				array_map(
						array(
					$this,
					'unlink'
						), $unlink
				);
				$this->unlink('../notes', true);
				echo $this->lang['nightly_ok'] . '<br>';
			} else {
				echo $this->lang['update_fail'] . '<br>';
			}
			rename('../config.inc.back.php', '../config.inc.php');
		} else {
			echo $this->lang['update_fail'] . '<br>';
			echo $zip_ar;
		}
		unlink('nightly.zip');
	}

	/**
	 * Updates to a main version.
	 * @param object $xml
	 * @param mixed $version
	 */
	public function updateMain($xml, $version = null)
	{
		$content = file_get_contents('http://github.com/downloads/chakuzo/Twibber/ ' . str_replace(' ', '', $xml->version . '.zip'));
		file_put_contents('update.zip', $content);
		$zip = new ZipArchive;
		$zip_ar = $zip->open('update.zip');
		if ($zip_ar === TRUE) {
			$zip->extractTo('../');
			$zip->close();
			if (!empty($xml->sqlstate))
				mysql_query($xml->sqlstate);
			echo '<br>' . $this->lang['updated_from'] . ' ' . $version . ' ' . $this->lang['updated_to'] . ' ' . $xml->version . '!<br>';
		} else {
			echo '<br>Failed to update! Try Manuell to update? <a href="http://github.com/downloads/chakuzo/Twibber/ ' . str_replace(' ', '', $xml->version . '.zip') . '">Click</a><br>';
			echo $zip_ar;
		}
		unlink('update.zip');
	}

	/**
	 * Checks for any Updates (and handles it)
	 * @param boolean $handle
	 * @param boolean $only_return
	 * @return mixed
	 */
	public function checkUpdate($handle = false, $only_return = false)
	{
		$xml = simplexml_load_file('https://github.com/chakuzo/Twibber/raw/master/install/update.xml');
		if ($xml->version != $this->version) {
			if ($only_return)
				return $xml;
			if ($handle)
				$this->updateMain();
			echo '<h3>';
			echo $this->lang['update'] . " <a href='update.php?update=main'>" . $this->lang['update_install'] . "</a><br>";
			echo $this->lang['update_notes'] . ' ' . $xml->note;
			echo '</h3>';
			return;
		}
		echo $this->lang['no_update'] . '<br>';
		echo "Du magst Updates? Versuch doch mal <a href='update.php?update=nightly'>Nightly Builds</a> <b>Achtung! Es könnte unstabil sein, und nicht alles funktionieren.</b>";
		return;
	}

	/**
	 * Avoids error, if file is deleted or something else.
	 * @param array $unlink
	 * @param boolean $dir
	 * @return boolean
	 */
	public function unlink(array $unlink, $dir = false)
	{
		foreach ($unlink as $index => $file) {
			if (file_exists($file)) {
				if ($dir) {
					rmdir($file);
					continue;
				}

				if (!unlink($file))
					return false;
			}
		}
		return;
	}

}

?>
