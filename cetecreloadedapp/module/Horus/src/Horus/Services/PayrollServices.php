<?php
namespace Horus\Services;

use System\Services\UsersService;
use Horus\Model\PayrollModel;

class PayrollServices
{
	const SALARY_FOOD_OPERATOR   = 30;//Salario de comida por operador
	const SALARY_FOOD_ASSISTANCE = 30;//Salario de comida por asistente
	private $payrollModel;
	private $userService;
	
	/*
	 * Instanciar modelo de nomina
	 */
	private function getPayrollModel()
	{
		return $this->payrollModel = new PayrollModel();
	}
	
	/*
	 * Servicio de usuarios
	 */
	private function getUserService()
	{
		return $this->userService = new UsersService();
	}
	
	/*
	 * OBTENER SALARIO POR EMPLEADOS
	 */
	private function getSalaryByUser($idUser, $date, $type)
	{
		$salaryUser = $this->getPayrollModel()->getSalaryByUser($idUser, $desde, $date);
		return $salaryUser;
	}
	
	/*
	 * CALCULAR RANGO DE FECHAS
	 */
	public function calcularRangoFechas($date, $type)
	{
		$desde = "";
		// Validamos Fechas dependiento el tipo de pago(semanal, quincenal o mensual)
		if($type == "S"){
			$dias = 7;
			$desde = date("Y-m-d", strtotime("$date - $dias day"));
		}else if($type == "Q"){
			$dias = 15;
			$desde = date("Y-m-d", strtotime("$date - $dias day"));
		}else if($type == "M"){
			$dias = 30;
			$desde = date("Y-m-d", strtotime("$date - $dias day"));
		}
		
		// FECHA ACTUAL MENOS UN DIA (HASTA)
		$hasta = date("Y-m-d", strtotime("$date - 1 day"));
		$data  = array("desde" => $desde, "hasta" => $hasta);
		
		return $data;
	}
	
	/*
	 * SALARIO DE VIAJES FORANEOS
	 */
	private function obtenerSalarioViajesForaneos($id_user, $date, $type)
	{
		$rangoFechas = $this->calcularRangoFechas($date, $type);
		
		$salarioViajesForaneos = $this->getPayrollModel()->obtenerSalarioViajesForaneos($id_user,$rangoFechas['desde'], $rangoFechas['hasta']);
		//print_r($salarioViajesForaneos[0]['salario_viajes_foraneos']); exit;
		return $salarioViajesForaneos[0]['salario_viajes_foraneos'];
	}
	
	/*
	 * SALARIO VIAJES LOCALES
	 */
	private function obtenerSalarioViajesLocales($id_user, $date, $type)
	{
		$rangoFechas = $this->calcularRangoFechas($date, $type);
		
		$salarioViajesForaneos = $this->getPayrollModel()->obtenerSalarioViajesLocales($id_user, $rangoFechas['desde'], $rangoFechas['hasta']);
		//print_r($salarioViajesForaneos[0]['salario_viajes_foraneos']); exit;
		return $salarioViajesForaneos[0]['salario_viajes_locales'];
	}
	
	/*
	 * TOTAL DE VIAJES LOCALES
	 */
	private function obtenerTotalViajesLocales($id_user, $date, $type)
	{
		$total = array();
		$rangoFechas = $this->calcularRangoFechas($date, $type);
		$totalViajesLocales = $this->getPayrollModel()->totalViajesLocales($id_user, $rangoFechas['desde'], $rangoFechas['hasta']);
		//echo "<pre>"; print_r($totalViajesLocales);
		foreach ($totalViajesLocales as $row){
			$total[$row['id_shipping']] = $row['id_shipping'];
		}
		//echo "<pre>"; print_r(count($total)); exit;
		//return $totalViajesLocales[0]['total_viajes_locales'];
		return count($total);
	}
	
	/*
	 * TOTAL DE VIAJES FORANEOS
	 */
	private function obtenerTotalViajesForaneos($id_user, $date, $type)
	{
		$total = array();
		$rangoFechas = $this->calcularRangoFechas($date, $type);
		$totalViajesForaneos = $this->getPayrollModel()->totalViajesForaneos($id_user, $rangoFechas['desde'], $rangoFechas['hasta']);
		
		foreach ($totalViajesForaneos as $row){
			//$total[strtotime($row['date_shipping'])] = $row['date_shipping'];
			$total[$row['id_shipping']] = $row['id_shipping'];
		}
		
		return count($total);
	}
	
