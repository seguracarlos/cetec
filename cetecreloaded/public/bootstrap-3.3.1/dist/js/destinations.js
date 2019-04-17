(function($){
	$(document).on("ready", function(){

		$(".num").numeric().formatCurrency();

		$(".num").on("change", function(){
			$(this).formatCurrency();
		});

		$("#submitbutton").validarFormulario({
			form: "destinations"
		});

		// Cargamos el municipio si es que ya tiene uno asignado
		//loadDistrictSelected();
		//loadNeighborhoodSelected();

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

		// Click en el boton asignar destino
		$("#buttonAddDestinations").on("click", function(){
			var $val  = $("#state_id option:selected").val(); 
			var $text = $("#state_id option:selected").text();
			var deleteRow = "<button type='button' class='btn btn-danger'  id='0' onclick='delit(this)'><span class='glyphicon glyphicon-trash'></span></button>";

			var rowid = $('#places').dataTable().fnAddData([
					 	    	$val,
					 	        $text,
					 	        deleteRow,
				]);
			        	
			var theNode = $('#places').dataTable().fnSettings().aoData[rowid[0]].nTr;
			theNode.setAttribute('id', 'tr'+$val);
			//alert($val+" - "+$text);
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