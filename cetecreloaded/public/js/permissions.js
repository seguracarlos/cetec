$(document).ready(function(){
	//Cambiar idioma a las alertas
	alertify.set({
		labels: {
			ok     : "Aceptar",
			cancel : "Cancelar"
		}
	});

	/**********Click en el boton guardar rol**********/
	/*$("#saveRole").on("click", function(){
		$("#destinationPermissions").val();//Obtenemos los valores del select #permissionsDestination
	});*/

	/*
		CLICK EN EL BOTON GUARDAR ROL
	*/
	$("#saveRole").on("click", function(e){
		e.preventDefault();
		$boton = $(this);
		$boton.attr("disabled", true);
		console.log("Voy a guardar un empleado");
		alertify.confirm("¿Deseas guardar los cambios?", function(e){
			if(e){
				console.log("aceptaste");
				$("#permissionsForm").submit();
			}else{
				$boton.attr("disabled", false);
			}
		});
	});

	/***************Click en el boton asignar permisos >>***************/
	$('#origin').click(function(){
		return !$('#permissions option:selected').remove().appendTo('#destinationPermissions');
	});

	/***************Click en el boton quitar permisos <<***************/
	$('#destination').click(function(){
		//Verificamos si los option tienen el data-status
		if($("#destinationPermissions option:selected").data("status") == "update"){
			//Confirmacmos si se van a borrar los permisos al rol
			alertify.confirm("<p>Estas seguro de eliminar el rol con sus permisos</p>", function (e) {
				if(e){
					//alertify.success("Has pulsado '" + alertify.labels.ok + "'");
					$.ajax({
						url: $basePath + "/system/permissions/deletepermissionsrole",
						type: "POST",
						dataType: "json",
						data: {"identifier" : "del", "id_resource" : $("#destinationPermissions option:selected").val(), "id_role" : $id_role },
						success: function(response){
							if(response.response == "true"){
								//Quitamos el data-estatus, quitamos el option:selected y mandamos alerta
								$('#destinationPermissions option:selected').removeAttr("data-status");
								$('#destinationPermissions option:selected').remove().appendTo('#permissions');
								alertify.success("Has pulsado '" + alertify.labels.ok + "'");
							}
						}
					});
				}else{
					alertify.error("Has pulsado '" + alertify.labels.cancel + "'");
				}
			});
			return false;
		}else{
			return !$('#destinationPermissions option:selected').remove().appendTo('#permissions');
		}
	});

	/*
		DEOBLE CLICK EN CADA RECURSO
	*/
	$("#destinationPermissions").on("dblclick", function(){
		$id_resource = $("#destinationPermissions option:selected").val();
		//Validamos si el select de permisos asignados esta vacio o no
		if($("#destinationPermissions").val() == null){
			alertify.alert("No hay permisos asignados");
		}else{
			//Validamos si la lista de permisos por recurso esta visible ono
			if($("#checkPermissions").is(':visible')){
				alertify.alert("Primero guarda los permisos actuales");
			}
			else if($("#checkPermissions").is(':hidden')){
				//Deshabilitamos botones de origen y detino
				ButtonDisable("origin","Bloqueado");
				ButtonDisable("destination","Bloqueado");
				//Validamos si los permisos son nuevos o ya existen
				if($("#destinationPermissions option:selected").data("status") == "update"){
					console.log("Ya tienes permisos asignado");
					$.ajax({
						url: $basePath + "/system/permissions/getpermissionbyidresource",
						type: "POST",
						dataType: "json",
						data: {"pbr" : "ok", "id_role" : $id_role, "id_resource" : $id_resource},
						success: function(response){
							console.log(response.permissionbyrole);
							$("#showPermissions").html("");
							var table = '<ul class="list-group">';
							$.each(response.permissionbyrole,function(index, obj){
								//Validamos si el permiso esta activo o inactivo
								if(obj.status == 1){
									table += '<li class="list-group-item">'
										  + 	'<label class="label-checkbox" for="perm'+obj.id+'">'
    								  	  +			'<input type="checkbox" id="perm'+obj.id+'" data-permission="'+obj.id+'" checked="true" value="'+obj.id_role_perm+'" />'
    								  	  +			'<i></i>'
    								  	  +			'<span>'+ obj.agroupName + " - " + obj.name_esp +'</span>'
    								  	  +		'</label>'
										/*
										  +  '<input type="checkbox" id="perm'+obj.id+'" data-permission="'+obj.id+'" checked="true" value="'+obj.id_role_perm+'"/>'
										  +  ''+" "+'<label for="perm'+obj.id+'">'+obj.agroupName + " - " + obj.name_esp +'</label>'
										*/
										  +  '</li>';
								}else{
									table += '<li class="list-group-item">'
										  + 	'<label class="label-checkbox" for="perm'+obj.id+'">'
    								  	  +			'<input type="checkbox" id="perm'+obj.id+'" data-permission="'+obj.id+'" value="'+obj.id_role_perm+'" />'
    								  	  +			'<i></i>'
    								  	  +			'<span>'+ obj.agroupName + " - " + obj.name_esp +'</span>'
    								  	  +		'</label>'
										/*
										  +  '<input type="checkbox" id="perm'+obj.id+'" data-permission="'+obj.id+'" value="'+obj.id_role_perm+'"/>'
										  +  ''+" "+'<label for="perm'+obj.id+'">'+obj.agroupName + " - " + obj.name_esp +'</label>'
										*/
										  +  '</li>';
								}
							});
							table += '</ul>';
							$("#showPermissions").append(table);
							$("#checkPermissions").slideDown(1000);
						}
					});
				}else{
					console.log("No tienes permisos asignado");
					$.ajax({
						url: $basePath + "/system/permissions/getpermissionbyidresource",
						type: "POST",
						dataType: "json",
						data: {"id_resource" : $id_resource },
						success: function(response){
							console.log(response);
							$("#showPermissions").html("");
							var table = '<ul class="list-group">';
							$.each(response.data,function(index,obj){
								table += '<li class="list-group-item">'
									  + 	'<label class="label-checkbox" for="perm'+obj.id+'">'
    								  +			'<input type="checkbox" id="perm'+obj.id+'" data-permission="'+obj.id+'" value="" />'
    								  +			'<i></i>'
    								  +			'<span>'+ obj.agroupName + " - " + obj.name_esp +'</span>'
    								  +		'</label>'
										/*
										+ '<input type="checkbox" id="perm'+obj.id+'" data-permission="'+obj.id+'" value=""/>'
										+ ''+" "+'<label for="perm'+obj.id+'">'+ obj.agroupName + " - " + obj.name_esp +'</label>'
										*/
									  +	'</li>';
							});
							table += '</ul>';
							$("#showPermissions").append(table);
							$("#checkPermissions").slideDown(1000);
							//console.log(table);
						}
					});//Fin de ajax
				}
			}//Fin de la validacion de la lista de permisos si esta oculta
		}
		//alert($("#destinationPermissions option:selected").text());
	});

	/*
		CLICK EN EL BOTON GUARDAR O ACTUALIZAR PERMISOS DE UN ROL
	*/
	$("#savePermissions").on("click", function(e){
		e.preventDefault();
		var array  = [];
		var status = 0;
		//Preguntamos si se desea o no asignar los permisos
		alertify.confirm("Seguro de asignar permisos?", function(e){
			if (e) {
				console.log("Guardaste los permisos");
				//Recorremos todos los checkbox y sacamos sus valores
				$("#showPermissions ul.list-group li.list-group-item input[type=checkbox]").each(function(index, el) {
					//Asignamos status 1. Se selecciono 0. No esta seleccionado
					if (this.checked) { status = 1;} else if(!this.cheched){ status = 0;}
					//Creamos un objeto por cada checkbox
					array.push({
						id 				   : $(this).val(),
						id_role_permission : $id_role,
						status             : status,
						id_permission      : $(this).data('permission')
					});
				});
				console.log(array);
				console.log(JSON.stringify(array));
				//Llamamos a la funcion ajax que agrega o edita los permisos
				if($("#destinationPermissions option:selected").data("status") == "update"){
					console.log("Actualizamos permisos");
					addOrUpdatePermissionsOfRole(array,"edit","ed");
				}else{
					console.log("Agregamos permisos");
					addOrUpdatePermissionsOfRole(array,"add","ad");
				}
				//Convierte el arreglo a json
				//var jsonString = JSON.stringify(array);
				//Ocultamos la lista con permisos
				$("#checkPermissions").slideUp(1000);
				//Habilitaos los botones de origen y destino
				ButtonEnable("origin","<span class='glyphicon glyphicon-chevron-right'></span><span class='glyphicon glyphicon-chevron-right'></span>");
				ButtonEnable("destination","<span class='glyphicon glyphicon-chevron-left'></span><span class='glyphicon glyphicon-chevron-left'></span>");
			}
		});
	});

	/*
		CLICK EN EL BOTON CANCELAR PERMISOS DE UN ROL
	*/
	$("#cancelPermissions").on("click", function(e){
		e.preventDefault();
		//Ocultamos la lista con permisos
		$("#checkPermissions").slideUp(1000);
		//Habilitaos los botones de origen y destino
		ButtonEnable("origin","<span class='glyphicon glyphicon-chevron-right'></span><span class='glyphicon glyphicon-chevron-right'></span>");
		ButtonEnable("destination","<span class='glyphicon glyphicon-chevron-left'></span><span class='glyphicon glyphicon-chevron-left'></span>");
	});

	//Boton de cancelar nuevo rol
	$("#cancelPermission").on("click", function(){
		return history.back();
	});
});

//Deshabilita botones y cambia html
function ButtonDisable(button, txt)
{
	$("#" + button).attr("disabled", "true");
	$("#" + button).html(txt);
}

//Habilita botones y cambia html
function ButtonEnable(button, txt){
	$("#"+button).removeAttr('disabled');
	$("#"+button).html(txt);
}

/*
	FUNCION AJAX PARA EDITAR Y AGREGAR PERMISOS
*/
function addOrUpdatePermissionsOfRole(array, path, identifier){
	//{"parametro1" : "valor1", "parametro2" : "valor2"}
	$.ajax({
		url: $basePath + "/system/permissions/" + path,
		type: "POST",
		dataType: "json",
		data: {"identifier" : identifier, "permissions" : array },
		success: function(response){
			//alert(response.response);
			$("#destinationPermissions option:selected").attr("data-status","update");
		}
	});
}