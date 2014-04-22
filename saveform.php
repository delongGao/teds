<?php
// ============================== authentication ===============================
//if (session_status() == PHP_SESSION_NONE) {
//    session_start();
//}
//session_regenerate_id();
//if(!isset($_SESSION['user_email'])) {    // if there is no valid session
//    header("Location: index.php?notice=login_first");
//}
require "session_inc.php";
// ============================== authentication ===============================


//debug stuff, setting up values to be taken from previous form
$ids['user'] = 2; //user id - get from session
$ids['persona'] = 0; //persona id - set in form
$ids['scenario'] = 0; //scenario io - set in form
$ids['project'] = $_POST['actProject']; //project id, get from rater.php form submit
$ids['artifact'] = $_POST['actArtifact']; //artifact id, get from rater.php form submit

//set persona id
if($_POST['personae']){
	$ids['persona'] = $_POST['personae'];
	$_SESSION['personae'] = $_POST['personae'];
}

//set scenario id
if($_POST['scenario']){
	$ids['scenario'] = $_POST['scenario'];
	$_SESSION['scenario'] = $_POST['scenario'];
}

require_once "dbconnect.php";

try {
	$dbq = db_connect();
	$dbq->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	$sql['psid'] = 'SELECT psID from personaScenario WHERE personaID='.$ids['persona'].' AND scenarioID='.$ids['scenario'];
	// $sql['upid'] = 'SELECT userPersonaeID from userPersonae WHERE userID='.$ids['user'].' AND personaeID='.$ids['persona'];
	$sql['upid'] = 'SELECT userPersonaID from userPersona WHERE userID='.$ids['user'].' AND personaID='.$ids['persona'];
	$sql['paid'] = 'SELECT projectArtifactID from projectArtifact WHERE projectID='.$ids['project'].' AND artifactID='.$ids['artifact'];
	
	foreach($sql as $k => $v){
		foreach ($dbq->query($v) as $row) {
			$ids[$k] = $row[0];
		}	
	}

	$dbq = NULL;
}
catch(PDOException $e){
// Report errors
	printf ($e->getMessage());
}


//store retireved form values into PHP SESSION
$_SESSION['ids'] = $ids;

if($_POST['rate']){
	$_SESSION['rateform'] = $_POST['rate'];
}

if($_POST['ratingNarrative'] != ""){
	$_SESSION['ratingNarrative'] = $_POST['ratingNarrative'];
}

?>
