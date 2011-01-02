<!doctype html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
	<?php
	    $xml = simplexml_load_file("http://twbbler.googlecode.com/svn/trunk/update.xml");
	    print_r($xml);
	?>
    </body>
</html>