	/*
	 * TOTAL BONOS Y DESCUENTOS 
	 */
	/*private function obtenerBonosDescuentos($id_user, $date, $type)
	{
		$rangoFechas = $this->calcularRangoFechas($date, $type);
		$totalBonosDescuentos = $this->getPayrollModel()->obtenerBonosDescuentos($id_user, $rangoFechas['desde'], $rangoFechas['hasta']);
		return $totalBonosDescuentos;
	}*/
	
	/*
	 * SUMA TOTAL DE LOS BONOS APLICADOS
	 */
	private function obtenerBonos($id_user, $date, $type)
	{
	 	$rangoFechas = $this->calcularRangoFechas($date, $type);
		$totalBonos  = $this->getPayrollModel()->obtenerBonos($id_user, $date, $type);
	 	return $totalBonos[0]['bonos'];
	}
	
	/*
	 * OBTENER LA LISTA DE LOS BONOS APLICABLES POR EMPLEDO
	 */
	private function getAllAplicableBonus($id_user,$type)
	{
		$suma = array();
		$data = array(
				"id_employee" => $id_user,
				"type"        => $type
		);
		
		$aplicableBonus = $this->getPayrollModel()->getAllBonusByUser($data);
		
		foreach ($aplicableBonus as $row){
			$suma[] = $row['amount'];
		}
		return array_sum($suma);
	}
	 
	/*
	 * SUMA TOTAL DE LOS DESCUENTOS APLICADOS
	 */
	private function obtenerDescuentos($id_user, $date, $type)
	{
	 	$rangoFechas     = $this->calcularRangoFechas($date, $type);
	  	$totalDescuentos = $this->getPayrollModel()->obtenerDescuentos($id_user, $date, $type);
	  	return $totalDescuentos[0]['descuentos'];
	}
	
	/*
	 * OBTENER LA LISTA DE LOS DESCUENTOS APLICABLES POR EMPLEDO
	 */
	private function getAllAplicableDiscount($id_user,$type)
	{
		$suma = array();
		$data = array(
				"id_employee" => $id_user,
				"type"        => $type
		);
	
		$aplicableDiscount = $this->getPayrollModel()->getAllBonusByUser($data);
	
		foreach ($aplicableDiscount as $row){
			$suma[] = $row['amount'];
		}
		return array_sum($suma);
	}
	
	/*
	 * OBTENER EL SALARIO CUANDO YA FUE PAGADA LA NOMINA
	 */
	private function getSalaryPaidPayrollByUser($id_user, $date)
	{
		$salaryPaid = $this->getPayrollModel()->getSalaryPaidPayrollByUser($id_user, $date);
		return $salaryPaid[0]['amount'];
	}
	
	/*
	 * LISTADO DE LA NOMINA PARA GENERAR UN EXCEL
	 */
	public function generateExcelPayroll($type, $date)
	{
		$NTyp = 0;
		if($type == "S"){
			$NTyp = 1;
		}elseif($type == "Q"){
			$NTyp = 2;
		}elseif($type == "M"){
			$NTyp = 3;
		}
		// LISTA DE EMPLEADOS EN LA NOMINA
		$rows = $this->getPayrollModel()->generateExcelPayroll($NTyp, $date);
		$data = array();
		//echo "<pre>"; print_r($rows); exit;
		foreach ($rows as $row){
			
			if ($row['pay'] == 1){
				$pay = "Pagado";
			}else{
				$pay = "Pendiente";
			}
			
			$data[] = array(
					'user_id'  => $row['user_id'],
					'name'     => $row['name'],
					'surname'  => $row['surname'],
					'lastname' => $row['lastname'],
					'name_job' => $row['name_job'],
					//'date_admission' => $row['date_admission'],
					'countBanc'      => $row['countBanc'],
					'pay'	   => $pay,
					'amount'   => \Util_Tools::currency($row['amount']),
					'date'     => $row['date'],
			);
		}
		
		return $data;
	}
	
