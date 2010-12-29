<?php
include("Twibber.class.php");
If(wcf_name_prefix == "WCF1_"){
    Throw new Exception("Konfigurieren sie bitte Twibber.class.php !");
}else{
    if($mysqli->query(file_get_contents("sql.sql"))){
	echo "Erfolgreich installiert!";
	unlink(__FILE__);
    }else{
	Throw new Exception("Fehler!");
    }
}
?>
