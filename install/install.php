<?php
require_once('Install.class.php');

// to avoid notices "undefined index (...)"
$step = (isset($_GET['step'])) ? $_GET['step'] : 1;
$action = (isset($_GET['action'])) ? $_GET['action'] : '';

if (!empty($action)) {
	$config_array = array('mysql_user', 'mysql_pw', 'mysql_db', 'wcf_prefix', 'mysql_user_wcf', 'mysql_pw_wcf', 'mysql_db_wcf', 'wcf_dir'); // Array to check, if they're empty

	foreach ($config_array as $c => $value) { // Loop through the array
		if (empty($_REQUEST[$value])) { // if its empty...
			exit('alert("Please fill out all fields! (' . $value . ')");'); // ...exit.
		}
	}

	$write = Install::writeConfig($_REQUEST['mysql_user'], $_REQUEST['mysql_pw'], $_REQUEST['mysql_db'], $_REQUEST['mysql_host'], $_REQUEST['wcf_prefix'], $_REQUEST['tb_lang'], $_REQUEST['mysql_user_wcf'], $_REQUEST['mysql_pw_wcf'], $_REQUEST['mysql_db_wcf'], $_REQUEST['mysql_host_wcf'], $_REQUEST['admin_group_id'], $_REQUEST['update_group_id'], $_REQUEST['wcf_dir'], $_REQUEST['gzip_on']); // else write config...

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