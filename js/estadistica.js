$(document).ready(function() {

    var options = {
        chart: {
            height: 600,
            renderTo: 'estadistica',
            showInLegend: true,
        	type: 'pie'
        },
        title: {
            text: 'Estadisticas'
        },
        plotOptions: {
	          pie: {
	              allowPointSelect: true,
	              cursor: 'pointer',
	              dataLabels: {
	                  enabled: true,
	                  format: '<b>{point.name}</b>: {point.percentage:.1f} %',
	                  style: {
	                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
	                 }
	              },
	              showInLegend: false
	          }
	      },
        series: [{}]
    };

    var url = '/api/index.php/estadistica';
    $.getJSON(url, function(data) {
        jQuery.each(data, function() {
            this.title = {
		        format: '<b>{name}</b>',
		        verticalAlign: 'top',
		        y: -40
		    };
            options.series.push(this);
        });
        console.log(options.series);
        //options.series = (data);
        var chart = new Highcharts.Chart(options);
    });

    
});

