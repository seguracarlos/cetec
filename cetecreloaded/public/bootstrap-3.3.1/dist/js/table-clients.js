(function($){
	$(document).on("ready", function(){

		//CAMBIO EN EL INPUT RADIO CONSOLIDADO
		$('input[name=consolidated]:radio').change(function(){
			var $radioConsolidated = $("input[name=consolidated]:checked").val()
			//console.log($radioConsolidated);
			//console.log($(this).val());

			//Comprobar el tipo de usuario
			if($radioConsolidated == 2){
				$("#add_row").attr('disabled',true);
			}else{
				$("#add_row").attr('disabled',false);
			}
		});

		//AUTOCOMPLETE INPUT
		$( ".tags" ).autocomplete({
		   	autoFocus: true,
		   	minLength: 1,
		   	source: function( request, response ) {
        		$.ajax({
        			url: $basePath+"/in/journey/autocompleteclients",
        			type : "POST",
          			dataType: "json",
          			data: {
            				q: request.term
          				},
          			success: function( data ) {
          				//console.log(jQuery.isEmptyObject(data));
	          			if(!jQuery.isEmptyObject(data)){
	          				response($.map(data.data, function(item) {
	            				return {
	                       			label: item.Nombre,
	                       			id: item.id_end_customers,
	                     		};
	            			}));
	          			}
          			}
        		});
      		},
		});

		/*
			AL SELECCIONAR UN CLIENTE PRINCIPAL
		*/
		$("#company_ID").on('change', function(event) {
			event.preventDefault();
			var $selectClients = $(this);
			//alert($(this).val());
			//console.log($selectClients.find('option:selected').text());
			//Asignamos valor del combo al primer combo de la tabla de clientes
			$("#table-clients-send tbody tr:eq(0) td").find('select').val($(this).val());
			//console.log($("#table-clients-send tbody tr:eq(0)").attr('id'));


			// RECORREMOS EL #accordion
			$('#accordion .panel').each(function () {
				//console.log($(this).attr('data-parent'));
				// VALIDAMOS LA RELACION
				if($(this).attr('data-parent') == $("#table-clients-send tbody tr:eq(0)").attr('id')){
					// Asignamos titulo al collapse
					$(this).find('.panel-heading h4.panel-title a').text('Clientes de: '+$selectClients.find('option:selected').text());
				}

			});
		});

		/*
			CLICK EN EL BOTON AGREGAR FILA A LA TABLA #table-clients-send
		*/
		$('#add_row').on('click', function(event) {
			event.preventDefault();

			//FUNCION QUE AGREGA UNA FILA NUEVA
			addRowTable();
			/*
			// Get max row id and set new id
        	var $newid = 0;
        	var status = "";

			$('#table-clients-send tbody tr:last').each(function () {
				if($(this).attr('id') != 'addr0'){
					var client = $(this).find("td").eq(0).find("select option:selected").val();
					if (client != "") { status = 1; }else{ status = 0; }
				}else{
					status = 1;
				}
			});

        	if(status == 1){
				$.each($("#table-clients-send tbody tr"), function() {
	            	if (parseInt($(this).data("id")) > $newid) {
	                	$newid = parseInt($(this).data("id"));
	                	//console.log("id"+$newid);
	            	}
	        	});
	        	$newid++;
	        	//console.log($newid);

	        	var $tr = $("<tr></tr>", {
	            	id: "addr"+$newid,
	            	"data-id": $newid
	        	});

	        	// loop through each td and create new elements with name of newid
	        	$.each($("#table-clients-send tbody tr:nth(0) td"), function() {
	            	var $current_td  = $(this);
	            	//console.log($current_td);
	            	var $children_td = $current_td.children();

	            	// add new td and element if it has a nane
	            	if ($(this).data("name") != undefined) {
	                	var $td = $("<td></td>", {
	                    	"data-name": $($current_td).data("name")
	                	});

	                	var c = $($current_td).find($($children_td[0]).prop('tagName')).clone().val("");
	                	c.attr("name", $($current_td).data("name") + $newid);
	                	c.appendTo($($td));
	                	$td.appendTo($($tr));
	            	} else {
	                	var $td = $("<td></td>", {
	                    	'text': $('#table-clients-send tr').length
	                	}).appendTo($($tr));
	            	}

	        	});

	        	// AGREGAMOS LA FILA NUEVA A LA TABAL
	        	$($tr).appendTo($('#table-clients-send'));
	        	//$tabaleClientsBox.row.add($(tr)).draw();
		        //CAMPOS NUMERICOS
				$('.input-numeric').numeric();

	        	$($tr).find("td button.row-remove").on("click", function() {
	        		//event.preventDefault();
	        		// Boton actual
	        		var $button_row_remove 		= $(this);
	        		// Opcion seleccionada del combo actual
	        		var $select_option_selected = $button_row_remove.closest('tr').eq(0).find('select option:selected').val();
	        		// Acoordion
	        		var $container_accordion	= $('#accordion');

	        		// Validamos si el combo esta o no vacio
	        		if ($select_option_selected != "") {
	        			alertify.confirm("Seguro de elimar este cliente?", function(e){
		        			if (e) {
		        				//ELIMINAMOS FILA ACTUAL
		        				$button_row_remove.closest("tr").remove();

		        				$.each($container_accordion.find('.panel'), function(index, el) {
		        					if ($(this).attr('data-parent') == $button_row_remove.closest('tr').attr('id')) {
		        						$(this).remove();
		        						sumNumberBox('table-clients-send', 'total-box');
		        					};
		        				});

		        				// AGIGNAMOS UN NUMERO A CADA PANEL
	        					changeColor();
		        			};
		        		});
	        		}else{
	        			$button_row_remove.closest("tr").remove();
	        			// AGIGNAMOS UN NUMERO A CADA PANEL
	        			changeColor();
	        		};
	        	});
        	}else{
		    	alertify.alert("Tienes que elegir un cliente antes de agregar otro.");
		    }
		    */
		});

		/*
			CHANGE EN LOS COMBOS DE CLIENTES
		*/
		$(document).on('change', '.select-clients', function(event) {
			event.preventDefault();

			//FUNCION PARA GENERAR UN PANEL NUEVO
			addNewPanel($(this));
			/*
			//Combo actual
			var $current_select 			= $(this);
			//alert($current_select.val());
			if ($current_select.val() != "") {

				if ($current_select.data("status") == "update") {

					// RECORREMOS EL #accordion
					$('#accordion .panel').each(function () {

						// VALIDAMOS LA RELACION
						if($(this).attr('data-parent') == $current_select.parents('tr').attr('id')){
							// Asignamos titulo al collapse
							$(this).find('.panel-heading h4.panel-title a').text('Clientes de: '+$current_select.find('option:selected').text());
						}

					});

				}else{
					// Accordion
					var $accordion = $('#accordion');
					// Collapse de clientes a entregar
					var $collapse_clients_delivery	= $('#collapse-clients-delivery');
					// Tablas totales
					var $tables_total 				= $accordion.find('.panel').length;
					//console.log($tables_total);
					// 	Agregar la nueva tabla
					//console.log($collapse_clients_delivery.clone().children());
					var $collapse_clone = $collapse_clients_delivery.clone().children().appendTo($accordion);
					// Asignamos un data paren al collapse
					$collapse_clone.attr('data-parent', $current_select.closest('tr').attr('id'));
					// Asignamos id al titulo del collapse
					$collapse_clone.find('.panel-heading').attr('id', 'heading'+$current_select.closest('tr').attr('id'));
					// Asignamos titulo al collapse
					$collapse_clone.find('.panel-heading h4.panel-title a').text('Clientes de: '+$current_select.find('option:selected').text());
					// Asignamos un id nuevo al collapse
					//$collapse_clone.attr('id', 'accordion'+$tables_total);
					// Asignamos data-parent al link del titulo del collapse
					$collapse_clone.find('.panel-heading h4.panel-title a').attr('data-parent', '#accordion');
					// Asignamos href al link del titulo del collapse
					$collapse_clone.find('.panel-heading h4.panel-title a').attr('href', '#collapse'+$current_select.closest('tr').attr('id'));
					// Asignamos id al .panel-collapse
					$collapse_clone.find('.panel-collapse').attr('id', 'collapse'+$current_select.closest('tr').attr('id'));
					// AGIGNAMOS UN NUMERO A CADA PANEL
		        	changeColor();
		        	//AUTOCOMPLETE INPUT
		        	$( ".tags" ).autocomplete({
		        		autoFocus: true,
		        		 minLength: 1,
				    	source: function( request, response ) {
        					$.ajax({
          						url: $basePath+"/in/journey/autocompleteclients",
          						type : "POST",
          						dataType: "json",
          						data: {
            						q: request.term
          						},
          						success: function( data ) {
          							//console.log(jQuery.isEmptyObject(data));
          							if(!jQuery.isEmptyObject(data)){
          								response($.map(data.data, function(item) {
            								return {
                                    			label: item.Nombre,
                                    			id: item.id_end_customers,
                                    		};
                            			}));
          							}
            						//response( data.data );
            						//response($.map(data.data, function(item) {
            						//	if($.isEmpyObject(data.data)){
            						//		return {
                                    //			label: item.Nombre,
                                    //			id: item.id_end_customers,
                                    //		};
            						//	}
                            		//}));
          						}
        					});
      					},
				    	//source : $basePath
				    });
				    //CAMPOS NUMERICOS
					$('.input-numeric').numeric();
				};

			}else{
				alertify.alert("Selecciona un cliente");
			};
			// ANADIMOS UN DATA-STATUS AL COMBO
			$current_select.attr('data-status', 'update');
			*/

		});


		/*
			CLICK EN EL BOTON AGREGAR FILA EN LA TABLA DE CLIENTES DE ENTREGA
		*/
		$(document).on('click', '.add_row_delivery', function(event) {
			event.preventDefault();
			// BOTON ACTUAL
			var $button_add_row_delivery = $(this);
			//alert($button_add_row_delivery.parents('table').attr('class'));
			// Get max row id and set new id
        	var $newid = 0;
        	// STATUS
        	var status = "";

        	$button_add_row_delivery.parents('table').find('tbody tr:last').each(function () {
				var client = $(this).find("td").eq(0).find("input").val();
				var folio  = $(this).find("td").eq(1).find("input").val();
				var box    = $(this).find("td").eq(2).find("input").val();

				if (client != "" && folio != "" && box != "") { status =1; }else{ status =0; }
			});

			if (status ==1) {
				$.each($button_add_row_delivery.parents('table').find('tr'), function() {
	            	if (parseInt($(this).data("id")) > $newid) {
	                	$newid = parseInt($(this).data("id"));
	            	}
	        	});
	        	$newid++;

	        	var $tr = $("<tr></tr>", {
	            	id: "addr"+$newid,
	            	"data-id": $newid
	        	});

	        	// loop through each td and create new elements with name of newid
	        	$.each($button_add_row_delivery.parents('table').find('tbody tr:eq(0) td'), function() {
	            	var $current_td  = $(this);
	            	//console.log($current_td);
	            	var $children_td = $current_td.children();

	            	// add new td and element if it has a nane
	            	if ($(this).data("name") != undefined) {
	                	var $td = $("<td></td>", {
	                    	"data-name": $($current_td).data("name")
	                	});

	                	var c = $($current_td).find($($children_td[0]).prop('tagName')).clone().val("");
	                	c.attr("name", $($current_td).data("name") + $newid);
	                	c.appendTo($($td));
	                	$td.appendTo($($tr));
	            	} else {
	                	var $td = $("<td></td>", {
	                    	'text': $button_add_row_delivery.parents('table').find('tbody tr').length
	                	}).appendTo($($tr));
	            	}
	        	});

	        	// AGREGAMOS LA FILA NUEVA A LA TABAL
	        	$($tr).appendTo($button_add_row_delivery.parents('table'));
	        	//$tableClientsBoxDelivery.row.add($(tr)).draw();
		        //CAMPOS NUMERICOS
				$('.input-numeric').numeric();
				//AUTOCOMPLETE INPUT
		        $( ".tags" ).autocomplete({
				    autoFocus: true,
				     minLength: 1,
				    source: function( request, response ) {
        				$.ajax({
          					url: $basePath+"/in/journey/autocompleteclients",
          					type : "POST",
          					dataType: "json",
          					data: {
            					q: request.term
          					},
          					success: function( data ) {
            					if(!jQuery.isEmptyObject(data)){
          							response($.map(data.data, function(item) {
            							return {
                                   			label: item.Nombre,
                                   			id: item.id_end_customers,
                                   		};
                            		}));
          						}
          					}
        				});
      				},
				});

	        	$($tr).find("td button.row-remove_delivery").on("click", function() {
	        		// Boton actual
		        	var $button_row_remove_delivery 	 = $(this);
		        	// Opcion seleccionada del combo actual
		        	var $select_option_selected_delivery = $button_row_remove_delivery.closest('tr').eq(0).find('input').val();
		        	// TOTAL DE CAJAS EN LA TABLA
		        	var $total_box = 0;

		        	$button_row_remove_delivery.closest('tr').addClass('no-seleccionar');

		        	// Validamos si el combo esta o no vacio
		        	if ($select_option_selected_delivery != ""){
		        		alertify.confirm("Seguro de elimar este cliente?", function(e){
			        		if (e) {
		            			// RECORREMOS LA TABLA Y OBTENEMOS EL VALOR EN LOS CAMPOS CAJAS
								$button_row_remove_delivery.parents('table').find('tbody tr:not(".no-seleccionar") td .sumbox').each(function(index, value) {
									// VALIDAMOS EL VALOR DE LOS CAMPOS CAJAS
									var num 	= (value.value=="") ? 0 : value.value;
									// SUMAMOS EL TOTAL DE CAJAS
									$total_box += parseInt(num);
								});

								// ASIGNMOS EL TOTAL DE CAJAS EN LA TABLA AL SPAN TOTAL
								var c = $button_row_remove_delivery.parents('table').find('tfoot tr th:eq(2) span').text(parseInt($total_box));

								// RECORREMOS LA TABLA DE CLIENTES
								$('#table-clients-send tbody tr').each(function () {
									// VALIDAMOS LA RELACION ENTRE TABLAS
									if($(this).attr('id') == $button_row_remove_delivery.parents('div.panel').attr('data-parent')){
										// ASIGNAMOS VALOR AL INPUT CAJAS
										$(this).find('td:eq(1) input[type=text]').val(c.text());
										// SUMAMOS TODOS LOS CAMPOS CAJAS DE LA TABLA
										sumNumberBox('table-clients-send', 'total-box');
									}
								});

								// BORRAMOS LA FILA ACTUAL
		            			$button_row_remove_delivery.closest("tr").remove();
			        		}else{
			        			$button_row_remove_delivery.closest('tr').removeClass('no-seleccionar');
			        		};
			        	});
		        	}else{
		        		// BORRAMOS LA FILA ACTUAL
		            	$button_row_remove_delivery.closest("tr").remove();
		        	};
		            //$tableClientsBoxDelivery.row( $(this).parents('tr') ).remove().draw();
		        });
			}else{
		    	alertify.alert("Ingresa todos los datos para poder agregar nuevas filas.");
		    };
		});

		/*
			AL ESCRIBIR UNA CANTIDAD SUMA Y LO MUESTRA EN LA TABLA
		*/
		$(document).on("keyup", '.sumbox', function(){
			var $current_box 	= $(this);
			var $total_box 		= 0;

			$current_box.parents('table').find('tbody tr td .sumbox').each(function(index, value) {
				var num 	= (value.value=="") ? 0 : value.value;
				$total_box += parseInt(num);
			});

			var c = $current_box.parents('table').find('tfoot tr th:eq(2) span').text(parseInt($total_box));

			$('#table-clients-send tbody tr').each(function () {
				if($(this).attr('id') == $current_box.parents('div.panel').attr('data-parent')){
					$(this).find('td:eq(1) input[type=text]').val(c.text());
					sumNumberBox('table-clients-send', 'total-box');
				}
			});

		});

	}); /* end document ready*/
})(jQuery);

