
$(document).ready(function() {
var $id_presentation = $("#id_presentation").val();
$(".file").css({"visibility":"hidden"});
$(".fileHeader").css({"visibility":"hidden"});//
$(".fileLeft").css({"visibility":"hidden"});//
$(".fileRight").css({"visibility":"hidden"});//
$(".fileFooter").css({"visibility":"hidden"});//


$(document).on("click", ".imageHeader", function(){$(this).prev().click();}).show();
$(document).on("click", ".imageRight", function(){$(this).prev().click();}).show();
$(document).on("click", ".imageLeft", function(){$(this).prev().click();}).show();
$(document).on("click", ".imageFooter", function(){$(this).prev().click();}).show();

/* ******************** Al cambiar la imagen de header ******************** */
	$(document).on("change", ".fileHeader", function(){
	var $file = this.files[0], $fileName = $file.name, $fileSize = $file.size, $fileType = $file.type;
	if($fileType.match('image.*')){
		var $image = $(this), $id_image = $image.attr("id");
		var $inputQuestion = $image.parent().prev();//Id del input pregunta 
		var reader = new FileReader();
	
		reader.readAsDataURL(this.files[0]);
            reader.onload = function (e) {
                //Initiate the JavaScript Image object.
                var image = new Image();
 
                //Set the Base64 string return from FileReader as source.
                image.src = e.target.result;
                       
                //Validate the File Height and Width.
                image.onload = function () {
                    var height = this.height;
                    var width = this.width;

                    if($fileSize<1050000){
                    	if(height < 900 || width < 900){
                    			$image.parent().css('background-image', 'none');
								$image.next().html(""); 
								$image.next().next().html("");//Limpiamos imagen y tache cada vez que hacemos un cambio
								$image.next().append("<img src='" + e.target.result + "' class='thumbHeader'/>");//Agregamos vista previa de la imagen
								$image.next().next().addClass("deleteImage").append("<img src='"+$basePath+"/public/img/close.png' width='19' height='19' alt='close'/>");//Agregamos tache en la imagen
								if($("#fileHeaderStatus").val()==0){
									$.ajax({
	                                    url: $basePath+'/out/expenses/addsponsor',
	                                    type: 'POST',
	                                    dataType: 'JSON',
	                                    data: {id_presentation: $id_presentation , position : "top" ,nombre : $fileName,type : $fileType, valueImagen  : e.target.result},
	                                })
	                                .done(function(response) {
	                                    $(".fileHeaderStatus").attr("id",response.data.id_sponsor);
	                                    $(".fileHeaderStatus").val(response.data.imagepath);
	                                })
	                                .fail(function() {
	                                    console.log("error");
	                                })
	                                .always(function() {
	                                    console.log("complete");
	                                });
								}else{
									var $imagePath = $(".fileHeaderStatus").val();
									var $idSponsor =  $(".fileHeaderStatus").attr("id");
									$.ajax({
	                                    url: $basePath+'/out/expenses/editsponsor',
	                                    type: 'POST',
	                                    dataType: 'JSON',
	                                    data: {id_presentation: $id_presentation , position : "top" ,nombre : $fileName,type : $fileType, valueImagen  : e.target.result , imagePath : $imagePath, idSponsor : $idSponsor},
	                                })
	                                .done(function(response) {
	                                	 $(".fileHeaderStatus").attr("id",response.data.id_sponsor);
		                                 $(".fileHeaderStatus").val(response.data.imagepath);
	                                })
	                                .fail(function() {
	                                    console.log("error");
	                                })
	                                .always(function() {
	                                    console.log("complete");
	                                });
								}
                                
                    	}else{
                    		alertify.alert("Las dimensiones de la imagen no deben pasar los 900px");
                    	}	
                    }else{
                    	alertify.alert("La imagen no debe de pesar mas de 1MB");
                    }
                    
              
                };
 
            }

	}else{
		alertify.alert("Solo se permiten imagenes JPG, GIF, PNG");
	}
	});

/* ******************** Al cambiar la imagen de la izquierda ******************** */
$(document).on("change", ".fileLeft", function(){
	var $file = this.files[0], $fileName = $file.name, $fileSize = $file.size, $fileType = $file.type;

	if($fileType.match('image.*')){
		var $image = $(this), $id_image = $image.attr("id");
		var $inputQuestion = $image.parent().prev();//Id del input pregunta 
		var reader = new FileReader();
	
		reader.readAsDataURL(this.files[0]);
            reader.onload = function (e) {
                //Initiate the JavaScript Image object.
                var image = new Image();
 
                //Set the Base64 string return from FileReader as source.
                image.src = e.target.result;
                       
                //Validate the File Height and Width.
                image.onload = function () {
                    var height = this.height;
                    var width = this.width;

                    if($fileSize<1050000){
                    	if(height < 900 || width < 900){
                    			$image.parent().css('background-image', 'none');
								$image.next().html(""); 
								$image.next().next().html("");//Limpiamos imagen y tache cada vez que hacemos un cambio
								$image.next().append("<img src='" + e.target.result + "' class='thumbLeft'/>");//Agregamos vista previa de la imagen
								$image.next().next().addClass("deleteImage").append("<img src='"+$basePath+"/public/img/close.png' width='19' height='19' alt='close'/>");//Agregamos tache en la imagen
								
								if($("#fileLeftStatus").val()==0){
									$.ajax({
	                                    url: $basePath+'/out/expenses/addsponsor',
	                                    type: 'POST',
	                                    dataType: 'JSON',
	                                    data: {id_presentation: $id_presentation , position : "left" ,nombre : $fileName,type : $fileType, valueImagen  : e.target.result},
	                                })
	                                .done(function(response) {
	                                    $(".fileLeftStatus").attr("id",response.data.id_sponsor);
	                                    $(".fileLeftStatus").val(response.data.imagepath);
	                                })
	                                .fail(function() {
	                                    console.log("error");
	                                })
	                                .always(function() {
	                                    console.log("complete");
	                                });
								}else{
									var $imagePath = $(".fileLeftStatus").val();
									var $idSponsor =  $(".fileLeftStatus").attr("id");
									$.ajax({
	                                    url: $basePath+'/out/expenses/editsponsor',
	                                    type: 'POST',
	                                    dataType: 'JSON',
	                                    data: {id_presentation: $id_presentation , position : "left" ,nombre : $fileName,type : $fileType, valueImagen  : e.target.result , imagePath : $imagePath, idSponsor : $idSponsor},
	                                })
	                                .done(function(response) {
	                                	 $(".fileLeftStatus").attr("id",response.data.id_sponsor);
		                                 $(".fileLeftStatus").val(response.data.imagepath);
	                                })
	                                .fail(function() {
	                                    console.log("error");
	                                })
	                                .always(function() {
	                                    console.log("complete");
	                                });
								}
                    	}else{
                    		alertify.alert("Las dimensiones de la imagen no deben pasar los 900px");
                    	}	
                    }else{
                    	alertify.alert("La imagen no debe de pesar mas de 1MB");
                    }
                    
              
                };
 
            }

	}else{
		alertify.alert("Solo se permiten imagenes JPG, GIF, PNG");
	}
	});


/* ******************** Al cambiar la imagen de la Derecha ******************** */

$(document).on("change", ".fileRight", function(){
	var $file = this.files[0], $fileName = $file.name, $fileSize = $file.size, $fileType = $file.type;

	if($fileType.match('image.*')){
		var $image = $(this), $id_image = $image.attr("id");
		var $inputQuestion = $image.parent().prev();//Id del input pregunta 
		var reader = new FileReader();
	
		reader.readAsDataURL(this.files[0]);
            reader.onload = function (e) {
                //Initiate the JavaScript Image object.
                var image = new Image();
 
                //Set the Base64 string return from FileReader as source.
                image.src = e.target.result;
                       
                //Validate the File Height and Width.
                image.onload = function () {
                    var height = this.height;
                    var width = this.width;

                    if($fileSize<1050000){
                    	if(height < 900 || width < 900){
                    			$image.parent().css('background-image', 'none');
								$image.next().html(""); 
								$image.next().next().html("");//Limpiamos imagen y tache cada vez que hacemos un cambio
								$image.next().append("<img src='" + e.target.result + "' class='thumbRight'/>");//Agregamos vista previa de la imagen
								$image.next().next().addClass("deleteImage").append("<img src='"+$basePath+"/public/img/close.png' width='19' height='19' alt='close'/>");//Agregamos tache en la imagen
								
								if($("#fileRightStatus").val()==0){
									$.ajax({
	                                    url: $basePath+'/out/expenses/addsponsor',
	                                    type: 'POST',
	                                    dataType: 'JSON',
	                                    data: {id_presentation: $id_presentation , position : "right" ,nombre : $fileName,type : $fileType, valueImagen  : e.target.result},
	                                })
	                                .done(function(response) {
	                                    $(".fileRightStatus").attr("id",response.data.id_sponsor);
	                                    $(".fileRightStatus").val(response.data.imagepath);
	                                })
	                                .fail(function() {
	                                    console.log("error");
	                                })
	                                .always(function() {
	                                    console.log("complete");
	                                });
								}else{
									var $imagePath = $(".fileRightStatus").val();
									var $idSponsor =  $(".fileRightStatus").attr("id");
									$.ajax({
	                                    url: $basePath+'/out/expenses/editsponsor',
	                                    type: 'POST',
	                                    dataType: 'JSON',
	                                    data: {id_presentation: $id_presentation , position : "right" ,nombre : $fileName,type : $fileType, valueImagen  : e.target.result , imagePath : $imagePath, idSponsor : $idSponsor},
	                                })
	                                .done(function(response) {
	                                	 $(".fileRightStatus").attr("id",response.data.id_sponsor);
		                                 $(".fileRightStatus").val(response.data.imagepath);
	                                })
	                                .fail(function() {
	                                    console.log("error");
	                                })
	                                .always(function() {
	                                    console.log("complete");
	                                });
								}
                    	}else{
                    		alertify.alert("Las dimensiones de la imagen no deben pasar los 900px");
                    	}	
                    }else{
                    	alertify.alert("La imagen no debe de pesar mas de 1MB");
                    }
                    
              
                };
 
            }

	}else{
		alertify.alert("Solo se permiten imagenes JPG, GIF, PNG");
	}
	});


/* ******************** Al cambiar la imagen del footer ******************** */
$(document).on("change", ".fileFooter", function(){
	var $file = this.files[0], $fileName = $file.name, $fileSize = $file.size, $fileType = $file.type;

	if($fileType.match('image.*')){
		var $image = $(this), $id_image = $image.attr("id");
		var $inputQuestion = $image.parent().prev();//Id del input pregunta 
		var reader = new FileReader();
	
		reader.readAsDataURL(this.files[0]);
            reader.onload = function (e) {
                //Initiate the JavaScript Image object.
                var image = new Image();
 
                //Set the Base64 string return from FileReader as source.
                image.src = e.target.result;
                       
                //Validate the File Height and Width.
                image.onload = function () {
                    var height = this.height;
                    var width = this.width;

                    if($fileSize<1050000){
                    	if(height < 900 || width < 900){
                    			$image.parent().css('background-image', 'none');
								$image.next().html(""); 
								$image.next().next().html("");//Limpiamos imagen y tache cada vez que hacemos un cambio
								$image.next().append("<img src='" + e.target.result + "' class='thumbFooter'/>");//Agregamos vista previa de la imagen
								$image.next().next().addClass("deleteImage").append("<img src='"+$basePath+"/public/img/close.png' width='19' height='19' alt='close'/>");//Agregamos tache en la imagen
								
								if($("#fileFooterStatus").val()==0){
									$.ajax({
	                                    url: $basePath+'/out/expenses/addsponsor',
	                                    type: 'POST',
	                                    dataType: 'JSON',
	                                    data: {id_presentation: $id_presentation , position : "buttom" ,nombre : $fileName,type : $fileType, valueImagen  : e.target.result},
	                                })
	                                .done(function(response) {
	                                    $(".fileFooterStatus").attr("id",response.data.id_sponsor);
	                                    $(".fileFooterStatus").val(response.data.imagepath);
	                                })
	                                .fail(function() {
	                                    console.log("error");
	                                })
	                                .always(function() {
	                                    console.log("complete");
	                                });
								}else{
									var $imagePath = $(".fileFooterStatus").val();
									var $idSponsor =  $(".fileFooterStatus").attr("id");
									$.ajax({
	                                    url: $basePath+'/out/expenses/editsponsor',
	                                    type: 'POST',
	                                    dataType: 'JSON',
	                                    data: {id_presentation: $id_presentation , position : "buttom" ,nombre : $fileName,type : $fileType, valueImagen  : e.target.result , imagePath : $imagePath, idSponsor : $idSponsor},
	                                })
	                                .done(function(response) {
	                                	 $(".fileFooterStatus").attr("id",response.data.id_sponsor);
		                                 $(".fileFooterStatus").val(response.data.imagepath);
	                                })
	                                .fail(function() {
	                                    console.log("error");
	                                })
	                                .always(function() {
	                                    console.log("complete");
	                                });
								}
                    	}else{
                    		alertify.alert("Las dimensiones de la imagen no deben pasar los 900px");
                    	}	
                    }else{
                    	alertify.alert("La imagen no debe de pesar mas de 1MB");
                    }
                    
              
                };
 
            }

	}else{
		alertify.alert("Solo se permiten imagenes JPG, GIF, PNG");
	}
	});

	$(document).on("click", ".deleteImage", function(){
		$idSponsor = $(this).prev().prev().prev().attr("id");
		$imagePath = $(this).prev().prev().prev().val();
		if(confirm("Seguro de eliminar la imagen?")){
			$(this).prev().html(""), $(this).html(""), $(this).prev().prev().val("");
			$(this).parent().css('background-image', 'url('+$basePath+'/public/img/uploadimage.png)');
			$.ajax({
		        url: $basePath+'/out/expenses/deletesponsor',
				type: 'POST',
				dataType: 'json',
				data: {id_sponsor: $idSponsor, imagePath : $imagePath},
			})
			.done(function(response) {
				//Limpiamos la imagen, Limpiamos el tache, Limpiamos valor del input file
		
			})
			.fail(function() {
				console.log("error");
			})
			.always(function() {
				console.log("complete");
			});
			
		
		}else{ return false; }
	});

});
