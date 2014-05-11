<?php
    require_once "session_inc.php";
    require_once "header.inc.php";
    require_once "dbconnect.php";

//set up some SQL statements
$sql["project"] = 'SELECT * from project';
$sql["project_atft"] = 'SELECT * FROM projectArtifact pa
                        join project p on p.projectID = pa.projectID
                        join artifact a on a.artifactID = pa.artifactID';
$sql["persona"] = 'select personaeID as perid, personaTitle as perTitle from personae';

try {
	$dbq = db_connect();
	
?>

<div id="wrapper">
     <?php
     	include "nav_part.inc.php"; 
     ?>

      <div id="page-wrapper">

          <!-- notice info -->
          <div id="noticeInfo"></div>

		<!-- container -->
		<div id="sitecontainer" style="width:900px;">
            <h1>User Rating Progress Information</h1>
            <table id="user_rating_progress_tbl" class="table table-bordered table-hover table-striped tablesorter">
                <thead>
                <tr>
                    <th>Project</th>
                    <th>Artifact</th>
                    <th>Persona</th>
                    <th>Scenario</th>
                    <th>User</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>

                <?php
                $pre_result = $dbq->prepare("SELECT pjt.projectTitle as project, a.artifactTitle as artifact,
                                             p.personaTitle as persona, s.scenarioTitle as scenario,
                                             CONCAT(upro.firstName, ' ', upro.lastName) as userprofile,
                                             urp.isComplete as complete,
                                             urp.completionDate as completionDate,
                                             upro.email as email,
                                             urp.userRatingProgressID as urpID
                                             FROM userRatingProgress urp
                                             join userProfile upro on urp.userID = upro.userID
                                             join userPersonae uper on uper.userID = upro.userID
                                             join personae p on uper.personaeID = p.personaeID
                                             join personaScenario ps on p.personaeID = ps.personaID
                                             join scenario s on ps.scenarioID = s.scenarioID
                                             join projectArtifact pa on urp.projectArtifactID = pa.projectArtifactID
                                             join project pjt on pjt.projectID = pa.projectID
                                             join artifact a on a.artifactID = pa.artifactID
                                             where upro.userID = urp.userID
                                             and p.personaeID = urp.personaID
                                             and s.scenarioID = urp.scenarioID");
                $pre_result->execute();
                while ($row = $pre_result->fetch(PDO::FETCH_ASSOC)) {
                    // print_r($row);
                    // $languageID = $row['scenarioLanguageID'];
                    // $lan_re = mysql_query("select languageTitle from languages where languageID = ".(string)$row['scenarioLanguageID']);
                    // print($lan_re);
                    printf('<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>',
                            $row['project'],$row['artifact'],$row['persona'],$row['scenario'], $row['userprofile'],
                            $row['complete'] ? "Completed at " . $row['completionDate'] :
                            "<button class='email_sender btn btn-primary btn-sm' data-target='#emailModal' data-email='" . $row['email'] .
                            "' data-urpid='" . $row['urpID'] . "'>Send Invitation</button>"
                    );
                }
                ?>
                </tbody>
            </table>


			<h1>Admin Form</h1>

			<form id="addProject" name="addProject" action="adminproc.php" method="post">

				<!--- Add Project -->
				<h2>1. Choose a Project</h2>
				<div id="project">
				    <select name="project" id="projectID" class="form-control notEmpty">
                        <option value="" disabled selected>Select your option</option>
				
                        <?
                            //make project options
                            foreach ($dbq->query($sql["project"]) as $row) {
                                printf('<option value="' . $row['projectID'] . '">' . $row['projectTitle'] . '</option>');
                            }
                        ?>
				    </select>
                    <!-- link to add project -->
                    <a href="admin_pjt_project.php">Add New Project</a>
				</div>
                <div id="project_based_wrapper" style="display: none;" class="dependWrapper">
                    <h3>1.1 Choose Project Artifact</h3>
                    <select id="projectArtifactReceiver" class="form-control notEmpty" name="artifact">
                        <option value="" disabled selected>Select your option</option>
                    </select>
                    <a href="admin_pjt_atft.php">Add New Artifact</a>

                    <!--==========================================================================================-->
                </div>

                <!-- Add persona-based params -->
                <h2>2. Choose a Persona</h2>
                <select id="personaID" class="form-control notEmpty" name="persona">
                    <option value="" disabled selected>Select your option</option>
                    <?
                    //make persona options
                    foreach ($dbq->query($sql["persona"]) as $row) {
                        printf('<option value="' . $row['perid'] . '">' . $row['perTitle'] . '</option>');
                    }
                    ?>
                </select>
                <a href="admin_pjt_persona.php">Add New Persona</a>

                <div id="persona_based_wrapper" style="display: none;" class="dependWrapper">
                    <h3>2.1 Choose Scenario</h3>
                    <select id="personaScenarioReceiver" class="form-control notEmpty" name="scenario">
                        <option value="" disabled selected>Select your option</option>
                    </select>
                    <a href="admin_pjt_scenario.php">Add New Scenario</a>
                    <!-- seperate line -->
                    <h3>2.2 Choose User</h3>
                    <select id="personaUserReceiver" class="form-control notEmpty" name="user">
                        <option value="" disabled selected>Select your option</option>
                    </select>
                    <a href="admin_pjt_user.php">Add New User</a>
                </div>
                <!-- user rating progress / user rating process -->
                <input type="hidden" value="user_rating_progress" name="source" class="notEmpty">
                <!--==========================================================================================-->
				<div class="form-group">
                    <input type="submit" class="btn btn-success form-control form-button">
                </div>
			</form>
		</div>

          <?
          // logout form
          require_once "logout_form.inc.php";
          ?>

      <div id="emailModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
              <div class="modal-content">
                  <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                      <h4 class="modal-title" id="myModalLabel">Please confirm...</h4>
                  </div>
                  <div class="modal-body">
                      Please confirm: by clicking the "Send it!" button, this invitation
<!--                      <div class="rating_info_check"></div>-->
                      will be sent to the following user via his/her email:
                      <div class="email_check"></div>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                      <button type="button" class="btn btn-primary" data-dismiss="modal" id="email_sender_confirm">Send it!</button>
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
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script src="javascripts/modernizr.foundation.js"></script>
	<script src="javascripts/foundation.js"></script>
	<script src="javascripts/app.js"></script>
	<script src="javascripts/admin.js"></script>
    <script>
        $(function() {
//            console.log("triggered");
            AjaxHandler.init("#projectID", "#projectArtifactReceiver", "project_artifact");
            AjaxHandler.init("#personaID", "#personaScenarioReceiver", "persona_scenario");
            AjaxHandler.init("#personaID", "#personaUserReceiver", "persona_user");
        });
    </script>

	<?php
     	$active = "Process";
     	include "footer.inc.php"; 
     ?>


