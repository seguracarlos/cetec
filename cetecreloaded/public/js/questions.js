	$(document).on("ready", function(){

		var $id_topic = $("#id_topic").val(); //id del examen
		/***********Click en el boton agregar nueva pregunta***********/
		$(document).on("click", "#nueva", function(e){
			e.preventDefault();
			var $addNewQuestion = $(".addNewQuestion").html();//Contenido html de la clase nuevapregunta
			$(".contenedor").append($addNewQuestion);
		});

		/********************* Click en el boton eliminar pregunta completa *********************/
		$(document).on("click", ".delete-question", function(){

			var $btn_delete_question = $(this); //boton actual
			var $question = $(this).next().next().find(".question").val();			
			if($question != ""){
				alertify.confirm("<p>Seguro que deseas eliminar esta pregunta?</p>", function (e) {
					if(e){
						$btn_delete_question.parent().remove();	
					}else{
						return false;
					}
				}); 
			}else{$btn_delete_question.parent().remove();}
		});


		/***********Al obtener el blur de cada input .question***********/
		$("body").on("blur", ".question", function(){
			var input = $(this), $valorInput = input.val(), $idInput = input.attr("id");
			var $inputImagen = input.next().find("input:file");//input imagen
			if($valorInput != ""){
				input.parent().parent().parent().find(".tittle-question").html(input.val());
				}else{
					$(this).closest('.pregunta').find('.tipoPregunta').val('0');
					$(this).closest('.pregunta').find('.RespuestasPregunta').html("");
				}
		});

		
		


		/***********Al cambiar el tipo de pregunta principal***********/
		$(document).on("change", "select.tipoPregunta", function(){
			var valor    = $(this).parent().find('.question').val();
			var type     = $(this).val();
			if(valor  != ""){
				changeTypeQuestionPrincipal(type, $(this));
			}else{
				$('.tipoPregunta').val('0');
				alertify.alert("Tienes que escribir una pregunta");
			}
		});

	

		/*********** Click en el boton guardar preguntas ***********/
		$("#guardar").on("click", function(e){
			var $questions = [];
			var url = $basePath +  "exams/examquestions/" + $id_topic;
			var incompleteQuestions = 0;
			var options = [];
			var typeSelected = 0;
			var contents = {};
			var repeatedQuestions = false;
			var emptyOptions = 0;

			
			$("#contenedor .contenedorPregunta").each(function(index1,value) {
				if($(this).find(".CheckAnswer").is(":checked")){
					options[index1] = 1;
				}else{
					options[index1] = 0;
				}
				if($(this).find(".tipoPregunta ").val() == 0){
					typeSelected = 1;
				}
				
			});
			
			$("#contenedor .contenedorPregunta .question").each(function() {
			    var questionName = $(this).val();
			    if (contents[questionName]) {
			    	repeatedQuestions = true;
			        return false;
			    }
			    contents[questionName] = true;
			});


			$("#contenedor .contenedorPregunta").find('.RespuestasPregunta .respuesta').each(function(){
				if($(this).val() == ""){
					emptyOptions = 1;
				}
			});

			$.each(options, function( index, value ) {
				  if(value == 0){
					  incompleteQuestions = 1;
				  }
			});
			
			if(typeSelected == 0){
				if(!repeatedQuestions){
					if(emptyOptions == 0){
						if(incompleteQuestions == 0){
							$.each($("#contenedor .contenedorPregunta"), function( index, value ) {
								var answers = generarOpciones($(this));
									$questions.push({
										question : $(this).find(".question").val(),
										type     : $(this).find(".tipoPregunta").val(),
										id_topic : $id_topic,
										answers  : answers,
									});
								});					
							$.ajax({
								type: 'POST',
								url: $basePath + 'exams/examquestions/save',
								data: {questions : $questions},
								dataType: 'json',
								success: function(response){
									$(location).attr('href',url);
									console.log(response);
								},
								error: function(){ console.log("Fallo, intentalo otra vez"); }
							});	

					}else{
						alertify.alert("Existen preguntas que no tienen marcada una respuesta correcta");
					}

				}else{
					alertify.alert("Existen Preguntas que tienen respuestas en blanco");	
				}

			}else{
				alertify.alert("Existen Preguntas Repetidas");
			}

		}else{
			alertify.alert("Existen preguntas que no tienen un tipo de pregunta seleccionado");
		}			

		});

		/*********** Click en el boton cancelar preguntas ***********/
		$("#cancelar").on("click", function(e){
			e.preventDefault();
			var survey = $("#id_survey").val();
			var url = $basePath +  "exams/examquestions/" + $id_topic;
			$(location).attr('href',url);
		});

		$(".file").css({"visibility":"hidden"});
		$(".fileQuestion").css({"visibility":"hidden"});//Escondes el input file de preguntas

	});// ends document.ready
	
	function generarOpciones($contenedor)
	{
		var ans = [];
		
		$($contenedor).find('.RespuestasPregunta .A').each(function(){
			ans.push({
				  answer  : $(this).find('.respuesta').val(),
				  id      : generateHash(),
				  correct : ($(this).find('.CheckAnswer').is(':checked')) ? 1 : 0,
			  });
		});
		
		return ans;
		
	}
	
