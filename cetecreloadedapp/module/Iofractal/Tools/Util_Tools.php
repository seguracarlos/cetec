<?php

class Util_Tools
{
	/*
	 * Metodo para quitar el formato de pesos a los numeros 
	 */
	static function notCurrencyOutPoint($number){
		$sig[]=',';
		$sig[]='$';
		return str_replace($sig,'',$number);
	}
	
	/*
	 * Metodo para dar formato de fecha mysql
	 */
	static function dateDBFormat($separator, $dateSource)
	{
// 		$explode = explode($separator, $dateSource);
// 		$anio    = $explode[2] . '-';
// 		$mes     = $explode[1] . '-';
// 		$dia     = $explode[0];
	
// 		$fullDBDate = $anio . $mes . $dia;
// 		return $fullDBDate;
	}
	
	/*
	 * Metodo para dar formato de fecha dd/mm/yy
	 */
	static function dateFormat($separator, $dateSource)
	{
		$explode = explode($separator, $dateSource);
		$dia     = $explode[2] . '/';
		$mes     = $explode[1] . '/';
		$anio    = $explode[0];
	
		$fullDBDate = $dia . $mes . $anio;
		return $fullDBDate;
	}
	
	/*
	 * Metodo para formatear un numero plano a moneda
	 */
	static function currency($amount) {
		$format = number_format($amount, 2, '.', ',');
		return "$" . $format;
	}
	
	/*
	 * Metodo para dar formato a un numero
	 */
	public static function format ($num , $format){
		$var = "";
		$val = $format - (strlen("".$num));  // Funcion que cuenta el numero de digitos
	
		for($i = 0; $i < $val; $i++){
			$var .= "0";
		}
		return $var.$num;
	}
	
	/*
	 * CALCULAR EDAD EN BASE A UNA FECHA
	 */
	public static function calculateAge($dateOfBirth)
	{
		list($ano, $mes, $dia) = explode("-", $dateOfBirth);
		
		$ano_diferencia 	 = date("Y") - $ano;
		$mes_diferencia		 = date("m") - $mes;
		$dia_diferencia		 = date("d") - $dia;
		
		if ((($dia_diferencia < 0) && ($mes_diferencia == 0)) || ($mes_diferencia < 0)) {
			$ano_diferencia--;
		}
		
		return $ano_diferencia;
	}
}