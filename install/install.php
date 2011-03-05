<?php
require_once('Install.class.php');

$step = (isset($_GET['step'])) ? $_GET['step'] : 1;
$action = (isset($_GET['action'])) ? $_GET['action'] : '';

if (!empty($action)) {
	$config_array = array('mysql_user', 'mysql_pw', 'mysql_db', 'wcf_prefix', 'mysql_user_wcf', 'mysql_pw_wcf', 'mysql_db_wcf', 'wcf_dir'); // Array to check, if they're empty

	foreach ($config_array as $c => $value) // Loop through the array
		if (empty($_GET[$value])) // if its empty...
			exit('alert("Please fill out all fields!");'); // ...exit.

	$write = $Install->writeConfig($_GET['mysql_user'], $_GET['mysql_pw'], $_GET['mysql_db'], $_GET['mysql_host'], $_GET['wcf_prefix'], $_GET['tb_lang'], $_GET['mysql_user_wcf'], $_GET['mysql_pw_wcf'], $_GET['mysql_db_wcf'], $_GET['mysql_host_wcf'], $_GET['admin_group_id'], $_GET['update_group_id'], $_GET['wcf_dir'], $_GET['gzip_on']); // else write config...

	if ($write) // ...check if all ok
		exit('alert("The config had been written successfully");enableButton();'); // ...then exit.
	else // ...else
		exit('alert("Oups! Something went wrong. Please try again.");'); // ...exit.
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
		<script>
			function enableButton(){
				$("button").removeAttr("disabled");
			}
		</script>
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