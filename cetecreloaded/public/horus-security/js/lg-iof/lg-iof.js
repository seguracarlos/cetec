$(document).ready(function(){

	// Boton de inicio de sesion
	var $btn_login = $("#btn_login");

	/*
	INICIA UN AJAX
	 */
	$( document ).ajaxStart(function() {
		//alert("Manejador ajaxStart Accionado.");
		// Deshabilitamos el boton
		$btn_login.prop('disabled', true);

		// Cubra toda una página.
		$('html').plainOverlay('show');
		/*$('body').plainOverlay('show',{
  progress: function() { return $('<img src="'+$basePath+'/public/horus-security/loading-master/loading-spinning-bubbles.svg" alt="Loading icon" />'); }
});*/ // Or, $(document), $(window)
	});

	/*
		TERMINA UN AJAX
	 */
	$( document ).ajaxStop(function() {
		//alert("Manejador ajaxStop Accionado.");
		// Habilitamos el boton
		//$btn_login.prop('disabled', false);

		// Cubra toda una página.
		$('html').plainOverlay('hide');
	});

    /*
	* Para probar el script que debe descomentar la función
	* TestLocalStorageData y actualiza la página. La funcion
	* Cargará algunos datos de prueba y la loadProfile
	* Va a hacer los cambios en la interfaz de usuario
	*/
    //testLocalStorageData();
    // Perfil de carga si es que existe
    loadProfile();

	var $formLogin = $("#loginForm");        // Formulario
	var $email     = $("#email");            // Campo de correo
	var $password  = $("#password");         // Campo de contrasena
	var $tries     = $("#try");              // Campo de n. de intentos
	var $fail      = 0;                      // Error
	var $divRecaptcha = $("#div-recaptcha"); //Div que contiene recaptcha
	var $code      = "";                     // Codigo
	var $errors    = "";                     // Errores

	/*
		CLICK EN EL BOTON ENTRAR
	*/
	$("#btn_login").on('click', function(event) {
		event.preventDefault();

		var $error = 0;
		var $valPhotoFile;

		// Recorremos los tags que son obligatorios
		$("#loginForm :input.input-required").each(function(index, el) {
			// Validamos el contenido de los tags
			if ($(this).val() == "") {
				// Comprobamos si existe la clase
				if($(this).parents('.input-group').hasClass("has-success")){
					// Removemos clase
					$(this).parents('.input-group').removeClass("has-success");
				}
				// Agregamos clase
				$(this).parents('.input-group').addClass("has-error");
				// Incrementamos la variable de acuerdo al numero de tags vacios.
				$error++;
			}else{
				if($(this).parents('.input-group').hasClass("has-error")){
					$(this).parents('.input-group').removeClass("has-error");
				}
				$(this).parents('.input-group').addClass("has-success");
			}
		}); // end each inputs form
		//console.log("errores: "+$error);
		// Validamos que el correo y contraseña no esten vacios
		if($email.val() != "" && $password.val() != "" && $error == 0 ){

			// Validamos el numero de intentos
			if ($tries.val() > 3) {
				$fail++;
				$divRecaptcha.removeClass('hidden');
				//$divRecaptcha.addClass('show');

				if(grecaptcha.getResponse() != "") {
					$fail = 0;
				}

			}
			//console.log($fail);

			var value = $("input[name=password]").val();
			var hash  = sha3(value);
			$("input[name=password]").val(hash);

			if($fail == 0) {
				//$formLogin.submit();
				$.ajax({
					url: $basePath + '/login',
					type: 'POST',
					dataType: 'json',
					data: $formLogin.serialize(),
				})
				.done(function(response) {

					//Reseteamos el captcha si es que ya fue resulto y hubo error
					if(grecaptcha.getResponse() != "") {
						grecaptcha.reset();
						//console.log("Contador: "+$fail);
					}
					
					//console.log("success");
					// Numero de intentos hechos
					$("#try").val(response.tries);

					// Limpiamos la contrasena
					$password.val("");

					// Limpiamos la clase de error del input password
					$password.parents('.input-group').removeClass("has-success");

					// Codigo de la respuesta
					$code = response.code;
					//console.log("Codigo: "+$code);

					//Validamos el codigo
					switch ($code)
					{
						case 0:
							alertify.error("Verifique sus datos e intentelo de nuevo.");
							
							// Habilitamos el boton
							$btn_login.prop('disabled', false);
							
							break;
						case 1:
							// Validamos de nuevo el response
							if(response.response == "ok"){

								// Deshabilitamos el boton
								//console.log($btn_login);
								//$btn_login.prop('disabled', true);
								//$btn_login.removeClass('btn-success');
								//$btn_login.addClass('btn-danger');

								var $dataUserProfile = response.data;
								/*
								console.log($dataUserProfile.name);
								console.log($dataUserProfile.email);
								console.log($dataUserProfile.photofile);
								*/
								//console.log("imagen: " + $dataUserProfile.photofile);
								//console.log("var valPhotoFile: "+$valPhotoFile);
								// Valor por defecto de la imagen de usuario
								//var $valuePhotoFile;

								// Validamos valor de la img de usuario
								if ($dataUserProfile.photofile != '') {
									$valPhotoFile = $dataUserProfile.photofile;
								} else{
									$valPhotoFile = $basePath + '/public/horus-security/img/user-male-icon.png';
								}
								//console.log("var valPhotoFile 2: "+$valPhotoFile);
								
								//Cargamos los datos en localStorage
								testLocalStorageData($dataUserProfile.name, $dataUserProfile.email, $valPhotoFile);

								// Redireccionamos dentro del sistema
								location.href = $basePath + '/horus/welcome/index';
							}
							break;
						case 2:
							
							if($tries.val() > 3) {
								alertify.log("Cuenta bloqueda contacta con el administrador.");
								//$("#btn_login").attr("disabled", true);
								$btn_login.prop( "disabled", true );
								$btn_login.removeClass('btn-success');
								$btn_login.addClass('btn-danger');
							}else{
								alertify.error("Verifique sus datos e intentelo de nuevo.");
								// Habilitamos el boton
								$btn_login.prop('disabled', false);
							}
							
							break;
						case 3:

							// Habilitamos el boton
							$btn_login.prop('disabled', false);

							$errors = response.status;

							// Validamos errores de formulario
							if (!jQuery.isEmptyObject($errors.loginCsrf)) {
								alertify.error("El formulario presentado no se originó desde el sitio esperado, recarga la página");
							}else if (!jQuery.isEmptyObject($errors.email)) {
								alertify.error("Ingresa una direccion de correo valida");
							}

							break;
						default:
							alertify.log("Intentalo nuevamente.");
					}

				})
				.fail(function() {
					//console.log("error");
				})
				.always(function() {
					//console.log("complete");
				});

			}
			
		}else{
			alertify.log("El correo y contraseña son obligaorios");
		}

	});

	var sha3 = function(value) {
		return CryptoJS.SHA3(value).toString();
	}

	/*
	 * LIMPIAR SESION
	 */
	$(".acount-new").click(function(event) {
		event.preventDefault();
		clearLocalStorage();
	});


}); /* fin del document ready*/

