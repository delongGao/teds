<?php require_once "session_inc.php";

$pid = null; //project id
$aid = null; //artifact id


//get project and artifact IDS from GET variables via <form> submit in index.php
if ( isset($_GET['selProject']) && isset($_GET['selArtifact']) ){
	$pid = $_GET['selProject'];
	$aid = $_GET['selArtifact'];
}

require_once "header.inc.php";
require_once "dbconnect.php";

try {
$dbq = db_connect();
$dbq->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>

	<!-- container -->
	<div id="sitecontainer">
		<div class="row">
			<div id="artPane" class="eight columns">
			<form action="process.php" id="rateForm" method="post" enctype="multipart/form-data">
				<input type="hidden" name="actProject" value="<?echo $pid;?>">
				<input type="hidden" name="actArtifact" value="<?echo $aid;?>">
			
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
<select id="personae" name="personae">
<?
	//populate personas the "language" value (5) is hard coded!
	$sth = $dbq->query('CALL getAllPersonae(5,@title,@id)');
	while ($row = $sth->fetch()){
		if (isset($_SESSION['personae']) && $_SESSION['personae'] == $row['ID']){
			printf('<option selected="selected" value="%s">%s</option>', $row['ID'], $row['Title']);
		} else {
			printf('<option value="%s">%s</option>', $row['ID'], $row['Title']);
		}	
	}
	$sth->closeCursor();
?>
</select>					
						</td>
						<td>
2. Current Scenario
<select id="scenario" name="scenario">
<?
//populate scenarios the "language" value (5) is hard coded!

	$sth = $dbq->query('CALL getAllScenarios(5,@title,@id)');
	while ($row = $sth->fetch()){
		if (isset($_SESSION['scenario']) && $_SESSION['personae'] == $row['ID']){
			printf('<option selected="selected" value="%s">%s</option>', $row['ID'], $row['Title']);
		} else {
			printf('<option value="%s">%s</option>', $row['ID'], $row['Title']);
		}	
	}
	$sth->closeCursor();
?>
</select>
						</td>						
					</tr>
				</table>
				
			<h2>Categories</h2>
			
<ul id="categories">
<?
//populate categories the "language" value (1) is hard coded!

	$sth = $dbq->query('CALL getParentCategories(1,@cid,@ctitle,@cdesc)');
	
	while ($prow = $sth->fetch()){
	printf('<li><b>%s</b>', $prow['categoryTitle']);
?>
	<ul>
<?	
		foreach($dbq->query('CALL getCategoryAndChildren('. $prow['categoryID'] .',@cid,@ctitle,@description)') as $row) {
			if (isset($_SESSION['rateform'])){
				printf('<li>' . $row['categoryTitle'] . '<input name="rate[' . $row['categoryID'] .  ']" type="text" value="' . $_SESSION['rateform'][$row['categoryID']] . '"/><b class="toggle">Show Definition</b><div class="definition"><p>' . $row['categoryDescription'] . '</p></div></li>');
			} else {
				printf('<li>' . $row['categoryTitle'] . '<input name="rate[' . $row['categoryID'] .  ']" type="text" /><b class="toggle">Show Definition</b><div class="definition"><p>' . $row['categoryDescription'] . '</p></div></li>');
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

} catch (PDOException $e) {
     print ("getMessage(): " . $e->getMessage () . "\n");
}
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
</body>
</html>

