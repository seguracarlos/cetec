$(document).on("ready", function(){

	$('[data-tooltip="tooltip"]').tooltip();
	$('#listpayroll').DataTable({
		"aoColumnDefs" : [ {
	    	'bSortable' : false,
	        'aTargets' : [ 0, 9, 10 ]
		} ],
		"order": [[ 3, "asc" ]]
	});
	$('[data-toggle=tooltip]').tooltip();

	/*
		CLICK EN EL BOTON DETALLE DE PAGO
	 */
	$("#modalDetail").on('show.bs.modal', function(event){
    	// Obtenemos el boton que activo el modal
    	var $button   = $(event.relatedTarget);
    	// Obtener el data-user del boton
    	var $id_user = $button.data('user');
    	// Obtener el data-namempl
    	var $name_empl = $button.data('namempl');
    	// Asignamos titulo de la ventana modal
    	$(this).find('.modal-title').text('DETALLE DE PAGO: "' + $name_empl + '"');
		// Funcion cargar detalle
    	getDetailPayrollByUser($(this), $id_user);
	});

	/*
		CLICK EN EL BOTON BONOS
	 */
	$("#modalBonus").on('show.bs.modal', function(event){
		console.log("Mostrando modal bonos");
    	// Obtenemos el boton que activo el modal
    	var $button   = $(event.relatedTarget);
    	//console.log($button);
    	// Obtener el data-user del boton
    	var $id_user = $button.data('user');
    	//console.log($id_user);
		// Funcion cargar detalle
    	getBonusPayrollByUser($(this), $button, $id_user);
	});

	/*
		CERRAR MODAL DE APLICAR BONOS
	*/
	$("#modalBonus").on('hidden.bs.modal', function(event){
    	// Obtenemos el boton que activo el modal
    	var $button   = $(event.relatedTarget);
    	$button.blur();
	});

	/*
		CLICK EN LOS CHECKBOX DE LA TABLA DE BONOS
	*/
	$(document).on("click", ".check-bonus", function(){
		//variable para contar
		var cont = 0;
		//Cantidad de checkbox dentro de un elemento
		var cantidad_checkbox = $('body #table-bonus-applicable tbody tr td input[type=checkbox]').length;
		var $span_total_amount      = $.trim($("#span_total_amount").formatCurrency().clone().toNumber().text());
		var $amount_bono_selected   = $(this).parents("tr").find('td:eq(1) span').formatCurrency().clone().toNumber().text();
		var $suma_total_amount      = 0;

		// Validamos si solo hay un checkbox en la tabla y el contador es 0
		if(cont == 0){
			//Desactivamos el checkbox
			$("#checkall-bonus").prop("checked", false);//Desmarcar el checkbox de todos
		}

		//Recorremos los checkbox seleccionados
		$("body #table-bonus-applicable tbody tr td input[type=checkbox]:checked").each(function(index, val){
			//Verificamos si esta activado
			if ($(this).is(":checked")){
				//Incrementamos contador
				cont = index+1;
				//Validamos si todos estan seleccionados o no
				if (cont >= cantidad_checkbox){
					//Activanos el checkbox
					$("#checkall-bonus").prop("checked", true);
				}else{
					//Desactivamos el checkbox
					$("#checkall-bonus").prop("checked", false);//Desmarcar el checkbox de todos
				}
			}
		});

		console.log("Contador: "+cont);
		console.log("Cantidad check: "+cantidad_checkbox);

		// Validamos si esta seleccionado un elemento
		if($(this).is(":checked")){
			//Aplicar estilo de tache al texto de todas las td
			$(this).parents('tr').find('td').css({"text-decoration" : "line-through", "color" : "rgb(128, 144, 160)" });
			//Deshabilitar boton de borrar bono.
			$(this).parents('tr').find('td:eq(5) a').attr("disabled", true);
			//Suma del total a pagar mas el bono seleccionado
			$suma_total_amount = parseFloat($span_total_amount) + parseFloat($amount_bono_selected);
			//Cambios el texto de total a pagar
			$("#span_total_amount").text($suma_total_amount).formatCurrency();
			//$("#listpayroll tbody tr#user2").find('td:eq(9) a').text($suma_total_amount).formatCurrency();
		}else{
			//Aplicar estilo de tache al texto de todas las td
			$(this).parents('tr').find('td').css({"text-decoration" : "none", "color" : "#333" });
			//Deshabilitar boton de borrar bono.
			$(this).parents('tr').find('td:eq(5) a').attr("disabled", false);
			$suma_total_amount = parseFloat($span_total_amount) - parseFloat($amount_bono_selected);
			$("#span_total_amount").text($suma_total_amount).formatCurrency();
			//$("#listpayroll tbody tr#user2").find('td:eq(9) a').text($suma_total_amount).formatCurrency();
		}

	});

	/*
		CLICK EN EL BOTON APLICAR BONOS
	*/
	$(document).on("click", "#aplicateBonus", function(){
		var $span_total_amount      = $.trim($("#span_total_amount").formatCurrency().clone().toNumber().text());
		var $array            = [];
		var $totalAmount      = 0;
		var $totalAmountFinal = 0;

		// Recorremos los checkbox de la tabla para saber su valor
		$(".check-bonus").each(function(index){
		//console.log( index + ": " + $( this ).val() );
			if($(this).is(":checked")){
				$totalAmount += parseFloat($(this).parents("tr").find('td:eq(1) span').formatCurrency().clone().toNumber().text());
				//$array.push($(this).val());
				$array.push({
					id   : $(this).val(),
					type : "BONO",
					date : $date,
		    	});
			}
		});

		if($totalAmount != 0){
			alertify.confirm("Seguro de aplicar?", function(e){
				if (e) {

					$("#modalBonus").find(".modal-dialog").plainOverlay('show');

					$.ajax({
						method   : "POST",
						url      : $basePath + "/horus/payroll/amountuserpayroll",
						dataType : "json",
						data     : {"bonos" : $array}
					})
					.done(function(response) {
						$("#modalBonus").find(".modal-dialog").plainOverlay('hide');
						alertify.set({ delay: 1000 });
  						alertify.success("Bono Agregado");
  							setTimeout(function(){
								$("#modalBonus").modal('hide');
						}, 2000);
						/*.on('plainoverlayhide', function(event) {
							alertify.set({ delay: 1000 });
  							alertify.success("Bono Agregado");
  								setTimeout(function(){
									$("#modalBonus").modal('hide');
							}, 2000);
						});*/
					})
					.fail(function() { console.log( "error" ); })
					.always(function() { console.log( "complete" ); });

					// Id del usuario
					var $id_user_amount = $("#input_user_amount").val();
					// Asignamos el nuevo valor al total a pagar
					$("#listpayroll tbody tr#user"+$id_user_amount).find('td:eq(7) a').text($span_total_amount).formatCurrency();
					$("#listpayroll tbody tr#user"+$id_user_amount).find('td:eq(7) a').data('total', $span_total_amount);
					// Obtemos el total de bonos
					var $percepciones      = $.trim($("#listpayroll tbody tr#user"+$id_user_amount).find('td:eq(5)').formatCurrency().clone().toNumber().text());
					// Actualizamos el total de bonos
					var $totalPercepciones = parseFloat($percepciones) - parseFloat($totalAmount);
					// Asignamos el nuevo valor de los bonos a la tabla
					$("#listpayroll tbody tr#user"+$id_user_amount).find('td:eq(5)').text($totalPercepciones).formatCurrency();
					console.log($totalPercepciones);
				};
			});
		}else{
			alertify.alert("No hay bonos que aplicar. Selecciona almenos uno");
		}
	});

	/*
		CLICK EN EL BOTON DESCUENTOS
	 */
	$("#modalDiscount").on('show.bs.modal', function(event){
		console.log("Mostrando modal descuentos");
    	// Obtenemos el boton que activo el modal
    	var $button   = $(event.relatedTarget);
    	//console.log($button);
    	// Obtener el data-user del boton
    	var $id_user = $button.data('user');
    	//console.log($id_user);
		// Funcion cargar detalle
    	getDiscountPayrollByUser($(this), $button, $id_user);
	});

	/*
		CLICK EN LOS CHECKBOX DE LA TABLA DE DESCUENTOS
	*/
	$(document).on("click", ".check-discount", function(){
		//variable para contar
		var cont = 0;
		//Cantidad de checkbox dentro de un elemento
		var cantidad_checkbox = $('body #table-discounts-applicable tbody tr td input[type=checkbox]').length;
		var $span_total_amount    = $.trim($("#span_total_amount2").formatCurrency().clone().toNumber().text());
		var $amount_bono_selected = $(this).parents("tr").find('td:eq(1) span').formatCurrency().clone().toNumber().text();
		var $suma_total_amount    = 0;

		// Validamos si solo hay un checkbox en la tabla y el contador es 0
		if(cont == 0){
			//Desactivamos el checkbox seleccionar todos
			$("#checkall-discounts").prop("checked", false);//Desmarcar el checkbox de todos
		}

		//Recorremos los checkbox de la tabla
		$("body #table-discounts-applicable tbody tr td input[type=checkbox]:checked").each(function(index, val){
			//Verificamos si esta activado
			if ($(this).is(":checked")){
				//Incrementamos contador
				cont = index+1;
				//Validamos si todos estan seleccionados o no
				if (cont >= cantidad_checkbox){
					//Activanos el checkbox
					$("#checkall-discounts").prop("checked", true);
				}else{
					//Desactivamos el checkbox
					$("#checkall-discounts").prop("checked", false);//Desmarcar el checkbox de todos
				}
			}
		});

		console.log("Contador: "+cont);
		console.log("Cantidad check: "+cantidad_checkbox);

		//if (parseFloat($span_total_amount) < parseFloat($amount_bono_selected))

		if($(this).is(":checked")){
			//Total a pagar menos descuento seleccionado
			$suma_total_amount = parseFloat($span_total_amount) - parseFloat($amount_bono_selected);

			//Comprobar si es un numero positivo o no
			if($suma_total_amount > 0){
				console.log("Es correcto");
				$(this).parents('tr').find('td').css({"text-decoration" : "line-through", "color" : "rgb(128, 144, 160)" });
				//Deshabilitar boton de borrar descuento.
				$(this).parents('tr').find('td:eq(5) a').attr("disabled", true);

				$("#span_total_amount2").text($suma_total_amount).formatCurrency();
			}else{
				console.log("In correcto");
				console.log("CONTADOR: "+cont);
				//Desactivamos el checkbox
				$("#checkall-discounts").prop("checked", false);//Desmarcar el checkbox de todos
				alertify.alert("No puedes aplicar el descuento.");
				return false;
				//$(this).prop("checked", false);
			}
		}else{
			$(this).parents('tr').find('td').css({"text-decoration" : "none", "color" : "#333" });
			//Habilitar boton de borrar descuento.
			$(this).parents('tr').find('td:eq(5) a').attr("disabled", false);
			$suma_total_amount = parseFloat($span_total_amount) + parseFloat($amount_bono_selected);
			$("#span_total_amount2").text($suma_total_amount).formatCurrency();
		}
		console.log($suma_total_amount);
	});

	/*
		CLICK EN EL BOTON APLICAR DESCUENTOS
	*/
	$(document).on("click", "#aplicateDiscount", function(){
		var $span_total_amount      = $.trim($("#span_total_amount2").formatCurrency().clone().toNumber().text());
		var $array            = [];
		var $totalAmount      = 0;
		var $totalAmountFinal = 0;

		// Recorremos los checkbox de la tabla para saber su valor
		$(".check-discount").each(function(index){
		//console.log( index + ": " + $( this ).val() );
			if($(this).is(":checked")){
				$totalAmount += parseFloat($(this).parents("tr").find('td:eq(1) span').formatCurrency().clone().toNumber().text());
				//$array.push($(this).val());
				$array.push({
					id   : $(this).val(),
					type : "DESCUENTO",
					date : $date,
		    	});
			}
		});

		if($totalAmount != 0){
			alertify.confirm("Seguro de aplicar?", function(e){

				if (e) {

					$("#modalDiscount").find(".modal-dialog").plainOverlay('show');

					$.ajax({
						method   : "POST",
						url      : $basePath + "/horus/payroll/amountuserpayroll",
						dataType : "json",
						data     : {"bonos" : $array}
					})
					.done(function(response) {
						$("#modalDiscount").find(".modal-dialog").plainOverlay('hide');
						alertify.set({ delay: 1000 });
  						alertify.success("Descuento(s) Agregado");
  							setTimeout(function(){
								$("#modalDiscount").modal('hide');
						}, 2000);
						/*.on('plainoverlayhide', function(event) {
							alertify.set({ delay: 1000 });
  							alertify.success("Descuento(s) Agregado");
  								setTimeout(function(){
									$("#modalDiscount").modal('hide');
							}, 2000);
						});*/
					})
					.fail(function() { console.log( "error" ); })
					.always(function() { console.log( "complete" ); });
					//var filaActual = $("#listpayroll tbody tr#user2").find('td:eq(9) a').text();
					//console.log("Total Bonos: "+$totalAmount);
					//Suma Final
					//$totalAmountFinal
					var $id_user_amount = $("#input_user_amount2").val();
					//var percepciones = $("#listpayroll tbody tr#user"+$id_user_amount).find('td:eq(8) a').formatCurrency().clone().toNumber().text();
					//console.log(percepciones);
					//var sumita = parseFloat(percepciones)-parseFloat($span_total_amount);
					//$("#listpayroll tbody tr#user"+$id_user_amount).find('td:eq(8) a').text(percepciones).formatCurrency();
					$("#listpayroll tbody tr#user"+$id_user_amount).find('td:eq(7) a').text($span_total_amount).formatCurrency();
					$("#listpayroll tbody tr#user"+$id_user_amount).find('td:eq(7) a').data('total', $span_total_amount);

					var $deducciones      = $.trim($("#listpayroll tbody tr#user"+$id_user_amount).find('td:eq(6)').formatCurrency().clone().toNumber().text());
					var $totalDeducciones = parseFloat($deducciones) - parseFloat($totalAmount);
					$("#listpayroll tbody tr#user"+$id_user_amount).find('td:eq(6)').text($totalDeducciones).formatCurrency();

					//console.log($totalAmount);
				};
			});
		}else{
			alertify.alert("No hay descuentos que aplicar. Selecciona almenos uno");
		}
	});

	/*
		CLICK EN EL CHECKBOX SELECCIONAR TODOS
	 */
	$(document).on("click", "#checkall", function () {
		var rows = $("#listpayroll").dataTable().fnGetNodes();

        if ($("#checkall").is(':checked')) {
        	$.each(rows, function(index, val) {
        		$(this).find("td:eq(0) input[type=checkbox]").prop("checked", true);
        	});
        } else {
        	$.each(rows, function(index, val) {
        		$(this).find("td:eq(0) input[type=checkbox]").prop("checked", false);
        	});
        }
    });

	/*
		DESMARCAR EL CHECKBOX (TODOS) SI OTRO SE SELECCIONO INDIVIDUALMENTE
	 */
    $(document).on("click", ".payCheck", function(){
    	//$(oTable.fnGetNodes()).find("input[type='checkbox' name='caseConsent']:checked ...
    	var cont  = 0;
    	var table = $('#listpayroll').DataTable();
    	var filas = table.rows().nodes();
    	var totalCheckbox = $(filas).find('input[type=checkbox]').length;

    	$(filas).find('input[type=checkbox]:checked').each(function(index, val){
			//Verificamos si esta activado
			if ($(this).is(":checked")){
				//Incrementamos contador
				cont = index+1;
				//Validamos si todos estan seleccionados o no
				if (cont >= totalCheckbox){
					//Activanos el checkbox
					$("#checkall").prop("checked", true);
				}else{
					//Desactivamos el checkbox
					$("#checkall").prop("checked", false);//Desmarcar el checkbox de todos
				}
			}
		});

    	console.log(totalCheckbox);
    	console.log(cont);
    });

    /*
	 	CLICK EN EL BOTON PAGAR NOMINA
     */
    $("#addPayRoll").on("click", addPayRoll);

    /*
		CLICK EN EL BOTON AGREGAR PRESTAMO
     */
    $(document).on("click", ".buttonAddLoan", function(){
    	$("#modalLoans").modal("show"); //Mostramos modal
    });

    // Funciones de modales
    $('#modalLoans').on('show.bs.modal', function(e){ //Mostrar modal
    	console.log("Me mostre");
		$(this).find(".modal-body").slideDown().html('<center><img style="" src="'+$basePath+'/public/img/gif-load.gif"> cargando...</center>');
    }).on('shown.bs.modal', function(e){ //Terminar de mostrar modal
    	console.log("Me termine de mostre");
    	$.ajax({
			method   : "GET",
			url      : $basePath + "/horus/payroll/addloan",
			data     : $("#formLoans").serializeArray()
		})
		.done(function(response) {
			$("#modalLoans").find(".modal-body").html(response);
			console.log( "done" );
		})
		.fail(function() { console.log( "error" ); })
		.always(function() { console.log( "complete" ); });
    });

    // Click en el boton guardar prestamo
    $(document).on("click", ".saveLoans", function(){

        alertify.confirm("Seguro de agregar el prestamo?", function(e){
            if(e){
            	$.ajax({
        			method   : "POST",
        			url      : $basePath + "/horus/payroll/addloan",
        			dataType : "json",
        			data     : $("#formLoans").serializeArray()
        		})
        		.done(function(response) {
        			$('#modalLoans').modal('hide');
        			alertify.success("Prestamo agregado");
        			console.log( "success" );
        		})
        		.fail(function() { console.log( "error" ); })
        		.always(function() { console.log( "complete" ); });
            }
        });

    });

});  //END DOCUMENT READY

