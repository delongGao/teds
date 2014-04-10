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
		<h1>User Information</h1>
		<table id="pjt_user_tbl" class="table table-bordered table-hover table-striped tablesorter">
			<thead>
              	<tr>
	                <th>User Name</th>
	               	<th>User Email</th>
	               	<th>User Language</th>
	               	<th>User Type</th>
              	</tr>
            </thead>
			<tbody>

				<?php
					$pre_result = $dbq->prepare("select firstName, lastName, email, preferredLanguage, AuthorityLevel from userProfile");
					$pre_result->execute();
					while ($row = $pre_result->fetch(PDO::FETCH_ASSOC)) {
						// print_r($row);
						printf('<tr><td>%s %s</td><td>%s</td><td>%s</td><td>%s</td></tr>', $row['firstName'],$row['lastName'],$row['email'],$row['preferredLanguage'],$row['AuthorityLevel'] == 2 ? "Administrator" : "User");
					}
				?>
			</tbody>
		</table>

		<!-- adding new action -->
		<div class="action_wrapper">
			<div class="center-block"><button class="toggle btn btn-default">Add New User</button></div>
			<div class="clearfix"></div>
			<div class="toggle-content center-block" style="display:none;">
				<form id="addProject" name="addProject" action="adminproc.php" method="post">
					<h2>Add Project User(s)</h2>
					<a class="addmore" href="#" id="addMoreUsers">Add Another User</a>
					<div id="users">
						<div class="addUser">
							<label for="email[]">User Email</label><input class="input-text form-control" type="text" name="email[]" />
							<label for="firstName[]">User Firstname</label><input class="input-text form-control" type="text" name="firstName[]" />
							<label for="lastName[]">User Lastname</label><input class="input-text form-control" type="text" name="lastName[]" />		
							<label for="passwordValue[]">User Password</label><input class="input-text form-control" type="text" name="passwordValue[]" />
							<label for="languageID[]">User Preferred Language</label>
							<select name="languageID[]" class="form-control">
								<?
									//make languages select
									foreach ($dbq->query('SELECT * FROM languages') as $row) {
										printf('<option value="' . $row['languageID'] . '">' . $row['languageTitle'] . '</option>');
									}
								?>
							</select>	
							<label for="AuthorityLevel[]">User Authority Type</label>
							<select name="AuthorityLevel[]" class="form-control">
								<option value=1>User</option>
								<option value=2>Administrator</option>
							</select>	
						</div>
					</div>
					<input type="hidden" name="source" value="user">
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
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script src="javascripts/modernizr.foundation.js"></script>
	<script src="javascripts/foundation.js"></script>
	<script src="javascripts/app.js"></script>
	<script src="javascripts/admin.js"></script>


<?php
     	$active = "User";
     	include "footer.inc.php"; 
?>