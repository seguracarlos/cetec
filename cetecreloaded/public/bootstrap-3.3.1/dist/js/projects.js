(function($){
	$(document).on("ready", function(){

		/* ********* Asignar datepicker a los input de fechas ********* */
		$("#start_date").datepicker({ dateFormat: 'dd/mm/yy' }).datepicker("setDate", new Date());
		$("#end_date").datepicker({ dateFormat: 'dd/mm/yy' });

		/* ********* Funcion que inicializa varios valores ********* */
		start();

		/* ********** Inputs solo numericos ********** */
		inputsNumeric();

		/* ********* Tabla de los datos del proyecto ********* */
		$("#tableListData").dataTable();

		/* ********* Funcion para cargar los datos de la tabla ********* */
		cargarDatosProyecto();

		/* Agregar las fechas de pago de los proyectos */
		//addDatesOfPayments();

		/* ********* Click en el boton asignar recursos humanos ********* */
		$('#destination_users').on("click", function(){
			
			if($("#users_disp option:selected").val() != null){

				var id_user   = $('#users_disp option:selected').val();
				var cost      = $('#users_disp option:selected').data("cost");
				var name      = $('#users_disp option:selected').text();
				var deleteRow = "<button type='button' class='btn btn-danger'  id='0' onclick='delit(this)'><span class='glyphicon glyphicon-trash'></span></button>";

				var obj = {
				       	//"ROW_ID"          : 1,
				        "ITEM_QUANTITY"   : 1,
				        "ITEM_CONCEPT"    : "Asignación " + name,
				        "ITEM_UNITYPRICE" : cost,
				        "ITEM_PRICE"      : cost
				};  

				var rowid = $('#tableListData').dataTable().fnAddData([
					 	    	obj['ITEM_QUANTITY'],
					 	        obj['ITEM_CONCEPT'],
					 	        obj['ITEM_UNITYPRICE'],
					 	        obj['ITEM_PRICE'],
					 	        deleteRow
				]);
			        	
			    var theNode = $('#tableListData').dataTable().fnSettings().aoData[rowid[0]].nTr;
				theNode.setAttribute('id', 'trUser'+id_user);

				var c       = parseFloat($('#cost').formatCurrency().clone().toNumber().val()) + parseFloat(cost);
				$("#cost").val(c).formatCurrency();
				calcadd();

				$('#users_disp option:selected').remove().appendTo('#users_asig');
				convertTableToJson();

			}else{
				alertify.alert("No hay recursos disponibles o no has seleccionado ningun elemento");
			}

		});

		/* ********* Click en el boton remover recursos humanos ********* */
		$('#origin_users').on("click", function(){

			if($("#users_asig option:selected").val() != null){
						var cost      = $('#users_asig option:selected').data("cost");
						var name      = $('#users_asig option:selected').text();
						var c = parseFloat($('#cost').formatCurrency().clone().toNumber().val()) - parseFloat(cost);

						if (c <= 0){
							$("#cost").val(0).formatCurrency();	
						}
						else{
							$("#cost").val(c).formatCurrency();	
						}

					// Eliminamos de la tabla
					delitRowbyIdUser("trUser"+$('#users_asig option:selected').val());

					// Tabla a json
					convertTableToJson();

					// Calculamos los costos del proyecto
					calcadd();
					$('#users_asig option:selected').remove().appendTo('#users_disp');
			}else{ alertify.alert("No hay recursos asignados"); }
			
		});

		/* ********* Click en el boton asignar servicios ********* */
		$('#destination_services').on("click", function(){

			if( $("#services_disp option:selected").val() != null ) {

			var id_serv   = $('#services_disp option:selected').val();
			var cost      = $('#services_disp option:selected').data("cost");
			var name      = $('#services_disp option:selected').text();
			var deleteRow = "<button type='button' class='btn btn-danger'  id='0' onclick='delit(this)'><span class='glyphicon glyphicon-trash'></span></button>";

			var obj = {
			       	//"ROW_ID"          : 1,
			        "ITEM_QUANTITY"   : 1,
			        "ITEM_CONCEPT"    : "Servicio: " + name,
			        "ITEM_UNITYPRICE" : cost,
			        "ITEM_PRICE"      : cost
			};  

			var rowid = $('#tableListData').dataTable().fnAddData([
				 	    	obj['ITEM_QUANTITY'],
				 	        obj['ITEM_CONCEPT'],
				 	        obj['ITEM_UNITYPRICE'],
				 	        obj['ITEM_PRICE'],
				 	        deleteRow
			]);
		        	
		    var theNode = $('#tableListData').dataTable().fnSettings().aoData[rowid[0]].nTr;
			theNode.setAttribute('id', 'trServ'+id_serv);

			var c       = parseFloat($('#cost').formatCurrency().clone().toNumber().val()) + parseFloat(cost);
			$("#cost").val(c).formatCurrency();
			calcadd();

			$('#services_disp option:selected').remove().appendTo('#services_asig');
			convertTableToJson();

			}else{ alertify.alert("No hay servicios disponibles"); }
			
		});

		/* ********* Click en el boton remover servicios ********* */
		$('#origin_services').on("click", function(){

			if($("#services_asig option:selected").val() != null){
				var cost      = $('#services_asig option:selected').data("cost");
				var name      = $('#services_asig option:selected').text();
				var c = parseFloat($('#cost').formatCurrency().clone().toNumber().val()) - parseFloat(cost);

				if (c <= 0){
					$("#cost").val(0).formatCurrency();	
				}
				else{
					$("#cost").val(c).formatCurrency();	
				}

				// Eliminamos de la tabla
				delitRowbyIdService("trServ"+$('#services_asig option:selected').val());

				// Tabla a json
				convertTableToJson();

				// Calculamos los costos del proyecto
				calcadd();

				$('#services_asig option:selected').remove().appendTo('#services_disp');
			}else{
				alertify.alert("No hay servicios asignados");
			}
			
		});

		/* ********* Click en el boton agregar rows a la tabla del detalle ********* */
		$("#add_button").on("click", function(){
			addit();
		});

		/* ********* Al cambiar el input de precio unitario ********* */
		$('#unityPrice').change(function(){
			$('#unityPrice').formatCurrency();
			$('#price').val(parseFloat($('#unityPrice').clone().toNumber().val()*$('#quantity').val()));
			$('#price').formatCurrency();
		});

		/* ********* Al cambiar el input de precio cantidad ********* */
		$('#quantity').change(function(){
			if($('#unityPrice').val() == ""){
				$('#price').val();
			}else{
				$('#price').val(parseFloat($('#unityPrice').clone().toNumber().val()*$('#quantity').val()));
				$('#price').formatCurrency();
			}
		});	 

		/* ********* Click en el boton guardar proyecto ********* */
		$("#submitbutton").on("click", function(e){
			e.preventDefault();
			/*var table = $('#tableListData').tableToJSON({
       			ignoreColumns: [4]
  			}); // Convert the table into a javascript object
			var jsonTableString = JSON.stringify(table);
			$("#costtable").val(jsonTableString);*/
			//convertTableToJson();
			addTeam();
			addServicesToProject();
			$("#projects").submit();
			//console.log(table);
			//alert(jsonTableString);
		});

	}); /* end document ready*/
})(jQuery);

