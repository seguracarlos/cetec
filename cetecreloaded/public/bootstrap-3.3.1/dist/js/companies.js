(function($){
	$(document).on("ready", function(){

		var $id        = "";

		// Click en el boton mostrar contactos por cliente
		$('.btn-modal-cont').on('click', function(evt){
			$id = $(this).closest('tr').data('id');
			
			getAllContactsByIdCompany($id);

			$("#modalContacts")
				.on('show.bs.modal', function (e) {
		        	$('#modal-body-contacts').html('<center><img style="" src="'+$basePath+'/public/img/gif-load.gif"> cargando...</center>');
		    	})
		    	.on('hidden.bs.modal', function (e) {
		        	$('#modal-body-contacts').html("");
		    	})
				.modal({ keyboard: false, backdrop : 'static' });
		});

		// Mostrar modal con los proyectos de una compania
		$('#modalCompanyProjects')
			.on('show.bs.modal', function (event) {
				var contenido = "";
				var button    = $(event.relatedTarget);
			  	var recipient = button.data('company');
			  	$id           = button.closest('tr').data('id');
			  	var modal     = $(this);

			  	modal.find('.modal-title').text('Proyectos de ' + recipient);

			  	$.ajax({
		        	url      : $basePath + "/in/customers/getprojects",
		        	type     : "POST",
		        	dataType : "json",
		        	data     : {"id":$id},
		        	success: function(response){
			        	if(response.data.length != 0){
				  			$.each(response.data, function(index, val) {
								contenido  += '<fieldset class="responsive-fieldset fieldset">'
							 		  	   + '<legend class="legend amaranth">'
									       + 'Proyecto "'+val.project_name+'"'
									       + '</legend>'
									       + '<div style="font-size:16px;">'
									       //+ '<label class="text-info">Proyecto: </label> '+ val.project_name +'</br>'
									       + '<label class="text-info">Fecha inicio: </label> '+ val.start_date +'</br>'
									       + '<label class="text-info">Fecha fin: </label> '+ val.end_date +'</br>'
									       + '</div>'
							 		       + '</fieldset>';
							});
				  		}else{
				  			contenido += '<center><p class="text-info">No hay proyectos</p></center>';
				  		}
			  			modal.find('.modal-body').html(contenido);
		        	},error    : function(){alert("Ocurrio un erro$contenidor");}
		    	});

			})
			.on('hidden.bs.modal', function (event) {
				//$(this).removeData('bs.modal');
				$(this).find('.modal-body').html("");
		    });

		/*
			FUNCION QUE COMPRUEBA SI HAY DATOS DE SEGURIDAD
		*/
		checkSecurityData();

		/*
			CUANDO SE SELECCIONA EL CHECKBOX DE SEGURIDAD
		*/
		$("#canlogin").on("click", function(){
			if($("#canlogin").is(':checked')){
				$("#contenedor-datos-seguridad").show( "fold", 
	                      {horizFirst: true}, 2000 );
			}else{
				$("#contenedor-datos-seguridad").hide( "fold", 
	                     {horizFirst: true }, 2000 );
			}
		});

	});

})(jQuery);

function getAllContactsByIdCompany(id)
{
	var $rows="";
	//boton.attr("disabled", true);
	$.ajax({
		url      : $basePath + "/in/customers/getcontacts",
		type     : "POST",
		dataType : "json",
		data     : {"id":id},
		success  : function(response){
			//console.log(response.data);
			$.each(response.data, function(index, val) {
				 $rows += '<fieldset class="responsive-fieldset fieldset">'
				 		+ '<legend class="legend amaranth">'
						+ 'Ficha de "'+val.name +'"'
						+ '</legend>'
						+ '<div style="font-size:16px;">'
						+ '<label class="text-info">Nombre completo: </label> '+ val.name+" "+val.surname+" "+val.lastname+'</br>'
						+ '<label class="text-info">Puesto: </label> '+ val.name_job +'</br>'
						+ '<label class="text-info">Tipo de contacto: </label> '+"favorito"+'</br>'
						+ '<label class="text-info">Teléfono: </label> '+ val.phone +'</br>'
						+ '<label class="text-info">Correo: </label> '+ val.email
						+ '</div>'
				 		+ '</fieldset>';
			});

			$('#modal-body-contacts').html($rows);
		},error: function(){alert("Ocurrio un error");}
	});
}

/*
 * Comprobar los datos de seguridad
 */
function checkSecurityData()
{
	if($("#canlogin").is(':checked')){
		console.log("Esta activo");
		$("#contenedor-datos-seguridad").css("display","block");
	}else{
		console.log("No esta activo");
		$("#contenedor-datos-seguridad").css("display","none");
	}
}
