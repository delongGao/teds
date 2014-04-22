<?php
//require_once "session_inc.php";
//require_once "header.inc.php";
//require_once "dbconnect.php";

//set up some SQL statements
//$sql["language"] = 'SELECT * from languages';

//try {
//$dbq = db_connect();
//$dbq->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
    session_regenerate_id();
}
if(isset($_SESSION['user_email'])) {    // if there is no valid session
    header("Location: admin.php?notice=success");
}

?>
<!DOCTYPE html>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="en"> <!--<![endif]-->
    <head>
        <meta charset="utf-8" />

        <!-- Set the viewport width to device width for mobile -->
        <meta name="viewport" content="width=device-width" />
        <meta name="description" content="">
        <meta name="author" content="">
        <title>Site Rater</title>
        <!-- Included CSS Files -->
        <link rel="stylesheet" href="stylesheets/foundation.css">
        <link rel="stylesheet" href="stylesheets/app.css">
        <link rel="stylesheet" href="stylesheets/base.css">
        <!-- <link href='http://fonts.googleapis.com/css?family=Ropa+Sans:400,400italic' rel='stylesheet' type='text/css'>		 -->

        <!--[if lt IE 9]>
        <link rel="stylesheet" href="stylesheets/ie.css">
        <![endif]-->


        <!-- IE Fix for HTML5 Tags -->
        <!--[if lt IE 9]>
        <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        <!-- template -->

        <!-- Bootstrap core CSS -->
        <link href="stylesheets/bootstrap.css" rel="stylesheet">
        <!-- Add custom CSS here -->
        <link href="stylesheets/sb-admin.css" rel="stylesheet">
        <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
        <!-- customized css -->
        <link rel="stylesheet" type="text/css" href="stylesheets/main.css">
        <!-- Page Specific CSS -->
        <!-- <link rel="stylesheet" href="http://cdn.oesmith.co.uk/morris-0.4.3.min.css"> -->
    </head>
    <body>
	<!-- container -->
	<div id="indexWrapper">
        <h2>TEDS Evaluation Admin</h2>
        <p>Authorized user only</p>

		<div id="introSel">
            <form action="adminproc.php" id="initForm" method="post">
                <label for="user_email" class="">User Email:</label><br />
                <input type="text" class="form-control" name="user_email" /><br />
                <label for="password" class="">Password:</label><br />
                <input type="password" name="password" class="form-control">

                <input type="hidden" name="source" value="index">
                <br />
                <p><input type="submit" value="Submit" class="btn btn-success form-control form-button" /></p>
            </form>
        </div>
        <div id="footWrapper">
            <p><a href="https://www.washington.edu/" target="blank">University of Washington</a></p>
            <p><a href="https://ischool.uw.edu/" target="blank">Information School</a></p>
            <p>Contact us at <a href="mailto:gaodl@uw.edu">link</a> | <small>All rights reserved</small></p>
        </div>
	</div>
    <div id="noticeInfo"></div>
	<!-- sitecontainer -->

	<!-- Included JS Files -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script src="javascripts/modernizr.foundation.js"></script>
	<script src="javascripts/foundation.js"></script>
	<script src="javascripts/app.js"></script>
    <script src="javascripts/main.js"></script>
    <script src="javascripts/notice.js"></script>
    <?
        require_once "notice.inc.php";
    ?>

</body>
</html>