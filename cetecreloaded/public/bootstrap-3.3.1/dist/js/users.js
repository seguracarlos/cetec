(function($){
	$(document).on("ready", function(){

		// CARGAR IMAGENES
		loadingAvatar("photofile_ife");
		loadingAvatar("photofile_license");
		loadingAvatar("photofile_certification");

		//Funcion que comprueba si hay datos de seguridad
		checkSecurityData();

		// Click en el boton guardar
		$("#saveUser").on("click", function(e){
			e.preventDefault();
			$boton = $(this);
			$boton.attr("disabled", true);
			console.log("Voy a guardar un empleado");
			alertify.confirm("¿Deseas guardar los cambios?", function(e){
				if(e){
					console.log("aceptaste");
					$("#userForm").submit();
				}else{
					$boton.attr("disabled", false);
				}
			});
		});

		// Cuando se selecciona el checkbox de datos de seguridad
		$("#canlogin").on("click", function(){
			if($("#canlogin").is(':checked')){
				//$("#contenedor-datos-seguridad").slideDown("slow");
				//$("#contenedor-datos-seguridad").show("slide", { direction: "left" }, 1000);
				 //$('#contenedor-datos-seguridad').show("explode", { pieces: 64 }, 2000);
				$("#contenedor-datos-seguridad").show( "fold",
	                      {horizFirst: true}, 2000 );
			}else{
				//$("#contenedor-datos-seguridad").slideUp("slow");
				//$("#contenedor-datos-seguridad").hide("slide", { direction: "left" }, 1000);
				//$('#contenedor-datos-seguridad').hide("explode", { pieces: 64 }, 2000);
				$("#contenedor-datos-seguridad").hide( "fold",
	                     {horizFirst: true }, 2000 );
			}
		});

		//$("#date_admission").datepicker({ dateFormat: 'dd/mm/yy' }).datepicker("setDate", new Date());
		$("#date_admission, #birthday").datepicker({
							dayNames: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"],
							dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sá"],
							dayNamesShort: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"],
							monthNames: ["Enero", "Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"],
							monthNamesShort: ["Ene", "Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"],
							//defaultDate: new Date(),
							dateFormat: "dd/mm/yy",
							//altFormat: "yy-mm-dd",
							showButtonPanel: true,
							currentText: "Hoy",
							closeText: "Cerrar",
							changeMonth: true,
      						changeYear: true

		});

		//Cargamos la imagen del usuario
		cargarImagenUsuario();
		//Comprobar tipo de usuario
		comprobarTipoUsuario();
		// cargar roles disponibles
		//cargarRoles();

		// Ocultar y mostrar boton de carga de archivo
//		$('#preview').hover(
//			function(){
//				$(this).find('a').fadeIn();
//			}, function() {
//				$(this).find('a').fadeOut();
//			}
//		);

		//OCULTAR Y MOSTRAR BOTON DE SELLECCIONAR ARCHIVO
		$('.thumbnail-image').hover(
			function(){
				$(this).find('a').fadeIn();
			}, function() {
				$(this).find('a').fadeOut();
			}
		);
		/*console.log("fwtgreyhrtyretertg poe");
		$(document).on('click', '.thumbnail-image', function(event) {
			event.preventDefault();
			alert("fsdgfdsfdsf");
		});*/

		/*
		$(document).on('hover', '.thumbnail-image',
			function(event) {
				console.log("holis");
				event.preventDefault();
				$(this).find('a').fadeIn();
			},
			function(event) {
				console.log("holis");
				event.preventDefault();
				$(this).find('a').fadeOut();
			}
		);
		*/

		// Click en el boton elejir archivo
		$('#file-select').on('click', function(e) {
			e.preventDefault();
			$('#file').click();
		});

		//CLICK EN EL BOTON ELEGIR ARCHIVO
		$(document).on('click', '.file-select', function(event) {
			event.preventDefault();
			$(this).parent().prev().click();
		});

		// Cuando el input file cambia
		$('input[type=file]').change(function() {
			//var file   = (this.files[0].name).toString();
			var $file  = this.files[0], $fileName = $file.name, $fileSize = $file.size, $fileType = $file.type;
			var reader = new FileReader();

			if($fileType.match('image.*')){
				reader.onload = function (e) {
					$('#preview img').attr('src', e.target.result);
					$('#photofile').val(e.target.result);
					$('#photofilename').val($fileName);
				}

				reader.readAsDataURL(this.files[0]);
			}else{
				alertify.alert("Solo se permiten imagenes JPG, GIF, PNG");
			}

		});

		//CUANDO EL INPUT FILE CAMBIA
		$(document).on('change', '.input-file', function(event) {
			event.preventDefault();
			console.log("Input file change");
			var $inputFile = $(this);
			var $file  = this.files[0], $fileName = $file.name, $fileSize = $file.size, $fileType = $file.type;
			var reader = new FileReader();

			if($fileType.match('image.*')){
				reader.onload = function (e) {
					/*$('#preview img').attr('src', e.target.result);
					$('#photofile').val(e.target.result);
					$('#photofilename').val($fileName);*/
					$inputFile.next('.thumbnail-image').find('img').attr('src', e.target.result);
					//$inputFile.next('.thumbnail-image').find('img').attr('data-zoom-image', e.target.result);
					$inputFile.prev('input[type=hidden]').val(e.target.result);
					//$inputFile.next().find('div').addClass("closePromotion").append("<img src='"+$basePath+"/public/img/close.png' width='19' height='19' alt='close'/>");
					//alert($fileSize);
				}

				reader.readAsDataURL(this.files[0]);
			}else{
				alertify.alert("Solo se permiten imagenes JPG, GIF, PNG");
			}

		});
		
		// ZOOM A LAS IAGENES
		//$('.image-zoom').elevateZoom();


		// Al cambiar el tipo de usuario (Interno/Externo)
		$('input[name=user_type]:radio').change(function(){
			//Comprobar el tipo de usuario
			if($('input:radio[name=user_type]:checked').val() == 2){
				$("#external_user_div").slideDown();
				rolesExtInt(2);
			}else{
				$("#external_user_div").slideUp();
				rolesExtInt(1);
			}
		});

	}); /* end document ready*/

	function cargarImagenUsuario()
	{
		var $image = $("#photofile");
		//console.log($image.length);
		//if (jQuery.isEmptyObject($image)) {
		if ($image.length != 0) {
			if(($image.val().length != 0)){
				$('#preview img').attr('src', $image.val());
			}
		};
		
		
		//}
		/*if (typeof($image) === "undefined") {
		    alert("objetoso no está definido.");
		}*/
		/*if (typeof $image == "undefined"){
    		alert("Variable obj3 no definida");
		}*/
	}

	// Comprobar el tipo de usuario
	function comprobarTipoUsuario()
	{
		if($('input:radio[name=user_type]:checked').val() == 2){
			$("#external_user_div").css("display", "block");
		}else{
			$("#external_user_div").css("display", "none");
		}
	}

	//Cargar Roles disponibles en una lista desplegable
	/*function cargarRoles()
	{
		$.ajax({
			url: $basePath + "/system/users/getAllRoles" ,
			type: "POST",
			dataType: "json",
			success: function(response){
				var $select_roles = $('#role');
				var $options      = "";

				$options += '<option value="0">--selecciona--</option>';

				$.each(response[0], function(index, obj){
					$options += '<option value="' + obj.rid + '"  data-user="' + obj.type_user + '">'
							 + obj.role_name
							 + '</option>' ;
				});

				$select_roles.append($options);

				//Validamos la vista en la que se encuentra y el rol del usuario
				if($nameView == "add_user"){
					rolesExtInt(1);
				}else if($nameView == "edit_user"){
					rolesExtInt($('input:radio[name=user_type]:checked').val());
					$('#role option[value="'+$role_user+'"]').attr("selected", true);
				}else if($nameView == "add_employee"){
					rolesExtInt(1);
				}else if($nameView == "edit_employee"){
					rolesExtInt($('input:radio[name=user_type]:checked').val());
					$('#role option[value="'+$role_user+'"]').attr("selected", true);
				}


			},
			error: function(response){
				console.log("error");
			}
		});
	}*/

	/** Mostrar y ocultar roles segun el tipo de usuario **/
	function rolesExtInt(type_role)
	{
		$('#role').val("");

		$("#role option").each(function(){
			var $options = $(this);

			//validar usuario externo o interno
			if(type_role == 1){
				$("#role option[data-user='2']").hide();
				$("#role option[data-user='1']").show();
			}else if(type_role == 2){
				$("#role option[data-user='1']").hide();
				$("#role option[data-user='2']").show();
			}
		});
	}

})(jQuery);

/*
 * Comprobar los datos de seguridad
 */
function checkSecurityData()
{
	if($("#canlogin").is(':checked')){
		console.log("Esta activo");
		$("#contenedor-datos-seguridad").css("display","block");
	}else{
		console.log("No esta activo");
		$("#contenedor-datos-seguridad").css("display","none");
	}
}

/*
	CARGAR IMAGENES
*/
function loadingAvatar($imagefile)
{
	var $image = $("#" + $imagefile);
	//console.log($image.length);
	//if (jQuery.isEmptyObject($image)) {
	if ($image.length != 0) {
		if(($image.val().length != 0)){
			$image.parent('div').find('.thumbnail img').attr('src', $image.val());
		}
	};
}
