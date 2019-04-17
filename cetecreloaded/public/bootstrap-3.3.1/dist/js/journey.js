(function($){
	$(document).on("ready", function(){

		/*
			AL ESCRIBIR UNA CANTIDAD SUMA Y LO MUESTRA EN LA TABLA
		*/
		$(document).on("keyup", 'input[name=box]', function(){
			sumNumberBox("table_clients_box", "total");
		});

		$(document).on("keyup", 'input[name=box_delivery]', function(){
			sumNumberBox("table_clients_box_delivery", "total_delivery");
		});

		/*
			LISTAS CON BUSCADOR INTEGRADO
		*/
		$("#id_truck").select2({
		  formatNoMatches: function(term) {
				return "<div class='select2-result-label'><span class='select2-match'></span>No se encontraron resultados</div>"
			}
		});

		$("#id_operator").select2({
		  formatNoMatches: function(term) {
				return "<div class='select2-result-label'><span class='select2-match'></span>No se encontraron resultados</div>"
			}
		});

		$("#id_destination, #company_ID").select2({
		  formatNoMatches: function(term) {
				return "<div class='select2-result-label'><span class='select2-match'></span>No se encontraron resultados</div>"
			}
		});


		$('#accordion').on('hidden.bs.collapse', toggleChevron);
		$('#accordion').on('shown.bs.collapse', toggleChevron);

		$('#accordion2').on('hidden.bs.collapse', toggleChevron);
		$('#accordion2').on('shown.bs.collapse', toggleChevron);

		// Llena tabla con los clientes y las cajas
		//createTableClientsBox();

		// Llenamos la tabla de los clientes que reciben
		//createTableClientsBox2();

		// Genera la lista con los ayudantes de un viaje
		generarListaAyudantes();

		// MOSTRAR DETALLE DE CAMION SI YA SE SELECCIONO ALGUNO
		cargarCamion();

		//MOSTRAMOS CIUDADES ADICIONALES
		showCitysAditional();


		$("#start_date").datepicker({ dateFormat: 'dd/mm/yy' }).datepicker("setDate", new Date());
		$(".num").numeric().formatCurrency();

		/*$(".num").on("change", function(){
			$(this).formatCurrency();
		});*/

		/*$("#submitbutton").validarFormulario({
			form: "shipping"
		});*/

		/*
			CLICK EN EL BOTON GUARDAR
		*/
		$("#submitbutton").on("click", function(e){
			e.preventDefault();
			$boton = $(this);
			$boton.attr("disabled", true);

			//TABLA A JSON
			tableToJson();
			tableToJson2();

			//AGREGAR CIUDADES ADICIONALES
			addCitysAditional();

			alertify.confirm("¿Deseas guardar los cambios?", function(e){
				if(e){
					$("#shipping").submit();
				}else{
					$boton.attr("disabled", false);
				}
			});
		});


		// Funcion que se ejecuta cuando se seleccionar un destino
		$("#id_destination").on("change", function(){

			var $options_destination = $("#id_destination option:selected").data("options");
			var $amount_operator     = $("#amount_operator");
			var $amount_assistant    = $("#amount_assistant");

			if ($("#id_destination").val() == "") {
				alertify.alert("Esta vacio");
				$("input:radio[name=type_destination]").attr('checked',false);
				$("input:radio[name=direct_route]").attr('checked',false);
				$amount_operator.val("");
				$amount_assistant.val("");
				$("#panel-city-add").fadeOut("slow");
			}else{
				$("input:radio[name=type_destination]").filter("[value='"+$options_destination.type_destination+"']").prop("checked", true);
				$("input:radio[name=direct_route]").filter("[value='"+$options_destination.direct_route+"']").prop("checked", true);
				$amount_operator.val($options_destination.operator_salary);
				$amount_assistant.val($options_destination.assistant_salary);

        		if ($options_destination.direct_route == 1) {
            		$("#panel-city-add").fadeOut("slow");
        		}
        		else {
            		$("#panel-city-add").fadeIn("slow");
        		}
			};
		});

		// Funcion que se ejecuta cuando se seleccionar un operador
		$("#id_operator").on("change", function(){
			if ($("#id_operator").val() == "") {
				alertify.alert("No seleccionaste ningun elemento");
				$("#id_assistant ").val("");
				$("#id_assistant option").show();
			}else{
				$("#id_assistant ").val("");
				//Recorremos la lista desplegable
				$("#id_assistant option").each(function(index, val){
					if($(this).val() == $("#id_operator option:selected").val()){
						$("#id_assistant option[value='"+ $("#id_operator option:selected").val() + "']").hide();
					}else if($(this).val() != $("#id_operator option:selected").val()){
							$(this).show();
					}
					//val.value
				});
			};
		});

		// Funcion que se ejecuta cuando se seleccionar un ayudante
		$(document).on("change", ".id_assistant", function(){
			var array    = [];

			$.each($(".id_assistant option:selected"), function(){
			   array.push($(this).val());
			});
			$("#ids_ayudantes").val(array.join(', '));
			console.log(array.join(', '));
		});

		// Funcion que se ejecuta cuando se da click en el boton agregar ayudante
	    $(document).on('click', '.buttonAddAssistant', function(e){
	        e.preventDefault();
	        if ($('.contenedor_ayudantes').find('.entry').length < 5) {
	          	var $contenedor_ayudantes = $(".contenedor_ayudantes");
		        var $boton_actual         = $(this).parents(".entry:first");
		        var $nuevo_boton          = $($boton_actual.clone()).appendTo($contenedor_ayudantes);

	            $nuevo_boton.find('select').val('');
	            $contenedor_ayudantes.find('.entry:not(:last) .buttonAddAssistant')
		        	.removeClass('buttonAddAssistant').addClass('btn-remove')
		        	.removeClass('btn-primary').addClass('btn-danger')
		        	.html('<span class="glyphicon glyphicon-minus-sign"></span>');
			}else{
	           	alertify.alert("Solo se permiten 5 ayudantes maximo");
	           	return false;
			};
	    }).on('click', '.btn-remove', function(e)
	    {
			$(this).parents('.entry:first').remove();
			e.preventDefault();
			return false;
		});

		// Funcion que se ejecuta cuando se selecciona una unidad(camion)
		$("#id_truck").on("change", function(e){
			showDetailTruck($("#id_truck").val());
		}); // end change #id_truck

		/*
			CLICK EN EL BOTON AGREGAR FILA A LA TABLA #table_clients_box
		*/
    	/*$("#add_row").on("click", function(){

    		var status = "";

    		$('#table_clients_box tbody tr:last').each(function () {
				var client = $(this).find("td").eq(0).find("select option:selected").val();
				var box    = $(this).find("td").eq(1).find("input").val();

				if (client != "") { status =1; }else{ status =0; }

			});

			if(status == 1){
				// Obtener newIdRow máximo y establecer nueva identificación
				var newIdRow = 0;

				$.each($("#table_clients_box tbody tr"), function() {
					if (parseInt($(this).data("id")) > newIdRow) {
		           		newIdRow = parseInt($(this).data("id"));
					}
				});

				newIdRow++;

				var tr = $("<tr></tr>", {
		           	id        : "addr" + newIdRow,
		           	"data-id" : newIdRow
		        });

				// Recorrer cada td y crear nuevos elementos con nombre de newIdRow
				$.each($("#table_clients_box tbody tr:nth(0) td"), function(index, val) {
					var current_td  = $(this);
					var children_td = current_td.children();

					// Anadir nueva td y el elemento si tiene una name
					if ($(this).data("name") != undefined) {

						var td = $("<td></td>", {
		                   	"data-name": $(current_td).data("name")
		               	});
	                	var row = $(current_td).find($(children_td[0]).prop('tagName')).clone().val("");
	                	//row.attr("name", $(current_td).data("name") + newIdRow);
	                	row.attr("name", $(current_td).data("name"));
	                	row.appendTo($(td));
	                	td.appendTo($(tr));
					}else{
						var td = $("<td></td>", {
		                    'text': $('#table_clients_box tr').length
		                }).appendTo($(tr));
					}; // end if

				}); // end each

				// Agregamos la fila nueva
		        $(tr).appendTo($('#table_clients_box'));
		        //$tabaleClientsBox.row.add($(tr)).draw();
		        //CAMPOS NUMERICOS
				$('.input-numeric').numeric();

		        $(tr).find("td button.row-remove").on("click", function() {
		            $(this).closest("tr").remove();
		            //$tabaleClientsBox.row( $(this).parents('tr') ).remove().draw();
		        });
		    }else{
		    	alertify.alert("Hay datos vacios. Revisa y vuelve a intentar");
		    }
    	});*/

		// Click en el boton agregar fila a la tabla #table_clients_box_delivery
    	/*$(document).on("click", "#add_row_delivery", function(){

    		var status = "";

    		$('#table_clients_box_delivery tbody tr:last').each(function () {
				var client = $(this).find("td").eq(0).find("select option:selected").val();
				var folio  = $(this).find("td").eq(1).find("input").val();
				var box    = $(this).find("td").eq(2).find("input").val();

				if (client != "" && folio != "" && box != "") { status =1; }else{ status =0; }

			});

			if (status ==1) {
				// Obtener newIdRow máximo y establecer nueva identificación
				var newIdRow = 0;

				$.each($("#table_clients_box_delivery tbody tr"), function() {
					if (parseInt($(this).data("id")) > newIdRow) {
	            		newIdRow = parseInt($(this).data("id"));
					}
				});

				newIdRow++;

				var tr = $("<tr></tr>", {
	            	id        : "addr" + newIdRow,
	            	"data-id" : newIdRow
	        	});

				// Recorrer cada td y crear nuevos elementos con nombre de newIdRow
				$.each($("#table_clients_box_delivery tbody tr:nth(0) td"), function(index, val) {
					var current_td  = $(this);
					var children_td = current_td.children();

					// Anadir nueva td y el elemento si tiene una name
					if ($(this).data("name") != undefined) {

						var td = $("<td></td>", {
	                    	"data-name": $(current_td).data("name")
	                	});

	                	var row = $(current_td).find($(children_td[0]).prop('tagName')).clone().val("");
	                	//row.attr("name", $(current_td).data("name") + newIdRow);
	                	row.attr("name", $(current_td).data("name"));
	                	row.appendTo($(td));
	                	td.appendTo($(tr));
					}else{
						var td = $("<td></td>", {
	                    	'text': $('#table_clients_box_delivery tr').length
	                	}).appendTo($(tr));
					}; // end if

				}); // end each

				// Agregamos la fila nueva
	        	$(tr).appendTo($('#table_clients_box_delivery'));
	        	//$tableClientsBoxDelivery.row.add($(tr)).draw();
	        	//CAMPOS NUMERICOS
				$('.input-numeric').numeric();

	        	$(tr).find("td button.row-remove_delivery").on("click", function() {
	            	$(this).closest("tr").remove();
	            	//$tableClientsBoxDelivery.row( $(this).parents('tr') ).remove().draw();
	        	});

        	}else{
		    	alertify.alert("Hay datos vacios. Revisa y vuelve a intentar");
		    };

    	});*/


		// Boton generar arreglo con los datos de la tabla clientes de entrega
		/*$("#save_row_delivery").on("click", function(){

			var obj = [];
			var status = "";

			$('#table_clients_box_delivery tbody tr').each(function () {
				//tr:not(:first)
				//tr:not(:first)
				var client = $(this).find("td").eq(0).find("select option:selected").val();
				var folio  = $(this).find("td").eq(1).find("input").val();
				var box    = $(this).find("td").eq(2).find("input").val();

				if (client != "" && box != "") {

					obj.push({
						id_client : client,
						num_folio : folio,
						num_box   : box,
				    });

					status = JSON.stringify(obj);
				}else{
					status = "";
				}
			});

			// Validamos si hay campos vacios
			if(status != ""){
				// Agregamos el detalle de la tabla a un capo oculto
				$("#detail_table2").val(status);
				console.log(status);
			}else{
				alertify.alert("Hay campos vacios no puedes agreagar");
			}
		});*/

		/*
			AGREGAR CIUDADES ADICIONALES
		*/
		/*$(document).on("keyup", "input[name=city]", function(e){
			addCitysAditional();
		});*/

		/*
			CLICK EN EL BOTON AGREGAR CIUDADES ADICIONALES
		*/
		$(document).on("click", ".buttonAddCity", function(e){
			e.preventDefault();

			if ($('#fields').find('.group-city:not(:first)').length < 10) {
				var contentCitys = $("#fields");
	            var currentCity  = $(this).parents(".group-city:first");
	            var newCity      = $(currentCity.clone()).appendTo(contentCitys);

	            newCity.find('input').val('');
	            contentCitys.find(".group-city:not(:last) .buttonAddCity")
	            	.removeClass("buttonAddCity").addClass("buttonRemoveCity")
	            	.removeClass("btn-success").addClass("btn-danger")
	            	.html('<span class="glyphicon glyphicon-minus"></span>');
			}else{
	           	alertify.alert("Solo se permiten 10 puntos de entrega extra");
	           	return false;
			};

		}).on("click", ".buttonRemoveCity", function(e){
			$(this).parents('.group-city:first').remove();
			e.preventDefault();
			return false;
		}); // end agregar ciudad adicional

		/*
			CLICK EN LOS COMBOS DE CLIENTES
		*/
		/*$(document).on('change', '.select-clients', function(event) {
			event.preventDefault();
			//var valor = $("#miCombo option:selected");
			var $selectcli = $(this).find('option:selected').text();
			console.log($selectcli);

			$('#table_clients_box tbody tr td select').find('option:selected').each(function(index, el) {
				console.log($(this).text());
				if ($selectcli == $(this).text()) {
					alert("Ya se selecciono este cliente elige otro");
				};
			});
			//alert($(this).find('option:selected').text());

			var $conte = $("#contenedor-clientes-entrega");
			var $cli="";
			$cli += '<h1>'+$(this).find('option:selected').text()+'</h1>';
			//$conte.append($cli);
			$("#accordion2").clone().appendTo($conte);
		});*/

	}); /* end document ready*/

})(jQuery);

