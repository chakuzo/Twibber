<?php
include("./class/Twibber.class.php");
if($_GET['mode'] == "logout"){
    setcookie("twibber_nick", "", time() - 3600);
    setcookie("twibber_pw", "", time() - 3600);
    setcookie("twibber_salt", "", time() - 3600);
    Header("Location: index.php");
}
if($_POST['nickname'] != "" && $_POST['password'] != ""){
    $return = wcf::getData($_POST['nickname'], $_POST['password']);
    if($return){
	setcookie("twibber_nick", $_POST['nickname'], time()+(365 * 24 * 60 * 60));
	setcookie("twibber_pw", sha1($_POST['password']), time()+(365 * 24 * 60 * 60));
	setcookie("twibber_salt", wcf::getSalt($_POST['nickname']), time()+(365 * 24 * 60 * 60));
	Header("Location: index.php");
    }
}
?>