	/*
	 * OBTENER LISTADO DE NOMINA A PAGAR (POR VIAJES-ENLASA)
	 */
	public function getPayRollByUserToDateEnlasa($type, $date)
	{
		$NTyp = 0;
		if($type == "S"){
			$NTyp = 1;
		}elseif($type == "Q"){
			$NTyp = 2;
		}elseif($type == "M"){
			$NTyp = 3;
		}
		// LISTA DE EMPLEADOS EN LA NOMINA
		$rows = $this->getPayrollModel()->getPayRollByUserToDateEnlasa($NTyp, $date);
		//echo "<pre>"; print_r($rows); exit;
		//$desdeHasta = $this->calcularRangoFechas($date,$type);
		//$rangoFechas = $this->fechas($desdeHasta['desde'], $desdeHasta['hasta']);
		
		foreach ($rows as $row){
			// SALARIO POR VIAJES
			//$salaryShippings       = $this->getSalaryByUserShippings($row['user_id'] ,$date,$type);
			
			// TOTAL DIAS DE ASISTENCIA
			$assistanceDaysByUser  = $this->assistanceDaysByUser($row['user_id'] ,$date, $type);
			//echo "<pre>"; print_r($assistanceDaysByUser);
			// FECHAS DE ASISTENCIA
			$diasAplicaAsistencia  = $this->diasAplicaAsistencia($row['user_id'] ,$date, $type);
			//echo "<pre>"; print_r($diasAplicaAsistencia);
			// IMPORTE POR COMIDA
			if($row['name_job'] == "Operador"){
				$food = self::SALARY_FOOD_OPERATOR;
			}else{
				$food = self::SALARY_FOOD_ASSISTANCE;
			}
			
			// SALARIO POR ASISTENCIA
			$salaryByAssistance    = ($row['cost'] + $food) * ($assistanceDaysByUser - $diasAplicaAsistencia);
			//echo "<pre>"; print_r($salaryByAssistance); exit;
			// SALARIO TOTAL POR VIAJES LOCALES
			$salarioViajesLocales  = $this->obtenerSalarioViajesLocales($row['user_id'], $date, $type);
			
			// SALARIO TOTAL POR VIAJES FORANEOS
			$salarioViajesForaneos = $this->obtenerSalarioViajesForaneos($row['user_id'], $date, $type);
			
			// TOTAL DE VIAJES LOCALES
			$totalViajesLocales    = $this->obtenerTotalViajesLocales($row['user_id'], $date, $type);
			
			// TOTAL DE VIAJES FORANEOS
			$totalViajesForaneos   = $this->obtenerTotalViajesForaneos($row['user_id'], $date, $type);
			
			//TOTAL DE BONOS APLICABLES POR EMPLEADO
			$aplicablesBonos       = $this->getAllAplicableBonus($row['user_id'], "BONO");
			
			// SUMA TOTAL DE LOS BONOS APLICADOS POR EMPLEADO
			$totalBonos            = $this->obtenerBonos($row['user_id'], $date, $type);
			
			//TOTAL DE DESCUENTOS APLICABLES POR EMPLEADO
			$aplicableDiscount     = $this->getAllAplicableDiscount($row['user_id'], "DESCUENTO");
			
			// SUMA TOTAL DE LOS DESCUENTOS APLICADOS POR EMPLEADO
			$totalDescuentos       = $this->obtenerDescuentos($row['user_id'], $date, $type);
			
			if ($row['pay'] == 0){
				// TOTAL SALARIO POSIBLE
				$salarioTotal      = ($salarioViajesForaneos + $salarioViajesLocales + $totalBonos + $salaryByAssistance) - $totalDescuentos;
			}else {
				// TOTAL SALARIO PAGADO
				$salarioTotal =	$this->getSalaryPaidPayrollByUser($row['user_id'], $date);
			}
			
			//echo "<pre>"; print_r($salarioViajesLocales); exit;
			$payroll[] = array(
					'user_id'         => $row['user_id'],
					'name'            => $row['name'],
					'surname'         => $row['surname'],
					'lastname'        => $row['lastname'],
					'pay'             => $row['pay'],
					'cost'            => $row['cost'],
					'period'          => $row['period'],
					'mannerofpayment' => $row['mannerofpayment'],
					'date_admission'  => $row['date_admission'],
					'name_job'        => $row['name_job'],
					'info' => array(
							'days_assistance'         => $assistanceDaysByUser,
							'amount_food'             => $food,
							'salario_asistencia'      => $salaryByAssistance,
							'total_bonos_aplicados'   => $totalBonos,
							'total_descu_aplicados'   => $totalDescuentos,
							'total_viajes_locales'    => $totalViajesLocales,
							'total_viajes_foraneos'   => $totalViajesForaneos,
							'salario_viajes_locales'  => $salarioViajesLocales,
							'salario_viajes_foraneos' => $salarioViajesForaneos,
							'salario_total'           => $salarioTotal,
							'bonos'                   => $aplicablesBonos,
							'descuentos'              => $aplicableDiscount
					)
			);
		}
		//echo "<pre>"; print_r($payroll); exit;
		return $payroll;
	}
	
