(function($){
	$(document).on("ready", function(){

	    /*
			Click en boton agregar pagos
	    */
	    $("#buttonModalPayments").on("click", function(){
	    	$("#modalPayments")
	    		.on('shown.bs.modal', function(e){ 
	    			addDatesOfPayments();
	    		})
	    		.on('hidden.bs.modal', function(){
	    			addDatesToInputHidden();
	    		})
	    		.modal({ keyboard: false, backdrop : 'static' });
	    }); /* end click buttonModalPayments */
		
	}); /* end document ready*/
})(jQuery);

	function addDatesOfPayments()
	{
		var total            = 0;
		var totalInput       = $('#total');    // input total
		totalInput.formatCurrency();          // Formato al input total
		var advanceInput     = $('#advance'); // Input anticipo
		advanceInput.formatCurrency();        // Formato al input anticipo
		var ivaInput         = $("#iva");     // Input iva
		var datesHiddenInput = $("#dates");   // Input donde se acumulan las fechas

		/*DIVISION*/
		var inputDateValue   = datesHiddenInput.val();       // variable que guarda el valor de las fechas
		var pays             = $("#numberofpayments").val(); // Valor del input n. pagos
		
		totalInput.toNumber();   // Convertimos a numero el valor de total
		advanceInput.toNumber(); // Convertimos a numero el valor de anticipo
		
		var advance         = parseFloat(advanceInput.val()); // Parseamos a float el valor de anticipo
		var subtotal        = parseFloat(totalInput.val());   // Parseamos a float el valor de total

		if(subtotal > advance && advance != 0){
			total =  subtotal - advance; // Acumulamos el total
		}else {
			total =  totalInput.val() ; // Acumulamos el total
		}

		//$("#max").remove();

		var date = new Date(); // Creamos una variable con la fecha actual
		var pay  = total / pays; // Dividimos el totalentre el numero de pagos

		if(pays != "" && pays != 0 && total != "" && total != 0 ){
			if(subtotal > (advanceInput.val()) || (advanceInput.val())==0 || (advanceInput.val())=="" ){
			
				//$("#paysArea").html(null);
				$("#modal-body-payments").html("");
				var j = 0; 
				var x = 0;
				
				for(var i = 0; pays > i; i++){
					j++;
					if ( j < 6 && pays < 6 && pays.length < 2 ){
					
					//centerPopup("paysBack","paysPop");
					//loadPopup("paysBack","paysPop");
					
					var month = date.getMonth() + (1 + j);
					var year  = date.getFullYear();

					if(month > 12){x++;month = x; year++;}
					if(month < 10){month = "0"+month;}
					if(month == 2){
						day = 28;
					}

					var dateInputVal = inputDateValue.split(",");
					var displayDate  = (inputDateValue == "") ? date.getDate() + "/" + month + "/" + year : dateInputVal[i] == undefined ? "":dateInputVal[i] ; 

					$("#modal-body-payments").append( "<div  style='padding:3px;'  class='row'>"
						+  "<div class='col-md-12'>"
						+   "<div class='col-md-6'>"
						+    "<table>"
						+    	"<tbody>"
						+        "<tr>"
						+         "<td style='vertical-align:middle; padding:3px;'>"
						+          "<label class='text-info'>Pago "+j+":</label>"
						+         "</td>"
						+         "<td>"
						+    	   "<input type='text' id='pay"+j+"' class='form-control' value='"+pay+"' readonly  />"
						+         "</td>"
						+        "</tr>"
						+    	"</tbody>"
						+    "</table>"
						+   "</div>"
						+   "<div class='col-md-6'>"
						+    "<table>"
						+    	"<tbody>"
						+        "<tr>"
						+         "<td style='vertical-align:middle; padding:3px;'>"
						+          "<label class='text-info'>Fecha "+j+":</label>"
						+         "</td>"
						+         "<td>"
						+          "<input type='text' id='datepicker"+j+"' class='form-control inputDate' value='"+displayDate+"' />"
						+         "</td>"
						+        "</tr>"
						+    	"</tbody>"
						+    "</table>"
						+   "</div>"
						+  "</div>"
						+ "</div>");

						$('#pay'+j).formatCurrency();
						$("#datepicker"+j).datepicker({
							dayNames        : ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"],
							dayNamesMin     : ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sá"],
							dayNamesShort   : ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"],
							monthNames      : ["Enero", "Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"],
							monthNamesShort : ["Ene", "Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"],
							defaultDate     : +j*30,
							dateFormat      : "dd/mm/yy",
							altFormat       : "yy-mm-dd",
							showButtonPanel : true,
							currentText     : "Hoy",
							closeText       : "Cerrar"
												
						});

					} /* end if */
				} /* end for */
			if( pays > 5 ){ $("#paysCalendar").after("<label id='max' style='color:red;'><br/>maximo 5 pagos/minimo 1</label>"); }
		
			}
			else if ( subtotal<advance ) {
				$("#paysCalendar").after("<label id='max' style='color:red;'><br/>El total debe de ser mayor que el anticipo</label>");
			} /* end else if */
		} /* end  if*/
			
		
	}

	/* Agrega las fechas al in´put de fechas */
	function addDatesToInputHidden()
	{
		var arrayDate = new Array();
		$('#total').formatCurrency();
		//advanceInput.formatCurrency();
		$("#dates").val("");
		$("#modal-body-payments input.inputDate").each(function(index) {
			arrayDate.push($(this).val());
		});
		$("#dates").val(arrayDate);
	}