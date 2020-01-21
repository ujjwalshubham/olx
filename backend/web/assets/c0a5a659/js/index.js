/*
Document: base_pages_dashboard.js
Author: Zeunix
Description: Custom JS code used in Dashboard Page (index.html)
 */



var BasePagesDashboard = function() {
	// Chart.js Chart: http://www.chartjs.org/docs
	var initDashChartJS = function() {

		// Get Chart Containers
		var $dashChartBarsCnt3 = jQuery( '.js-chartjs-bars3' )[0].getContext( '2d'),
			$dashChartLinesCnt4 = jQuery( '.js-chartjs-lines4' )[0].getContext( '2d' )

		// Set global chart options
		var $globalOptions = {
			showScale: false,
			tooltipCornerRadius: 2,
			maintainAspectRatio: false,
			responsive: true,
			animation: false,
			pointDotStrokeWidth: 2
		};


		// Init Lines Chart Bars

	};

	return {
		init: function () {
			// Init ChartJS chart
			initDashChartJS();
		}
	};
}();

// Initialize when page loads
jQuery( function() {
	BasePagesDashboard.init();
});
