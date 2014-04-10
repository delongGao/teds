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
		<h1>Persona Information</h1>
		<table id="pjt_personae_tbl" class="table table-bordered table-hover table-striped tablesorter">
			<thead>
              	<tr>
	                <th>Persona Title</th>
	               	<th>Persona Description</th>
	               	<th>Persona Language</th>
              	</tr>
            </thead>
			<tbody>

				<?php
					$pre_result = $dbq->prepare("select personaTitle, personaDescription, personaLanguage from personae");
					$pre_result->execute();
					while ($row = $pre_result->fetch(PDO::FETCH_ASSOC)) {
						// print_r($row);
						printf('<tr><td>%s</td><td>%s</td><td>%s</td></tr>', $row['personaTitle'],$row['personaDescription'] ? $row['personaDescription'] : "No information provided", $row['personaLanguage']);
					}
				?>
			</tbody>
		</table>

		<!-- adding new action -->
		<div class="action_wrapper">
			<div class="center-block"><button class="toggle btn btn-default">Add New Persona</button></div>
			<div class="clearfix"></div>
			<div class="toggle-content center-block" style="display:none;">
				<form id="addProject" name="addProject" action="adminproc.php" method="post">
					<h2>Add Project Persona(s)</h2>
					<a class="addmore" href="#" id="addMorePersonas">Add Another Persona</a>
					<div id="personas">
						<div class="addPersona">
							<label for="personaTitle[]">Persona Title</label><input class="input-text form-control" type="text" name="personaTitle[]" />
							<label for="personaDesc[]">Persona Description</label><input class="input-text form-control" type="text" name="personaDesc[]" />		
						</div>
					</div>
					<input type="hidden" name="source" value="persona">
					<button class="btn btn-success form-control form-button">Submit</button>
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
	<!-- // <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script> -->
	<script src="javascripts/modernizr.foundation.js"></script>
	<script src="javascripts/foundation.js"></script>
	<script src="javascripts/app.js"></script>
	<script src="javascripts/admin.js"></script>

<?php
     	$active = "Persona";
     	include "footer.inc.php"; 
?>