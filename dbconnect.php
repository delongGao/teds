<?php
// ============================== authentication ===============================
//session_start();
//session_regenerate_id();
//if(!isset($_SESSION['user_email']))      // if there is no valid session
//{
//    header("Location: index.php?notice=login_first");
//}
// ============================== authentication ===============================

function db_connect (){
	// $dbh = new PDO('mysql:host=tedseval.ovid.u.washington.edu;port=52436;dbname=artifactRating', 'root', 'sunfl0w3r', array( // original remote server
    // $dbh = new PDO('mysql:host=localhost;port=3306;dbname=artifactRating2', 'root', 'root', array( // 'sunfl0w3r',
   $dbh = new PDO('mysql:host=tedsrate.ovid.u.washington.edu;port=3306;dbname=artifactRating2', 'root', 'dongh3d3long', array( // new remote server
      PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
      PDO::ATTR_PERSISTENT => true
   )); 
   
	return ($dbh);
}
?>
