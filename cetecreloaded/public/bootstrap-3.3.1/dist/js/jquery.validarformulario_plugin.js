(function ($) {
    $.fn.validarFormulario = function (opciones) {

        var configuracion = $.extend({
            form: null,
        }, opciones);

        return this.each(function () {
            $(this).on("click", function(e){
            	var $resultado = true;
				var $error     = 0;

				// Validamos todos los campos del formulario
				$("#"+configuracion.form+" .input-required").each(function(){
					if ($(this).val() == "") {
						if($(this).parent().hasClass("has-success")){
							$(this).parent().removeClass("has-success");
						}
						$(this).parent().addClass("has-error");
						$error++;
					}else{
						if($(this).parent().hasClass("has-error")){
							$(this).parent().removeClass("has-error");
						}
						$(this).parent().addClass("has-success");
					}
				});

				if($error > 0){
					alertify.alert("Los "+$error+" campos resaltados en rojo son obligatorios");
				}else{
					alertify.confirm("¿Quiere guardar los cambios?", function (e) {
					    if (e) {
					       $("#"+configuracion.form).submit();
					    }
					});
				}
				e.preventDefault();
            });
        });
    }
}(jQuery));