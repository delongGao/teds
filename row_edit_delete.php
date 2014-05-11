<?php //
//	require_once "dbconnect.php";
//
//
//	$targetid = $_GET['targetid'];
//	// echo $targetid;
//
//	$sql = "SELECT count(*) FROM `userRatingProgress` urp
//			join projectArtifact pa on urp.projectArtifactID = pa.projectArtifactID
//			where pa.artifactID = " . $targetid . " and urp.isComplete = 'true'";
//
//	try {
//		$dbq = db_connect();
//
//		$result = $dbq->query($sql);
//
//        if ($result > 0) {
//
//        } else {
//
//        }
//
//		//close connection
//		$dbq = NULL;
//	} catch (PDOException $e) {
//	     print ("getMessage(): " . $e->getMessage () . "\n");
//	}
//?>