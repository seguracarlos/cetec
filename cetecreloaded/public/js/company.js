function showbtnchangelog(){
	$('#btnchangelog').html('<input type="file" accept="image/*" name="filelogo" id="filelogo" onchange="onFileSelected(event)" /><br><input type="button" class="btn btn-success" value="Guardar" onclick="updatelogo()" /><br><br><input type="button" class="btn btn-danger" value="Cancelar" onclick="showchangeimag()" />');
}
function showchangeimag(){
	var baseurl = basep+'/company/getlogo';
	$.ajax({
		type: "POST",
		url: baseurl,
		dataType: "json",
		success: function(response){
			var img;
			if(response.data){
				img = response.data;
			}else{
				img = 'https://lh5.googleusercontent.com/-b0-k99FZlyE/AAAAAAAAAAI/AAAAAAAAAAA/eu7opA4byxI/photo.jpg?sz=100';
			}
			$('#companylog').attr('src',img);
			$('#imglogo').val('');
			$('#btnchangelog').html('<input type="button" value="Cambiar logotipo" class="btn btn-success" onclick="showbtnchangelog()"/>');
		},
		error: function(){
			alert("Fallo, intentalo otra vez");
		}
	});
}
function onFileSelected(event){
	var selectedFile = event.target.files[0];
	var reader = new FileReader();

	var imgtag = document.getElementById("filelogo");
	imgtag.title = selectedFile.name;

	reader.onload = function(event) {
		$('#companylog').attr('src',event.target.result);
	    $('#imglogo').val(event.target.result);
	};

	reader.readAsDataURL(selectedFile);

}
function updatelogo(){
	var imag = $("#imglogo").val();
	if(imag == ''){
		alert('Debes elegir una imagen para el logo de la empresa.');
		return;
	}
	var baseurl = basep+'/company/updatelogo';
	$.ajax({
		type: "POST",
		url: baseurl,
		data: {'image' : imag},
		dataType: "json",
		success: function(response){
			$('#companylog').attr('src',response.data);
			$('#imglogo').val('');
			$('#btnchangelog').html('<input type="button" value="Cambiar logotipo" class="btn btn-success" onclick="showbtnchangelog()"/>');
			alert('Imágen guardada correctamente!');
		},
		error: function(){
			alert("Fallo, intentalo otra vez");
		}
	});
}
function editInfo(){
	var company = company_users;
	var baseurl = basep+'/company/getinfo';
	$.ajax({
		type: "POST",
		url: baseurl,
		data: {'company' : company},
		dataType: "json",
		success: function(response){
			$("#editsavecancel").html('<div class="col-md-6"><input style="text-aling:center;width:90%;" type="button" value="Guardar" class="btn btn-success" onclick="saveEdit()" /></div><div class="col-md-6"><input style="text-aling:center;width:90%;" type="button" value="Cancelar" class="btn btn-danger" onclick="cancelEdit()" /></div>');
			$("#namecompany-companytd").html('<input class="form-control" name="name_company" style="width:100%;" type="text" value="'+response.data.name_company+'" />');
			$("#marca-companytd").html('<input class="form-control" name="brand" style="width:100%;" type="text" value="'+response.data.brand+'" />');
			$("#rfc-companytd").html('<input class="form-control" name="rfc" style="width:100%;" type="text" value="'+response.data.rfc+'" />');
			$("#website-companytd").html('<input class="form-control" name="website" style="width:100%;" type="text" value="'+response.data.website+'" />');
			$("#business-companytd").html('<input class="form-control" name="business" style="width:100%;" type="text" value="'+response.data.business+'" />');
			$("#phone-companytd").html('<input class="form-control" name="phone" style="width:100%;" type="text" value="'+response.data.phone+'" />');
			$("#extension-companytd").html('<input class="form-control" name="extension" style="width:100%;" type="text" value="'+response.data.extension+'" />');
			$("#map-companytd").html('<input class="form-control" name="map" style="width:100%;" type="text" value="'+response.data.map+'" />');
			$("#statename-directiontd").html('<select class="form-control" style="width:100%;" name="states" id="states" onchange="changeState()" ><option value="0" selected>--Selecciona un estado--</option></select>');
			$("#districtname-directiontd").html('<select class="form-control" style="width:100%;" name="districts" id="districts" onchange="changeDistrict()"><option value="0" selected>--Selecciona Del. o Mpio.--</option></select>');
			$("#colony-directiontd").html('<select class="form-control" style="width:100%;" name="colonys" id="colonys" onchange="changeColony()"><option value="0" selected>--Selecciona una colonia--</option></select>');
			$("#street-directiontd").html('<input class="form-control" name="street" style="width:100%;" type="text" value="'+response.data.street+'" />');
			$("#number-directiontd").html('Numero exterior: <input class="form-control" name="number_ext" style="width:100%;" type="text" value="'+response.data.number_ext+'" /><br>Numero interior: <input class="form-control" name="number_int" style="width:90%;" type="text" value="'+response.data.number_int+'" />');
			$("#postalcode-directiontd").html('<input class="form-control" id="postal_code" name="postal_code" style="width:100%;" disabled="true" type="text" value="'+response.data.postal_code+'" />');
			$("#interbankclabe-banktd").html('<input class="form-control" name="interbank_clabe" style="width:100;" type="text" value="'+response.data.interbank_clabe+'" />');
			$("#numberacount-banktd").html('<input class="form-control" name="number_acount" style="width:100%;" type="text" value="'+response.data.number_acount+'" />');
			$("#namebank-banktd").html('<input class="form-control" name="name_bank" style="width:100%;" type="text" value="'+response.data.name_bank+'" />');
			$("#sucursalname-banktd").html('<input class="form-control" name="sucursal_name" style="width:100%;" type="text" value="'+response.data.sucursal_name+'" />');
			$.each( response.data.states, function( key, value ) {
				$('#states').append('<option value="'+value.id+'">'+value.state+'</option>');	
			});
			$('#states').val(response.data.id_state);
			$.each( response.data.districts, function( key, value ) {
				$('#districts').append('<option value="'+value.id+'">'+value.name+'</option>');	
			});
			$('#districts').val(response.data.id_district);
			$.each( response.data.colonys, function( key, value ) {
				$('#colonys').append('<option value="'+value.id+'">'+value.colony+'</option>');	
			});
			$('#colonys').val(response.data.id_colony);
			if(response.data.userFavorite != ''){
				$('#userfavorite-numberemployee').html('<td>Nombre:</td><td><input class="form-control" name="userf-name" style="width:100%;" type="text" value="'+response.data.userFavorite[0]['name']+'" /></td>');
				$('#userfavorite-rfc').html('<td>Ap.Paterno:</td><td><input class="form-control" name="userf-surname" style="width:100%;" type="text" value="'+response.data.userFavorite[0]['surname']+'" /></td>');
				$('#userfavorite-name').html('<td>Ap.Materno:</td><td><input class="form-control" name="userf-lastname" style="width:100%;" type="text" value="'+response.data.userFavorite[0]['lastname']+'" /></td>');
				$('#userfavorite-surname').html('<td>Puesto:</td><td><select class="form-control" style="width:100%;" name="jobs" id="jobs" ><option value="0" selected>--Selecciona una puesto--</option></select></td>');
				$('#userfavorite-lastname').html('<td>Telefono Local:</td><td><input class="form-control" name="userf-phone_local" style="width:100%;" type="text" value="'+response.data.userFavorite[0]['local_phone']+'" /></td>');
				$('#userfavorite-email').html('<td>Numero de telefono:</td><td><input class="form-control" name="userf-cell_phone" style="width:100%;" type="text" value="'+response.data.userFavorite[0]['cellphone']+'" /></td>');
				$('#userfavorite-d_name').html('<td>Email:</td><td><input class="form-control" name="userf-email" style="width:100%;" type="text" value="'+response.data.userFavorite[0]['email']+'" /></td>');
				$('#userfavorite-privilegio').html('<td>¿Puede entrar a Osiris?</td><td><div style="display:none;"><input type="hiddden" name="user_can_login" id="user_can_login" /></div><input class="form-control" id="can-login" name="can-login"  type="checkbox" value="0"  /></td>');
				$('#userfavorite-information').append('<tr id="userfavorite-usernamediv" style="display: none;"><td>Clave de usuario:</td><td><input class="form-control" name="userf-user_name" style="width:100%;" type="text" value="'+response.data.userFavorite[0]['user_name']+'" /></td></tr>');
				$.each( response.data.jobs_users, function( key, value ) {
					$('#jobs').append('<option value="'+value.id+'">'+value.name_job+'</option>');	
				});
				$('#jobs').val(response.data.userFavorite[0]['id_job']);
				if(response.data.userFavorite[0]['canlogin'] == '1'){
					$('#can-login').attr('checked','checked');
					$('#can-login').attr('onchange','notcanlogin()');
					$('#can-login').val('1');
					$('#user_can_login').val('1');
					$('#userfavorite-usernamediv').show();
				}else if(response.data.userFavorite[0]['canlogin'] == '0'){
					$('#can-login').attr('onchange','yescanlogin()');
					$('#can-login').val('0');
					$('#user_can_login').val('0');
				}
			}else if(response.data.userFavorite == ''){
				
			}
		},
		error: function(){
			alert("Fallo, intentalo otra vez");
		}
	});
}
function notcanlogin(){
	$('#can-login').attr('onchange','yescanlogin()');
	$('#can-login').removeAttr('checked');
	$('#can-login').val('0');
	$('#user_can_login').val('0');
	$('#userfavorite-usernamediv').hide(1000);
};
function yescanlogin(){
	$('#can-login').attr('onchange','notcanlogin()');
	$('#can-login').attr('checked','checked');
	$('#can-login').val('1');
	$('#user_can_login').val('1');
	$('#userfavorite-usernamediv').show(1000);
};
function cancelEdit(){
	var company = company_users;
	var baseurl = basep+'/company/getinfo';
	$.ajax({
		type: "POST",
		url: baseurl,
		data: {'company' : company},
		dataType: "json",
		success: function(response){
			$("#editsavecancel").html('<input type="button" value="Editar información" class="btn btn-success" onclick="editInfo()" />');
			$("#namecompany-companytd").html(response.data.name_company);
			$("#marca-companytd").html(response.data.brand);
			$("#rfc-companytd").html(response.data.rfc);
			$("#website-companytd").html(response.data.website);
			$("#business-companytd").html(response.data.business);
			$("#phone-companytd").html(response.data.phone);
			$("#extension-companytd").html(response.data.extension);
			$("#map-companytd").html('<a href="'+response.data.map+'">Ver mapa</a>');
			$("#statename-directiontd").html(response.data.state_name);
			$("#districtname-directiontd").html(response.data.district_name);
			$("#colony-directiontd").html(response.data.colony);
			$("#street-directiontd").html(response.data.street);
			$("#number-directiontd").html(response.data.number_ext);
			$("#postalcode-directiontd").html(response.data.postal_code);
			$("#interbankclabe-banktd").html(response.data.interbank_clabe);
			$("#numberacount-banktd").html(response.data.number_acount);
			$("#namebank-banktd").html(response.data.name_bank);
			$("#sucursalname-banktd").html(response.data.sucursal_name);
			if(response.data.userFavorite != ''){
				$('#userfavorite-numberemployee').html('<td style="width:50%">Numero de empleado:</td><td>'+response.data.userFavorite[0]["numberEmployee"]+'</td>');
				$('#userfavorite-rfc').html('<td style="width:50%">RFC:</td><td>'+response.data.userFavorite[0]["rfc"]+'</td>');
				$('#userfavorite-name').html('<td style="width:50%">Nombre:</td><td>'+response.data.userFavorite[0]["name"]+'</td>');
				$('#userfavorite-surname').html('<td style="width:50%">Apellido Paterno:</td><td>'+response.data.userFavorite[0]["surname"]+'</td>');
				$('#userfavorite-lastname').html('<td style="width:50%">Apellido Materno:</td><td>'+response.data.userFavorite[0]["lastname"]+'</td>');
				$('#userfavorite-email').html('<td style="width:50%">Correo electronico:</td><td>'+response.data.userFavorite[0]["email"]+'</td>');
				$('#userfavorite-d_name').html('<td style="width:50%">Departamento:</td><td>'+response.data.userFavorite[0]["d_name"]+'</td>');
				$('#userfavorite-privilegio').html('<td style="width:50%">Privilegios:</td><td>'+response.data.userFavorite[0]["role_name"]+'</td>');
				$('#userfavorite-usernamediv').remove();
			}
		},
		error: function(){
			alert("Fallo, intentalo otra vez");
		}
	});
}
function saveEdit(){
	var q = confirm("\u00BFEsta seguro de modificar la información de su empresa?");
	if(q){
		var baseurl = basep+'/company/updatecompany';
		$.ajax({
			type: "POST",
			url: baseurl,
			data: $('#formEditCompany').serialize(),
			dataType: "json",
			success: function(response){
				$("#editsavecancel").html('<input type="button" value="Editar información" class="btn btn-success" onclick="editInfo()" />');
				$("#namecompany-companytd").html(response.data.name_company);
				$("#marca-companytd").html(response.data.brand);
				$("#rfc-companytd").html(response.data.rfc);
				$("#website-companytd").html(response.data.website);
				$("#business-companytd").html(response.data.business);
				$("#phone-companytd").html(response.data.phone);
				$("#extension-companytd").html(response.data.extension);
				$("#map-companytd").html('<a href="'+response.data.map+'">Ver mapa</a>');
				$("#statename-directiontd").html(response.data.state_name);
				$("#districtname-directiontd").html(response.data.district_name);
				$("#colony-directiontd").html(response.data.colony);
				$("#street-directiontd").html(response.data.street);
				$("#number-directiontd").html(response.data.number_ext);
				$("#postalcode-directiontd").html(response.data.postal_code);
				$("#interbankclabe-banktd").html(response.data.interbank_clabe);
				$("#numberacount-banktd").html(response.data.number_acount);
				$("#namebank-banktd").html(response.data.name_bank);
				$("#sucursalname-banktd").html(response.data.sucursal_name);
				if(response.data.userFavorite != ''){
					$('#userfavorite-numberemployee').html('<td style="width:50%">Numero de empleado:</td><td>'+response.data.userFavorite[0]["numberEmployee"]+'</td>');
					$('#userfavorite-rfc').html('<td style="width:50%">RFC:</td><td>'+response.data.userFavorite[0]["rfc"]+'</td>');
					$('#userfavorite-name').html('<td style="width:50%">Nombre:</td><td>'+response.data.userFavorite[0]["name"]+'</td>');
					$('#userfavorite-surname').html('<td style="width:50%">Apellido Paterno:</td><td>'+response.data.userFavorite[0]["surname"]+'</td>');
					$('#userfavorite-lastname').html('<td style="width:50%">Apellido Materno:</td><td>'+response.data.userFavorite[0]["lastname"]+'</td>');
					$('#userfavorite-email').html('<td style="width:50%">Correo electronico:</td><td>'+response.data.userFavorite[0]["email"]+'</td>');
					$('#userfavorite-d_name').html('<td style="width:50%">Departamento:</td><td>'+response.data.userFavorite[0]["d_name"]+'</td>');
					$('#userfavorite-privilegio').html('<td style="width:50%">Privilegios:</td><td>'+response.data.userFavorite[0]["role_name"]+'</td>');
					$('#userfavorite-usernamediv').remove();
				}
			},
			error: function(){
				alert("Fallo, intentalo otra vez");
			}
	});
	}else{
		return;
	}
}
function changeState(){
	var baseurl = basep+'/company/getdistricts';
	var state = $('#states').val();
	$.ajax({
		type: "POST",
		url: baseurl,
		data: {'state' : state},
		dataType: "json",
		success: function(response){
			$("#districtname-directiontd").html('<select class="form-control" style="width:100%;" name="districts" id="districts" onchange="changeDistrict()" ><option value="0" selected>--Selecciona Del. o Mpio.--</option></select>');
			$("#colony-directiontd").html('<select class="form-control" style="width:100%;" name="colonys" id="colonys" onchange="changeColony()" ><option value="0" selected>--Selecciona una colonia--</option></select>');
			$("#postal_code").val('');
			$.each(response.districts, function( key, value ) {
				$('#districts').append('<option value="'+value.id+'">'+value.name+'</option>');	
			});
		},
		error: function(){
			alert("Fallo, intentalo otra vez");
		}
	 });
}
function changeDistrict(){
	var baseurl = basep+'/company/getcolonys';
	var district = $('#districts').val();
	$.ajax({
		type: "POST",
		url: baseurl,
		data: {'district' : district},
		dataType: "json",
		success: function(response){
			$("#colony-directiontd").html('<select class="form-control" style="width:100%;" name="colonys" id="colonys" onchange="changeColony()" ><option value="0" selected>--Selecciona una colonia--</option></select>');
			$("#postal_code").val('');
			$.each(response.colonys, function( key, value ) {
				$('#colonys').append('<option value="'+value.id+'">'+value.colony+'</option>');	
			});
		},
		error: function(){
			alert("Fallo, intentalo otra vez");
		}
	 });
}
function changeColony(){
	var baseurl = basep+'/company/getpostalcode';
	var colony = $('#colonys').val();
	$.ajax({
		type: "POST",
		url: baseurl,
		data: {'colony' : colony},
		dataType: "json",
		success: function(response){
			$("#postal_code").val(response.postal_code);
		},
		error: function(){
			alert("Fallo, intentalo otra vez");
		}
	 });
}