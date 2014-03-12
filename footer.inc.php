	<!-- Included JS Files -->
	<!-- template plugins -->
	<!-- JavaScript -->
    <script src="javascripts/jquery-1.10.2.js"></script>
    <script src="javascripts/bootstrap.js"></script>

    <!-- Page Specific Plugins -->
    <script src="http://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="http://cdn.oesmith.co.uk/morris-0.4.3.min.js"></script>
    <script src="javascripts/morris/chart-data-morris.js"></script>
    <script src="javascripts/tablesorter/jquery.tablesorter.js"></script>
    <script src="javascripts/tablesorter/tables.js"></script>
	<!-- /template plugins -->
	<script type="text/javascript">
		$(function() {
	 		var active = "<?= (string) $active ?>";
	 		console.log(active);
	 		$('.side-nav li a').each(function(key,item) {
	 			if (item.text.indexOf(active) >= 0) {
	 				// console.log(item);
	 				$('.side-nav li.active').removeClass("active");
	 				item.parentNode.classList.add("active");
	 			}
	 		})
	 	});
	</script>

	</body>
</html>