/* ********* Funcion que inicializa varios valores ********* */
function start()
{
	parseFloat($('#cost').formatCurrency().clone().toNumber().val());
	parseFloat($('#amount').formatCurrency().clone().toNumber().val());
	parseFloat($('#advance').formatCurrency().clone().toNumber().val());
	parseFloat($('#descto').formatCurrency().clone().toNumber().val());	
	parseFloat($('#subtotal').formatCurrency().clone().toNumber().val());
	parseFloat($('#tax').formatCurrency().clone().toNumber().val());
	parseFloat($('#total').formatCurrency().clone().toNumber().val());
}   

/* ********* Funcion que agrega datos a la tabla del detalle ********* */
function addit(){

	if($('#quantity').val() != "" && $('#concept').val() != "" && $('#unityPrice').val() != ""){ 	 	
 		if($('#quantity').val() == 0 || $('#unityPrice').formatCurrency().clone().toNumber().val() == 0){	
 			alertify.alert("Cantidad y Precio no pueden estar en 0.0");	 					
		}else{
			//var itemCount = 0;
			var objs      = [];
			//var deleteRow = '<a href="javascript:;" onclick="delit(this)"><span class="glyphicon glyphicon-trash"></a>';
			//var deleteRow = "<button type='button' class='btn btn-danger remove-item'  id='" + itemCount + "'><span class='glyphicon glyphicon-trash'></span></button>";
			var deleteRow = "<button type='button' class='btn btn-danger'  id='0' onclick='delit(this)'><span class='glyphicon glyphicon-trash'></span></button>";

			var obj = {
			    //"ROW_ID"          : itemCount,
			    "ITEM_QUANTITY"   : $("#quantity").val(),
			    "ITEM_CONCEPT"    : $("#concept").val(),
			    "ITEM_UNITYPRICE" : $("#unityPrice").val(),
			    "ITEM_PRICE"      : $("#price").val()
			};  
		     
		    // add object
		    objs.push(obj);
		    //itemCount++;

		    var rowid = $('#tableListData').dataTable().fnAddData([
			    obj['ITEM_QUANTITY'],
				obj['ITEM_CONCEPT'],
				obj['ITEM_UNITYPRICE'],
				obj['ITEM_PRICE'],
				deleteRow              			
			]);
		        	
		    var theNode = $('#tableListData').dataTable().fnSettings().aoData[rowid[0]].nTr;
			//theNode.setAttribute('id', "tr"+itemCount);
			theNode.setAttribute('id', "0");//Agregamos id al tr generado

	 		$('#price').formatCurrency().clone().toNumber().val(parseFloat($('#unityPrice').val()*$('#quantity').val()));
	 		var price = parseFloat($('#price').formatCurrency().clone().toNumber().val());
	 		 
	 		$('#cost').val(parseFloat($('#cost').formatCurrency().clone().toNumber().val())+price);
	 		
	 		convertTableToJson();
	 		calcadd();
	 		
		}

 		$('#quantity').val("");
	 	$('#concept').val("");
	 	$('#unityPrice').val("");
	 	$('#price').val("");

	}else{
		
		$('#quantity').val("");
	 	$('#concept').val("");
	 	$('#unityPrice').val("");
	 	$('#price').val("");		

	 	alertify.alert("Ingresa datos"); 	
	}

	if($('#quantity').val() > 0 && $('#unityPrice').val() != "" && $('#price').val() != "" && $('#concept').val() != ""){
			if($('#subtotal').val() != ""){
				$('#subtotal').formatCurrency();
				var subtotal = parseFloat($('#subtotal').formatCurrency().clone().toNumber().val()) + parseFloat($('#price').formatCurrency().clone().toNumber().val());
			    $('#subtotal').val(subtotal).formatCurrency();		
		
				$('#tax').val(parseFloat($('#subtotal').clone().toNumber().val()* 0.16 ));
				$('#tax').formatCurrency();
		
				$('#total').val(parseFloat($('#subtotal').clone().toNumber().val()) + (parseFloat($('#tax').clone().toNumber().val())));
				$('#total').formatCurrency();
			}else {
				$('#subtotal').formatCurrency();
				var subtotal = parseFloat($('#price').formatCurrency().clone().toNumber().val());
			    $('#subtotal').val(subtotal).formatCurrency();		
		
				$('#tax').val(parseFloat($('#subtotal').clone().toNumber().val()* 0.16 ));
				$('#tax').formatCurrency();
		
				$('#total').val(parseFloat($('#subtotal').clone().toNumber().val()) + (parseFloat($('#tax').clone().toNumber().val())));
				$('#total').formatCurrency();
			}
	 }
} /* end function addit */

