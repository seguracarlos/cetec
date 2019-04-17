(function($){
	$(document).on("ready", function(){

		// Tooltip a los elementos
		$('[data-tool="tooltip"]').tooltip();
		$('[data-toggle="tooltip"]').tooltip();

		/*
			CAMPOS NUMERICOS
		*/
		$('.input-numeric').numeric();

		/*
			FORMATO DE MONEDA A UN INPUT
		*/
		$(document).on('change', '.format-currency', function(event) {
			event.preventDefault();
			$(this).formatCurrency();
		});
	
		// Cambiar idioma a las alertas
		alertify.set({ 
			labels: {
				ok     : "Aceptar",
				cancel : "Cancelar"
			},
			buttonReverse: true
		});

	});
})(jQuery);

/*
FORMATO A LA FECHA
*/
function newFormatDate($date)
{
	var $dateFormat    = $date.split("-");
	var $dateNewFormat = $dateFormat[1]+"/"+$dateFormat[2]+"/"+$dateFormat[0];
	return $dateNewFormat;
}
