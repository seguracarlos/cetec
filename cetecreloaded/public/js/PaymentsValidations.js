/*
	ACTIVAR O DESACTIVAR CUENTA
*/
$(document).on("click", ".confirmAccount", function(){
		var $checkbox = $(this);
		var $idShipping = $checkbox.data('shipping');

			var $objeto = {
			"checkbox":$checkbox,
			"id_shipping":$idShipping
		};
		
		if ($checkbox.is(":checked")) {
			alertify.confirm("Estas seguro de activar esta cuenta?", function(result) {
				if (result) {
					$checkbox.prop("checked", true);
					activateAccount($objeto,1);
				   	//console.log("La cuenta fue activada.");				   	
				} else {
					$checkbox.prop("checked", false);
					//console.log("No se pudo activar la cuenta.");
				}
			});
		}else{
			alertify.confirm("Estas seguro de desactivar esta cuenta?", function(result) {
				if (result) {
					$checkbox.prop("checked", false);
				   console.log("Deshabilitaste la cuenta");
				   activateAccount($objeto,0);
				} else {
					$checkbox.prop("checked", true);
					console.log("No se deshabilito la cuenta");
				}
			});
		};
	});


function activateAccount($objeto,status)
{
	//var table = $('#shippings').DataTable();
	var tr    = $objeto.checkbox.parents('tr');
	var $email = tr.find("td:eq(2)").text();
	var $trim  = tr.find("td:eq(3)").html();

	var $objPost = {	
			key    : 1,
			id     : $objeto.id_shipping,
			email  : $email,
            status : status,
            trim   : $trim
        };
	
	console.log($objPost);
	$.ajax({
		method   : "POST",
		url      : $basePath + "/horus/employee/confirmshipping",
		dataType : "json",
		data     : $objPost
	})
	.done(function(response) {
		console.log("success");
		// Cerramos el fondo de espere...
		$.unblockUI();
		//Validar si el response devuelve ok
		if (response.response == "ok") {
			//Validar tipo de status 1.- Confirmada 2.-Pendiente
			if (status == 1) {
				//Cambiar texto y color del titulo de status
				tr.find('td:eq(6) span').text("Activa").removeClass("label-danger").addClass("label-success");
				//Deshabilitar boton de modificar
				//tr.find('td:eq(5) a').attr("disabled", true);
				//Deshabilitar boton de eliminar
				//tr.find('td:eq(6) a').attr("disabled", true);
				alertify.success('La cuenta se activo correctamente');
			}else{
				tr.find('td:eq(6) span').text("Inactiva").removeClass("label-success").addClass("label-danger");
				//tr.find('td:eq(5) a').attr("disabled", false);
				//tr.find('td:eq(6) a').attr("disabled", false);
				alertify.log('La cuenta se desactivo correctamente')
			}

		}else if(response.response == "paymentsFail"){
			$objeto.checkbox.prop("checked", false);
			alertify.alert("El alumno no ha cumplido con los pagos necesarios para activar su cuenta");
		}

	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
}

/* Confirmar Inscripcion */

$(document).on("click", ".confirmInscription", function(){
	var $idUser = $(this).attr("data-id");
	var $inscription = $(this);
	var $studentName = $(this).closest('tr').find('.studentName').html();
	if ($inscription.is(":checked")) {
		alertify.confirm("Confirmar pago de Inscripci\u00F3n de:"+$studentName, function(result) {
			if (result) {
			$inscription.prop("checked", true);
			$.ajax({
				method   : "POST",
				url      : $basePath + "/horus/employee/payments",
				dataType : "json",
				data     : {payment : "inscription" , status : 1, id_user : $idUser}
			})
			.done(function(response) {
				$.unblockUI();
				alertify.alert("El pago de Inscripci\u00F3n de: "+$studentName+" fue registrado correctamente.");
			})
			.fail(function() {
				alertify.alert("Ocurrio un error, intentelo de nuevo.");
				console.log("error");
			})
			.always(function() {
				console.log("complete");
			});
						
			} else {
				$inscription.prop("checked", false);
			}
		});
	}else if(!($inscription.is(":checked"))){
			alertify.confirm("Cancelar pago de Inscripción de:"+$studentName, function(result) {
				if (result) {
					$.ajax({
						method   : "POST",
						url      : $basePath + "/horus/employee/payments",
						dataType : "json",
						data     : {payment : "inscription", status : 0, id_user : $idUser}
					})
					.done(function(response) {
						$.unblockUI();
						alertify.alert("El pago de Inscripci\u00F3n de: "+$studentName+" fue cancelado correctamente.");
						$inscription.prop("checked", false);
					})
					.fail(function() {
						alertify.alert("Ocurrio un error, intentelo de nuevo.");
						console.log("error");
					})
					.always(function() {
						console.log("complete");
					});
				}else{
				 $inscription.prop("checked", true);
				}
			})
	}
})

/* Confirmar pago de Mes 1 */

$(document).on("click", ".confirmMonth1", function(){
	var $idUser = $(this).attr("data-id");
	var $studentName = $(this).closest('tr').find('.studentName').html();
	var $inscription = $(this).closest('tr').find('.confirmInscription');
	var $month1 = $(this);
	var $month2 =  $(this).closest('tr').find('.confirmMonth2');
	var $month3 =  $(this).closest('tr').find('.confirmMonth3');

	if ($month1.is(":checked")) {
		if(!$inscription.is(':checked')){
			alertify.alert("Aun no se ha realizado el pago de inscripci&oacute;n", function(result) {
			$month1.prop("checked", false);
			});
		}else{
			alertify.confirm("Confirmar pago del Mes 1 para:"+$studentName, function(result) {
			if (result) {
				$month1.prop("checked", true);
				$.ajax({
					method   : "POST",
					url      : $basePath + "/horus/employee/payments",
					dataType : "json",
					data     : {payment : "month_1" ,status : 1, id_user : $idUser}
				})
				.done(function(response) {
					$.unblockUI();
					alertify.alert("El pago del Mes 1 de: "+$studentName+" fue registrado correctamente.");
					console.log("success");
				})
				.fail(function() {
					alertify.alert("Ocurrio un error, intentelo de nuevo.");
					console.log("error");
				})
				.always(function() {
					console.log("complete");
				});
				} else {
					$month1.prop("checked", false);
				}
			});
		  }
		}else if(!($month1.is(":checked"))){
			alertify.confirm("Cancelar pago del Mes 1 de:"+$studentName, function(result) {
				if (result) {
					$.ajax({
						method   : "POST",
						url      : $basePath + "/horus/employee/payments",
						dataType : "json",
						data     : {payment : "month_1" ,status : 0, id_user : $idUser}
					})
					.done(function(response) {
						$.unblockUI();
						alertify.alert("El pago del Mes 1 de: "+$studentName+" fue cancelado correctamente.");
						$month1.prop("checked", false);
					})
					.fail(function() {
						alertify.alert("Ocurrio un error, intentelo de nuevo.");
						console.log("error");
					})
					.always(function() {
						console.log("complete");
					});
				}else{
					$month1.prop("checked", true);
				}
			})
		  }
	})
	
/* Confirmar pago de Mes 2 */
	
$(document).on("click", ".confirmMonth2", function(){
	var $idUser = $(this).attr("data-id");
	var $studentName = $(this).closest('tr').find('.studentName').html();
	var $inscription = $(this).closest('tr').find('.confirmInscription');
	var $month1 =  $(this).closest('tr').find('.confirmMonth1');
	var $month2 =  $(this);
	var $month3 =  $(this).closest('tr').find('.confirmMonth3');

		if ($month2.is(":checked")) {
			if(!$month1.is(':checked')){
				alertify.alert("Aun no se ha realizado el pago del primer mes", function(result) {
				$month2.prop("checked", false);
				});
			}else{
				alertify.confirm("Confirmar pago del Mes 2", function(result) {
				if (result) {
				$month2.prop("checked", true);
				$.ajax({
					method   : "POST",
					url      : $basePath + "/horus/employee/payments",
					dataType : "json",
					data     : {payment : "month_2" ,status : 1,id_user : $idUser}
				})
				.done(function(response) {
					$.unblockUI();
					alertify.alert("El pago del Mes 2 de: "+$studentName+" fue registrado correctamente.");
					console.log("success");
				})
				.fail(function() {
					alertify.alert("Ocurrio un error, intentelo de nuevo.");
					console.log("error");
				})
				.always(function() {
					console.log("complete");
				});
				} else {
				$month2.prop("checked", false);
				}
			});
		  }
		}else if(!($month2.is(":checked"))){
			alertify.confirm("Cancelar pago del Mes 2 de:"+$studentName, function(result) {
				if (result) {
					$.ajax({
						method   : "POST",
						url      : $basePath + "/horus/employee/payments",
						dataType : "json",
						data     : {payment : "month_2" ,status : 0, id_user : $idUser}
					})
					.done(function(response) {
						$.unblockUI();
						alertify.alert("El pago del Mes 2 de: "+$studentName+" fue cancelado correctamente.");
						$month2.prop("checked", false);
					})
					.fail(function() {
						alertify.alert("Ocurrio un error, intentelo de nuevo.");
						console.log("error");
					})
					.always(function() {
						console.log("complete");
					});
				}else{
					$month2.prop("checked", true);
				}
			})
		}
	})
	
/* Confirmar pago de Mes 3 */
	
$(document).on("click", ".confirmMonth3", function(){
	var $idUser = $(this).attr("data-id");
	var $studentName = $(this).closest('tr').find('.studentName').html();
	var $inscription = $(this).closest('tr').find('.confirmInscription');
	var $month1 =  $(this).closest('tr').find('.confirmMonth1');
	var $month2 =  $(this).closest('tr').find('.confirmMonth2');;
	var $month3 =  $(this);

	if ($month3.is(":checked")) {
		if(!$month2.is(':checked')){
			alertify.alert("Aun no se ha realizado el pago del Segundo Mes", function(result) {
			$month3.prop("checked", false);
			});
		}else{
			alertify.confirm("Confirmar pago del Mes 3", function(result) {
			if (result) {
				$month3.prop("checked", true);
				$.ajax({
					method   : "POST",
					url      : $basePath + "/horus/employee/payments",
					dataType : "json",
					data     : {payment : "month_3" ,status : 1, id_user : $idUser}
				})
				.done(function(response) {
					console.log("success");
					$.unblockUI();
					alertify.alert("El pago del Mes 3 de: "+$studentName+" fue registrado correctamente.");
					console.log("success");
				})
				.fail(function() {
					alertify.alert("Ocurrio un error, intentelo de nuevo.");
					console.log("error");
				})
				.always(function() {
					console.log("complete");
				});
				} else {
					$month3.prop("checked", false);
				}
			});
		  }
		}else if(!($month3.is(":checked"))){
			alertify.confirm("Cancelar pago del Mes 3 de:"+$studentName, function(result) {
				if (result) {
					$.ajax({
						method   : "POST",
						url      : $basePath + "/horus/employee/payments",
						dataType : "json",
						data     : {payment : "month_3" ,status : 0, id_user : $idUser}
					})
					.done(function(response) {
						$.unblockUI();
						alertify.alert("El pago del Mes 3 de: "+$studentName+" fue cancelado correctamente.");
						$inscription.prop("checked", false);
					})
					.fail(function() {
						$.unblockUI();
						alertify.alert("Ocurrio un error, intentelo de nuevo.");
						console.log("error");
					})
					.always(function() {
						console.log("complete");
					});
				}else{
					$month3.prop("checked", true);
				}
			})
		}
	})
	
/* Confirmar entrega de documentos */
	
	$(document).on("click", ".Documents", function(){
		var $check = $(this);
		var $idUser = $(this).attr("data-id");
		var $studentName = $(this).closest('tr').find('.studentName').html();
		$documents = 0;
		if ($(this).is(":checked")) {
			$documents = 1;
			alertify.confirm("Confirmar entrega de documentos de:"+$studentName, function(result) {
				if(result){
					$.ajax({
						method   : "POST",
						url      : $basePath + "/horus/employee/documents",
						dataType : "json",
						data     : {documents : $documents , user_id : $idUser}
						})
						.done(function(response) {
							console.log("success");
							$.unblockUI();
							alertify.alert("Entrega de documentos guardada");
						})
						.fail(function() {
							$.unblockUI();
							console.log("error");
						})
						.always(function() {
							console.log("complete");
						});
				}else{
					$check.prop("checked", false);
				}
		})
	  }else{
		  alertify.confirm("Seguro que desea cancelar la entrega de documentos de:"+$studentName, function(result) {
				if(result){
					$.ajax({
						method   : "POST",
						url      : $basePath + "/horus/employee/documents",
						dataType : "json",
						data     : {documents : $documents , user_id : $idUser}
						})
						.done(function(response) {
							console.log("success");
							$.unblockUI();
							alertify.alert("Entrega de documentos cancelada");
							$check.prop("checked", false);
						})
						.fail(function() {
							$.unblockUI();
							console.log("error");
						})
						.always(function() {
							console.log("complete");
						});
				}else{
					$check.prop("checked", true);
				}
		}) 
	  }
	});

/* Siguiente Trimestre */

$(document).on("click", ".check-confirm-nextTrim", function(){
	var $nextTrim = $(this);
	var $inscription = $("#inscription");
	var $month1 = $("#month_1");
	var $month2 = $("#month_2");
	var $month3 = $("#month_3");
	
	if ($nextTrim.is(":checked")) {
		if(!$inscription.is(':checked')||!$month1.is(':checked')||!$month2.is(':checked')||!$month3.is(':checked')){
			alertify.alert("El alumno no ha cumplido con todos los pagos para cambiar al siguiente trimestre");
			$nextTrim.prop("checked", false);
		}else{
		alertify.confirm("Seguro que deseas habilitar el siguiente trimestre?", function(e) {
			if (e) {
				$nextTrim.prop("checked", true);
				$.ajax({
					method   : "POST",
					url      : $basePath + "/horus/employee/payments",
					dataType : "json",
					data     : {payment : "nextTrim" , id_user : $id_user}
				})
				.done(function(response) {
					console.log("success");
					if(response.data=="Score"){
						alertify.alert("El alumno no ha aprobado el examen para el siguiente trimestre");
						$nextTrim.prop("checked", false);
					}
					if(response.data=="6"){
						$("#next").fadeOut();
					}
					if(response.data!="Score"||response.data<6){
						alertify.success("El alumno a avanazo de trimestre");
						$("#trim").html(response.data);
						if(response.data==5){
							$inscription.prop("checked",false);
							$inscription.attr("disabled", false);
						}else{
							$inscription.attr("disabled", true);
						}							
						$month1.prop("checked",false);
						$month2.prop("checked",false);
						$month3.prop("checked",false);
						$nextTrim.prop("checked", false);							
					}
				})
				.fail(function() {
					console.log("error");
				})
				.always(function() {
					console.log("complete");
				});
			} else {

				$nextTrim.prop("checked", false);
			}
		});
	  }
	}
})
