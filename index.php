<?php
require_once "session_inc.php";
require_once "header.inc.php";
require_once "dbconnect.php";

//set up some SQL statements
$sql["language"] = 'SELECT * from languages';

try {
$dbq = db_connect();
$dbq->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>

	<!-- container -->
	<div id="sitecontainer">
		<div id="introSel">
		<form action="rater.php" id="initForm" method="get">

		
		<h2>1. Select a Language</h2>		
		<select name="selLanguage">	
		<?
			//make languages select
			foreach ($dbq->query($sql["language"]) as $row) {
				printf('<option value="%s">%s</option>', $row['languageID'], $row['languageTitle']);
			}
		?>
		</select>
				
		<h2>2. Select a Project</h2>
		<select id="selProject" name="selProject">
		<?	
			foreach($dbq->query('CALL getAllProjects(@ID,@projectTitle,@Description)') as $row) {
				printf('<option value="%s">%s</option>', $row['ID'], $row['projectTitle']);
			}
		?>
		</select>
		<br /><br />
		<h2>3. Select an Artifact</h2>
		<select id="selArtifact" name="selArtifact">
		<?	
			foreach($dbq->query('CALL getAllArtifacts(@ID,@artifactTitle,@description,@url,@type)') as $row) {
				printf('<option value="%s">%s</option>',$row['ID'], $row['title']);
			}
		?>
		</select>

		<p><input type="submit" value="Submit" /></p>
	</form>
	</div>
<?
	//close connection
	$dbq = NULL;
} catch (PDOException $e) {
     print ("Could not connect to server.\n");
     print ("getMessage(): " . $e->getMessage () . "\n");
}
?>


<?
print "Session: " . session_id();
if (defined("CRYPT_BLOWFISH") && CRYPT_BLOWFISH) {
    echo "CRYPT_BLOWFISH is enabled!";
}
else {
    echo "CRYPT_BLOWFISH is not available";
}
?>	
	</div>
	<!-- sitecontainer -->

	<!-- Included JS Files -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script src="javascripts/modernizr.foundation.js"></script>
	<script src="javascripts/foundation.js"></script>
	<script src="javascripts/app.js"></script>
</body>
</html>