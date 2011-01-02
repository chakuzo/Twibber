<?php
$version = "0.2";
?><!doctype html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Update</title>
    </head>
    <body>
	<?php
	    $xml = simplexml_load_file("http://twbbler.googlecode.com/svn/trunk/update.xml");
	    if($xml->version < $version){
		echo "Update verfügbar!";
	    }
	?>
    </body>
</html>
