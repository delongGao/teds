<?php
require_once "header.inc.php";
require_once "session_inc.php";

$pid = null; //project id
$aid = null; //artifact id


//get project and artifact IDS from GET variables via <form> submit in index.php
//if ( isset($_GET['selProject']) && isset($_GET['selArtifact']) ){
//	$pid = $_GET['selProject'];
//	$aid = $_GET['selArtifact'];
//}

require_once "dbconnect.php";

// selLanguage=5&selProject=26&selArtifact=60&selScenario=27&selPersona=20
if (isset($_GET['selLanguage']) && isset($_GET['selProject']) && isset($_GET['selScenario']) && isset($_GET['selPersona']) && isset($_GET['selArtifact'])) {
    foreach ($_GET as $key => $value) {
        if (preg_match("/^\s*$/i", $value)) {
            ?>
            <div class="error_container">
                <h2>Authentication failed: an invalid URL is provided!</h2>
                <p>Please make sure you have not modified the link</p>
                <p>Try again using the original link from your email.</p>
                <p>If the problem still occurs, contact us: <a href="mailto:gaodl@uw.edu">TEDS team</a></p>
                <p>Sorry for the trouble.</p>
            </div>
            <?

            return;
        }
    }

    try {
        $dbq = db_connect();
        $dbq->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        $pid = $_GET['selProject'];
        $aid = $_GET['selArtifact'];
        $lanID = $_GET['selLanguage'];
        $personaID = $_GET['selPersona'];
        $scenarioID = $_GET['selScenario'];

        $authenticate_query =  "SELECT * FROM userRatingProgress urp
                                join projectArtifact pa on urp.projectArtifactID = pa.projectArtifactID
                                join userProfile upro on upro.userID = urp.userID
                                where pa.projectID = " . $pid .
                                " and pa.artifactID = " . $aid .
                                " and upro.preferredLanguage = " . $lanID .
                                " and urp.personaID = " . $personaID .
                                " and urp.scenarioID = " . $scenarioID;

//        echo($authenticate_query);

        $flag = $dbq->query($authenticate_query)->fetchAll();
//        print_r($flag);

        if (!$flag) {
            // authentication failed: user identity mismatch
        ?>
            <div class="error_container">
                <h2>Authentication failed: user identity mis-match!</h2>
                <p>We are sorry, but according to our record, you don't have access to this rating. Please make sure the link is not modified. </p>
                <p>Please try again using the original link from your email. </p>
                <p>And if it still does not work, please contact us: <a href="mailto:gaodl@uw.edu">TEDS team</a></p>
            </div>
        <?
        } else {
//            print_r($flag[0]['userRatingProgressID']);
//            echo($flag[0]['userRatingProgressID']);
//            echo($flag[0]['userID']);

            // available variables
            $uid = $flag[0]['userID'];
            $urpID = $flag[0]['userRatingProgressID'];
        ?>
                <!-- container -->
                <div id="sitecontainer">
                    <div class="row">
                        <div id="artPane" class="eight columns">
                        <form action="process.php" id="rateForm" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="actProject" value="<?echo $pid;?>" class="notEmpty">
                            <input type="hidden" name="actArtifact" value="<?echo $aid;?>" class="notEmpty">
                            <input type="hidden" name="userID" value="<?= $uid ?>" class="notEmpty">
                            <input type="hidden" name="personaID" value="<?= $personaID ?>" class="notEmpty">
                            <input type="hidden" name="scenarioID" value="<?= $scenarioID ?>" class="notEmpty">
                            <input type="hidden" name="urpID" value="<?= $urpID ?>" class="notEmpty">
            <?
                $sth = $dbq->prepare('CALL getArtifact('.$aid.',@title,@url,@desc,@type)');
                $sth->execute();
                while ($row = $sth->fetch()){
            ?>
                <dl id="anchorSel" class="sub-nav">
                  <dt>Active site view:</dt>
                  <dd class="active"><a href="#">
            <?
                //populate site (artifact) title in view toggle
                printf($row['title']);
            ?>
                  </a></dd>
                  <dd><a href="#">Anchor Site</a></dd>
                </dl>


                <div id="sitePane">
                    <div id="currRate" class="activeSite">
            <?
                 printf("<h2>%s: %s</h2>", $row['title'], $row['URL']);
                 print_r('<iframe width="100%" scrolling="auto" src="' . $row['URL'] . '"></iframe>');
                }
                $sth->closeCursor();
            ?>
                    </div>
                    <div id="anchor" class="activeSite">
                        <h2>Anchor Site - Wikipedia.org, http://en.wikipedia.org</h2>
                        <iframe width="100%" scrolling="auto" src="http://en.wikipedia.org"></iframe>
                    </div>
                </div>
                        </div>


                        <div id="ratePane" class="four columns">
            <?
                //populate project title and description
                $sth = $dbq->query('CALL getProject('.$pid.',@title,@desc)');
                //printf ("rows/cols returned: %d, %d\n", $sth->rowCount(),$sth->columnCount());

                while ($row = $sth->fetch()){
                 printf ("<h2>%s</h2><p>%s</p>", $row['title'], $row['description']);
                }
                $sth->closeCursor();
            ?>
                            <table width="100%">
                                <tr>
                                    <td>
            1. Current Persona:
            <p id="personae">
            <?
                //populate personas the "language" value (5) is hard coded!
                $sth = $dbq->query('select * from personae where personae.personaeID = ' . $personaID);
                while ($row = $sth->fetch()){
                    echo($row['personaTitle']);
                }
                $sth->closeCursor();
            ?>
            </p>
                                    </td>
                                    <td>
            2. Current Scenario
            <p id="scenario">
            <?
            //populate scenarios the "language" value (5) is hard coded!

                $sth = $dbq->query('select * from scenario where scenario.scenarioID = ' . $scenarioID);
                while ($row = $sth->fetch()){
                    echo($row['scenarioTitle']);
                }
                $sth->closeCursor();
            ?>
            </p>
                                    </td>
                                </tr>
                            </table>

                        <h2>Categories</h2>

            <ul id="categories">
            <?
            //populate categories the "language" value (1) is hard coded!

                $sth = $dbq->query('CALL getParentCategories(5,@cid,@ctitle,@cdesc)');

                while ($prow = $sth->fetch()){
                printf('<li><b>%s</b>', $prow['categoryTitle']);
            ?>
                <ul>
            <?
                    foreach($dbq->query('CALL getCategoryAndChildren('. $prow['categoryID'] .',@cid,@ctitle,@description)') as $row) {
                        if (isset($_SESSION['rateform'])){
                            printf('<li>' . $row['categoryTitle'] . '<input class="notEmpty" name="rate[' . $row['categoryID'] .  ']" type="text" value="' . $_SESSION['rateform'][$row['categoryID']] . '"/><b class="toggle">Show Definition</b><div class="definition"><p>' . $row['categoryDescription'] . '</p></div></li>');
                        } else {
                            printf('<li>' . $row['categoryTitle'] . '<input class="notEmpty" name="rate[' . $row['categoryID'] .  ']" type="text" /><b class="toggle">Show Definition</b><div class="definition"><p>' . $row['categoryDescription'] . '</p></div></li>');
                        }
                    }
            ?>
                </ul>
            <?
                print "</li>";
                }
                $sth->closeCursor();

            //close connection
            $dbq = NULL;
        ?>
            </ul>

            <h2>Descriptive Comments</h2>
                        <?
                        if (isset($_SESSION['ratingNarrative'])){
                            printf('<textarea id="detailrating" name="ratingNarrative">'.$_SESSION['ratingNarrative'].'</textarea>');
                        } else {
                            printf('<textarea id="detailrating" name="ratingNarrative"></textarea>');
                        }
                        ?>


                        <h2>Screenshots</h2>
                        <input name="scn[]" type="file" />
                        <input name="scn[]" type="file" />

                        <br /><hr /><br />

                        <a id="saveForm" href="#" class="small white radius button">Save Form</a><input style="margin-left:25px;" type="submit" value="Submit Ratings" />

                        </form>
                    </div>

                </div>


            </div>
            <!-- sitecontainer -->

            <!-- Included JS Files -->
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
            <script src="javascripts/modernizr.foundation.js"></script>
            <script src="javascripts/foundation.js"></script>
            <script src="javascripts/app.js"></script>

            <script>
                $(document).ready(function() {
                    //automatically set height of iframe based on browser window size on page load
                    var ifheight = $(window).height() - $("#sitePane").offset()['top']-50;
                    $("#sitePane iframe").height(ifheight);

                    //toggle category box collapse/expand on click of main category title
                    $("#categories > li b").click(function(){
                        $(this).parent("li").find("ul").toggle();
                    }).click();

                    //ajax call to save current php session, sessions are currently default files and are stored for 4 weeks with clientside cookie reference
                    $("#saveForm").click(function(){
                        $.post("saveform.php", $("#rateForm").serialize(), function(data) {
                            //console.log($("#rateForm").serialize());
                            $('#savemsg').reveal();
                        });
                        return false;
                    });

                    //toggle between anchor site display and current rating site
                    $("#anchorSel a").click(function(e){
                        $(this).parent("dd").toggleClass("active").siblings().toggleClass("active");
                        $("#sitePane .activeSite").toggle();
                        return false;
                    });

                    $(".toggle").click(function(){
                        $(this).next(".definition").toggle();
                    });
                });

            </script>
            <?
            printf('existing session: %s', session_id() );
            ?>

            <div id="savemsg" class="reveal-modal">
                <h2>Your form been saved</h2>
                <p>The fields you have filled out so far have been saved, but they have not been submitted. Please submit all results once you are done.</p>
                <a class="close-reveal-modal">&#215;</a>
            </div>
<?
        }
    } catch (PDOException $e) {
         print ("getMessage(): " . $e->getMessage () . "\n");
    }
    ?>


<?
} else {
?>
    <div class="error_container">
        <h2>Authentication failed: an invalid URL is provided!</h2>
        <p>Please make sure you have not modified the link</p>
        <p>Try again using the original link from your email.</p>
        <p>If the problem still occurs, contact us: <a href="mailto:gaodl@uw.edu">TEDS team</a></p>
        <p>Sorry for the trouble.</p>
    </div>
<?
}
?>

</body>
</html>