	/*
	 * OBTENER BONOS  Y DESCUENTOS POR EMPLEADO
	 */
	public function amountUserPayroll($data)
	{
		$rows = $this->getPayrollModel()->getAllBonusByUser($data);
		return $rows;
	}
	
	/*
	 * Calcular salario por empleado (ENLASA-VIAJES)
	 */
	private function getSalaryByUserShippings($idUser, $date, $type)
	{
		$desde = "";
		
		if($type == "S"){
			$dias = 7;
			$desde = date("Y-m-d", strtotime("$date - $dias day"));
		}else if($type == "Q"){
			$dias = 15;
			$desde = date("Y-m-d", strtotime("$date - $dias day"));
		}else if($type == "M"){
			$dias = 30;
			$desde = date("Y-m-d", strtotime("$date - $dias day"));
		}
		
		// FECHA ACTUAL MENOS UN DIA
		$dateReal   = date("Y-m-d", strtotime("$date - 1 day"));
		$salaryUser = $this->getPayrollModel()->getSalaryByUserShippings($idUser, $desde, $dateReal);
		return $salaryUser[0]['totalpagar'];
	}
	
	/*
	 * Calcular dias de asistencia (ENLASA-ASISTENCIA)
	 */
	public function assistanceDaysByUser($idUser, $date, $type)
	{
		$desde = "";
		
		if($type == "S"){
			$dias = 7;
			$desde = date("Y-m-d", strtotime("$date - $dias day"));
		}else if($type == "Q"){
			$dias = 15;
			$desde = date("Y-m-d", strtotime("$date - $dias day"));
		}else if($type == "M"){
			$dias = 30;
			$desde = date("Y-m-d", strtotime("$date - $dias day"));
		}
		
		// FECHA ACTUAL MENOS UN DIA
		$dateReal       = date("Y-m-d", strtotime("$date - 1 day"));
		$assistanceDays = $this->getPayrollModel()->assistanceDaysByUser($idUser, $desde, $dateReal);
		return $assistanceDays[0]['dias_asistencia'];
	}
	
	
	/*
	 * Obtener dias de asistencia por usuario
	 */
	private function getAssistantUserWeekly($id, $desde, $dateReal)
	{
		
		$shippings = $this->getPayrollModel()->detailShippingsPayrollByIdUser($id, $desde, $dateReal);
		//echo "<pre>"; print_r($shippings);
		foreach ($shippings as $row){
			if (!isset($infoShippins[$row['date_shipping']])){
				if ($row['type_destination'] == 2){
				$infoShippins[$row['date_shipping']] = array(
						'id_shipping' => $row['id_shipping'],
						'date_assistance' => $row['date_shipping'],
						'type_destination' => $row['type_destination'],
						'direct_route'     => $row['direct_route'],
						//'name_destination' => $row['name_destination'],
						//'description_destination' => $row['description_destination'],
						'id_user'       => $row['id_user'],
				);
				
				}
			}
		}
		
		//echo "<pre>"; print_r($infoShippins);
		$assistanceDays = $this->getPayrollModel()->getAssistantUserWeekly($id, $desde, $dateReal);
		//echo "<pre>"; print_r($assistanceDays);
		
		/*foreach ($assistanceDays as $row){
			foreach ($infoShippins as $row2){
				if ($row['date_assistance'] == $row2['date_assistance']){
					//unset($row['date_assistance']);
					$a[] = array(
						'date_assistance' => $row['date_assistance']
					);
				}
			}
		}*/
		//echo "<pre>"; print_r(array_diff_assoc($infoShippins, $assistanceDays)); 
		//exit;
		//echo "<pre>"; print_r($combinarArreglo);
		//$arreUnico = $this->arrayUnico($combinarArreglo, "date_assistance");
		//echo "<pre>"; print_r($arreUnico); 
		//exit;
		
		return $assistanceDays;
	}
	