/*
	CONVERTIR TABLA A FORMATO JSON
 */
function convertTableToJson()
{
	var table = $('#tab_logic').tableToJSON({
       			ignoreColumns: [2]
  	}); // Convert the table into a javascript object
	var jsonTableString = JSON.stringify(table);
	//$("#costtable").val(jsonTableString);
	alert(jsonTableString);
}

/*
	GENERAR LISTA DE AYUDANTES
 */
function generarListaAyudantes()
{
	var $ayudantes            = $("#ids_assistants");
	var $contenedor_ayudantes = $(".contenedor_ayudantes");

 	if($ayudantes.val() != ""){
 		var $objeto = $.parseJSON($ayudantes.val());
 		var $entry  = $(".div_ayudante");

	 	$.each($objeto, function(idx, obj) {
	 		var clonar = $entry.find('.form-group').clone();
	 		clonar.appendTo(".contenedor_ayudantes");
	 		clonar.children('div').find($('select')).val(obj.id_ayudante);
		});

		// Recorremos todos los inputs de ayudantes
		$( ".contenedor_ayudantes .entry:not(:last) .buttonAddAssistant" ).each(function( index ) {
		 	//Recorremos todos los elementos menos el ultimo y cambias propiedades
			$(this)
		    	.removeClass('buttonAddAssistant').addClass('btn-remove')
		    	.removeClass('btn-primary').addClass('btn-danger')
		        .html('<span class="glyphicon glyphicon-minus-sign"></span>');

		});

 	}
}