/* ********* Funcion para calcular costos ********* */
function calcadd()
{
	$('#amount').val(0);
	$('#amount').val(parseFloat($('#amount').formatCurrency().clone().toNumber().val())+parseFloat($('#cost').formatCurrency().clone().toNumber().val()));
	$('#amount').formatCurrency();
	$("#amhid").val($("#amount").val());
	calc();
} /* end function calcadd */

/* ********* Funcion para calcular costos ********* */
function calc(){
	
	if ($('#cost').formatCurrency().clone().toNumber().val() <= 0){

		$("#amount").val(0).formatCurrency();
		$("#amhid").val(0).formatCurrency();
		$("#advance").val(0).formatCurrency();
		$("#descto").val(0).formatCurrency();
		$("#subtotal").val(0).formatCurrency();
		$("#tax").val(0).formatCurrency();
		$("#total").val(0).formatCurrency();

	}else{

		var am       = parseFloat($('#amount').formatCurrency().clone().toNumber().val());
		var an       = parseFloat($('#advance').formatCurrency().clone().toNumber().val());
		var descto   = parseFloat($('#descto').formatCurrency().clone().toNumber().val());
		var subtotal = (am - descto) - an;
	
	    $('#subtotal').val(subtotal).formatCurrency();	
		$('#tax').val(parseFloat($('#subtotal').clone().toNumber().val() * 0.16 ));
		$('#tax').formatCurrency();
		$('#total').val(parseFloat($('#subtotal').clone().toNumber().val()) + (parseFloat($('#tax').clone().toNumber().val())));
		$('#total').formatCurrency();	 		
	}
}

/* ********* Funcion para borrar un row de la tabla ********* */
function delit(o){
	
	var pos         = $("#tableListData").dataTable().fnGetPosition($(o).parent().parent()[0]);
	var amounttable = $(o).parent().parent().find('td:eq(3)').clone().toNumber().html();
	var amountfinal = $("#tableListData").dataTable().fnDeleteRow(pos);
	var c           = parseFloat($('#cost').formatCurrency().clone().toNumber().val()) - parseFloat(amounttable);
	
	if (parseFloat($('#cost').formatCurrency().clone().toNumber().val()) <=0 ){
		$("#cost").val(0).formatCurrency();	
	}
	else{
		$("#cost").val(c).formatCurrency();	
	}
	
    calcdel(parseFloat(amounttable));
}

