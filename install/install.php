<?php
require_once('Install.class.php');
$step = (isset($_GET['step'])) ? $_GET['step'] : 1;
$action = (isset($_GET['action'])) ? $_GET['action'] : '';
if(!empty($action)){
	exit('alert("The config had been written successfully");');
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
			<br><button onclick="location.search = '?step=<?php echo ++$step ?>'" disabled>Next</button>
		</section>
	</body>
</html>