/*
	LLENAMOS LA TABLA DE CLIENTES QUE ENVIA
 */
function createTableClientsBox()
{
	//console.log("Llenar tabla de clientes envio");
	var detailTableClientsBox = $("#detail_table");

	if (detailTableClientsBox.val() != "") {
		//console.log(detailTableClientsBox.val());
		var objClientsBox = $.parseJSON(detailTableClientsBox.val());
		var filaClonar    = $("#table_clients_box tbody tr:nth(0)");
        var total_cajas   = 0;

		$.each(objClientsBox, function(index, obj){
			//Filas
			var tr = $("<tr></tr>", {
			    id        : "addr" + index,
			    "data-id" : index
			});

			//Columnas
			var td = $("<td></td>", { "data-name" : "clients" });
			var td2 = $("<td></td>", { "data-name" : "box" });
			var td3 = $("<td></td>", { "data-name" : "delete" });

			//Clonamos los elementos
			var listaClientes = filaClonar.children('td').eq(0).find($('select')).clone().val(obj.id_client); //Lista de clientes
	        var inputCajas    = filaClonar.children('td').eq(1).find($('input')).clone().val(obj.num_box); //Numero de cajas
	        var boton         = filaClonar.children('td').eq(2).find('button').clone(); //Boton eliminar

	        //Agregamos a cada fila los elementos clonados
			listaClientes.appendTo(td);
			inputCajas.appendTo(td2);
			boton.appendTo(td3);

			//Agremamos cada columna a las filas
			td.appendTo(tr);
			td2.appendTo(tr);
			td3.appendTo(tr);

			// Agregamos las nuevas filas a la tabla
	        $("#table_clients_box tbody").append(tr);

	        //total_cajas = parseFloat(obj.num_box)+parseFloat(obj.num_box);
	        total_cajas += (parseInt(obj.num_box));
		});
		//CAMPOS NUMERICOS
		$('.input-numeric').numeric();
		$("#total").text(parseInt(total_cajas)); //Total de cajas
	}else{
		var $primerTr = $("#table_clients_box tbody tr:nth(0)");
		//$primerTr.css("display", "");
		$primerTr.removeAttr('style');
		//console.log("Estas vacio");
	};
}

