<?php
session_start();
/*
if (!isset($_SESSION['initiated'])) {
    session_regenerate_id();
    $_SESSION['initiated'] = true;	
}
*/
?>


<?
if (isset($_SESSION['rateform'])){
	//print_r($_SESSION['rateform']);
	//print $_SESSION['ratingNarrative'];
	//printf("<p>".$_SESSION['rateform'][33]."</p>");
}
?>