/* ********* Funcion para calcular el importe despues de borrar una fila de la tabla ********* */
function calcdel(c){
	$('#amount').val(parseFloat($('#amount').formatCurrency().clone().toNumber().val())-c);
	$('#amount').formatCurrency();
	//$("#amhid").val($("#amount").val());
	calc();
}

/* ********* Funcion para cargar los datos de la tabla ********* */
function cargarDatosProyecto()
{
	if($("#costtable").val() != 0){
		var data = jQuery.parseJSON($("#costtable").val());

		$.each(data, function(idx, obj) {
			//alert(obj.Concepto);
			var rowid = $('#tableListData').dataTable().fnAddData([
	   			obj.Cantidad,
	   			obj.Concepto,
	   			obj.Precio,
				obj.Importe,
				"<button type='button' class='btn btn-danger'  id='" + idx + "'><span class='glyphicon glyphicon-trash'></span></button>"
			]);
			        	
			var theNode = $('#tableListData').dataTable().fnSettings().aoData[rowid[0]].nTr;
			theNode.setAttribute('id', "tr"+idx);
		});

	}
}

/* ********* Funcion para agregar los recursos humanos ********* */
function addTeam()
{
	var inputids = $("#developersIds");
	var ids      = "";
	var array    = [];

	$('#users_asig option').each(function(){
		array.push($(this).val());
	});

	ids = array.join(', ');
	inputids.val(ids);
}

/* ********* Funcion para agregar los servicios al proyecto ********* */
function addServicesToProject()
{
	var inputids = $("#servicesIds");
	var ids      = "";
	var array    = [];

	$('#services_asig option').each(function(){
		array.push($(this).val());
	});

	ids = array.join(', ');
	inputids.val(ids);
}


function delitRowbyIdUser(id){
	var table = $('#tableListData').DataTable();
	var allData = table.rows("#"+id);

	/*var pos         = $("#tableListData").dataTable().fnGetPosition($(o).parent().parent()[0]);
	var amounttable = $(o).parent().parent().find('td:eq(3)').clone().toNumber().html();
	var amountfinal = $("#tableListData").dataTable().fnDeleteRow(pos);
	var c           = parseFloat($('#cost').formatCurrency().clone().toNumber().val()) - parseFloat(amounttable);*/
	//console.log(id);
	console.log(allData);
	$("#tableListData").dataTable().fnDeleteRow(allData);
	//allData.remove();
	//var arrData = $("#tableListData").dataTable().fnGetData();
	//console.log($("#tableListData").dataTable());
	/*for (var i=0; i < arrData.length; i++) {
		if($("#tableListData").dataTable().fnGetData(i)[0]==id){
			var ii=i;

		}
	}*/

	/*$("#tableListData").dataTable().fnDeleteRow(ii);*/

	/*
		var pos         = $("#tableListData").dataTable().fnGetPosition($(o).parent().parent()[0]);
	var amounttable = $(o).parent().parent().find('td:eq(3)').clone().toNumber().html();
	var amountfinal = $("#tableListData").dataTable().fnDeleteRow(pos);
	var c           = parseFloat($('#cost').formatCurrency().clone().toNumber().val()) - parseFloat(amounttable);
	
	if (parseFloat($('#cost').formatCurrency().clone().toNumber().val()) <=0 ){
		$("#cost").val(0).formatCurrency();	
	}
	else{
		$("#cost").val(c).formatCurrency();	
	}
	
    calcdel(parseFloat(amounttable));
	*/		
}

function delitRowbyIdService(id){
	var table   = $('#tableListData').DataTable();
	var allData = table.rows("#"+id);

	$("#tableListData").dataTable().fnDeleteRow(allData);		
}


/* Funcion para que los inputs sean solo numericos */
function inputsNumeric()
{
	//$("#unityPrice").numeric().formatCurrency().val();
	//$("#price").numeric().formatCurrency().val();
	$("#cost").numeric().keyup(function(){$(this).formatCurrency();});
	$("#amount").numeric().keyup(function(){$(this).formatCurrency();});
	$("#advance").numeric().keyup(function(){$(this).formatCurrency();});
	$("#descto").numeric().keyup(function(){$(this).formatCurrency();});
	$("#subtotal").numeric().keyup(function(){$(this).formatCurrency();});
	$("#tax").numeric().keyup(function(){$(this).formatCurrency();});
	$("#total").numeric().keyup(function(){$(this).formatCurrency();});
}

/* Funcion que convierte los datos de una tabla en json */
function convertTableToJson()
{
	var table = $('#tableListData').tableToJSON({
       			ignoreColumns: [4]
  	}); // Convert the table into a javascript object
	var jsonTableString = JSON.stringify(table);
	$("#costtable").val(jsonTableString);
}