/*
	LLENAMOS LA TABLA DE CLIENTES A ENTREGAR
 */
function createTableClientsBox2()
{
	console.log("Llenar tabla de clientes a entregar");
	var detailTableClientsBox2 = $("#detail_table2");
	//console.log(detailTableClientsBox2.val());

	if (detailTableClientsBox2.val() != "") {
		var objClientsBox2 = $.parseJSON(detailTableClientsBox2.val());
		var filaClonar    = $("#table_clients_box_delivery tbody tr:nth(0)");
		var total_cajas   = 0;

		$.each(objClientsBox2, function(index, obj){
			//Filas
			var tr = $("<tr></tr>", {
			    id        : "addrow" + (parseInt(index)+1),
			    "data-id" : (parseInt(index)+1)
			});

			//Columnas
			var td = $("<td></td>", { "data-name" : "client_delivery" });
			var td2 = $("<td></td>", { "data-name" : "folio_delivery" });
			var td3 = $("<td></td>", { "data-name" : "box_delivery" });
			var td4 = $("<td></td>", { "data-name" : "delete_delivery" });

			//Clonamos los elementos
			var listaClientes = filaClonar.children('td').eq(0).find($('select')).clone().val(obj.id_client); //Lista de clientes
	        var inputFolio    = filaClonar.children('td').eq(1).find($('input')).clone().val(obj.num_folio); //Numero de folio
	        var inputCajas    = filaClonar.children('td').eq(2).find($('input')).clone().val(obj.num_box); //Numero de cajas
	        var boton         = filaClonar.children('td').eq(3).find('button').clone(); //Boton eliminar

	        //Agregamos a cada fila los elementos clonados
			listaClientes.appendTo(td);
			inputFolio.appendTo(td2);
			inputCajas.appendTo(td3);
			boton.appendTo(td4);

			//Agremamos cada columna a las filas
			td.appendTo(tr);
			td2.appendTo(tr);
			td3.appendTo(tr);
			td4.appendTo(tr);

			// Agregamos las nuevas filas a la tabla
	        $("#table_clients_box_delivery tbody").append(tr);

	        //total_cajas = parseFloat(obj.num_box)+parseFloat(obj.num_box);
	        total_cajas += (parseInt(obj.num_box));
		});
		//CAMPOS NUMERICOS
		$('.input-numeric').numeric();
		$("#total_delivery").text(parseInt(total_cajas)); //Total de cajas
	}else{
		var $primerTr = $("#table_clients_box_delivery tbody tr:nth(0)");
		//$primerTr.css("display", "");
		$primerTr.removeAttr('style');
		//console.log("Estas vacio");
	};
}

