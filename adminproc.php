<?php
require_once "session_inc.php";
require_once "dbconnect.php";
if($_POST){
	try {
		$dbq = db_connect();
		
		$projectTitle = $_POST['projectName'];
		$projectDescription = $_POST['projectDesc'];
		$projectLanguageID = $_POST['projectLang'];
		echo "<html><br/></html>";
		print($projectTitle);
		echo "<html><br/></html>";
		print($projectDescription);
		echo "<html><br/></html>";
		print($projectLanguageID);
		echo "<html><br/></html>";
		//try to insert the project into mysql database and get the new added project id
		//prepare PDO statement, addProject SPROC 
		//temperaly comment these codes
		
		$stmt = $dbq->prepare("CALL addProject(:ptitle,:pdescript,:pLan,@nid)");
		$stmt->bindValue(':ptitle',$projectTitle, PDO::PARAM_STR);
		$stmt->bindValue(':pdescript',$projectDescription, PDO::PARAM_STR);
		$stmt->bindValue(':pLan',$projectLanguageID, PDO::PARAM_INT);
		$stmt->execute();
		//debug statements
		$projectID = $dbq->query('SELECT @nid')->fetchColumn();
		echo "last inserted project id: ". $projectID . '<br />';
		//$ids['userRating'] = $dbq->query('SELECT @nrid')->fetchColumn();

		//try to insert artifact into database
		echo "<html><br/></html>";
		print_r($_POST['artifactTitle']);
		echo "<html><br/></html>";
		print_r($_POST['artifactURL']);
		$artifactTitle = $_POST['artifactTitle'];
		$artifactURL = $_POST['artifactURL'];
		echo "<html><br/></html>";
		echo "The number of artifacts:" . count($artifactTitle) . '<br />';
		echo "<html><br/></html>";
		$artifactTypeID = 4;
		$artifactLanguage = 5;
		$artifactIDs = array();
		for($i = 0; $i < count($artifactTitle); $i++) {
			//prepare PDO statement, addArtifact SPROC 
			echo $artifactURL[$i] . '<br/>';
			$stmt = $dbq->prepare("CALL addArtifact(:atitle,:aurl,:typeID,:Lan,@nid)");
			$stmt->bindValue(':atitle',$artifactTitle[$i], PDO::PARAM_STR);
			$url = urlencode($artifactURL[$i]);
			//$url = mysql_real_escape_string($url);
			 if  ( $ret = parse_url($url) ) {
      			if ( !isset($ret["scheme"]) ) {
       				$url = "http://{$url}";
       			}
			}
			$stmt->bindValue(':aurl',$url, PDO::PARAM_STR);
			$stmt->bindValue(':typeID',$artifactTypeID, PDO::PARAM_INT);
			$stmt->bindValue(':Lan',$artifactLanguage, PDO::PARAM_INT);
			$stmt->execute();

			$artifactID = $dbq->query('SELECT @nid')->fetchColumn();
			echo "last inserted artifact id: ". $artifactID . '<br />';
			array_push($artifactIDs, $artifactID);
		}
		echo '<br />';
		
		
		//update projectArtifact
		$paids = array();
		for($i = 0; $i < count($artifactIDs); $i++) {
			$artifactID = $artifactIDs[$i];
			echo 'add artifact id:' .$artifactID . ', to<br/>';
			echo 'project' . $projectID . '<br/>';
			$stmt = $dbq->prepare("CALL addProjectArtifact(:pid,:aid,:isAnchor,@nid)");
			$stmt->bindValue(':pid',$projectID, PDO::PARAM_INT);
			$stmt->bindValue(':aid',$artifactID, PDO::PARAM_INT);
			$stmt->bindValue(':isAnchor',null, PDO::PARAM_INT);
			$stmt->execute();

			$paid = $dbq->query('SELECT @nid')->fetchColumn();
			array_push($paids, $paid);
			echo "last inserted projectArtifact id: ". $paid . '<br />';
		}
		
		//insert personae
		echo "<html><br/></html>";
		print_r($_POST['personaTitle']);
		$personaTitle = $_POST['personaTitle'];
		echo "<html><br/></html>";
		print_r($_POST['personaDesc']);
		$personaDesc = $_POST['personaDesc'];
		echo "<html><br/></html>";
		$personaIDs = array();
		for($i = 0; $i < count($personaTitle); $i++) {
			//prepare PDO statement, addArtifact SPROC 
			echo $personaTitle[$i] . '<br/>';
			$stmt = $dbq->prepare("CALL addPersona(:title,:description,:languageID,@nid)");
			$stmt->bindValue(':title',$personaTitle[$i], PDO::PARAM_STR);
			$stmt->bindValue(':description',$personaDesc[$i], PDO::PARAM_STR);
			$stmt->bindValue(':languageID',5, PDO::PARAM_INT);
			$stmt->execute();
			$personaID = $dbq->query('SELECT @nid')->fetchColumn();
			echo "last inserted persona id: ". $personaID . '<br />';
			array_push($personaIDs, $personaID);
		}
		echo '<br />';

		//insert scenarios
		print_r($_POST['scenarioTitle']);
		$scenarioTitle = $_POST['scenarioTitle'];
		echo "<html><br/></html>";
		print_r($_POST['scenarioDesc']);
		$scenarioDesc = $_POST['scenarioDesc'];
		echo "<html><br/></html>";
		$scenarioIDs = array();
		for($i = 0; $i < count($scenarioTitle); $i++) {
			//prepare PDO statement, addArtifact SPROC 
			echo $scenarioTitle[$i] . '<br/>';
			$stmt = $dbq->prepare("CALL addScenario(:title,:description,:languageID,@nid)");
			$stmt->bindValue(':title',$scenarioTitle[$i], PDO::PARAM_STR);
			$stmt->bindValue(':description',$scenarioDesc[$i], PDO::PARAM_STR);
			$stmt->bindValue(':languageID',5, PDO::PARAM_INT);
			$stmt->execute();
			$scenarioID = $dbq->query('SELECT @nid')->fetchColumn();
			echo "last inserted scenario id: ". $scenarioID . '<br />';
			array_push($scenarioIDs, $scenarioID);
		}

		//update personaScenario
		$psIDs = array();
		for($i = 0; $i < count($personaIDs); $i++) {
			for($j = 0; $j < count($scenarioIDs); $j++) {
				$personaID = $personaIDs[$i];
				$scenarioID = $scenarioIDs[$j];
				$stmt = $dbq->prepare("CALL addPersonaScenario(:pid,:sid,@PSID)");
				$stmt->bindValue(':pid',$personaID, PDO::PARAM_INT);
				$stmt->bindValue(':sid',$scenarioID, PDO::PARAM_INT);
				$stmt->execute();
				$psID = $dbq->query('SELECT @PSID')->fetchColumn();
				echo "last inserted ps id: ". $psID . '<br />';
				array_push($psIDs, $psID);
			}
		}
		
		//update scenarioCategory
		$sql["categoryID"] = 'SELECT * from category where categoryID > 6';
		foreach ($dbq->query($sql["categoryID"]) as $cID) {
			
			for($i = 0; $i < count($scenarioIDs); $i++) {
				$sql["sceCate"] = 'INSERT INTO scenarioCategory (scenarioID, categoryID) VALUES ('. $scenarioIDs[$i].', '. $cID['categoryID'].')';
				$dbq->query($sql["sceCate"]);
			}
			
		}

		//print_r($_POST['rate']);
		

	}
	catch(PDOException $e){
	// Report errors
		printf ($e->getMessage());
	}
}
?>