/*
	FUNCION PAGAR NOMINA
 */

function addPayRoll()
{
	var rowsAll = $("#listpayroll").dataTable().fnGetNodes();
	//console.log(rowsAll);
	//var $valuePayRoll = "";
	var $array   = [];
	//var postData = new Object();
	//alert("Pagar Nomina semanal");
	// Recorremos los checkbox seleccionados de la tabla para saber su valor
	$.each(rowsAll, function(index, val) {
		var $imputChecked = $(this).find("td:eq(0) input[type=checkbox]");
		var $inputAmount  = $.trim($(this).find("td:eq(7) a").text());
		if($imputChecked.is(":checked")){
			//$valuePayRoll = $( this ).val() + "_" + $valuePayRoll;
			//$valuePayRoll += $(this).val() + ",";
			//$array.push($imputChecked.val());
			$array.push({
				id     : $imputChecked.val(),
				amount : $inputAmount
		    });
		}
	});

	//console.log($array);
	//var $idUsers = $array.join(", ");
	var $idUsers = $array;
	console.log($idUsers);

	// Validamos si hay checkbox seleccionados
	if($idUsers == null || $idUsers == ""){
		alertify.alert("No se han seleccionado empleados para pagar.");
	}else{
		alertify.confirm("\u00BFEl pago se har\u00e1 para todos lo usuarios seleccionados \n \u00BFEst\u00e1s seguro de pagar ahora? ", function (e) {
		    if (e) {

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

				$.ajax({
					method   : "POST",
					url      : $basePath + "/horus/payroll/addpayroll",
					dataType : "json",
					data     : {idUsers : $array, type : $type, date : $date}
				})
				.done(function(response) {
					console.log( "success" );
					// Ocultamos el fondo
					//$.unblockUI();
					if(response.response == "ok"){
						alertify.set({ delay: 3000 });
						alertify.success("Haz realizado el pago de n\u00f3mina. A un total de "+ response.totalEmployee);
						//Esperamos 3 segundos
						setTimeout(function() {
							// Recargo la página
            				location.reload();
						}, 3000);
					}
				})
				.fail(function() { console.log( "error" ); })
				.always(function() { console.log( "complete" ); });
		    }
		});
		//postData.idUsers = $valuePayRoll;
	}
	//console.log($valuePayRoll);
	//console.log(postData);
}