/********************* Funcion para cambiar el tipo de pregunta Principal*********************/
function changeTypeQuestionPrincipal(typeQuestion, select)
{
	
	var divCamposDinamicos = $(".camposDinamicos").html();//Contenido html de la clase camposDinamicos
	var divVerdaderoFalso  = $(".camposVerdaderoFalso").html();
	
			if(typeQuestion == 0){
				select.next().html("");
			}else if(typeQuestion == 1){//Abierta
				select.next().html("");
			}else if(typeQuestion == 3){//Si/No
				select.next().html("");
				select.next().append(divVerdaderoFalso);
				//select.next().append("<center><img src='"+$basePath+'/img/ajax-loader.gif'+"'></center>");
				//setTimeout(function(){
					//select.next().html("");
				//}, 1000); 
			}else if(typeQuestion == 2 || typeQuestion == 4){
				select.next().html("");
				select.next().append(divCamposDinamicos);
			}


}

/********************* Click en el boton agregar campos dinamicamente preguntas principales *********************/

$(document).on("click", ".agregarCampo", function(e){
	e.stopPropagation() 
	var divCamposDinamicos = $(".inputsDinamicos").html();
	$(this).parent().append(divCamposDinamicos);
						   
});


/********************* Solo una opcion correcta por pregunta *********************/

$(document).on('click', '.CheckAnswer', function () {
	
	if($(this).is(':checked')) {
		
		$(this).parent().parent().find('.CheckAnswer').removeAttr("data-id");
		$(this).attr("data-id","checked");
		$(this).parent().parent().find('.CheckAnswer').each(function() {
			if(!$(this).attr("data-id")){
				$(this).prop( "checked", false );
			}
		});
		
	} else {
		$(this).removeAttr("data-id");
	}
	
});


/********************* Click en el boton eliminar campos dinamicamente *********************/
$(document).on("click",".eliminar", function(e){ //click en eliminar campo
	e.preventDefault();
	var $this         = $(this);
	
		alertify.confirm("<p>Seguro que deseas eliminar esta respuesta?</p>", function (e) {
			if(e){
				$this.parent().remove();
			}else{
				return false;
			}
		});
	
});

function generateHash(){
	var aleatorio1 = Math.floor(Math.random() * (1 - 1000 + 1)) + 1;
	var aleatorio2 = Math.floor(Math.random() * (1 - 1000 + 1)) + 2;
	var aleatorio = aleatorio1 * aleatorio2/2;
	var hash = sha1(aleatorio.toString()); 
	return hash;
}





