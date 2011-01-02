<?php
$version = "0.2";
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
		    echo "Update verfügbar!<br>";
		    $xml->update_files;
		    $zip = new ZipArchive;
		    if ($zip->open("http://twbbler.googlecode.com/svn/trunk/".$xml->version.".zip") === TRUE) {
			$zip->extractTo(__DIR__.'/');
			$zip->close();
			echo 'Geupdatet!<br>';
			echo 'Notes: '.$xml->note;
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
