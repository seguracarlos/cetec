//Traduce todas las tablas a español
$(document).ready(function(){
	$.extend( $.fn.dataTable.defaults, {
		"language": {
			"processing"      : "Procesando...",
			 "search"         : "Buscar:",
			 "lengthMenu"     : "Mostrar _MENU_ registros por p&aacute;gina",
		     "info"           : "Mostrando del  _START_ a _END_ de _TOTAL_ registros totales",
		     "infoEmpty"      : "No hay registros disponibles",
		     "infoFiltered"   : "(filtrados _MAX_ elementos en total)",
		     "infoPostFix"    : "",
		     "loadingRecords" : "Cargando...",
		     "zeroRecords"    : "No se encontraron resultados",
		     "emptyTable"     : "No hay datos disponibles en la tabla",
		     "paginate": {
		    	 "first"    :   "Primero",
		    	 "previous" :   "Anterior",
		    	 "next"     :   "Siguiente",
		         "last"     :   "&uacute;ltimo"
		     },
		     "aria": {
		    	 "sortAscending"  : ": habilitada para ordenar la columna en orden ascendente",
		    	 "sortDescending" : ": habilitada para ordenar la columna en orden descendente"
		     }
		}
	});
});