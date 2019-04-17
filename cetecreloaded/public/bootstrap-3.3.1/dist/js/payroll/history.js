$(document).on("ready", function(){
	
	$("#employee").select2({
		  formatNoMatches: function(term) {
			    return "<div class='select2-result-label'><span class='select2-match'></span>No se encontraron resultados</div>"
			  }
			});
	
	//Se ejecuta al iniciar un ajax
	//$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);
	$( document ).ajaxStart(function() {
		$.blockUI({ 
            message: '<h1 style="margin-top:10px;margin-bottom:10px;"><img style="margin-top:-9px;" src="'+$basePath+'/public/img/loader-30.gif"> Espere...</h1>', 
            css: { 
            	border: 'none', 
	            padding: '15px', 
	            backgroundColor: '#000', 
	            '-webkit-border-radius': '10px', 
	            '-moz-border-radius': '10px', 
	            opacity: .5, 
	            color: '#fff' 
            } 
        });
	});

	//Se ejecuta al terminar un ajax
	$( document ).ajaxStop(function() {
		$.unblockUI();
	});
	
	var $tableHistoryPayroll = $("#historypayroll").DataTable();
	$("[data-toggle=tooltip]").tooltip();
	 
	// Eligir un empleado del combo
	$("#employee").on("change", function(){
		// Funcion que obtiene el historial de nomina por usuario
		getDataPayRoll($(this).val());
	});

});

function getDataPayRoll($idEmployee)
{
	var months = new Array('under','Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
	// Limpiamos la tabla
	$("#historypayroll").DataTable().clear().draw();
	
	
	if($idEmployee != ""){
						
		$.ajax({
			method   : "POST",
			url      : $basePath + "/horus/payroll/history",
			dataType : "json",
			data     : {id_employee : $idEmployee} 
		})
		.done(function(response) {
			
			console.log( "success" );
			
			if(response.response == "ok"){
				$.each(response.data, function(index, obj){
					//console.log(obj);
					var datePayroll       = obj.date.split("-");
					var datePayrollFormat = datePayroll[2] + " de " + months[parseInt(datePayroll[1])] + " del " + datePayroll[0];
					/*console.log(datePayroll);
					console.log(datePayrollFormat);
					console.log(months[3]);
					console.log(parseInt(datePayroll[1]));*/
					//Agregar fila a la tabla
					$("#historypayroll").DataTable().row.add( [
						datePayrollFormat,
						obj.cost,
				    	obj.type,
				    	obj.description,
				    ] ).draw();
				    
				});
			}
			
		})
		.fail(function() {
			console.log( "error" );
		})
		.always(function() {
			console.log( "complete" );
		});
	}
}