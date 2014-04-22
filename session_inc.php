<?php
// ============================== authentication ===============================
if (session_status() == PHP_SESSION_NONE) {
    session_start();
    session_regenerate_id();
}
if(!isset($_SESSION['user_email'])) {    // if there is no valid session
    header("Location: index.php?notice=no_access");
}
// ============================== authentication ===============================


/*
if (!isset($_SESSION['initiated'])) {
    session_regenerate_id();
    $_SESSION['initiated'] = true;	
}
*/
?>
