<?php
function db_connect (){
	// $dbh = new PDO('mysql:host=tedseval.ovid.u.washington.edu;port=52436;dbname=artifactRating', 'root', 'sunfl0w3r', array(
	$dbh = new PDO('mysql:host=localhost;port=3306;dbname=artifactRating2', 'root', 'root', array( // 'sunfl0w3r',  
	// $dbh = new PDO('mysql:host=localhost;port=3306;dbname=gaodl_artifactRating2', 'gaodl_aradmin', 'gdllj5201007', array( // 'sunfl0w3r',  
      PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
      PDO::ATTR_PERSISTENT => true
   )); 
   
	return ($dbh);
}
?>