	private function arrayUnico($array, $campo) {
		foreach ($array as $sub){
			$cmp[] = $sub[$campo];
		}
		echo "<pre>"; print_r($cmp); 
		$unique = array_unique($cmp);
		echo "<pre>"; print_r($unique); exit;
		foreach ($unique as $k => $campo){
			$resultado[] = $array[$k];
		}
		return $resultado;
	}
	
	/*
	 * CALCULAMOS LOS DIAS EN LOS QUE SE APLICA LA ASISTENCIA Y CUANDO NO
	 */
	private function diasAplicaAsistencia($idUser, $date, $type)
	{
		//print_r($idUser); exit;
		/*$getAssistantUserWeekly = $this->getAssistantUserWeekly($idUser, $date, $type);
		$detailShippingsPayrollByIdUser = $this->getPayrollModel()->detailShippingsPayrollByIdUser($idUser, $date, $type);
		foreach ($getAssistantUserWeekly as $row){
			$arre1[] = array(
				'date_assistance' => $row['date_assistance'],
			);
		}*/
		// FECHA ACTUAL MENOS UN DIA
		$dateReal     = date("Y-m-d", strtotime("$date - 1 day"));
		$desde        = "";
		
		if($type == "S"){
			$dias = 7;
			$desde = date("Y-m-d", strtotime("$date - $dias day"));
		}else if($type == "Q"){
			$dias = 15;
			$desde = date("Y-m-d", strtotime("$date - $dias day"));
		}else if($type == "M"){
			$dias = 30;
			$desde = date("Y-m-d", strtotime("$date - $dias day"));
		}
		
		//$infoAssists  = $this->getPayrollModel()->getAssistantUserWeekly($id, $desde, $dateReal);
		$shippings = $this->getPayrollModel()->detailShippingsPayrollByIdUser($idUser, $desde, $dateReal);
		//echo "<pre>"; print_r($shippings);
		$conta = 0;
		foreach ($shippings as $row){
			if (!isset($infoShippins[$row['date_shipping']])){
				$infoShippins[$row['date_shipping']] = array(
						'id_shipping' => $row['id_shipping'],
						'date_shipping' => $row['date_shipping'],
						'type_destination' => $row['type_destination'],
						'direct_route'     => $row['direct_route'],
						//'name_destination' => $row['name_destination'],
						//'description_destination' => $row['description_destination'],
						'id_user'       => $row['id_user'],
				);
				if ($row['type_destination'] == 2){
					$conta++;
				}
			}
		}
		//echo "<pre>"; print_r($infoShippins); 
		//echo "<pre>"; print_r($conta); exit;
		return $conta;
	}
	
