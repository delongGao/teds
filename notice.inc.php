<?
if (isset($_GET['notice']) && !preg_match("/^\s*$/i", $_GET['notice'])) {
    $notice = $_GET['notice'];
    switch ($notice) {
        case "success":
            $notice_msg = "Successfully logged in, welcome!";
            $type = "notice_success";
            break;
        case "no_access":
            $notice_msg = "Sorry, you do not have access to the requested page!";
            $type = "notice_warning";
            break;
        case "logout":
            $notice_msg = "Successfully logged out, see you next time!";
            $type = "notice_success";
            break;
        default:
            $notice_msg = "Sorry, you do not have access to the requested page!";
            $type = "notice_warning";
            break;
    }
    echo("
        <script>
            $(function() {
                Notice.init('" . $notice_msg . "', '" . $type . "');
            })
        </script>
        ");
}
?>