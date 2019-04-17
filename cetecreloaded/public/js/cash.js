//Cerrar ventanas modales
$(document).on('hidden.bs.modal', '.modal', function (e){
	//console.log(e);
	$(e.target).removeData("bs.modal").find(".modal-body").empty();
});

$(document).ready(function(){
	
	//Al cargar la ventana modal
	$("#modalEditCash").on('shown.bs.modal', function (e) {
        console.log("me estoy mostrando");
        $('#modal-body-cash').html('<center><img style="" src="'+$basePath+'/public/img/gif-load.gif"> cargando...</center>');
        
        $.ajax({
        	url : $basePath + "/horus/cash/add",
        	type: "POST",
        	dataType: "html",
        	success: function(response){
        		//alert(response);
                // establecer el tiempo de espera añadido para mostrar la carga
                setTimeout(function(){
                	$("#modal-body-cash").html(response);
                }, 2000);
        	}
        });
    });
	
	$(document).on("click", "#buttonEditCash", function(){
		var $form = $("#formEditDeparment");
		alert($form.serializeArray());
	});
	
});