	/*
	 * Pagar Nomina
	 */
	public function addPayRoll($data)
	{
		//$idUsers = explode(",", $data['idUsers']);
		
		foreach ($data['idUsers'] as $row)
		{
			//$salaryUser = $this->getSalaryByUserShippings($row ,$data['date'],$data['type']);
			//$pagoreal = $salaryUser[0];
			$payroll[] = array(
					"id_user"     => $row['id'],
					"amount"      => \Util_Tools::notCurrencyOutPoint($row['amount']),
					"date"        => $data['date'],
					"description" => "",
					"type"        => "NOMINA",
					"keys"        => "",
					//"total_pago"  => $salaryUser//$this->getSalaryByUserShippings($row, $data['date'], $data['type'])
			);
		}
		//echo "<pre>"; print_r($payroll); exit;
		$addPayRoll = $this->getPayrollModel()->addPayRoll($payroll);
		return  $addPayRoll;
	}
	
	
	/*
	 * Generar calendario de la nomina
	 */
	public function calendarPayroll($data)
	{
		// Formato fecha y-m-d
		$start = $data['start'];
		$end   = $data['end'];
			
		// Formato a fecha d-m-y
		$startDate = date("d-m-Y",strtotime($start));
		$endDate   = date("d-m-Y",strtotime($end));
			
		// Convertir a arreglo fechas
		$sDate = explode('-', $startDate);
		$eDate = explode('-', $endDate);
		$years = "";
			
		// Validar Fecha
		if ($sDate[1] == "12") {
			if ($sDate[0] == "1") {
				$years = $sDate[2];
			} else {
				$years = $eDate[2];
			}
		} else {
			$years = $sDate[2];
		}
			
		$users    = $this->getUserService()->getUsersAndDetails();
		$semana   = 0; $quincena = 0; $mes = 0;
			
		foreach ($users as $user){
			if ($user['period'] == 1){ $semana++; }
			if ($user['period'] == 2){ $quincena++; }
			if ($user['period'] == 3){ $mes++; }
		}
			
		$week = array(); $fortnight = array(); $month = array(); $arr = array();
			
		if ($semana > 0) {
			$week = $this->pagoSemana($years);
		} if ($quincena > 0) {
			$fortnight = $this->pagoQuincena($years);
		} if ($mes > 0){
			$month = $this->pagoMensual($years);
		}
			
		$calendar = array();
			
		foreach ($week as $w){
			$calendar[] = $w;
		}
		foreach ($fortnight as $f){
			$calendar[] = $f;
		}
		foreach ($month as $m){
			$calendar[] = $m;
		}
		
		return $calendar;
	}
	
	/*
	 * Agregar prestamo
	 */
	public function addLoans($formData)
	{
		if (isset($formData['numberpay'])){
			//echo "viene";
			for ($i =0; $i < $formData['numberpay']; $i++){
				$amount  = \Util_Tools::notCurrencyOutPoint($formData['amount']) / $formData['numberpay'];
				$data[] = array(
					"amount"      => (isset($formData['numberpay'])) ? $amount : "",
					"date"        => (isset($formData['numberpay'])) ? date("Y-m-d") : "",//date("Y-m-d"),
					"description" => (isset($formData['description'])) ? trim($formData['description']) : "",
					"type"        => (isset($formData['type'])) ? trim($formData['type']) : "",
					"id_user"     => (isset($formData['iduser'])) ? trim($formData['iduser']) : "",
				);
			}
		}else{
			//echo "no viene";
			$data[] = array(
					"amount"      => (isset($formData['amount'])) ? \Util_Tools::notCurrencyOutPoint($formData['amount']) : "",
					"date"        => date("Y-m-d"),
					"description" => (isset($formData['description'])) ? trim($formData['description']) : "",
					"type"        => (isset($formData['type'])) ? trim($formData['type']) : "",
					"id_user"     => (isset($formData['iduser'])) ? trim($formData['iduser']) : "",
			);
		}
		//print_r($data);exit;
		$loan = $this->getPayrollModel()->addLoans($data);
		return $loan;
	}
	
	/*
	 * FUNCION PARA APLICAR LOS BONOS O LOS DESCUENTOS
	 */
	public function aplicateAmountByEmployee($data)
	{
		//print_r($data);exit;
		foreach ($data as $row){
			$amounts[] = array(
					"id_paypayroll" => $row['id'],
					"status"        => 1,
					"date"          => $row['date']//date("Y-m-d")
			);
		}
		
		$aplicateAmounts = $this->getPayrollModel()->aplicateAmountByEmployee($amounts);
		return $aplicateAmounts;
	}
	
