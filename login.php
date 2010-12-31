<?php
include("Twibber.class.php");
if($_POST['nickname'] != "" && $_POST['password'] != ""){
    $return = wcf::getData($_POST['nickname'], $_POST['password']);
    echo ($return)?"Erfolgreich eingeloggt!":"Error! Falscher Benutzername oder Passwort.";
    if($return){
	setcookie("twibber_nick", $_POST['nickname'], time()+(365 * 24 * 60 * 60));
	setcookie("twibber_pw", sha1($_POST['password']), time()+(365 * 24 * 60 * 60));
	setcookie("twibber_salt", wcf::getSalt($_POST['nickname']), time()+(365 * 24 * 60 * 60));
    }
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
