<?
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
?>
<!-- Sidebar -->
      <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <a class="navbar-brand" href="admin.php">TEDS Site Rater Admin</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse navbar-ex1-collapse">
          <ul class="nav navbar-nav side-nav">
            <li><a href="admin.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active" id="sidenav_separate"><a href="admin_rp.php"><i class="fa fa-bar-chart-o"></i> New Rating</a></li>
            <li class="sub_level"><a href="admin_pjt_project.php"> 1. Project</a></li>
            <li class="sub_level"><a href="admin_pjt_atft.php"> 2. Artifact</a></li>
              <li class="sub_level"><a href="admin_pjt_scenario.php"> 3. Scenario</a></li>
            <li class="sub_level"><a href="admin_pjt_persona.php"> 4. Persona</a></li>
              <li class="sub_level"><a href="admin_pjt_user.php"> 5. User</a></li>
              <li class="sub_level"><a href="admin_pjt_cate.php"> 6. Category</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right navbar-user">
              <li>
                  <a id="logout">Log out</a>
              </li>
<!--            <li class="dropdown user-dropdown">-->
<!--              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> John Smith <b class="caret"></b></a>-->
<!--              <ul class="dropdown-menu">-->
<!--                <li><a href="#"><i class="fa fa-user"></i> Profile</a></li>-->
<!--                <li><a href="#"><i class="fa fa-envelope"></i> Inbox <span class="badge">7</span></a></li>-->
<!--                <li><a href="#"><i class="fa fa-gear"></i> Settings</a></li>-->
<!--                <li class="divider"></li>-->
<!--                <li><a href="#"><i class="fa fa-power-off"></i> Log Out</a></li>-->
<!--              </ul>-->
<!--            </li>-->
          </ul>
        </div><!-- /.navbar-collapse -->
      </nav>