function toggleChevron(e) {
    $(e.target)
        .prev('.panel-heading')
        .find("i.indicator")
        .toggleClass('glyphicon-chevron-down glyphicon-chevron-up');
}

/*
	MOSTRAR DETALLE DE UNIDAD SI YA SE SELECCIONO UNA
*/
function cargarCamion()
{
	var $id = $("#id_truck option:selected").val();
	//console.log($id);
	if($id != ""){
		showDetailTruck($id);
	}
}

/*
	MOSTRAR DETALLE DE UNIDAD
*/
function showDetailTruck($id)
{
	if ($id == "") {
		$(".datos-unidad").slideUp("slow", function(){
			$(".nombre-unidad").html("");
			$(".nombre-placa").html("");
			$(".nombre-marca").html("");
			$(".nombre-modelo").html("");
			$(".nombre-capacidad").html("");
		});
	}else{
		var $options_truck = $("#id_truck option:selected").data("options");

		$(".nombre-unidad").html('Cargando...<img src="'+$basePath+'/public/img/loader-30.gif">');
		$(".nombre-placa").html('Cargando...<img src="'+$basePath+'/public/img/loader-30.gif">');
		$(".nombre-marca").html('Cargando...<img src="'+$basePath+'/public/img/loader-30.gif">');
		$(".nombre-modelo").html('Cargando...<img src="'+$basePath+'/public/img/loader-30.gif">');
		$(".nombre-capacidad").html('Cargando...<img src="'+$basePath+'/public/img/loader-30.gif">');

		$(".datos-unidad").slideDown("slow", function(){
			setTimeout(function(){
				$(".nombre-unidad").html($options_truck.article);
				$(".nombre-placa").html($options_truck.id_product);
				$(".nombre-marca").html($options_truck.brand);
				$(".nombre-modelo").html($options_truck.model);
				$(".nombre-capacidad").html($options_truck.capacity+" toneladas");
			}, 1000);
		});
	};
}

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
	AGREGAR CIUDADES ADICIONALES
