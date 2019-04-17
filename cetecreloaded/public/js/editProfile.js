$(function(){
	$('a[title]').tooltip();
	
	//Validaciones al escribir las contraseñas
	$(".password").keyup(function(){
		var ucase = new RegExp("[A-Z]+");
		var lcase = new RegExp("[a-z]+");
		var num   = new RegExp("[0-9]+");

		//Validar longitud de caracteres de la contraseña numero 1
		if($("#password1").val().length >= 8){
			$("#8char").removeClass("glyphicon-remove");
			$("#8char").addClass("glyphicon-ok");
			$("#8char").css("color","#00A41E");
		}else{
			$("#8char").removeClass("glyphicon-ok");
			$("#8char").addClass("glyphicon-remove");
			$("#8char").css("color","#FF0004");
		}

		//Validar si hay una letra mayuscula en la contraseña numero 1
		if(ucase.test($("#password1").val())){
			$("#ucase").removeClass("glyphicon-remove");
			$("#ucase").addClass("glyphicon-ok");
			$("#ucase").css("color","#00A41E");
		}else{
			$("#ucase").removeClass("glyphicon-ok");
			$("#ucase").addClass("glyphicon-remove");
			$("#ucase").css("color","#FF0004");
		}

		//Validar si hay una letra minuscula en la contraseña numero 1
		if(lcase.test($("#password1").val())){
			$("#lcase").removeClass("glyphicon-remove");
			$("#lcase").addClass("glyphicon-ok");
			$("#lcase").css("color","#00A41E");
		}else{
			$("#lcase").removeClass("glyphicon-ok");
			$("#lcase").addClass("glyphicon-remove");
			$("#lcase").css("color","#FF0004");
		}

		//Validar si hay un numero en la contraseña numero 1
		if(num.test($("#password1").val())){
			$("#num").removeClass("glyphicon-remove");
			$("#num").addClass("glyphicon-ok");
			$("#num").css("color","#00A41E");
		}else{
			$("#num").removeClass("glyphicon-ok");
			$("#num").addClass("glyphicon-remove");
			$("#num").css("color","#FF0004");
		}
		
		//Validar si las dos contraseñas son iguales
		if($("#password1").val().length == 0 && $("#password2").val().length == 0){
			/*$("#pwmatch").removeClass("glyphicon-ok");
			$("#pwmatch").addClass("glyphicon-remove");
			$("#pwmatch").css("color","#FF0004");*/
			alertify.alert("Estan vacios, ingresa un valor");
		}else{
			if($("#password1").val() == $("#password2").val()){
				$("#pwmatch").removeClass("glyphicon-remove");
				$("#pwmatch").addClass("glyphicon-ok");
				$("#pwmatch").css("color","#00A41E");
			}else{
				$("#pwmatch").removeClass("glyphicon-ok");
				$("#pwmatch").addClass("glyphicon-remove");
				$("#pwmatch").css("color","#FF0004");
			}
		}
	});
	
	$("#changePass").on("click", function(e){
		e.preventDefault();
	    if ($("#pwmatch").hasClass('glyphicon-ok') && $("#8char").hasClass('glyphicon-ok') && $("#lcase").hasClass('glyphicon-ok') && $("#ucase").hasClass('glyphicon-ok') && $("#num").hasClass('glyphicon-ok')){
			alertify.confirm("<p>Estas seguro de cambiar tu contraseña</p>", function (e) {
				if(e){
					//alertify.error("Tu contraseña fue editada");
					$("#formPass").submit();
				}else{
					return false;
				}
			}); 
	    }else{
	    	alertify.alert("Tu contraseña no es valida, verificala");
	    }
	});
	
	/***********Click en el boton editar perfil***********/
	$("#editProfile").on("click", function(e){
		e.preventDefault();
		//alert($("#formEditProfile").serialize());
		alertify.confirm("<p>Estas seguro de editar tu perfil</p>", function (e) {
			if(e){
				//alertify.error("Tu contraseña fue editada");
				$("#formEditProfile").submit();
			}else{
				return false;
			}
		});
	});
		
	/***********Click en el boton siguiente contraseña***********/
	$("#nextPass").on("click", function(){
		if($("#inputPassReal").val().length == 0){
			alertify.alert("Ingresa tu contraseña");
		}else{
			$.ajax({
				url      : $basePath + "/users/editprofile",
				type     : "POST",
				dataType : "json",
				data     : {"checkPass": "ok", "id" : $("#user_id").val(), "val" : $("#inputPassReal").val()},
				success: function(response){
					//console.log(response.response);
					if(response.response == "true"){
						$("#rowPassReal").fadeOut();
						$("#rowPassChange").fadeIn(4000);
					}else{
						alertify.alert("Tu contraseña es incorrecta");
					}
				}
			});
		}
	});
	
});