/*
	FUNCION PARA SUMAR EL NUMERO DE CAJAS
*/
function sumNumberBox(tabla, total) {
	var $total_box = 0
	$("#"+tabla+" tbody tr td .sumbox").each(
		function(index, value) {
			var num = (value.value=="") ? 0 : value.value;
			$total_box += parseInt(num);
		}
	);
	$("#"+total).text(parseInt($total_box));
}

/*
	CAMBIAR COLOR PAR IMPAR
*/
function changeColor()
{
	$('#accordion .panel:not(:first)').each(function (index, el) {
    	console.log(index);
		if ((index+1) % 2 !== 0) {
        	// pintamos el elemento par
        	//$(this).css("background-color", "#c8c");
        	$(this).removeClass('panel-primary');
			$(this).addClass('panel-warning');
      	}
      	else {
      		$(this).removeClass('panel-warning');
			$(this).addClass('panel-primary');
        	// pintamos el elemento impar
        	//$(this).css("background-color", "#acf");
      	}
		//$(this).filter(":odd").css("background-color", "#c8c");
    	//$(this).filter(":even").css("background-color", "#acf");
	});
}

/*
	FUNCION PAR AGENERAR NUEVAS FILAS
*/
function addRowTable()
{
			// Get max row id and set new id
        	var $newid = 0;
        	var status = "";

        	// RECORREMOS LA TABLA DE CLIENTES
			$('#table-clients-send tbody tr:last').each(function () {
				//VALIDAMOS SI ES LA PRIMERA FILA Y SU ID
				if($(this).attr('id') != 'addr0'){
					var client = $(this).find("td").eq(0).find("select option:selected").val();
					if (client != "") { status = 1; }else{ status = 0; }
				}else{
					status = 1;
				}
			});

        	if(status == 1){
				$.each($("#table-clients-send tbody tr"), function() {
	            	if (parseInt($(this).data("id")) > $newid) {
	                	$newid = parseInt($(this).data("id"));
	                	//console.log("id"+$newid);
	            	}
	        	});
	        	$newid++;
	        	//console.log($newid);

	        	var $tr = $("<tr></tr>", {
	            	id: "addr"+$newid,
	            	"data-id": $newid
	        	});

	        	// loop through each td and create new elements with name of newid
	        	$.each($("#table-clients-send tbody tr:nth(0) td"), function() {
	            	var $current_td  = $(this);
	            	//console.log($current_td.eq(0).find('select').attr('disabled', false));
	            	//$current_td.eq(0).find('select').attr('disabled', false);
	            	var $children_td = $current_td.children();
	            	//console.log($current_td.children('slect'));
	            	// add new td and element if it has a nane
	            	if ($(this).data("name") != undefined) {
	                	var $td = $("<td></td>", {
	                    	"data-name": $($current_td).data("name")
	                	});
	                	//$children_td[0].attr('disabled', false);
	                	var c = $($current_td).find($($children_td[0]).prop('tagName')).clone().val("");
	                	c.attr("name", $($current_td).data("name") + $newid);
	                	c.appendTo($($td));
	                	$td.appendTo($($tr));
	            	} else {
	                	var $td = $("<td></td>", {
	                    	'text': $('#table-clients-send tr').length
	                	}).appendTo($($tr));
	            	}

	        	});

	        	// AGREGAMOS LA FILA NUEVA A LA TABAL
	        	$($tr).appendTo($('#table-clients-send'));
	        	// HABILITAMOS EL COMBO SELECT DE CLIENTES
	        	$($tr).eq(0).find('select').attr('disabled', false);
	        	//$tabaleClientsBox.row.add($(tr)).draw();
		        //CAMPOS NUMERICOS
				$('.input-numeric').numeric();

	        	$($tr).find("td button.row-remove").on("click", function() {
	        		//event.preventDefault();
	        		// Boton actual
	        		var $button_row_remove 		= $(this);
	        		// Opcion seleccionada del combo actual
	        		var $select_option_selected = $button_row_remove.closest('tr').eq(0).find('select option:selected').val();
	        		// Acoordion
	        		var $container_accordion	= $('#accordion');

	        		// Validamos si el combo esta o no vacio
	        		if ($select_option_selected != "") {
	        			alertify.confirm("Seguro de elimar este cliente?", function(e){
		        			if (e) {
		        				//ELIMINAMOS FILA ACTUAL
		        				$button_row_remove.closest("tr").remove();

		        				$.each($container_accordion.find('.panel'), function(index, el) {
		        					if ($(this).attr('data-parent') == $button_row_remove.closest('tr').attr('id')) {
		        						$(this).remove();
		        						sumNumberBox('table-clients-send', 'total-box');
		        					};
		        				});

		        				// AGIGNAMOS UN NUMERO A CADA PANEL
	        					changeColor();
		        			};
		        		});
	        		}else{
	        			$button_row_remove.closest("tr").remove();
	        			// AGIGNAMOS UN NUMERO A CADA PANEL
	        			changeColor();
	        		};
	        	});
        	}else{
		    	alertify.alert("Tienes que elegir un cliente antes de agregar otro.");
		    }
}

