<?php
function db_connect (){
	// $dbh = new PDO('mysql:host=tedseval.ovid.u.washington.edu;port=52436;dbname=artifactRating', 'root', 'sunfl0w3r', array(
	$dbh = new PDO('mysql:host=localhost;port=8889;dbname=artifactRating', 'root', 'root', array( // 'sunfl0w3r',  
      PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
      PDO::ATTR_PERSISTENT => true
   )); 
   
	return ($dbh);
}
?>
