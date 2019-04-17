(function($){
	$(document).on("ready", function(){
		
		//bootstrap WYSIHTML5 - text editor
	    //$(".textarea").wysihtml5();

		//The Calender
	    $("#calendar").datepicker();

	    // Reloj
	    clock();

	    // Grafica
	    chart();

	    /*$('.dataTable').DataTable({
	    	//"bLengthChange": false,
	    });*/

	    /*FSFRDGD*/

	    /*$(document).on('shown.bs.modal', function (e) {
	    	console.log("HOLA");
    		$(e.target).before($(e.target).find('.modal-backdrop').clone().css('z-index', $(e.target).css('z-index')-1)); 
    		$(e.target).find('.modal-backdrop').removeClass('in').css('transition', 'none');
		});

		$(document).on('hide.bs.modal', function (e) {
			console.log("BYE");
    		$(e.target).prev('.modal-backdrop').remove();
		});*/

	    /*$('.dataTables_length select').on('change', function() {
	    	$('#myModal5').modal('handleUpdate');
	    	//$('#myModal5 #myModal4 #myModal3 ').data('bs.modal').handleUpdate()
	    }); */

	    /*$('.paginate_button').on('click', function() {
	    	console.log("rf4re");
	    	$('#myModal5').modal('handleUpdate');
	    	//$('#myModal5 #myModal4 #myModal3 ').data('bs.modal').handleUpdate()
	    });*/


    
	}); /* end document ready*/
})(jQuery);

/*
 * Funcion que muestra el reloj
 */
function clock()
{
	// Crea dos variables con los nombres de los meses y los días en una matriz
	var monthNames = [ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre" ]; 
	var dayNames   = ["Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sabado"];

	// Creamos un objeto newDate()
	var newDate = new Date();
	// Extrae la fecha actual del objeto Date
	newDate.setDate(newDate.getDate());
	// Salida: el día, fecha, mes y año    
	$('#Date').html(dayNames[newDate.getDay()] + " " + newDate.getDate() + ' ' + monthNames[newDate.getMonth()] + ' ' + newDate.getFullYear());

	setInterval( function() {
		// Create a newDate() object and extract the seconds of the current time on the visitor's
		var seconds = new Date().getSeconds();
		// Add a leading zero to seconds value
		$("#sec").html(( seconds < 10 ? "0" : "" ) + seconds);
	},1000);
		
	setInterval( function() {
		// Create a newDate() object and extract the minutes of the current time on the visitor's
		var minutes = new Date().getMinutes();
		// Add a leading zero to the minutes value
		$("#min").html(( minutes < 10 ? "0" : "" ) + minutes);
	},1000);
		
	setInterval( function() {
		// Create a newDate() object and extract the hours of the current time on the visitor's
		var hours = new Date().getHours();
		// Add a leading zero to the hours value
		$("#hours").html(( hours < 10 ? "0" : "" ) + hours);
	}, 1000);
}

/*
 * Funcion que genera graficas
 */
function chart()
{
		$('#chart').highcharts({
			chart: {
	        	plotBackgroundColor: null,
	        	plotBorderWidth: null,//null,
	        	plotShadow: false
	        },
	        title: {
	            text: 'Ingresos y Egresos, 2015'
	        },
	        tooltip: {
	            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
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
	                }
	            }
	        },
	        series: [{
	            type: 'pie',
	            name: 'Browser share',
	            data: [
					['Ingresos',   60.0],
					['Egresos',   20.0],
					{
	                    name: 'Cxc',
	                    y: 20.0,
	                    sliced: true,
	                    selected: true
	                }
	            ]
	        }]
	    });
}