/*
	FUNCION PARA GENBERAR EL DETALLE DE LA NOMINA POR USUARIO
 */
 function getDetailPayrollByUser(modal, id)
 {
 	$detalle       = "";
 	var meses      = new Array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
	var diasSemana = new Array("Domingo","Lunes","Martes","Mi\u00e9rcoles","Jueves","Viernes","S\u00e1bado");
	// MOSTRAR IMAGEN DE CARGANDO
	var $html = '<center><img style="" src="'+$basePath+'/public/img/gif-load.gif"> cargando...</center>';
	modal.find(".modal-body").html($html);

 	$.ajax({
 		url      : $basePath + "/horus/payroll/detailuserpayroll",
		method   : "POST",
		dataType : "json",
		data     : {id_employee : id, type : $type, date : $date},
 	})
 	.done(function(response) {
 		console.log("success");
 		//var $obj = jQuery.parseJSON(response);

 		$detalle += '<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">';

	  	$detalle +=		'<div class="panel panel-warning">'
	    		 +			'<div class="panel-heading" role="tab" id="headingOne">'
	     		 +				' <h4 class="panel-title">'
	    		 +					'<a class="collapsed font-raleway" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">Viajes</a>'
	    		 +					'<i class="pull-right glyphicon glyphicon-folder-close"></i>'
	    		 +				'</h4>'
	    		 +			'</div>'
	    		 + 			'<div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">'
	    		 +				'<div class="panel-body">';

	    				$detalle += '<div class="container-fluid">';
	    				if (!jQuery.isEmptyObject(response.data.shippings)) {

	    					$.each(response.data.shippings, function(index,obj){

								var $dateFormat   = obj.date_shipping.split("-");
								var $dateShipping = new Date($dateFormat[1]+"/"+$dateFormat[2]+"/"+$dateFormat[0]);
								var $dateFull     = diasSemana[$dateShipping.getDay()] + ", " + $dateShipping.getDate() + " de " + meses[$dateShipping.getMonth()] + " de " + $dateShipping.getFullYear();

								$detalle 	+= 	'<div class="row">'
								 		  	+ 		'<div class="col-md-12">'
										  	+ 			'<h3 class="font-lato text-info">'+$dateFull+'</h3>'
											+           '<p class="text-muted font-lato">Viaje #: '+obj.id_shipping+'</p>'
											+			'<p class="text-muted font-lato">Tipo: '+((obj.type_destination == 1) ? "Local" : "Foraneo")+'</p>'
											+			'<p class="text-muted font-lato">Directo / Ruta: '+((obj.direct_route == 1) ? "Directo" : "Ruta")+'</p>'
											+           '<p class="text-muted font-lato">Destino: '+obj.name_destination+'</p>'
											+           '<p class="text-muted font-lato">Descripci&oacute;n: '+obj.description_destination+'</p>'
											+			'<div class="row">';

								$.each(obj.detailShippings, function(index2, val) {
									if (val.type_user == 1) {

										$detalle += '<div class="col-md-6">'
												 +		'<div class="card">'
												 +  		'<label style="padding:0;" class="label-checkbox text-muted font-lato" for="'+(obj.id_shipping+val.type_user)+'">Operador: <input type="checkbox" value="'+(obj.id_shipping+val.type_user)+'" id="'+(obj.id_shipping+val.type_user)+'" checked="checked" disabled="disabled" /><i></i></label>'
												 +  		'<p class="text-muted font-lato">Monto: <span class="numerito">'+val.amount+'</span></p>'
												 +		'</div>'
												 +	'</div>';

									}else if(val.type_user == 2){

										$detalle += '<div class="col-md-6">'
												 +		'<div class="card">'
												 +  		'<label style="padding:0;" class="label-checkbox text-muted font-lato" for="'+(obj.id_shipping+val.type_user)+'">Ayudante: <input type="checkbox" value="'+(obj.id_shipping+val.type_user)+'" id="'+(obj.id_shipping+val.type_user)+'" checked="checked" disabled="disabled" /><i></i></label>'
												 +  		'<p class="text-muted font-lato">Monto: <span class="numerito">'+val.amount+'</span></p>'
												 +		'</div>'
												 +	'</div>';

									};
								});

								$detalle    += 			'</div><hr/>'
											+   	'</div>'
										  	+ 	'</div>';

							});

	    				}else{
	    					$detalle += '<div style="margin-bottom:0;" class="alert alert-danger" role="alert"><strong>No hay viajes realizados</strong></div>';
	    				};


						$detalle += '</div>'; //Fin .container-fluid

	    $detalle +=   			'</div>' //Fin .panel-body
	    		 + 			'</div>' //Fin #collapseOne
	  			 + 		'</div>'; //Fin primer panel

	  	$detalle +=		'<div class="panel panel-warning">'
	    		 + 			'<div class="panel-heading" role="tab" id="headingTwo">'
	    		 +				'<h4 class="panel-title">'
	    		 +					'<a class="collapsed font-raleway" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">Bonos</a>'
	    		 +					'<i class="pull-right glyphicon glyphicon-folder-close"></i>'
	    		 +				'</h4>'
	    		 +			'</div>'
	    		 +			'<div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">'
	    		 +				'<div class="panel-body">';

	    		 				$detalle += '<div class="container-fluid">'
	    		 						 + 		'<div class="row">'
	    		 						 + 			'<div class="col-md-12">';


	    		 				if (!jQuery.isEmptyObject(response.data.bonus)) {
	    		 						$detalle +=		'<table class="table">'
	    		 						 		 +			'<thead><th>Importe</th><th>Descripcion</th><th>Fecha</th></thead>'
	    		 						 		 +			'<tbody>';
						    		 						$.each(response.data.bonus, function(index, val) {
						    		 						 	$detalle += '<tr>'
						    		 						 			 +		'<td class="numerito">'+val.amount+'</td>'
						    		 						 			 +		'<td>'+val.description+'</td>'
						    		 						 			 +		'<td>'+val.date+'</td>'
						    		 						 	 		 +	 '</tr>'
						    		 						});
	    		 						$detalle += 		'</tbody>'
	    		 							 	 +		'</table>';
	    		 				}else{
	    		 					$detalle += '<div style="margin-bottom:0;" class="alert alert-danger" role="alert"><strong>No hay bonos aplicados</strong></div>';
	    		 				};
	    		 				$detalle +=			'</div>'
	    		 						 +		'</div>'
	    		 						 +	'</div>'//Fin .container-fluid

	    $detalle +=				'</div>'
	    		 +			'</div>'
	  			 +		'</div>'; //Fin segundo panel

	  	$detalle += 	'<div class="panel panel-warning">'
	    		 + 			'<div class="panel-heading" role="tab" id="headingThree">'
	    		 +				'<h4 class="panel-title">'
	    		 +					'<a class="collapsed font-raleway" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">Descuentos</a>'
	    		 +					'<i class="pull-right glyphicon glyphicon-folder-close"></i>'
	    		 +				'</h4>'
	    		 +			'</div>'
	    		 +			'<div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">'
	    		 +				' <div class="panel-body">';

	    			    		$detalle += '<div class="container-fluid">'
	    		 						 + 		'<div class="row">'
	    		 						 + 			'<div class="col-md-12">';


	    		 				if (!jQuery.isEmptyObject(response.data.discounts)) {
	    		 						$detalle +=		'<table class="table">'
	    		 						 		 +			'<thead><th>Importe</th><th>Descripcion</th><th>Fecha</th></thead>'
	    		 						 		 +			'<tbody>';
						    		 						$.each(response.data.discounts, function(index, val) {
						    		 						 	$detalle += '<tr>'
						    		 						 			 +		'<td class="numerito">'+val.amount+'</td>'
						    		 						 			 +		'<td>'+val.description+'</td>'
						    		 						 			 +		'<td>'+val.date+'</td>'
						    		 						 	 		 +	 '</tr>'
						    		 						});
	    		 						$detalle += 		'</tbody>'
	    		 							 	 +		'</table>';
	    		 				}else{
	    		 					$detalle += '<div style="margin-bottom:0;" class="alert alert-danger" role="alert"><strong>No hay descuentos aplicados</strong></div>';
	    		 				};
	    		 				$detalle +=			'</div>'
	    		 						 +		'</div>'
	    		 						 +	'</div>'//Fin .container-fluid
	    $detalle += 			'</div>'
	    		 + 			'</div>'
	  			 + 		'</div>'; //Fin  tercer panel

	  	$detalle += 	'<div class="panel panel-warning">'
	    		 + 			'<div class="panel-heading" role="tab" id="headingFour">'
	    		 +				'<h4 class="panel-title">'
	    		 +					'<a class="collapsed font-raleway" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">Asistencias</a>'
	    		 +					'<i class="pull-right glyphicon glyphicon-folder-close"></i>'
	    		 +				'</h4>'
	    		 +			'</div>'
	    		 +			'<div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">'
	    		 +				' <div class="panel-body">';

	    			    		$detalle += '<div class="container-fluid">'
	    		 						 + 		'<div class="row">'
	    		 						 + 			'<div class="col-md-12">';
	    		 				if (!jQuery.isEmptyObject(response.data.assists)) {
	    		 						$detalle +=		'<table class="table">'
	    		 						 		 +			'<thead><th>Fecha</th><th>Salario</th><th>Comida</th></thead>'
	    		 						 		 +			'<tbody>';
						    		 						$.each(response.data.assists, function(index, val) {
						    		 							var $dateFormat2   = val.date_assistance.split("-");
																var $dateShipping2 = new Date($dateFormat2[1]+"/"+$dateFormat2[2]+"/"+$dateFormat2[0]);
																var $dateFull2     = diasSemana[$dateShipping2.getDay()] + ", " + $dateShipping2.getDate() + " de " + meses[$dateShipping2.getMonth()] + " de " + $dateShipping2.getFullYear();
																var $comida = "";
																if(val.name_job == "Operador"){
																	$comida = 30;
																}else{
																	$comida = 30;
																}

						    		 						 	$detalle += '<tr>'
						    		 						 			 +		'<td>'+$dateFull2+'</td>'
						    		 						 			 +		'<td class="numerito">'+val.cost+'</td>'
						    		 						 			 +		'<td class="numerito">'+$comida+'</td>'
						    		 						 	 		 +	 '</tr>'
						    		 						});
	    		 						$detalle += 		'</tbody>'
	    		 							 	 +		'</table>';
	    		 				}else{
	    		 					$detalle += '<div style="margin-bottom:0;" class="alert alert-danger" role="alert"><strong>No hubo asistencia en la semana.</strong></div>';
	    		 				};
	    		 				$detalle +=			'</div>'
	    		 						 +		'</div>'
	    		 						 +	'</div>'//Fin .container-fluid


	    $detalle += 			'</div>' //Fin .panel-body
	    		 + 			'</div>' //Fin .panel-collapse
	  			 + 		'</div>'; //Fin  cuarto panel

		$detalle +=	'</div>'; //Fin panel-group

		// Agrega contenido al modal
		modal.find(".modal-body").hide().html($detalle).fadeIn(3000);
		$('.numerito').toNumber().formatCurrency(); // Formato de moneda
		$('#modalDetail').modal('handleUpdate'); // Modifica el tamaño del fondo de acuerdo al contenido

		// Modifica el tamaño del fondo de acuerdo al contenido cada que abrimos un panel
		// Cambiamos los iconos dependiendo si esta abierto o cerrado
		$('#accordion .collapse')
        	.on('shown.bs.collapse', function() {
        		$('#modalDetail').modal('handleUpdate');
            	$(this)
                	.parent()
                	.find(".glyphicon-folder-close")
                	.removeClass("glyphicon-folder-close")
                	.addClass("glyphicon-folder-open");
            })
         	.on('hidden.bs.collapse', function() {
            	$(this)
                	.parent()
                	.find(".glyphicon-folder-open")
                	.removeClass("glyphicon-folder-open")
                	.addClass("glyphicon-folder-close");
            });

 	})
 	.fail(function() {
 		console.log("error");
 	})
 	.always(function() {
 		console.log("complete");
 	});
 }
