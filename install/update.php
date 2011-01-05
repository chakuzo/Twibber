<?php
$version = "0.1";
?><!doctype html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Update</title>
    </head>
    <body>
	<div>
	    <?php
		$xml = simplexml_load_file("http://twbbler.googlecode.com/svn/trunk/update.xml");
		if($xml->version < $version){
		    echo "Update verfügbar! <a href='update.php?update=true'>Updates Installieren</a><br>";
		    echo 'Notes: '.$xml->note;
		    $files = $xml->update_files;
		    $zip = new ZipArchive;
		    if ($zip->open("http://twbbler.googlecode.com/svn/trunk/".$xml->version.".zip") === TRUE && $_GET['updae'] == 'true') {
			$zip->extractTo(__DIR__.'/');
			$zip->close();
			echo 'Geupdatet von '.$version.' zu '.$xml->version.'!<br>';
		    } else {
			echo 'Failed to update!<br>';
			echo $zip->getStatusString;
		    }
		}else{
		    echo "Kein Update verfügbar";
		}
	    ?>
	</div>
    </body>
</html>