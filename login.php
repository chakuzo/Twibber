<?php
if($_GET['mode'] == "logout"){
    setcookie("twibber_nick", "", time() - 3600);
    setcookie("twibber_pw", "", time() - 3600);
    setcookie("twibber_salt", "", time() - 3600);
}
include("Twibber.class.php");
if($_POST['nickname'] != "" && $_POST['password'] != ""){
    $return = wcf::getData($_POST['nickname'], $_POST['password']);
    if($return){
	echo "<script>var a = new Date(); a = new Date(a.getTime() +1000*60*60*24*365);
	document.cookie = 'twibber_nick=".$_POST['nickname']."; expires='+a.toGMTString()+';';
	document.cookie = 'twibber_pw=".sha1($_POST['password'])."; expires='+a.toGMTString()+';';
	document.cookie = 'twibber_salt=". wcf::getSalt($_POST['nickname'])."; expires='+a.toGMTString()+';';</script>";
    }
    echo ($return)?"Erfolgreich eingeloggt!":"Error! Falscher Benutzername oder Passwort.";
    exit();
}
?>
<!doctype html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Login Page</title>
    </head>
    <body>
	<form action="login.php?login=true" method="post">
	    <fieldset>
		<legend>Login</legend>
		<label for="nickname">Nickname: </label><input type="text" name="nickname" id="nickname">
		<br>
		<label for="password">Password: </label><input type="password" name="password" id="password">
		<button type="submit">Absenden</button>
	    </fieldset>
	</form>
    </body>
</html>