*/
function addCitysAditional()
{
	var obj = [];

	$('#fields .group-city').each(function () {

		var city_delivery = $(this).find("input.form-control").val();
		//console.log(city_delivery);
		var status="";

		if(city_delivery != ""){
			obj.push({ name_city : city_delivery });
			status = JSON.stringify(obj);
			$("#citys_delivery").val(status);
		}else{
			$("#citys_delivery").val(status);
		}

	});
	console.log(JSON.stringify(obj));
}

/*
	CARGAR CIUDADES ADICIONALES SI YA HAY
*/
function showCitysAditional()
{
	var $citysAditional = $("#citys_delivery");
	var $direct_route   = $("input:radio[name=direct_route]:checked").val(); //1.- Directo 2.- Ruta

	//console.log("Directo / Ruta: "+$direct_route);
	//console.log($citysAditional.val());

	if($direct_route == 2){
		var $contenedor_ciudades = $("#fields");

		$("#panel-city-add").fadeIn("slow", function(){
			if($citysAditional.val() != ""){
				var objCitysAditional = $.parseJSON($citysAditional.val());
				//var clonar = "";
				//var $entry  = $(".div_ayudante");

				$.each(objCitysAditional, function(index, val) {
					//console.log(jQuery.isEmptyObject(objCitysAditional));
					var clonar = jQuery('.ciudades').find('.group-city').clone();
			 		clonar.appendTo('div.campos-ciudades');
			 		clonar.children('div.input-group').find($('input')).val(val.name_city);
			 		//:nth-child(1):not(:last)
					//console.log(val.name_city);
				});

				// Recorremos todos los inputs addcity
				$( "#fields .group-city:not(:last) .buttonAddCity" ).each(function( index ) {
				 	//console.log( index + ": " + $( this ).html() );
				 	//$(this).css("background-color", "black");
				 	//console.log(index);
				 	$(this)
					 	.removeClass("buttonAddCity").addClass("buttonRemoveCity")
			           	.removeClass("btn-success").addClass("btn-danger")
			        	.html('<span class="glyphicon glyphicon-minus"></span>');
				});

				//console.log("Soy ruta y me muestro");
			}else{
				var clonar = jQuery('.ciudades').find('.group-city').clone();
			 	clonar.appendTo('div.campos-ciudades');
			}
		});
	}
}

/*
	CONVERTIR CONTENIDO DE UNA TABLA A FORMATO JSON
*/
function tableToJson()
{
	var obj = [];
	var status = "";

	$('#table_clients_box tbody tr').each(function () {

		var client = $(this).find("td").eq(0).find("select option:selected").val();
		var box    = $(this).find("td").eq(1).find("input").val();

		if (client != "" && box != "") {

			obj.push({
				id_client : client,
				num_box   : box,
			});

			status = JSON.stringify(obj);

		}else{
			status = "";
		}
	});

	// Agregamos el detalle de la tabla a un capo oculto
	$("#detail_table").val(status);
	console.log(status);

}

function tableToJson2()
{
	var obj = [];
	var status = "";

	$('#table_clients_box_delivery tbody tr').each(function () {
		//tr:not(:first)
		//tr:not(:first)
		var client = $(this).find("td").eq(0).find("select option:selected").val();
		var folio  = $(this).find("td").eq(1).find("input").val();
		var box    = $(this).find("td").eq(2).find("input").val();

		if (client != "" && box != "") {
			obj.push({
				id_client : client,
				num_folio : folio,
				num_box   : box,
		    });
			status = JSON.stringify(obj);
		}else{
			status = "";
				}
		});

	// Agregamos el detalle de la tabla a un capo oculto
	$("#detail_table2").val(status);
	console.log(status);
}