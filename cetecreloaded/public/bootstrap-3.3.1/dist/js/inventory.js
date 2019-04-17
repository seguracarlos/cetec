(function($){
	$(document).on("ready", function(){

		//Cargamos la imagen del articulo
		//cargarImagenArticulo();

		/*
			VALIDAR QUE TIPO DE ARTICULO SE AGREGO
		*/
		validateTypeArticle();

		// Click en el boton guardar
		$("#saveInventory").on("click", function(e){
			e.preventDefault();
			$boton = $(this);
			$boton.attr("disabled", true);
			console.log("Voy a guardar un inventario");
			alertify.confirm("¿Deseas guardar los cambios?", function(e){
				if(e){
					console.log("aceptaste");
					$("#inventoryForm").submit();
				}else{
					$boton.attr("disabled", false);
				}
			});
		});
		
		// Ocultar y mostrar boton de carga de archivo
		$('#preview').hover(
			function(){
				$(this).find('a').fadeIn();
			}, function() {
				$(this).find('a').fadeOut();
			}
		);

		// Click en el boton elegir archivo
		$('#file-select').on('click', function(e) {
			e.preventDefault();  
			$('#file').click();
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
			}else{ alertify.alert("Solo se permiten imagenes JPG, GIF, PNG"); }			
		});
		
		// Al cambiar el tipo de articulo
		$("#types_id_types").on("change", function(){
			var $opc = $("#types_id_types option:selected").val();
			console.log($opc);
			if($opc == 5){
				$("#camposCamiones").fadeIn("slow");
			}else{
				$("#camposCamiones").fadeOut("slow");
			}
		});
		
	}); /* end document ready*/
})(jQuery);

/*
	VALIDAR QUE TIPO DE ARTICULO SE AGREGO
*/
function validateTypeArticle()
{
	var $opc = $("#types_id_types option:selected").val();
	console.log("Tipo de articulo: "+$opc);
	if($opc == 5){
		$("#camposCamiones").fadeIn("slow");
	}else{
		$("#camposCamiones").fadeOut("slow");
	}
}
	
// Cargar imagen de un inventario
function cargarImagenArticulo()
{
	if(($photo.length != 0)){
		$('#preview img').attr('src', $basePath +"/public/img/inventories/"+ $photo);
	}
}
	
	
	/** Funcion que muestra articulos segun el tipo seleccionado **/
	/*function typesArticles($type)
	{
		var $options = "";
		$("#article option").hide();
		$("#article option[value='']").show();
		$("#article").val("");
		
		$("#article option").each(function(){
			var $options = $(this);
			if($options.data("art") == $type){
				$("#article option[data-art='"+$type+"']").show();
			}
		});
	}*/
	
