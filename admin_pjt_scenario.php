<?php
	require_once "header.inc.php";
	require_once "dbconnect.php";

	//set up some SQL statements
	$sql["language"] = 'SELECT * from languages';

	try {
		$dbq = db_connect();
		
?>

<div id="wrapper">
	<?php
     	include "nav_part.inc.php"; 
     ?>

	<div id="page-wrapper">
		<h1>Scenario Information</h1>
		<table id="pjt_scenario_tbl" class="table table-bordered table-hover table-striped tablesorter">
			<thead>
              	<tr>
	                <th>Scenario Title</th>
	               	<th>Scenario Description</th>
	               	<th>Scenario Language</th>
              	</tr>
            </thead>
			<tbody>

				<?php
					$pre_result = $dbq->prepare("select scenarioTitle, scenarioDescription, scenarioLanguageID from scenario");
					$pre_result->execute();
					while ($row = $pre_result->fetch(PDO::FETCH_ASSOC)) {
						// print_r($row);
						// $languageID = $row['scenarioLanguageID'];
						// $lan_re = mysql_query("select languageTitle from languages where languageID = ".(string)$row['scenarioLanguageID']);
						// print($lan_re); 
						printf('<tr><td>%s</td><td>%s</td><td>%s</td></tr>', $row['scenarioTitle'],$row['scenarioDescription'] ? $row['scenarioDescription'] : "No information provided", "placeholder");
					}
				?>
			</tbody>
		</table>

		<!-- adding new action -->
		<div class="action_wrapper">
			<div class="center-block"><button class="toggle btn btn-default">Add New Scenario</button></div>
			<div class="clearfix"></div>
			<div class="toggle-content center-block" style="display:none;">
				<form id="addProject" name="addProject" action="adminproc.php" method="post">
					<h2>Add Project Scenario(s)</h2>
					<a class="addmore" href="#" id="addMoreScenarios">Add Another Scenario</a>
					<div id="scenarios" class="parent_contain">
						<div class="addScenario">
							<label for="scenarioTitle[]">Scenario Title</label><input class="input-text notEmpty" type="text" name="scenarioTitle[]" />
							<label for="scenarioDesc[]">Scenario Description</label><input class="input-text notEmpty" type="text" name="scenarioDesc[]" />
						</div>
					</div>
					<input type="hidden" name="source" value="scenario" class="notEmpty">
					<input class="btn btn-success" type="submit">
				</form>
			</div>
		</div>
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
	<script src="javascripts/jquery-1.11.0.min.js"></script>
	<script src="javascripts/modernizr.foundation.js"></script>
	<script src="javascripts/foundation.js"></script>
	<script src="javascripts/app.js"></script>
	<script src="javascripts/admin.js"></script>
<?php
     	$active = "Scenario";
     	include "footer.inc.php"; 
?>