<?php
include("Twibber.class.php");
$text = strip_tags($_POST['text']);
$nick = strip_tags($_POST['nick']);
if($_GET['new_entry'] == "1"){
    $Twibber->createTwibber($text, $nick);
}
?>