/*function getDetailPayrollByUser(modal, id)
{

	var $detalle   = "";
	var meses      = new Array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
	var diasSemana = new Array("Domingo","Lunes","Martes","Mi\u00e9rcoles","Jueves","Viernes","S\u00e1bado");

	// Mostrar imagen de cargando
	var $html = '<center><img style="" src="'+$basePath+'/public/img/gif-load.gif"> cargando...</center>';
	modal.find(".modal-body").html($html);

	$.ajax({
		url      : $basePath + "/horus/payroll/detailuserpayroll",
		method   : "POST",
		dataType : "json",
		data     : {id_employee : id, type : $type, date : $date}
	})
	.done(function(response) {
		//console.log(response.data.shippings);
		//var $obj = jQuery.parseJSON(response);
		//console.log($obj.data[0].article);
		$detalle += '<div class="container-fluid">';
		$.each(response.data.shippings, function(index,obj){

			var $dateFormat   = obj.date_shipping.split("-");
			var $dateShipping = new Date($dateFormat[1]+"/"+$dateFormat[2]+"/"+$dateFormat[0]);
			var $dateFull     = diasSemana[$dateShipping.getDay()] + ", " + $dateShipping.getDate() + " de " + meses[$dateShipping.getMonth()] + " de " + $dateShipping.getFullYear();
			//console.log($dateFull);
			$detalle 	+= 	'<div class="row">'
			 		  	+ 		'<div class="col-md-12">'
					  	+ 			'<h3 class="font-lato text-info">'+$dateFull+'</h3>'
						+           '<p class="text-muted font-lato">Viaje #: '+obj.id_shipping+'</p>'
						+			'<p class="text-muted font-lato">Tipo: '+((obj.type_destination == 1) ? "Local" : "Foraneo")+'</p>'
						+			'<p class="text-muted font-lato">Directo / Ruta: '+((obj.direct_route == 1) ? "Directo" : "Ruta")+'</p>'
						+           '<p class="text-muted font-lato">Destino: '+obj.name_destination+'</p>'
						+           '<p class="text-muted font-lato">Descripci&oacute;n: '+obj.description_destination+'</p>'
						+			'<div class="row">';

			$.each(obj.detailShippings, function(index2, val) {
				if (val.type_user == 1) {

					$detalle += '<div class="col-md-6">'
							 +		'<div class="card">'
							 +  		'<label style="padding:0;" class="label-check text-muted font-lato" for="'+(obj.id_shipping+val.type_user)+'">Operador: <input type="checkbox" value="'+(obj.id_shipping+val.type_user)+'" id="'+(obj.id_shipping+val.type_user)+'" checked="checked" disabled="disabled" /><i></i></label>'
							 +  		'<p class="text-muted font-lato">Monto: <span class="numerito">'+val.amount+'</span></p>'
							 +		'</div>'
							 +	'</div>';

				}else if(val.type_user == 2){

					$detalle += '<div class="col-md-6">'
							 +		'<div class="card">'
							 +  		'<label style="padding:0;" class="label-check text-muted font-lato" for="'+(obj.id_shipping+val.type_user)+'">Ayudante: <input type="checkbox" value="'+(obj.id_shipping+val.type_user)+'" id="'+(obj.id_shipping+val.type_user)+'" checked="checked" disabled="disabled" /><i></i></label>'
							 +  		'<p class="text-muted font-lato">Monto: <span class="numerito">'+val.amount+'</span></p>'
							 +		'</div>'
							 +	'</div>';

				};
			});

			$detalle    += 			'</div><hr/>'
						+   	'</div>'
					  	+ 	'</div>';

		});
		$detalle += '</div>';

		modal.find(".modal-body").hide().html($detalle).fadeIn(3000); // Agrega contenido al modal
		$('.numerito').toNumber().formatCurrency(); // Formato de moneda
		$('#modalDetail').modal('handleUpdate'); // Modifica el tamaño del fondo de acuerdo al contenido
		console.log( "success" );
	})
	.fail(function() { console.log( "error" ); })
	.always(function() { console.log( "complete" ); });
}*/