	/*
	 * Obtener el detalle de nomina por usuario
	 */
	public function detailPayrollByIdUser($id, $type, $date)
	{
		$infoShippins = array();
		$infoBonus    = $this->getPayrollModel()->getAllBonusesAndDiscountsWithoutApplyingByUser($id, $date, "BONO");
		$infoDisco    = $this->getPayrollModel()->getAllBonusesAndDiscountsWithoutApplyingByUser($id, $date, "DESCUENTO");
		
		// FECHA ACTUAL MENOS UN DIA
		$dateReal     = date("Y-m-d", strtotime("$date - 1 day"));
		$desde        = "";
		
		if($type == "S"){
			$dias = 7;
			$desde = date("Y-m-d", strtotime("$date - $dias day"));
		}else if($type == "Q"){
			$dias = 15;
			$desde = date("Y-m-d", strtotime("$date - $dias day"));
		}else if($type == "M"){
			$dias = 30;
			$desde = date("Y-m-d", strtotime("$date - $dias day"));
		}
		
		//$infoAssists  = $this->getPayrollModel()->getAssistantUserWeekly($id, $desde, $dateReal);
		$infoAssists  = $this->getAssistantUserWeekly($id, $desde, $dateReal);
		$shippings 	  = $this->getPayrollModel()->detailShippingsPayrollByIdUser($id, $desde, $dateReal);
	
		foreach ($shippings as $row){
			if (!isset($infoShippins[$row['id_shipping']])){
				$infoShippins[$row['id_shipping']] = array(
						'id_shipping' => $row['id_shipping'],
						'date_shipping' => $row['date_shipping'],
						'type_destination' => $row['type_destination'],
						'direct_route'     => $row['direct_route'],
						'name_destination' => $row['name_destination'],
						'description_destination' => $row['description_destination'],
						'id_user'       => $row['id_user'],
						'detailShippings' => array(),
				);
			}
			$infoShippins[$row['id_shipping']]['detailShippings'][] = array(
					'type_user' => $row['type_user'],
					'amount'    => $row['amount']
			);
		}
		//Array con la informacion
		$detailUser = array(
				'shippings' => $infoShippins,
				'bonus'     => $infoBonus,
				'discounts' => $infoDisco,
				'assists'   => $infoAssists,
		);
		//print_r($detailUser);exit;
		return $detailUser;
	}
	
	/*
	 * Obtener el ultimo dia del mes
	 */
	private function getLastDayMonth($year,$month) {
		return date("d",(mktime(0,0,0,$month+1,1,$year)-1));
	}
	
	/*
	 * Pago semana
	 */
	private function pagoSemana($years)
	{
		$meses          = array('01','02','03','04','05','06','07','08','09','10','11','12');
		$arreglomeses   = array();
		$arreglodelunes = array();
	
		foreach ($meses as $mes){
			$ndays = $this->getLastDayMonth($years,$mes);
			for($i = 1; $i <= $ndays; $i++){
				$currentdate  = mktime(0, 0, 0, $mes , $i, $years);
				// Validar el dia de la semana
				if(date('N',$currentdate) == 4){
					$d = ($i <= 9)?"0".$i:$i;
					$dateFinal = $years.'-'. $mes .'-'. $d;
					$arreglomeses[] = array(
							'start' => $dateFinal,
							'title' => 'Semanal',
							'uri'   => '/horus/payroll/listpayroll/S/'.$dateFinal
					);
					//'uri'   => '/horus/payroll/listpayroll?t=S&d='.$dateFinal);
				}
			}
		}
	
		return $arreglomeses;
	}
	
