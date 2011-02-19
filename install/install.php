<?php

require_once('Twibber.class.php');
If (wcf_name_prefix == 'WCF1_') {
	die("Konfigurieren Sie bitte Twibber.class.php !");
} else {
	if (!$mysqli->query(file_get_contents("sql.sql"))) {
		die("Error: " . $mysqli->error . "\n");
	} else {
		echo "Erfolgreich installiert!";
		unlink(__FILE__);
		unlink("sql.sql");
	}
}

?>