/*
	FUNCION PARA OBTENER LA LISTA DE BONOS POR EMPLEADO
*/
function getBonusPayrollByUser(modal, boton, id)
{
	var $total_pagar = $.trim(boton.parents("tr").find('td:eq(7) a').text()); //Total a pagar
	var $total_bonus = $.trim(boton.parents('tr').find('td:eq(5)').text());
	console.log("Total a pagar: "+$total_pagar);
	console.log("Total bonos: "+$total_bonus);

	var $detalle   = "";
	var meses      = new Array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
	var diasSemana = new Array("Domingo","Lunes","Martes","Mi\u00e9rcoles","Jueves","Viernes","S\u00e1bado");

	// Mostrar imagen de cargando
	var $html = '<center><img style="" src="'+$basePath+'/public/img/gif-load.gif"> cargando...</center>';
	modal.find(".modal-body").html($html);

	$.ajax({
		url      : $basePath + "/horus/payroll/amountuserpayroll",
		method   : "POST",
		dataType : "json",
		data     : {id_employee : id, type : "BONO"}
	})
	.done(function(response) {
		console.log(response);

		if (response.response == "ok") {
			$detalle += '<div class="container-fluid">'
					 +		'<div class="row">'
					 +			'<div class="col-md-12">'
					 //+				'<div class="page-header" style="margin: 0; margin-bottom:30px;">'
	  				 //+					'<h1 class="text-left font-raleway text-muted" style="margin:0;">Lista de bonos</h1>'
					 //+				'</div>'
					 +				'<p>'
					 +					'<strong class="text-info" style="display:inline-block;">Aplicar todos los bonos:</strong>'
					 +					'<label class="label-checkbox" for="checkall-bonus" style="display:inline-block;">'
			  		 +						'<input type="checkbox" value="checkall-bonus" id="checkall-bonus">'
			  		 +						'<i></i>'
					 +					'</label>'
					 +				'</p>'
					 +				'<div class="panel panel-primary">'
	 				 +					'<div class="panel-heading">Lista de bonos aplicables.</div>'
	  				 //+					'<div class="panel-body">'
	    			 //+						'<p>...</p>'
	  				 //+					'</div>'
	  				 +						'<input type="hidden" value="'+id+'" id="input_user_amount">'
					 +						'<div class="table-responsive">'
					 +						'<table id="table-bonus-applicable" class="table table-striped table-hover">'
					 + 							'<thead>'
					 +								'<th></th>'
					 +								'<th>Importe</th>'
					 +								'<th>Fecha</th>'
					 +								'<th>Tipo</th>'
					 +								'<th>Descripcion</th>'
					 +								'<th></th>'
					 +							'</thead>'
					 +							'<tbody>';


			$.each(response.data, function(index,obj){

				$detalle += '<tr>'
						 +		'<td>'
						 +		'<label for="'+"bono"+(index+1)+'" class="label-checkbox text-muted font-lato" style="padding:0; margin-bottom:0;">'
						 +			'<input type="checkbox" id="'+"bono"+(index+1)+'" class="check-bonus" value="'+obj.id_paypayroll+'">'
						 +			'<i></i>'
						 +		'</label>'
						 +		'</td>'
						 +		'<td>'
						 +			'<span class="numerito">'+obj.amount+'</span>'
						 +		'</td>'
						 +		'<td>'+newFormatDate(obj.date)+'</td>'
						 +		'<td>'+obj.type+'</td>'
						 +		'<td>'+obj.description+'</td>'
						 +		'<td>'
						 +			'<a href="#" class="btn btn-xs btn-danger remove-bonus" data-bono="'+obj.id_paypayroll+'" data-amount="'+obj.amount+'" data-tool="tooltip" data-placement="top" title="Eliminar bono">'
						 +				'<span class="glyphicon glyphicon-trash"></span>'
						 +			'</a>'
						 +		'</td>'
						 +	'</tr>';
			});

			$detalle +=							'</tbody>'
					 +						'</table>'
					 +						'</div>'
					 +				'</div>'
					 +				'<p class="text-muted"><strong>Total bonos: <span id="span_total_bonus" class="pull-right">'+$total_bonus+'</span></strong></p>'
					 +				'<p class="text-muted"><strong>Total a pagar: <span id="span_total_amount" class="pull-right">'+$total_pagar+'</span></strong></p>'
					 +			'</div>'
					 +		'</div>'
					 +	'</div>';
			$("#aplicateBonus").show();//Mostrar boton de aplicar bonos
		}else{
			$detalle += '<div style="margin-bottom:0;" class="alert alert-danger text-center" role="alert">No existen bonos para aplicar</div>';
			$("#aplicateBonus").hide();//Ocultar boton de aplicar bonos
		};
		/*var $obj = jQuery.parseJSON(response);
		console.log($obj.data[0].article);*/

		modal.find(".modal-body").hide().html($detalle).fadeIn(3000); // Agrega contenido al modal
		$('.numerito').toNumber().formatCurrency(); // Formato de moneda
		$('[data-tool=tooltip]').tooltip(); //Tooltip a los links
		$('#modalBonus').modal('handleUpdate'); // Modifica el tamaño del fondo de acuerdo al contenido

		//console.log( "success" );
	})
	.fail(function() { console.log( "error" ); })
	.always(function() { console.log( "complete" ); });
}