	/*
	 * Pago quincena
	 */
	private function pagoQuincena($years){
		$meses           = array('01','02','03','04','05','06','07','08','09','10','11','12');//array de meses
		$arreglomeses    = array();//array vacio de meses
		$arreglodequince = array();//array vacio de las quincenas
		$dateReal        = "";
	
		foreach ($meses as $mes){//recorre los memes
	
			$ndays = $this->getLastDayMonth($years,$mes);//funcion que calcula los dias del mes
			for($i=1; $i <= $ndays; $i++){//itera la funcion que trae los dias del mes
				if($i == 15){
	
					$dateString = $years . "-" . $mes . "-" . $i;
					$dateDay = date("l",strtotime($dateString));
					if($dateDay == "Saturday"){
						$dateReal = date('Y-m-d',strtotime($dateString.'-1 day'));
					}elseif ($dateDay == "Sunday"){
						$dateReal = date('Y-m-d',strtotime($dateString.'-2 day'));
					}else{
						$dateReal = $dateString;
					}
	
					$arreglomeses[] = array(
							'start' => $dateReal,
							'title' => 'Quincenal',
							'uri'   => '/horus/payroll/listpayroll/Q/'.$dateReal
							//'uri' => '/Horus/payroll/listpayroll?t=Q&d='.$dateReal
					);
	
				}else if($i == 30){
	
					$dateString = $years . "-" . $mes . "-" . $i;
					$dateDay = date("l",strtotime($dateString));
	
					if($dateDay == "Saturday"){
						$dateReal = date('Y-m-d',strtotime($dateString.'-1 day'));
					}elseif ($dateDay == "Sunday"){
						$dateReal = date('Y-m-d',strtotime($dateString.'-2 day'));
					}else{
						$dateReal = $dateString;
					}
	
					$arreglomeses[] = array(
							'start' => $dateReal,
							'title' => 'Quincenal',
							'uri'   => '/horus/payroll/listpayroll/Q/'.$dateReal
					);
	
				}
			}
		}
	
		return $arreglomeses;
	}
	
	/*
	 * Pago mensual
	 */
	private function pagoMensual($years){
		$meses        = array('01','02','03','04','05','06','07','08','09','10','11','12');//array de meses
		$arreglomes   = array();//array vacio de meses
		$arreglomeses = array();//array vacio de las quincenas
		$dateReal     = "";
	
		foreach ($meses as $mes){//recorre los memes
	
			if($mes == "02"){
				$ndays = $this->getLastDayMonth($years,$mes);//funcion que calcula los dias del mes
				for($i=1; $i <= $ndays; $i++){//itera la funcion que trae los dias del mes
	
					if($i == $ndays){
	
						$dateString = $years . "-" . $mes . "-" . $i;
						$dateDay = date("l",strtotime($dateString));
	
						if($dateDay == "Saturday"){
							$dateReal = date('Y-m-d',strtotime($dateString.'-1 day'));
						}elseif ($dateDay == "Sunday"){
							$dateReal = date('Y-m-d',strtotime($dateString.'-2 day'));
						}else{
							$dateReal = $dateString;
						}
	
						$arreglomeses[] = array(
								'start' => $dateReal,
								'title' => 'Mensual',
								'uri'   => '/horus/payroll/listpayroll/M/'.$dateReal
						);
					}
				}
			}else{
					
				$ndays = $this->getLastDayMonth($years,$mes);//funcion que calcula los dias del mes
				for($i=1; $i <= $ndays; $i++){//itera la funcion que trae los dias del mes
	
					if($i == 30){
	
						$dateString = $years . "-" . $mes . "-" . $i;
						$dateDay = date("l",strtotime($dateString));
	
						if($dateDay == "Saturday"){
							$dateReal = date('Y-m-d',strtotime($dateString.'-1 day'));
						}elseif ($dateDay == "Sunday"){
							$dateReal = date('Y-m-d',strtotime($dateString.'-2 day'));
						}else{
							$dateReal = $dateString;
						}
						$arreglomeses[] = array(
								'start' => $dateReal,
								'title' => 'Mensual',
								'uri'   => '/horus/payroll/listpayroll/M/'.$dateReal
						);
					}
				}
			}
		}
		return $arreglomeses;
	}
	
	
	/*
	 * Rango de fechas
	 */
	function fechas($start, $end) {
		$range = array();
	
		if (is_string($start) === true) $start = strtotime($start);
		if (is_string($end) === true ) $end = strtotime($end);
	
		if ($start > $end) return createDateRangeArray($end, $start);
	
		do {
			$range[] = date('Y-m-d', $start);
			$start = strtotime("+ 1 day", $start);
		} while($start <= $end);
	
		return $range;
	}
	
	/*
	 * ELIMINAR BONOS O DESCUENTOS POR USUARIOS
	 */
	public function deleteAmountByUser($id)
	{
		$id_amount = (int) $id;
		$deleteRow = $this->getPayrollModel()->deleteAmountByUser($id_amount);
		return $deleteRow;
	}

}