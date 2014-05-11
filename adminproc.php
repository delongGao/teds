<?php
// require_once "session_inc.php";
// ============================== authentication ===============================
//if (session_status() == PHP_SESSION_NONE) {
//    session_start();
//}
//session_regenerate_id();
//if(!isset($_SESSION['user_email'])) {    // if there is no valid session
//    header("Location: index.php?notice=login_first");
//}
    require_once "session_inc.php";
// ============================== authentication ===============================

    require_once "dbconnect.php";

if($_POST){
    $source = $_POST['source']; // source param
    $authenticated = false;
	try {
		$dbq = db_connect();
		

		// echo "<html><br/></html>";
		// print($projectTitle);
		// echo "<html><br/></html>";
		// print($projectDescription);
		// echo "<html><br/></html>";
		// print($projectLanguageID);
		// echo "<html><br/></html>";
		

		switch ($source) {
			case 'project':
				//try to insert the project into mysql database and get the new added project id
				//prepare PDO statement, addProject SPROC 
				//temperaly comment these codes
				$projectTitle = $_POST['projectName'];
				$projectDescription = $_POST['projectDesc'];
				$projectLanguageID = $_POST['projectLang'];
				$stmt = $dbq->prepare("CALL addProject(:ptitle,:pdescript,:pLan,@nid)");
				$stmt->bindValue(':ptitle',$projectTitle, PDO::PARAM_STR);
				$stmt->bindValue(':pdescript',$projectDescription, PDO::PARAM_STR);
				$stmt->bindValue(':pLan',$projectLanguageID, PDO::PARAM_INT);
				$stmt->execute();
				//debug statements
				$projectID = $dbq->query('SELECT @nid')->fetchColumn();
				// echo "last inserted project id: ". $projectID . '<br />';
				// echo "title: ". $projectTitle . "<br/>";
				// echo "description: " . $projectDescription . "<br />";
				// echo "language id: " . $projectLanguageID . "<br />";
				// $ids['userRating'] = $dbq->query('SELECT @nrid')->fetchColumn();
				break;

			case 'scenario':
				//insert scenarios
				// print_r($_POST['scenarioTitle']);
				$scenarioTitle = $_POST['scenarioTitle'];
				$scenarioDesc = $_POST['scenarioDesc'];
				$scenarioIDs = array();
				for($i = 0; $i < count($scenarioTitle); $i++) {
					//prepare PDO statement, addArtifact SPROC 
					// echo $scenarioTitle[$i] . '<br/>';
					$stmt = $dbq->prepare("CALL addScenario(:title,:description,:languageID,@nid)");
					$stmt->bindValue(':title',$scenarioTitle[$i], PDO::PARAM_STR);
					$stmt->bindValue(':description',$scenarioDesc[$i], PDO::PARAM_STR);
					$stmt->bindValue(':languageID',5, PDO::PARAM_INT);
					$stmt->execute();
					$scenarioID = $dbq->query('SELECT @nid')->fetchColumn();
					// echo "last inserted scenario id: ". $scenarioID . '<br />';
					array_push($scenarioIDs, $scenarioID);
				}
                //update scenarioCategory
                $sql["categoryID"] = 'SELECT * from category where categoryID > 6';
                foreach ($dbq->query($sql["categoryID"]) as $cID) {

                    for($i = 0; $i < count($scenarioIDs); $i++) {
                        $sql["sceCate"] = 'INSERT INTO scenarioCategory (scenarioID, categoryID) VALUES ('. $scenarioIDs[$i].', '. $cID['categoryID'].')';
                        $dbq->query($sql["sceCate"]);
                    }

                }
				break;

			case 'atft':
				//try to insert artifact into database
				// echo "<html><br/></html>";
				// print_r($_POST['artifactTitle']);
				// echo "<html><br/></html>";
				// print_r($_POST['artifactURL']);
				$artifactTitle = $_POST['artifactTitle'];
				$artifactURL = $_POST['artifactURL'];
				// echo "<html><br/></html>";
				// echo "The number of artifacts:" . count($artifactTitle) . '<br />';
				// echo "<html><br/></html>";
				$artifactTypeID = 4;
				$artifactLanguage = 5;
				$artifactIDs = array();
				for($i = 0; $i < count($artifactTitle); $i++) {
					//prepare PDO statement, addArtifact SPROC 
					// echo $artifactURL[$i] . '<br/>';
					$stmt = $dbq->prepare("CALL addArtifact(:atitle,:aurl,:typeID,:Lan,@nid)");
					$stmt->bindValue(':atitle',$artifactTitle[$i], PDO::PARAM_STR);
					//$url = mysql_real_escape_string($url);
//					if  ( $ret = parse_url($url) ) {
//                    if ( !isset($ret["scheme"]) ) {
                    $url = $artifactURL[$i];
//                    if (strpos($url, 'http') == FALSE) {
                    if (strpos($url, 'http') < 0) {
                        $url = "http://{$url}";
                    }
//                    }
//					}
                    $url = urlencode($url);
					$stmt->bindValue(':aurl',$url, PDO::PARAM_STR);
					$stmt->bindValue(':typeID',$artifactTypeID, PDO::PARAM_INT);
					$stmt->bindValue(':Lan',$artifactLanguage, PDO::PARAM_INT);
					$stmt->execute();

					$artifactID = $dbq->query('SELECT @nid')->fetchColumn();
					// echo "last inserted artifact id: ". $artifactID . '<br />';
					array_push($artifactIDs, $artifactID);
//                    echo $artifactID;
				}

//                print_r($artifactIDs);
				
				//update projectArtifact
				$paids = array();
				for($i = 0; $i < count($artifactIDs); $i++) {
					$artifactID = $artifactIDs[$i];
                    $projectID = $_POST['projectID'][$i];
//                    echo($artifactID);
					// echo 'add artifact id:' .$artifactID . ', to<br/>';
					// echo 'project' . $projectID . '<br/>';
                    $the_query = "Insert Into projectArtifact (projectID, artifactID, isAnchor) VALUES(" . $projectID . ", " . $artifactID . ", " . "null);";
//                    echo($the_query);
					$stmt = $dbq->query($the_query);
//                    echo($projectID);
//					$stmt->bindValue(':pid',$projectID, PDO::PARAM_INT);
//					$stmt->bindValue(':aid',$artifactID, PDO::PARAM_INT);
//					$stmt->bindValue(':isAnchor',null, PDO::PARAM_INT);
//                    echo($stmt);
//					$stmt->execute();

					$paid = $dbq->query('SELECT @nid')->fetchColumn();
					array_push($paids, $paid);
					// echo "last inserted projectArtifact id: ". $paid . '<br />';
				}
				break;

			case 'persona':
				//insert personae
				// echo "<html><br/></html>";
				// print_r($_POST['personaTitle']);
				$personaTitle = $_POST['personaTitle'];
				// echo "<html><br/></html>";
				// print_r($_POST['personaDesc']);
				$personaDesc = $_POST['personaDesc'];
				// echo "<html><br/></html>";
				$personaIDs = array();
				for($i = 0; $i < count($personaTitle); $i++) {
					//prepare PDO statement, addArtifact SPROC 
					// echo $personaTitle[$i] . '<br/>';
					$stmt = $dbq->prepare("CALL addPersona(:title,:description,:languageID,@nid)");
					$stmt->bindValue(':title',$personaTitle[$i], PDO::PARAM_STR);
					$stmt->bindValue(':description',$personaDesc[$i], PDO::PARAM_STR);
					$stmt->bindValue(':languageID',5, PDO::PARAM_INT);
					$stmt->execute();
					$personaID = $dbq->query('SELECT @nid')->fetchColumn();
					// echo "last inserted persona id: ". $personaID . '<br />';
					array_push($personaIDs, $personaID);
				};
				// echo '<br />';

				// prepare scenarioIDs
				$scenarioIDs = array();
				$pre_result = $dbq->prepare("select scenarioID from scenario");
				$pre_result->execute();
				while ($row = $pre_result->fetch(PDO::FETCH_ASSOC)) {
					// print_r($row);
					$scenarioIDs[] = $row['scenarioID'];
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
						// echo "last inserted ps id: ". $psID . '<br />';
						// array_push($psIDs, $psID);
					}
				}
				break;

			case 'user':
				//try to insert the project into mysql database and get the new added project id
				//prepare PDO statement, addProject SPROC 
				//temperaly comment these codes
				$email = $_POST['email'];
				$firstName = $_POST['firstName'];
				$lastName = $_POST['lastName'];
//				if ($_POST['passwordValue'] == $_POST['passwordVery']) {
//                    $passwordHashed = password_hash($_POST['passwordValue'], PASSWORD_DEFAULT) ;
//                } password not needed, admin user can only be created at backend
				$languageID = $_POST['languageID'];
				$AuthorityLevel = 1;
                $userPersonas = $_POST['userPersona'];
				$userID = null;
//				for ($i = 0; $i < count($email); $i++) {
                    $the_query = "INSERT INTO `userProfile`(`email`, `firstName`, `lastName`, `preferredLanguage`, `passwordValue`, `AuthorityLevel`)
                         VALUES ('" . (string)$email . "','" . (string)$firstName . "','" . (string)$lastName . "','" . (string)$languageID . "','placeholder','" . (string)$AuthorityLevel . "')";
//                    echo $the_query;

					$stmt = $dbq->prepare(
                        $the_query
                    );
					$stmt->execute();
					//debug statements
					$userID = $dbq->query('SELECT LAST_INSERT_ID();')->fetchColumn();
//					array_push($userIDs, $userID);
//				}

                // update userPersonae
//                echo("last added userid " . $userID);
//                echo("");
//                print_r($userPersonas);
                for ($i=0;$i < count($userPersonas);$i++) {
                    $the_query = "INSERT INTO `userPersonae`(`userID`, `personaeID`) VALUES (" . (string)$userID . "," . (string)$userPersonas[$i] . ")";
                    $stmt = $dbq->prepare(
                        $the_query
                    );
                    $stmt->execute();
                }

				break;

            case "user_rating_progress":
                $project = $_POST['project'];
                $artifact = $_POST['artifact'];
                $persona = $_POST['persona'];
                $scenario = $_POST['scenario'];
                $user = $_POST['user'];

                $project_artifactID = $dbq->query('select * from projectArtifact pa
                                                   join project p on pa.projectID = p.projectID
                                                   join artifact a on pa.artifactID = a.artifactID
                                                   where p.projectID = ' . $project . '
                                                   and a.artifactID = ' . $artifact)->fetchColumn();
//                $persona_scenarioID = $dbq->query('SELECT * FROM personaScenario ps
//                                                   join personae p on ps.personaID = p.personaeID
//                                                   join scenario s on ps.scenarioID = s.scenarioID
//                                                   where p.personaeID = ' . $persona . '
//                                                   and s.scenarioID = ' . $scenario)->fetchColumn();
//                $persona_userID = $dbq->query('SELECT * FROM userPersonae up
//                                               join personae p on up.personaeID = p.personaeID
//                                               join userProfile u on up.userID = u.userID
//                                               where p.personaeID = ' . $persona . '
//                                               and u.userID = ' . $user)->fetchColumn();

                $the_query = "INSERT INTO `userRatingProgress`(`userID`, `personaID`, `scenarioID`, `projectArtifactID`, `isComplete`, `completionDate`)
                              VALUES (" . $user . "," . $persona . "," . $scenario . "," . $project_artifactID . ",null,null)";
                $stmt = $dbq->prepare(
                    $the_query
                );
                $stmt->execute();

                $user_ratingID = $dbq->query('SELECT LAST_INSERT_ID();')->fetchColumn();

                break;

            case "index":

                if (isset($_POST['user_email']) && isset($_POST['password'])) {
                    $user_email = $_POST['user_email'];
                    $password = $_POST['password'];
                    if (!preg_match("/^\s*$/i", $user_email) && !preg_match("/^\s*$/i", $password)) {
                        $auth_query = "select * from userProfile
                                       where email = '" . (string)$user_email . "'
                                        and AuthorityLevel = 2";
                        $result = $dbq->query($auth_query)->fetchAll();
//                        echo($auth_query);
                        if ($result) {
//                            print_r($result);
                            if ($password == $result[0]['passwordValue']) {
                                $authenticated = true;
                            }
                        }
                    }
                }
                break;

            case "logout":
                // log the user
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                // delete session
                unset($_SESSION['user']);
                session_destroy();
                break;

			default:
				//print_r($_POST['rate']);
				break;
		}
	}
	catch(PDOException $e){
	// Report errors
		// printf ($e->getMessage());
	}
}
// redirect based on source param
//echo($authenticated);
//echo($source);

if ($source == "user_rating_progress") {
    $source_url = "admin_rp.php";
} elseif ($source == "index") {
    if ($authenticated) {
        // authenticate the user
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        // auth okay, setup session
        $_SESSION['user_email'] = $_POST['user_email'];
        $source_url = "admin.php?notice=success";


    } else {
        $source_url = "index.php?notice=no_access";
    }
} elseif($source == "logout") {
    $source_url = "index.php?notice=logout";
} else {
    $source_url = "admin_pjt_".(string)$source.".php";
}

header("Location: $source_url");
?>