/**
 * función que comprueba si el navegador soporta HTML5
 * almacen local
 *
 * @returns {boolean}
 */
function supportsHTML5Storage() {
    try {
        return 'localStorage' in window && window['localStorage'] !== null;
    } catch (e) {
        return false;
    }
}


/**
 * Función que obtiene los datos del perfil en caso
 * Thar ya ha guardado en localStorage. Solo el
 * UI será actualización en caso de que todos los datos se encuentra disponible
 *
 * Una de las claves que no existe en localStorage nula rentabilidad
 *
 */
function getLocalProfile(callback){
    var profileImgSrc      = localStorage.getItem("PROFILE_IMG_SRC");
    var profileName        = localStorage.getItem("PROFILE_NAME");
    var profileReAuthEmail = localStorage.getItem("PROFILE_REAUTH_EMAIL");

    if(profileName !== null && profileReAuthEmail !== null && profileImgSrc !== null) {
        callback(profileImgSrc, profileName, profileReAuthEmail);
    }
}

/**
 * Función principal que cargar el perfil si existe
 * en localStorage
 */
function loadProfile() {
	//console.log("Entre");
	//console.log("localStorage-img: "+localStorage.getItem("PROFILE_IMG_SRC"));
    if(!supportsHTML5Storage()) { return false; }
    // tenemos que proporcionar a la devolución de llamada del básico
    // información para establecer el perfil
    getLocalProfile(function(profileImgSrc, profileName, profileReAuthEmail) {
    	//console.log("Volvi a entrar");
        //los cambios en la interfaz de usuario
        $("#profile-img").attr("src",profileImgSrc);
        $("#profile-name").html(profileName);
        $("#reauth-email").html(profileReAuthEmail);
        $("#inputEmail").hide();
        $("#span-info-auth").hide();
        $("#email").val(profileReAuthEmail);
        $(".acount-new").removeClass('hide');
    });
}

/**
 * Datos de prueba. Estos datos estarán a salvo por la aplicación web
 * En el primer inicio de sesión con éxito de un usuario de autenticación.
 * Para probar los scripts, borrar los datos localStorage
 * Y comenta esta llamada.
 *
 * @returns {boolean}
 */
function testLocalStorageData($nameU, $emailU, $imgU) {
    if(!supportsHTML5Storage()) { return false; }
    /*
    localStorage.setItem("PROFILE_IMG_SRC", $basePath + '/public/horus-security/img/user-male-icon.png' );
    localStorage.setItem("PROFILE_NAME", "Jose Luis Martinez Cuapio");
    localStorage.setItem("PROFILE_REAUTH_EMAIL", "joseluis.martinez@iofractal.com");
    */
    
    localStorage.setItem("PROFILE_IMG_SRC", ($imgU != undefined) ? $imgU : $basePath + '/public/horus-security/img/user-male-icon.png');
    localStorage.setItem("PROFILE_NAME", ($nameU != undefined) ? $nameU : null);
    localStorage.setItem("PROFILE_REAUTH_EMAIL", ($emailU != undefined) ? $emailU : null);
}


function clearLocalStorage()
{
	// Eliminamos el elemento "PROFILE_IMG_SRC" del almacenamiento local.
	localStorage.removeItem('PROFILE_IMG_SRC');

	// Eliminamos el elemento "PROFILE_NAME" del almacenamiento local.
	localStorage.removeItem('PROFILE_NAME');

	// Eliminamos el elemento "PROFILE_REAUTH_EMAIL" del almacenamiento local.
	localStorage.removeItem('PROFILE_REAUTH_EMAIL');

	//los cambios en la interfaz de usuario
    $("#profile-img").attr("src", $basePath + '/public/horus-security/img/user-male-icon.png');
    $("#profile-name").html(' ');
    $("#reauth-email").html(' ');
    $("#inputEmail").show();
    $("#span-info-auth").show();
    $("#email").val('');
    $(".acount-new").addClass('hide');
	//alert("Limpiar sesion");
}

