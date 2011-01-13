<?php
include_once("../config.inc.php");
include("../class/Twibber.class.php");
$return = wcf::getLoginOK($_COOKIE['twibber_nick'], $_COOKIE['twibber_pw'], $_COOKIE['twibber_salt']);
if(!$return) Header("Location: ../index.php");
$return = wcf::getAdminOK($_COOKIE['twibber_nick'], $_COOKIE['twibber_pw'], $_COOKIE['twibber_salt'], true);
if(!$return) Header("Location: ../index.php");
$version = "0.3.3";
?><!doctype html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Update</title>
    </head>
    <body>
	<div><?php
		if($_GET['update'] == 'nightly'){
		    $zip = new ZipArchive();
		    $filename = "nightly.zip";
		    $zip_ar = $zip->open($filename, ZIPARCHIVE::CREATE);
		    if ($zip_ar !== TRUE) {
			exit("cannot open <$filename><br>");
		    }
		    $zip->addEmptyDir('class');
		    $zip->addFromString('class/update.php', file_get_contents("https://twbbler.googlecode.com/svn/trunk/class/Twibber.class.php"));
		    $zip->addEmptyDir('install');
		    $zip->addFromString('install/update.php', file_get_contents("https://twbbler.googlecode.com/svn/trunk/install/update.php"));
		    $zip->addEmptyDir('script');
		    $zip->addFromString('script/script.min.js', file_get_contents("https://twbbler.googlecode.com/svn/trunk/script/script.min.js"));
		    $zip->addEmptyDir('style');
		    $zip->addFromString('style/style.css', file_get_contents("https://twbbler.googlecode.com/svn/trunk/style/style.css"));
		    $zip->addEmptyDir('tpl');
		    $zip->addFromString('tpl/index.tpl', file_get_contents("https://twbbler.googlecode.com/svn/trunk/tpl/index.tpl"));
		    $zip->addFromString('tpl/index_login.tpl', file_get_contents("https://twbbler.googlecode.com/svn/trunk/tpl/index_login.tpl"));
		    $zip->addFromString('api.php', file_get_contents("https://twbbler.googlecode.com/svn/trunk/api.php"));
		    $zip->addFromString('index.php', file_get_contents("https://twbbler.googlecode.com/svn/trunk/index.php"));
		    $zip->addFromString('login.php', file_get_contents("https://twbbler.googlecode.com/svn/trunk/login.php"));
		    $zip->close();
		    $zip = new ZipArchive();
		    $filename = "nightly.zip";
		    $zip_ar = $zip->open($filename);
		    if ($zip_ar === TRUE) {
			$zip->extractTo("../");
			$zip->close();
			echo '<br>Nightly Version installiert!<br>';
		    } else {
			echo '<br>Failed to update!<br>';
			echo $zip_ar;
		    }
		    unlink("nightly.zip");
		    exit();
		}
		$xml = simplexml_load_file("http://twbbler.googlecode.com/svn/trunk/install/update.xml");
		if($xml->version != $version){
		    echo "Update verfügbar! <a href='update.php?update=update'>Updates Installieren</a><br>";
		    echo 'Notes: '.$xml->note;
		    $files = $xml->update_files;
		    $zip = new ZipArchive;
		    if($_GET['update'] == 'update'){
			$content = file_get_contents("http://twbbler.googlecode.com/svn/trunk/down/".str_replace(' ', '', $xml->version.".zip"));
			file_put_contents("update.zip", $content);
			$zip_ar = $zip->open("update.zip");
			if ($zip_ar === TRUE) {
			    $zip->extractTo("../");
			    $zip->close();
			    echo '<br>Geupdatet von '.$version.' zu '.$xml->version.'!<br>';
			} else {
			    echo '<br>Failed to update! Try Manuell to update? <a href="http://twbbler.googlecode.com/svn/trunk/down/'.str_replace(' ', '', $xml->version.".zip").'">Click</a>" <br>';
			    echo $zip_ar;
			}
			unlink("update.zip");
		    }
		}else{
		    echo "Kein Update verfügbar<br>";
		    echo "Du magst Updates? Versuch doch mal <a href='update.php?update=nightly'>Nightly Builds</a> <b>Achtung! Es könnte unstabil sein, und nicht alles funktionieren.</b>";
		}
	    ?>
	</div>
    </body>
</html>
