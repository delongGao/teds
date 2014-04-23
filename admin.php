<?
// ============================== authentication ===============================
//    if (session_status() == PHP_SESSION_NONE) {
//        session_start();
//
//    }
//    if(!isset($_SESSION['user_email'])) {    // if there is no valid session
//        header("Location: index.php?notice=login_first");
//    }
    require_once "session_inc.php";
// ============================== authentication ===============================

    require_once "dbconnect.php";
    try {
    $dbq = db_connect();
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Dashboard - SB Admin</title>

    <!-- Bootstrap core CSS -->
    <link href="stylesheets/bootstrap.css" rel="stylesheet">

    <!-- Add custom CSS here -->
    <link href="stylesheets/sb-admin.css" rel="stylesheet">
    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
    <!-- Page Specific CSS -->
    <link rel="stylesheet" href="http://cdn.oesmith.co.uk/morris-0.4.3.min.css">
    <link rel="stylesheet" href="stylesheets/main.css">
  </head>

  <body>

    <div id="wrapper">

      <!-- Sidebar -->
      <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <a class="navbar-brand" href="index.html">TEDS Site Rater Admin</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse navbar-ex1-collapse">
          <ul class="nav navbar-nav side-nav">
            <li class="active"><a href="index.html"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="admin_rp.php"><i class="fa fa-bar-chart-o"></i> New Rating</a></li>
            <li><a href="admin_pjt_project.php"><i class="fa fa-table"></i> Project</a></li>
            <li><a href="admin_pjt_atft.php"><i class="fa fa-edit"></i> Artifact</a></li>
            <li><a href="admin_pjt_persona.php"><i class="fa fa-font"></i> Persona</a></li>
            <li><a href="admin_pjt_scenario.php"><i class="fa fa-desktop"></i> Scenario</a></li>
            <li><a href="admin_pjt_cate.php"><i class="fa fa-wrench"></i> Categories</a></li>
            <li><a href="admin_pjt_user.php"><i class="fa fa-user"></i> User</a></li>
          </ul>

          <ul class="nav navbar-nav navbar-right navbar-user">
              <li>
                  <a href="#" id="logout">Log out</a>
              </li>
          </ul>
        </div><!-- /.navbar-collapse -->
      </nav>
      <div id="page-wrapper">

        <div class="row">
          <div class="col-lg-12">
            <h1>Dashboard <small>Completed Rating Progress</small></h1>
            <ol class="breadcrumb">
              <li class="active"><i class="fa fa-dashboard"></i> Dashboard</li>
            </ol>
          </div>
        </div><!-- /.row -->

<?
    // outer level query
    $query = "select CONCAT(upro.firstName, ' ', upro.lastName) as userName,
                upro.email as email,
                urp.completionDate as completeDate,
                pjt.projectTitle as pjtTitle,
                a.artifactTitle as aTitle,
                urp.userRatingProgressID as urpID

                from userRatingProgress urp
                join userProfile upro on urp.userID = upro.userID
                join personae p on urp.personaID = p.personaeID
                join scenario s on urp.scenarioID = s.scenarioID
                join projectArtifact pa on urp.projectArtifactID = pa.projectArtifactID
                join project pjt on pjt.projectID = pa.projectID
                join artifact a on a.artifactID = pa.artifactID
                where urp.isComplete = 'true'";

        $first_level_result = $dbq->prepare($query);
        $first_level_result->execute();
        while ($row = $first_level_result->fetch(PDO::FETCH_ASSOC)) {
?>

<?
    // inner level query
        $inner_query = "select c.categoryTitle as cTitle,
                        ur.ratingID as ratingScore
                        from userRatingProgress urp
                        join userRating ur on urp.userRatingProgressID = ur.userRatingProcessID
                        join scenarioCategory sc on ur.scenarioCategoryID = sc.SC_ID
                        join category c on c.categoryID = sc.categoryID
                        where urp.userRatingProgressID = " . $row['urpID'];
        $stmt = $dbq->prepare($inner_query);
        $stmt->execute();
        $second_level_result = $stmt->fetchAll();
//        print_r($second_level_result);
//        echo(count($second_level_result));
        $half = count($second_level_result) / 2;
        $sec_first_half = array_slice($second_level_result, 0, $half);
        $sec_second_half = array_slice($second_level_result, $half + 1);
//        print_r($sec_first_half);
//        echo(count($sec_first_half));
//        print_r($sec_second_half);
//        echo(count($sec_second_half));

?>

        <div class="row">
            <div class="panel-group urp_record_group" id="accordion">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse_<?= $row['urpID'] ?>">
                                <?= $row['userName'] . " | " . $row['pjtTitle'] . " | " . $row['aTitle'] ?>
                                <span class="left-small"><?= " -- Completed at: " . $row['completeDate'] ?></span>
                            </a>
                        </h4>
                    </div>
                    <div id="collapse_<?= $row['urpID'] ?>" class="panel-collapse collapse">
                        <div class="panel-body">
                            <div class="half-table-wrapper">
                                <table class="tbl_first_half table table-bordered table-hover table-striped tablesorter">
                                    <tr>
                                        <th>Category</th>
                                        <th>User rating score</th>
                                    </tr>
                                    <?
                                    for ($i = 0; $i < count($sec_first_half); $i++) {
                                        print "<tr class='data_wrapper'><td>" . $sec_first_half[$i]['cTitle'] . "</td>";
                                        print "<td>" . $sec_first_half[$i]['ratingScore'] . "</td></tr>";
                                    }
                                    ?>
                                </table>
                            </div>
                            <div class="half-table-wrapper">
                                <table class="tbl_second_half table table-bordered table-hover table-striped tablesorter">
                                    <tr>
                                        <th>Category</th>
                                        <th>User rating score</th>
                                    </tr>
                                    <?
                                    for ($i = 0; $i < count($sec_second_half); $i++) {
                                        print "<tr class='data_wrapper'><td>" . $sec_second_half[$i]['cTitle'] . "</td>";
                                        print "<td>" . $sec_second_half[$i]['ratingScore'] . "</td></tr>";
                                    }
                                    ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /accordion -->
        </div><!-- /.row -->
<?
        }
?>


        <?
            require_once "logout_form.inc.php";
        ?>
      </div><!-- /#page-wrapper -->
      <div id="noticeInfo"></div>
    </div><!-- /#wrapper -->

  <!-- /template -->


  
  
  <!-- Included JS Files -->
  <!-- template plugins -->
  <!-- JavaScript -->
    <script src="javascripts/jquery-1.10.2.js"></script>
    <script src="javascripts/bootstrap.js"></script>

    <!-- Page Specific Plugins -->
    <!-- // <script src="http://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script> -->
    <!-- // <script src="http://cdn.oesmith.co.uk/morris-0.4.3.min.js"></script> -->
    <script src="javascripts/morris/chart-data-morris.js"></script>
    <script src="javascripts/tablesorter/jquery.tablesorter.js"></script>
    <script src="javascripts/tablesorter/tables.js"></script>
    <script src="javascripts/main.js"></script>
    <script src="javascripts/notice.js"></script>
  <!-- /template plugins -->
    <?
        require_once "notice.inc.php";
    ?>
</body>
</html>
<?
    } catch (PDOException $e) {
        print ("getMessage(): " . $e->getMessage () . "\n");
    }
?>