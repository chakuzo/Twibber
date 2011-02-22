<?php

/**
 * Update class Modul
 *
 * @author Kurt
 */
class Update
{

	private $lang;
	public $version = '0.6';

	function __construct(Array $lang)
	{
		$this->lang = $lang;
	}

	function updateNightly()
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
				rename('../config.inc.back.php', '../config.inc.php');
				$unlink = array(
					'../.gitignore',
					'../README.txt',
					'sql.sql',
					'install.php',
					'update.xml',
					'../notes/install.txt'
				);
				array_map(
						array(
					$this,
					'unlink'
						), $unlink
				);
				$this->unlink('../notes', true);
				echo $lang['nightly_ok'] . '<br>';
			} else {
				echo $lang['update_fail'] . '<br>';
			}
		} else {
			echo $lang['update_fail'] . '<br>';
			echo $zip_ar;
		}
		$this->unlink('nightly.zip');
	}

	function unlink(array $unlink, $dir = false)
	{
		foreach ($unlink as $index => $file) {
			if (file_exists($file)) {
				if ($dir) {
					rmdir($file);
					return;
				}

				unlink($file);
			}
		}
	}

}

?>
