<?php

require_once('../global.php');
$return = WCF::getLoginOK($_COOKIE['twibber_nick'], $_COOKIE['twibber_pw'], $_COOKIE['twibber_salt']);
if (!$return) {
	header('Location: ../index.php');
	exit;
}
$return = WCF::getAdminOK($_COOKIE['twibber_nick'], $_COOKIE['twibber_pw'], $_COOKIE['twibber_salt'], true);
if (!$return) {
	header('Location: ../index.php');
	exit;
}

include_once('../lib/tpl/header.tpl');

if (isset($_GET['update']) && $_GET['update'] == 'nightly') {
	$zip = new ZipArchive();
	$filename = 'nightly.zip';
	$contents = file_get_contents('http://github.com/chakuzo/Twibber/zipball/master');
	file_put_contents('nightly.zip', $contents);
	$zip_ar = $zip->open($filename);
	if ($zip_ar === TRUE) {
		rename('../config.inc.php', '../config.inc.back.php');
		$extract = $zip->extractTo('../');
		if ($extract == true) {
			$zip->close();
			rename('../config.inc.back.php', '../config.inc.php');
			$unlink = array('../.gitignore', '../README.txt', 'sql.sql', 'install.php', 'update.xml', '../notes/install.txt');
			array_map('unlink', $unlink);
			@rmdir('../notes');
			echo $lang_nightly_ok . '<br>';
		} else {
			echo $lang_update_fail . '<br>';
		}
	} else {
		echo $lang_update_fail . '<br>';
		echo $zip_ar;
	}
	@unlink('nightly.zip');
	exit();
}
$xml = simplexml_load_file('https://github.com/chakuzo/Twibber/raw/master/install/update.xml');
if ($xml->version != $version) {
	echo $lang_update . " <a href='update.php?update=update'>" . $lang_update_install . "</a><br>";
	echo $lang_update_notes . ' ' . $xml->note;
	$files = $xml->update_files;
	$zip = new ZipArchive;
	if ($_GET['update'] == 'update') {
		$content = file_get_contents('http://github.com/downloads/chakuzo/Twibber/ ' . str_replace(' ', '', $xml->version . '.zip'));

		file_put_contents('update.zip', $content);
		$zip_ar = $zip->open('update.zip');
		if ($zip_ar === TRUE) {
			$zip->extractTo('../');
			$zip->close();
			//$delete = explode(", ",$xml->delete);
			//array_map("unlink", $delete);
			mysql_query($xml->sqlstate);
			echo '<br>' . $lang_updated_from . ' ' . $version . ' ' . $lang_updated_to . ' ' . $xml->version . '!<br>';
		} else {
			echo '<br>Failed to update! Try Manuell to update? <a href="http://github.com/downloads/chakuzo/Twibber/ ' . str_replace(' ', '', $xml->version . '.zip') . '">Click</a><br>';
			echo $zip_ar;
		}
		@unlink('update.zip');
	}
} else {
	echo $lang_no_update . '<br>';
	echo "Du magst Updates? Versuch doch mal <a href='update.php?update=nightly'>Nightly Builds</a> <b>Achtung! Es könnte instabil sein, und nicht alles funktionieren.</b>";
}

include_once('../lib/tpl/footer.tpl');

?>