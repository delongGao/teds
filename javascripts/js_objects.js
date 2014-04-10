Toggle = {
	init: function() {
		Toggle.activate();
	},
	activate: function() {
		$('.toggle').click(function() {
			$('.toggle-content').toggle();
		})
	}
}