/*
	FUNCION PARA GENERAR PANELES NUEVOS
*/
function addNewPanel($comboSelect)
{
	//Combo actual
	var $current_select = $comboSelect;
	//alert($current_select.val());
	if ($current_select.val() != "") {

		if ($current_select.data("status") == "update") {

			// RECORREMOS EL #accordion
			$('#accordion .panel').each(function () {

				// VALIDAMOS LA RELACION
				if($(this).attr('data-parent') == $current_select.parents('tr').attr('id')){
					// Asignamos titulo al collapse
					$(this).find('.panel-heading h4.panel-title a').text('Clientes de: '+$current_select.find('option:selected').text());
				}

			});

		}else{
			// Accordion
			var $accordion = $('#accordion');
			// Collapse de clientes a entregar
			var $collapse_clients_delivery	= $('#collapse-clients-delivery');
			// Tablas totales
			var $tables_total 				= $accordion.find('.panel').length;
			//console.log($tables_total);
			// 	Agregar la nueva tabla
			//console.log($collapse_clients_delivery.clone().children());
			var $collapse_clone = $collapse_clients_delivery.clone().children().appendTo($accordion);
			//var $collapse_clone = $accordion.clone().children().appendTo($accordion);
			// Asignamos un data paren al collapse
			$collapse_clone.attr('data-parent', $current_select.closest('tr').attr('id'));
			// Asignamos id al titulo del collapse
			$collapse_clone.find('.panel-heading').attr('id', 'heading'+$current_select.closest('tr').attr('id'));
			// Asignamos titulo al collapse
			$collapse_clone.find('.panel-heading h4.panel-title a').text('Clientes de: '+$current_select.find('option:selected').text());
			// Asignamos un id nuevo al collapse
			//$collapse_clone.attr('id', 'accordion'+$tables_total);
			// Asignamos data-parent al link del titulo del collapse
			$collapse_clone.find('.panel-heading h4.panel-title a').attr('data-parent', '#accordion');
			// Asignamos href al link del titulo del collapse
			$collapse_clone.find('.panel-heading h4.panel-title a').attr('href', '#collapse'+$current_select.closest('tr').attr('id'));
			// Asignamos id al .panel-collapse
			$collapse_clone.find('.panel-collapse').attr('id', 'collapse'+$current_select.closest('tr').attr('id'));
			// AGIGNAMOS UN NUMERO A CADA PANEL
		    changeColor();
		    //AUTOCOMPLETE INPUT
		    $( ".tags" ).autocomplete({
		    	autoFocus: true,
		    	minLength: 1,
		    	source: function( request, response ) {
        			$.ajax({
        				url: $basePath+"/in/journey/autocompleteclients",
        				type : "POST",
          				dataType: "json",
          				data: {
            				q: request.term
          				},
          				success: function( data ) {
          					//console.log(jQuery.isEmptyObject(data));
          					if(!jQuery.isEmptyObject(data)){
          						response($.map(data.data, function(item) {
            						return {
                               			label: item.Nombre,
                               			id: item.id_end_customers,
                               		};
                   				}));
          					}
            						//response( data.data );
            						//response($.map(data.data, function(item) {
            						//	if($.isEmpyObject(data.data)){
            						//		return {
                                    //			label: item.Nombre,
                                    //			id: item.id_end_customers,
                                    //		};
            						//	}
                            		//}));
          				}
        			});
      			},
				//source : $basePath
			});
			//CAMPOS NUMERICOS
			$('.input-numeric').numeric();
		};

	}else{
		alertify.alert("Selecciona un cliente");
	};
	// ANADIMOS UN DATA-STATUS AL COMBO
	$current_select.attr('data-status', 'update');

	//CERRAR TODOS LOS PANELES MENOS EL ULTIMO
	/*$('#accordion .panel:last').each(function (index, el) {
		//$(this).find('.panel-collapse').collapse('show');
		$(this).collapse('show');
		//if (active) $('#accordion .in').collapse('hide');
		//:not(:last)
		console.log(this);
		//$(this).find('.panel-collapse').addClass('in');
		//$(this).find('.panel-collapse').collapse('hide');
		//$(this).find('.in').collapse('hide');
	});*/
	/*
	$('.closeall').click(function(){
	  $('.panel-collapse.in')
	    .collapse('hide');
	});
	$('.openall').click(function(){
	  $('.panel-collapse:not(".in")')
	    .collapse('show');
	});
	*/
	$('#accordion').each(function (index, el) {
		//$(this).find('.collapse:not(:last)').slideUp('slow');
		$(this).find('.collapse:not(:last)').removeClass('in');
		//if($(this).find('.collapse:last')){
			//$(this).find('.collapse').collapse('show');
		//}else{
			//$(this).find('.collapse').collapse('hide');
		//}
	});
	//$('.panel-collapse:not(:last)').collapse('hide');
	//$('.panel-collapse.in').collapse('hide');
	//$('.panel-collapse:not(".in")').collapse('show');
}