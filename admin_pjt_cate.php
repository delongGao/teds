<?php
    require_once "session_inc.php";
	require_once "header.inc.php";
	require_once "dbconnect.php";

	//set up some SQL statements
	$sql["language"] = 'SELECT * from languages';

	try {
		$dbq = db_connect();
		
?>

<div id="wrapper">
	<?php
     	include "nav_part.inc.php"; 
     ?>

	<div id="page-wrapper">
		<h1>Category Information</h1>
		<table id="pjt_cate_tbl" class="table table-bordered table-hover table-striped tablesorter">
			<thead>
              	<tr>
	                <th>Category Title</th>
	               	<th>Category Description</th>
	               	<th>Category Language</th>
              	</tr>
            </thead>
			<tbody>

				<?php
					$pre_result = $dbq->prepare("select categoryTitle, categoryDescription, categoryLanguage from category");
					$pre_result->execute();
					while ($row = $pre_result->fetch(PDO::FETCH_ASSOC)) {
						// print_r($row);
						printf('<tr><td>%s</td><td>%s</td><td>%s</td></tr>', $row['categoryTitle'],$row['categoryDescription'] ? $row['categoryDescription'] : "No information provided", "placeholder");
					}
				?>
			</tbody>
		</table>
	</div>

    <?
    // logout form
    require_once "logout_form.inc.php";
    ?>

</div>

<?
		//close connection
		$dbq = NULL;
	} catch (PDOException $e) {
	     print ("getMessage(): " . $e->getMessage () . "\n");
	}
?>


<!-- include js files -->

<?php
     	$active = "Category";
     	include "footer.inc.php"; 
?>