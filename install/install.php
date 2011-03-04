<?php
require_once('Install.class.php');

$step = (isset($_GET['step'])) ? $_GET['step'] : 1;
$action = (isset($_GET['action'])) ? $_GET['action'] : '';

if (!empty($action)) {
	$config_array = array('mysql_user', 'mysql_pw', 'mysql_db', 'wcf_prefix', 'mysql_user_wcf', 'mysql_pw_wcf', 'mysql_db_wcf', 'wcf_dir'); // Array to check, if they're empty

	foreach ($config_array as $c => $value) // Loop through the array
		if (empty($_GET[$value])) // if its empty...
			exit('alert("Please fill out all fields!")'); // ...exit.

	$Install->writeConfig($_GET['mysql_user']); // else write config...
	exit('alert("The config had been written successfully");'); // ...and exit.
}

?>
<!doctype html>
<html>
	<head>
		<title>Twibber Installation Step <?php echo $step ?></title>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
		<style>
			.error{
				color: red;
				border: 1px dashed orange;
			}
			.success{
				color: green;
				border: 1px dashed lightgreen;
			}
		</style>
	</head>
	<body>
		<h1>Twibber Installation Step <?php echo $step ?></h1>
		<section>
			<?php
			$Install = new Install(0);
			$Install->displayForm($step);

			?>
			<br><button onclick="location.search = '?step=<?php echo++$step ?>'" disabled>Next</button>
		</section>
	</body>
</html>