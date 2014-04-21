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
		<h1>Project Artifact Information</h1>
		<table id="pjt_atft_tbl" class="table table-bordered table-hover table-striped tablesorter">
			<thead>
              	<tr>
	                <th>Artifact Title</th>
	                <th>Artifact URL</th>
	                <!-- <th>Artifact Type(id)</th> -->
	                <!-- <th>Artifact Language(id)</th> -->
	                <th>Artifact Description</th>
              	</tr>
            </thead>
			<tbody>

				<?php
					$pre_result = $dbq->prepare("select artifactTitle, artifactURL, artifactDescription from artifact");
					$pre_result->execute();
					while ($row = $pre_result->fetch(PDO::FETCH_ASSOC)) {
						// print_r($row);
						printf('<tr><td>%s</td><td><a href="%s" target="blank">%s</a></td><td>%s</td></tr>', $row['artifactTitle'],$row['artifactURL'],$row['artifactURL'],$row['artifactDescription'] ? $row['artifactDescription'] : "No information provided");
					}
				?>
			</tbody>
		</table>

		<!-- adding new action -->
		<div class="action_wrapper">
			<div class="center-block"><button class="toggle btn btn-default">Add New Artifact</button></div>
			<div class="clearfix"></div>
			<div class="toggle-content center-block" style="display:none;">
				<form id="addProject" name="addProject" action="adminproc.php" method="post">
					<h2>Add Project Artifact(s)</h2>
					<a class="addmore" href="#" id="addMoreArtifacts">Add Another Artifact</a>
					<div id="artifacts">
						<div class="addArtifact">
							<label for="artifactTitle[]">Artifact Title</label><input class="input-text form-control notEmpty" type="text" name="artifactTitle[]" />
							<label for="artifactURL[]">Artifact URL</label><input class="input-text form-control notEmpty" type="text" name="artifactURL[]" />
							<select name="projectID[]" class="form-control notEmpty">
								<?
									//make languages select
									foreach ($dbq->query('select * from project') as $row) {
										printf('<option value="' . $row['projectID'] . '">' . $row['projectTitle'] . '</option>');
									}
								?>
							</select>	
						</div>
					</div>
					<input type="hidden" name="source" value="atft" class="notEmpty">
					<input class="btn btn-success form-control form-button" type="submit">
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


<?php
     	$active = "Artifact";
     	include "footer.inc.php"; 
?>
<!-- include js files -->
	<!-- // <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script> -->
	<script src="javascripts/modernizr.foundation.js"></script>
	<script src="javascripts/foundation.js"></script>
	<script src="javascripts/app.js"></script>
	<script src="javascripts/admin.js"></script>
