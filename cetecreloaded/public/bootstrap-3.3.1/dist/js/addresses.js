(function($){
	$(document).on("ready", function(){

		$("#modalContacts").on('shown.bs.modal', function (e) {
        	$('#modal-body-contacts').html('<center><img style="" src="'+$basePath+'/public/img/gif-load.gif"> cargando...</center>');
    	});

		$("#submitbutton").validarFormulario({
			form: "companies"
		});

			// Al dar click en el boton submit de cada formulario
			/*$("#submitbutton").on("click", function(e){
				var $resultado = true;
				var $error     = 0;

				// Validamos todos los campos del formulario
				$("#companies .input-required").each(function(){
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
					       $("#companies").submit();
					    }
					});
				}
				e.preventDefault();
			});*/

		// Cargamos el municipio si es que ya tiene uno asignado
		loadDistrictSelected();
		loadNeighborhoodSelected();

		// Asignar el nombre de la compania al span de usuario favorito
		$(".name_client").html('"' + $("#companies input[name='name_company']").val() + '"');

		//Al escribir el nombre de la compania
		$("#companies input[name='name_company']").on("keyup", function(){
			$(".name_client").html('"' + $(this).val() + '"');
		});

		// Al cambiar el estado
		$("#state_id").on("change", function(){
			
			var $opc = $("#state_id option:selected").val();
			
			if($opc != 0 && $opc != "" ){
				getAllDistrict($opc);
			}else{
				$("#district").html('<option value="0">--selecciona--</option>');
				$("#neighborhood").html('<option value="0">--selecciona--</option>');
				$("#postalcode").val("");
			}

		});

		// Al cambiar municipio
		$("#district").on("change", function(){
			
			var $opc = $("#district option:selected").val();
			
			if($opc != 0 && $opc != "" ){
				getAllNeighborhood($opc);
			}else{
				$("#neighborhood").html('<option value="0">--selecciona--</option>');
				$("#postalcode").val("");
			}
			
		});

		// Al cambiar colonia
		$("#neighborhood").on("change", function(){
			
			var $opc = $("#neighborhood option:selected");
			
			if($opc.val() != 0 && $opc.val() != ""){
				$("#postalcode").val($opc.data('code'));
			}else{
				$("#postalcode").val("");
			}
			
		});

	}); /* end document ready*/

	// Obtenemos todos los municipios o delegaciones
	function getAllDistrict($id_state)
	{
		$("#district").html('<option value="0">--selecciona--</option>');
		$("#neighborhood").html('<option value="0">--selecciona--</option>');
		$("#postalcode").val("");

		$.ajax({
			url : $basePath + "/iofractal/addresses/getalldistrict",
			type : "POST",
			dataType : "json",
			data : {id_state : $id_state},
			success : function(response){
				
				var $select_district = $('#district');
				var $options      = "";
					if(response.response == "ok"){
					
					$options += '<option value="0">--selecciona--</option>';
						$.each(response.data, function(index, obj){
						$options += '<option value="' + obj.id + '"  data-state="' + obj.state_id + '">'
							 	 + obj.name 
							 	 + '</option>' ;
					});
						$select_district.html($options);
				}
			},
			error: function(){console.log("Ocurrio un error");}
		});
	}

		// Obtenemos todos las colonias
	function getAllNeighborhood($id_district)
	{
		$("#neighborhood").html('<option value="0">--selecciona--</option>');
		$("#postalcode").val("");

		$.ajax({
			url      : $basePath + "/iofractal/addresses/getallneighborhood",
			type     : "POST",
			dataType : "json",
			data     : {id_district : $id_district},
			success  : function(response){
				
				var $select_neighborhood = $('#neighborhood');
				var $options      = "";

				if(response.response == "ok"){
					
					$options += '<option value="0">--selecciona--</option>';

					$.each(response.data, function(index, obj){
						$options += '<option value="' + obj.id + '"  data-district="' + obj.district_id + '" data-code="' + obj.postal_code + '">'
							 	 + obj.colony 
							 	 + '</option>' ;
					});

					$select_neighborhood.html($options);					
				}
			},
			error: function(){console.log("Ocurrio un error");}
		});
	}

	// Cargamos el municipio si es que ya tiene uno asignado
	function loadDistrictSelected()
	{
		if($district != 0){
			$.ajax({
				url : $basePath + "/iofractal/addresses/getalldistrict",
				type : "POST",
				dataType : "json",
				data : {id_state : $("#state_id option:selected").val()},
				success : function(response){
					var $select_district = $('#district');
					var $options         = "";
						if(response.response == "ok"){
						$options += '<option value="0">--selecciona--</option>';
							$.each(response.data, function(index, obj){
							$options += '<option value="' + obj.id + '"  data-state="' + obj.state_id + '">'
								 	 + obj.name 
								 	 + '</option>' ;
						});
							$select_district.html($options);
							$('#district > option[value="'+$district+'"]').attr('selected', 'selected');
						}
					},
					error: function(){console.log("Ocurrio un error");}
			});
		}
	}

	// Cargamos la si es que ya tiene uno asignado
	function loadNeighborhoodSelected()
	{
		if($neighborhood){
			$.ajax({
				url      : $basePath + "/iofractal/addresses/getallneighborhood",
				type     : "POST",
				dataType : "json",
				data     : {id_district : $district},
				success  : function(response){
					var $select_neighborhood = $('#neighborhood');
					var $options             = "";
					if(response.response == "ok"){
						$options += '<option value="0">--selecciona--</option>'
						$.each(response.data, function(index, obj){
							$options += '<option value="' + obj.id + '"  data-district="' + obj.district_id + '" data-code="' + obj.postal_code + '">'
								 	 + obj.colony 
								 	 + '</option>' ;
						});
						$select_neighborhood.html($options);
						$('#neighborhood > option[value="'+$neighborhood+'"]').attr('selected', 'selected');
					}
				},
				error: function(){console.log("Ocurrio un error");}
			});
		}
	}
	
})(jQuery);