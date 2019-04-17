$(document).ready(function(){

	$("#submitbutton").validarFormulario({
			form: "expenses"
		});
	
	$("#reference_of_expenses").on("change", function(){
		if($(this).val() == 1){
			howInCashExpenses(1);
		}else{
			howInCashExpenses(2);
		}
	});

});

function howInCashExpenses(type){
	$.ajax({
		type: 'GET',
		url: $basePath + "/Out/expenses/index",
		data: {type: type},
		dataType: "json",
		success: function(response){
			if(type == "1"){
				$("#amountAccountTxt").html("Saldo actual en la cuenta de gastos fijos " + formatNumber(response.amount,"$"));
			}else{
				 $("#amountAccountTxt").html("Saldo actual en la cuenta de gastos variables " + formatNumber(response.amount,"$"));
			}	 
			$("#amountAccount").val(response.amount);
		}
	  });
}