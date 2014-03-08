<?php
	require_once "header.inc.php";
	require_once "dbconnect.php";
	require_once "footer.inc.php";

	//set up some SQL statements
	$sql["language"] = 'SELECT * from languages';

	try {
		$dbq = db_connect();
		
?>

<div id="wrapper">
	<?php include "nav_part.inc.php" ?>

	<divid="page-wrapper">
		<h1>Project Scenario Information</h1>
	</div>
</div>

<?
		//close connection
		$dbq = NULL;
	} catch (PDOException $e) {
	     print ("getMessage(): " . $e->getMessage () . "\n");
	}
?>


<!-- include js files -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script src="javascripts/modernizr.foundation.js"></script>
	<script src="javascripts/foundation.js"></script>
	<script src="javascripts/app.js"></script>
	<script src="javascripts/admin.js"></script>
