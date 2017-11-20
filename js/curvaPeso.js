$(document).ready(function() {

    var options = {
        chart: {
            renderTo: 'curvaPeso',
        },
        title: {
            text: 'Evolucion'
        },
        yAxis: {
            title: {
                text: 'valor'
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle'
        },
        xAxis: {
            type: 'datetime',
            dateTimeLabelFormats: { // don't display the dummy year
                month: '%e. %b',
                year: '%b'
            },
            title: {
                text: 'fecha'
            }
        },
        series: [{}]
    };

    var paciente = document.getElementById("paciente");
    paciente = paciente.innerHTML;
    var url = 'grupo63.proyecto2017.linti.unlp.edu.ar/api/index.php/graficos/';
    $.getJSON(url.concat(paciente), function(data) {
        var datajs = [];
        options.series = [];
        jQuery.each(data, function() {
            var newseries = {
                name: '',
                data: []
            };
            newseries.name = this.name;
            newseries.data = acomodar(this.data);
            options.series.push(newseries);
        });
        var chart = new Highcharts.Chart(options);
    });

    
});

function acomodar (data) {
    var acomodado = [];
    jQuery.each(data, function() {
              var aux = [];
              var fecha = new Date(this[0]);
              var dia = fecha.getDate();
              var mes = fecha.getMonth();
              var ano = fecha.getFullYear();
              aux.push(Date.UTC(ano, mes, dia));
              aux.push(this[1]);
              acomodado.push(aux);
            });
    return acomodado;
}
