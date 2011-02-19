<?php
require_once('../config.inc.php');
require_once('../lib/lang/".TWIBBER_LANG.".lang.php');
require_once('../lib/class/Twibber.class.php');
$return = wcf::getLoginOK($_COOKIE['twibber_nick'], $_COOKIE['twibber_pw'], $_COOKIE['twibber_salt']);
if (!$return)
	header('Location: ../index.php');
$return = wcf::getAdminOK($_COOKIE['twibber_nick'], $_COOKIE['twibber_pw'], $_COOKIE['twibber_salt'], true);
if (!$return)
	header('Location: ../index.php');
$version = "0.5";

?><!doctype html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Update</title>
    </head>
    <body>
		<div><?php
if ($_GET['update'] == 'nightly') {
	$zip = new ZipArchive();
	$filename = 'nightly.zip';
	$zip_ar = $zip->open($filename, ZIPARCHIVE::CREATE);
	if ($zip_ar !== TRUE) {
		exit("cannot open <$filename><br>");
	}
	$zip->addEmptyDir('lib');
	$zip->addEmptyDir('lib/class');
	$zip->addFromString('lib/class/Twibber.class.php', file_get_contents('https://twbbler.googlecode.com/svn/trunk/lib/class/Twibber.class.php'));
	$zip->addEmptyDir('install');
	$zip->addFromString('install/update.php', file_get_contents('https://twbbler.googlecode.com/svn/trunk/install/update.php'));
	$zip->addEmptyDir('lib/script');
	$zip->addFromString('lib/script/script.min.js', file_get_contents('https://twbbler.googlecode.com/svn/trunk/lib/script/script.min.js'));
	$zip->addEmptyDir('lib/style');
	$zip->addFromString('lib/style/style.min.css', file_get_contents('https://twbbler.googlecode.com/svn/trunk/lib/style/style.min.css'));
	$zip->addEmptyDir('lib/tpl');
	$zip->addFromString('lib/tpl/index.tpl', file_get_contents('https://twbbler.googlecode.com/svn/trunk/lib/tpl/index.tpl'));
	$zip->addFromString('lib/tpl/index_login.tpl', file_get_contents('https://twbbler.googlecode.com/svn/trunk/lib/tpl/index_login.tpl'));
	$zip->addEmptyDir('lib/lang');
	$zip->addFromString('lib/lang/de.lang.php', file_get_contents('https://twbbler.googlecode.com/svn/trunk/lib/lang/de.lang.php'));
	$zip->addFromString('api.php', file_get_contents('https://twbbler.googlecode.com/svn/trunk/api.php'));
	$zip->addFromString('index.php', file_get_contents('https://twbbler.googlecode.com/svn/trunk/index.php'));
	$zip->addFromString('login.php', file_get_contents('https://twbbler.googlecode.com/svn/trunk/login.php'));
	$zip->addFromString('.htaccess', file_get_contents('https://twbbler.googlecode.com/svn/trunk/.htaccess'));
	$zip->close();
	$zip = new ZipArchive();
	$filename = 'nightly.zip';
	$zip_ar = $zip->open($filename);
	if ($zip_ar === TRUE) {
		$zip->extractTo('../');
		$zip->close();
		echo $lang_nightly_ok . '<br>';
	} else {
		echo $lang_update_fail . '<br>';
		echo $zip_ar;
	}
	unlink("nightly.zip");
	exit();
}
$xml = simplexml_load_file('http://twbbler.googlecode.com/svn/trunk/install/update.xml');
if ($xml->version != $version) {
	echo $lang_update . " <a href='update.php?update=update'>" . $lang_update_install . "</a><br>";
	echo $lang_update_notes . ' ' . $xml->note;
	$files = $xml->update_files;
	$zip = new ZipArchive;
	if ($_GET['update'] == 'update') {
		$content = file_get_contents("http://twbbler.googlecode.com/svn/trunk/down/" . str_replace(' ', '', $xml->version . ".zip"));
		file_put_contents("update.zip", $content);
		$zip_ar = $zip->open("update.zip");
		if ($zip_ar === TRUE) {
			$zip->extractTo("../");
			$zip->close();
			//$delete = explode(",",$xml->delete);
			//array_map("unlink", $delete);
			mysql_query($xml->sqlstate);
			echo '<br>' . $lang_updated_from . ' ' . $version . ' ' . $lang_updated_to . ' ' . $xml->version . '!<br>';
		} else {
			echo '<br>Failed to update! Try Manuell to update? <a href="http://twbbler.googlecode.com/svn/trunk/down/' . str_replace(' ', '', $xml->version . ".zip") . '">Click</a><br>';
			echo $zip_ar;
		}
		unlink("update.zip");
	}
} else {
	echo $lang_no_update . "<br>";
	echo "Du magst Updates? Versuch doch mal <a href='update.php?update=nightly'>Nightly Builds</a> <b>Achtung! Es könnte unstabil sein, und nicht alles funktionieren.</b>";
}

?>
		</div>
    </body>
</html>
