<?php
namespace Out\Services;

use Out\Model\ExpensesTripModel;
use Zend\Validator\File\Count;

class ExpensesTripService
{
	private $expensesTripModel;

	public function getExpensesTripModel()
	{
		return $this->expensesTripModel = new ExpensesTripModel();
	}
	
	public function fetchAllExpensesTrip($id_viaje)
	{
		$expenses = $this->getExpensesTripModel()->fetchAllExpensesTrip($id_viaje);
		$gasolina = array();
		$casetas = array();
		$alimentos = array();
		$varios = array();
		$viaticos = array();
		$totalGas = 0;
		$totalCas = 0;
		$totalAlim = 0;
		$totalVar = 0;
		$totalViat = 0;
		for($i=0;$i < count($expenses);$i++){
			if($expenses[$i]['type_expensestrip'] == 1){
				$gasolina[] = $expenses[$i];
				$totalGas = $totalGas + $expenses[$i]['amount_expensestrip']; 
			}
			if($expenses[$i]['type_expensestrip'] == 2){
				$casetas[] = $expenses[$i];
				$totalCas = $totalCas + $expenses[$i]['amount_expensestrip'];
			}
			if($expenses[$i]['type_expensestrip'] == 3){
				$alimentos[] = $expenses[$i];
				$totalAlim = $totalAlim + $expenses[$i]['amount_expensestrip'];
			}
			if($expenses[$i]['type_expensestrip'] == 4){
				$varios[] = $expenses[$i];
				$totalVar = $totalVar + $expenses[$i]['amount_expensestrip'];
			}
			if($expenses[$i]['type_expensestrip'] == 5){
				$viaticos[] = $expenses[$i];
				$totalViat = $totalViat + $expenses[$i]['amount_expensestrip'];
			}
		}
		$expensesAll = array(
				'gasolina' => $gasolina,
				'casetas' => $casetas,
				'alimentos' => $alimentos,
				'varios' => $varios,
				'viaticos' => $viaticos,
				'totalGas' => $totalGas,
				'totalCas' => $totalCas,
				'totalAlim' => $totalAlim,
				'totalVar' => $totalVar,
				'totalViat' => $totalViat
		);
		return $expensesAll;
	}
	public function addExpense($folio,$monto,$tipo,$id_viaje,$description){
		$dateActual = date("Y")."-".date("m")."-".date("d");
		$data = array(
			'id_trip_expensestrip' => $id_viaje,
			'date_expensestrip'	=> $dateActual,
			'type_expensestrip' => $tipo,
			'reference_expensestrip' => $folio,
			'description_expensestrip' => $description,
			'amount_expensestrip' => $monto
		);
		$addExpense = $this->getExpensesTripModel()->addExpense($data);
		return $addExpense;
	}
	public function getJourney($id_viaje){
		$journey = $this->getExpensesTripModel()->getJourney($id_viaje);
		$sinister = $this->getExpensesTripModel()->getSinister($id_viaje);
		$usersJourney = $this->getExpensesTripModel()->getUsersJourney($id_viaje);
		$types_gas = $this->getExpensesTripModel()->getTypesGas();
		$operador = array();
		$ayudantes = array();
		$trueSinister = null;
		if($sinister){
			$trueSinister = $sinister;
		}
		for($i=0;$i < count($usersJourney);$i++){
			if($usersJourney[$i]['type_user'] == 1){
				$operador = $usersJourney[$i];
			}else if($usersJourney[$i]['type_user'] == 2){
				$ayudantes[] = $usersJourney[$i];
			}
		}
		$dateJourney = array(
				'id_journey' => $journey[0]['id_shipping'],
				'client_folio' => $journey[0]['client_folio'],
				'destino' => $journey[0]['name_destination'],
				'fecha' => $journey[0]['start_date'],
				'end_date' => $journey[0]['end_date'],
				'cliente' => $journey[0]['name_company'],
				'unidad' => $journey[0]['id_product'],
				'end_gasoline_end_journey' => $journey[0]['end_gasoline_end_journey'], 
				'starting_mileage' => $journey[0]['starting_mileage'],
				'end_mileage' => $journey[0]['end_mileage'],
				'operador' => $operador,
				'ayudantes' => $ayudantes,
				'status_saveExpenses' => $journey[0]['status_saveExpenses'],
				'sinister' => $trueSinister,
				'type_gasoline' => $journey[0]['type_gasoline_name'],
				'type_gasoline_value' => $journey[0]['type_gasoline_value'],
				'type_gasSelect' => $types_gas,
				'status_full_data' => $journey[0]['status_full_data'],
				'return_viatic_rest' => $journey[0]['return_viatic_rest'],
				'internal_folio' => $journey[0]['internal_folio'],
				'type_destination' => $journey[0]['type_destination']
				
		);
		return $dateJourney;
	}
	public function deleteExpense($id_expense,$id_viaje){
		$deleteExpense = $this->getExpensesTripModel()->deleteExpense($id_expense,$id_viaje);
		$journey = $this->getExpensesTripModel()->getJourney($id_viaje);
		return $journey;
	}
	public function getGasolineCoust()
	{
		return $gasoline = $this->getExpensesTripModel()->getGasolineCoust();
	}
}