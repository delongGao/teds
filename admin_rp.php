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

		<!-- container -->
		<div id="sitecontainer" style="width:900px;">

			<h1>Admin Form</h1>

			<form id="addProject" name="addProject" action="adminproc.php" method="post">

				<!--- Add Project -->
				<h2>1. Add a Project</h2>
				<div id="project">
				<label for="projectName">Project Name</label><input type="text" name="projectName" />
				<label for="projectDesc">Project Description</label><textarea name="projectDesc"></textarea>
				<label for="projectLang">Project Language</label><select name="projectLang">
				
		<?
			//make languages select
			foreach ($dbq->query($sql["language"]) as $row) {
				printf('<option value="' . $row['languageID'] . '">' . $row['languageTitle'] . '</option>');
			}
		?>
				</select>

				</div>
			
				<!--- Add Artifacts
				addArtifact, in artifactTitle varchar(45), in artifactURL varchar(255), in artifactTypeID INT, in artifactLanguageID int, out newArtifactID int
				-->
				<!--==========================================================================================-->
				
				
				<h2>2. Add Project Artifact(s)</h2>
				<a class="addmore" href="#" id="addMoreArtifacts">Add Another Artifact</a>
				<div id="artifacts">
					<div class="addArtifact">
						<label for="artifactTitle[]">Artifact Title</label><input class="input-text" type="text" name="artifactTitle[]" />
						<label for="artifactURL[]">Artifact URL</label><input class="input-text" type="text" name="artifactURL[]" />	
					</div>
				</div>
				
				<!--==========================================================================================-->
			
				<h2>3. Add Project Persona(s)</h2>
				<a class="addmore" href="#" id="addMorePersonas">Add Another Persona</a>
				<div id="personas">
					<div class="addPersona">
						<label for="personaTitle[]">Persona Title</label><input class="input-text" type="text" name="personaTitle[]" />
						<label for="personaDesc[]">Persona Description</label><input class="input-text" type="text" name="personaDesc[]" />		
					</div>
				</div>

				<!--==========================================================================================-->
			
				<h2>4. Add Project Scenario(s)</h2>
				<a class="addmore" href="#" id="addMoreScenarios">Add Another Scenario</a>
				<div id="scenarios">
					<div class="addScenario">
						<label for="scenarioTitle[]">Scenario Title</label><input class="input-text" type="text" name="scenarioTitle[]" />
						<label for="scenarioDesc[]">Scenario Description</label><input class="input-text" type="text" name="scenarioDesc[]" />
					</select>			
					</div>
				</div>

				<!--==========================================================================================-->
			
				<h2>5. Add Project Rating Categories</h2>

				<div id="category" class="row">

					<ul>
					<?
						$sth = $dbq->query('CALL getParentCategories(1,@cid,@ctitle,@cdesc)');
						
						while ($prow = $sth->fetch()){
						printf('<li><b>%s</b>', $prow['categoryTitle']);
					?>
						<ul>
					<?	
							foreach($dbq->query('CALL getCategoryAndChildren('. $prow['categoryID'] .',@cid,@ctitle,@description)') as $row) {
									printf('<li>' . $row['categoryTitle'] . '<input name="rate[' . $row['categoryID'] .  ']" type="text" /><b class="toggle">Show Definition</b><div class="definition"><p>' . $row['categoryDescription'] . '</p></div></li>');
							}
					?>
						</ul>
					<?
						print "</li>";
						}
						$sth->closeCursor();		
					?>		
								
				</div>
				<button>Submit</button>
			</form>
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

	<?php
     	$active = "Process";
     	include "footer.inc.php"; 
     ?>


