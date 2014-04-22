<?php
    require_once "session_inc.php";
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
		<h1>Project Information</h1>
		<table id="pjt_tbl" class="table table-bordered table-hover table-striped tablesorter">
			<thead>
              	<tr>
	                <th>Project Title</th>
	                <th>Project Description</th>
              	</tr>
            </thead>
			<tbody>

				<?php
					foreach($dbq->query('CALL getAllProjects(@ID,@projectTitle,@Description)') as $row) {
						printf('<tr><td>%s</td><td>%s</td></tr>', $row['projectTitle'], $row['Description']);
					}
				?>
			</tbody>
		</table>

		<!-- adding new action -->
		<div class="action_wrapper">
			<div class="center-block"><button class="toggle btn btn-default">Add New Project</button></div>
			<div class="clearfix"></div>
			<div class="toggle-content center-block" style="display:none;">
				<form id="addProject" name="addProject" action="adminproc.php" method="post">
					<h2>Add Project</h2>
					<!-- <a class="addmore" href="#" id="addMoreScenarios">Add Another Scenario</a> -->
					<div id="projects" class="parent_contain">
						<label for="projectName">Project Name</label><input class="form-control notEmpty" type="text" name="projectName" />
						<label for="projectDesc">Project Description</label><textarea class="form-control notEmpty" name="projectDesc"></textarea>
						<label for="projectLang">Project Language</label>
						<select name="projectLang" class="form-control notEmpty">
							<?
								//make languages select
								foreach ($dbq->query($sql["language"]) as $row) {
									printf('<option value="' . $row['languageID'] . '">' . $row['languageTitle'] . '</option>');
								}
							?>
						</select>
					</div>
					<input type="hidden" name="source" value="project" class="notEmpty">
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
     	$active = "Project";
     	include "footer.inc.php"; 
?>
<!-- include js files -->
	<!-- // <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script> -->
	<script src="javascripts/modernizr.foundation.js"></script>
	<script src="javascripts/foundation.js"></script>
	<script src="javascripts/app.js"></script>
	<script src="javascripts/admin.js"></script>