/*
	FUNCION PARA OBTENER LA LISTA DE DESCUENTOS POR EMPLEADO
*/
function getDiscountPayrollByUser(modal, boton, id)
{
	var $total_pagar           = $.trim(boton.parents("tr").find('td:eq(7) a').text());
	var $total_discounts       = $.trim(boton.parents('tr').find('td:eq(6)').text());
	var $validateCheckallBonus = (parseFloat($.trim(boton.parents("tr").find('td:eq(6)').formatCurrency().clone().toNumber().text())) > parseFloat(boton.parents("tr").find('td:eq(7) a').data('total'))) ? 'disabled="disabled"' : "";
	console.log("Validacion: "+$validateCheckallBonus);
	console.log("Total a pagar: "+$total_pagar);
	console.log("Total descuentos: "+$total_discounts);
	var $detalle   = "";
	//var meses      = new Array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
	//var diasSemana = new Array("Domingo","Lunes","Martes","Mi\u00e9rcoles","Jueves","Viernes","S\u00e1bado");

	// MOSTRAR IMAGEN DE CARGANDO
	var $html = '<center><img style="" src="'+$basePath+'/public/img/gif-load.gif"> cargando...</center>';
	modal.find(".modal-body").html($html);

	$.ajax({
		url      : $basePath + "/horus/payroll/amountuserpayroll",
		method   : "POST",
		dataType : "json",
		data     : {id_employee : id, type : "DESCUENTO"},
	})
	.done(function(response) {
		//console.log(response);
		/*var $obj = jQuery.parseJSON(response);
		console.log($obj.data[0].article);*/
		if(response.response == "ok"){
			$detalle += '<div class="container-fluid">'
					 +		'<div class="row">'
					 +			'<div class="col-md-12">'
					 +				'<p>'
					 +					'<strong class="text-info" style="display:inline-block;">Aplicar todos los descuentos:</strong>'
					 +					'<label class="label-checkbox" for="checkall-discounts" style="display:inline-block;">'
			  		 +						'<input type="checkbox" value="checkall-discounts" id="checkall-discounts" '+$validateCheckallBonus+'>'
			  		 +						'<i></i>'
					 +					'</label>'
					 +				'</p>'
					 +				'<div class="panel panel-primary">'
	 				 +					'<div class="panel-heading">Lista de descuentos aplicables.</div>'
	  				 +						'<input type="hidden" value="'+id+'" id="input_user_amount2">'
					 +						'<div class="table-responsive">'
					 +						'<table id="table-discounts-applicable" class="table table-striped table-hover">'
					 + 							'<thead>'
					 +								'<th></th>'
					 +								'<th>Importe</th>'
					 +								'<th>Fecha</th>'
					 +								'<th>Tipo</th>'
					 +								'<th>Descripcion</th>'
					 +								'<th></th>'
					 +							'</thead>'
					 +							'<tbody>';


			$.each(response.data, function(index,obj){

				$detalle += '<tr>'
						 +		'<td>'
						 +		'<label for="'+"disc"+(index+1)+'" class="label-checkbox text-muted font-lato" style="padding:0; margin-bottom:0;">'
						 +			'<input type="checkbox" id="'+"disc"+(index+1)+'" class="check-discount" value="'+obj.id_paypayroll+'">'
						 +			'<i></i>'
						 +		'</label>'
						 +		'</td>'
						 +		'<td>'
						 +			'<span class="numerito">'+obj.amount+'</span>'
						 +		'</td>'
						 +		'<td>'+newFormatDate(obj.date)+'</td>'
						 +		'<td>'+obj.type+'</td>'
						 +		'<td>'+obj.description+'</td>'
						 +		'<td>'
						 +			'<a href="" class="btn btn-xs btn-danger remove-discount" data-discount="'+obj.id_paypayroll+'" data-amount="'+obj.amount+'" data-tool="tooltip" data-placement="top" title="Eliminar descuento">'
						 +				'<span class="glyphicon glyphicon-trash"></span>'
						 +			'</a>'
						 +		'</td>'
						 +	'</tr>';
			});

			$detalle +=							'</tbody>'
					 +						'</table>'
					 +						'</div>'
					 +				'</div>'
					 +				'<p class="text-muted"><strong>Total descuentos: <span id="span_total_discounts" class="pull-right">'+$total_discounts+'</span></strong></p>'
					 +				'<p class="text-muted"><strong>Total a pagar: <span id="span_total_amount2" class="pull-right">'+$total_pagar+'</span></strong></p>'
					 +			'</div>'
					 +		'</div>'
					 +	'</div>';
			$('#aplicateDiscount').show();//Mostrar boton de aplicar descuentos
		}else{
			$detalle += '<div style="margin-bottom:0;" class="alert alert-danger text-center" role="alert">No existen descuentos para aplicar</div>';
			$('#aplicateDiscount').hide();//Ocultar boton de aplicar descuentos
		};

		modal.find(".modal-body").hide().html($detalle).fadeIn(3000); // Agrega contenido al modal
		$('.numerito').toNumber().formatCurrency(); // Formato de moneda
		$('[data-tool=tooltip]').tooltip(); //Tooltip a los links
		$('#modalDiscount').modal('handleUpdate'); // Modifica el tamaño del fondo de acuerdo al contenido

		//console.log( "success" );
	})
	.fail(function() { console.log( "error" ); })
	.always(function() { console.log( "complete" ); });
}

	//CLICK EN EL BOTON REMOVER BONO
	$(document).on("click", ".remove-bonus", function(e){
		e.preventDefault();
		var $id_user_amount = $("#input_user_amount").val();
		var $totalBonos     = $.trim($("#listpayroll tbody tr#user"+$id_user_amount).find('td:eq(5)').formatCurrency().clone().toNumber().text()); //Total de bonos que aplicar
		console.log("Total de bonos: "+$totalBonos);
		//console.log("Removiendo bono");
		var $bono    = $(this);//Boton que aplica el clik
		var $id_bono = $bono.data('bono');//Id del bono
		var $amount_bono   = $bono.data('amount');//Importe del bono
		var $bonosActuales = parseFloat($totalBonos) - parseFloat($amount_bono);//Bonos reales
		console.log("Bonos reales: "+ $bonosActuales);

		alertify.confirm("Deseas eliminar el bono?", function(e){
			if (e) {
				$.ajax({
					url: $basePath + '/horus/payroll/deleteamountuser',
					type: 'POST',
					dataType: 'json',
					data: {idAmount : $id_bono},
				})
				.done(function() {
					$("#listpayroll tbody tr#user"+$id_user_amount).find('td:eq(5)').text($bonosActuales).formatCurrency();
					$bono.parents('tr').remove();
					alertify.set({ delay: 1000 });
					alertify.success("Bono eliminado correctamente");
					console.log("success");
				})
				.fail(function() {
					console.log("error");
				})
				.always(function() {
					console.log("complete");
				}); //Fin ajax
			}
		});//Fin confirmacion
	}); //Fin elminar importe

	//CLICK EN EL BOTON REMOVER DESCUENTO
	$(document).on("click", ".remove-discount", function(e){
		e.preventDefault();
		var $id_user_amount = $("#input_user_amount2").val();
		var $span_total_amount = $.trim($("#listpayroll tbody tr#user"+$id_user_amount).find('td:eq(6)').formatCurrency().clone().toNumber().text());
		console.log("Total de descuentos: "+$span_total_amount);

		//console.log("Removiendo descuento");
		var $discount = $(this);
		var $id_disc  = $discount.data('discount');//find('td:eq(0) label checkbox').val();//Id del descuento
		var $amount_discount   = $discount.data('amount');//Importe del descuento
		var $discountsActuality = parseFloat($span_total_amount) - parseFloat($amount_discount);//Bonos reales
		console.log("Descuentos reales: "+ $discountsActuality);

		alertify.confirm("Deseas eliminar el descuento?", function(e){
			if (e) {
				$.ajax({
					url: $basePath + '/horus/payroll/deleteamountuser',
					type: 'POST',
					dataType: 'json',
					data: {idAmount : $id_disc},
				})
				.done(function() {
					$("#listpayroll tbody tr#user"+$id_user_amount).find('td:eq(6)').text($discountsActuality).formatCurrency();
					$discount.parents('tr').remove();
					alertify.set({ delay: 1000 });
					alertify.success("Descuento eliminado correctamente");
					console.log("success");
				})
				.fail(function() {
					console.log("error");
				})
				.always(function() {
					console.log("complete");
				}); //Fin ajax
			}
		});//Fin confirmacion
	}); //Fin elminar importe


    /*
		CLICK EN EL CHECKBOX SELECCIONAR TODOS LOS BONOS APLICABLES
	 */
	$(document).on("click", "#checkall-bonus", function () {
		var $span_total_amount    = $.trim($("#span_total_amount").formatCurrency().clone().toNumber().text()); //Total a pagar
		var $amount_bono_selected = 0; //Valor de cada checkbox
		var $total_bonus_selected = 0; //Suma total de todos los checkbox seleccionados
		var $suma_total_amount    = 0; //Suma total de todos los checkbox mas/menos el total a pagar

		//Conprobamos si el checkbox de todos esta seleccionado
        if ($("#checkall-bonus").is(':checked')) {
        	//Recorremos todos los checkbox dentro de la tabla
            $("body #table-bonus-applicable tbody tr td input[type=checkbox]").each(function () {
            	//Recupera solo los que no estan seleccionados
            	if (!$(this).is(':checked')) {
            		//Valor de cada checkbox
            		$amount_bono_selected = $(this).parents("tr").find('td:eq(1) span').formatCurrency().clone().toNumber().text();
                	//Suma de todos los checkbox
                	$total_bonus_selected += parseFloat($amount_bono_selected);
                	//Activa los checkbox
                	$(this).prop("checked", true);
                	//Cambia diseño del contenido
                	$(this).parents('tr').find('td').css({"text-decoration" : "line-through", "color" : "rgb(128, 144, 160)" });
            		//Deshabilitar boton de borrar bono.
					$(this).parents('tr').find('td:eq(5) a').attr("disabled", true);
            	};
            });
            //Sumamos el total mas el total de los bonos
            $suma_total_amount = parseFloat($span_total_amount) + parseFloat($total_bonus_selected);
            //Asinamos al total el nuevo resultado
            $("#span_total_amount").text($suma_total_amount).formatCurrency();
        } else {
            $("body #table-bonus-applicable tbody tr td input[type=checkbox]").each(function () {
            	$amount_bono_selected = $(this).parents("tr").find('td:eq(1) span').formatCurrency().clone().toNumber().text();
                $total_bonus_selected += parseFloat($amount_bono_selected);
                $(this).prop("checked", false);
                $(this).parents('tr').find('td').css({"text-decoration" : "none", "color" : "#333" });
                //Habilitar boton de borrar bono.
				$(this).parents('tr').find('td:eq(5) a').attr("disabled", false);
            });
            $suma_total_amount = parseFloat($span_total_amount) - parseFloat($total_bonus_selected);
            $("#span_total_amount").text($suma_total_amount).formatCurrency();
        }
    });

    /*
		CLICK EN EL CHECKBOX SELECCIONAR TODOS LOS DESCUENTOS APLICABLES
	 */
	$(document).on("click", "#checkall-discounts", function () {
		var $span_total_amount    = $.trim($("#span_total_amount2").formatCurrency().clone().toNumber().text()); //Total a pagar
		var $amount_bono_selected = 0; //Valor de cada checkbox
		var $total_bonus_selected = 0; //Suma total de todos los checkbox seleccionados
		var $suma_total_amount    = 0; //Suma total de todos los checkbox mas/menos el total a pagar

		//Conprobamos si el checkbox de todos esta seleccionado
        if ($("#checkall-discounts").is(':checked')) {
        	//Recorremos todos los checkbox dentro de la tabla
            $("body #table-discounts-applicable tbody tr td input[type=checkbox]").each(function () {
            	//Recupera solo los que no estan seleccionados
            	if (!$(this).is(':checked')) {
            		//Valor de cada checkbox
            		$amount_bono_selected = $(this).parents("tr").find('td:eq(1) span').formatCurrency().clone().toNumber().text();
                	//Suma de todos los checkbox
                	$total_bonus_selected += parseFloat($amount_bono_selected);
                	//Activa los checkbox
                	$(this).prop("checked", true);
                	//Cambia diseño del contenido
                	$(this).parents('tr').find('td').css({"text-decoration" : "line-through", "color" : "rgb(128, 144, 160)" });
            		//Deshabilitar boton de borrar descuento.
					$(this).parents('tr').find('td:eq(5) a').attr("disabled", true);
            	};
            });
            //Sumamos el total mas el total de los bonos
            $suma_total_amount = parseFloat($span_total_amount) - parseFloat($total_bonus_selected);
            console.log($suma_total_amount);
            //Asinamos al total el nuevo resultado
            $("#span_total_amount2").text(parseFloat($suma_total_amount)).formatCurrency();
        } else {
            $("body #table-discounts-applicable tbody tr td input[type=checkbox]").each(function () {
            	$amount_bono_selected = $(this).parents("tr").find('td:eq(1) span').formatCurrency().clone().toNumber().text();
                $total_bonus_selected += parseFloat($amount_bono_selected);
                $(this).prop("checked", false);
                $(this).parents('tr').find('td').css({"text-decoration" : "none", "color" : "#333" });
                //Habilitar boton de borrar descuento.
				$(this).parents('tr').find('td:eq(5) a').attr("disabled", false);
            });
            $suma_total_amount = parseFloat($span_total_amount) + parseFloat($total_bonus_selected);
            console.log($suma_total_amount);
            $("#span_total_amount2").text(parseFloat($suma_total_amount)).formatCurrency();
        }
    });