<?php
namespace Out\Services;

use Out\Model\ShippingsModel;
use Out\Model\PaypayrollModel;
use Zend\Validator\File\Count;
use Zend\Db\Sql\Predicate\Operator;
use Out\Model\ExpensesTripModel;

class ShippingsService
{
	private $shippingsModel;
	private $paypayrollModel;
	private $expensesTripModel;
	
	public function getExpensesTripModel()
	{
		return $this->expensesTripModel = new ExpensesTripModel();
	}

	public function getShippingsModel()
	{
		return $this->shippingsModel = new ShippingsModel();
	}
	
	public function getPay_payrollModel()
	{
		return $this->paypayrollModel = new PaypayrollModel();
	}
	
	public function fetchAllShippings()
	{
		$dateActual = date("Y")."-".date("m")."-".date("d");
		$shippings = $this->getShippingsModel()->fetchAllShippingsList();
		for($j=0;$j<count($shippings);$j++){
			$users_shippings = $this->getShippingsModel()->getUsersShipping($shippings[$j]['id_shipping']);
			if($users_shippings){
				for($k=0;$k < count($users_shippings);$k++){
					if($users_shippings[$k]['type_user'] == 1){
						$shippings[$j]['operator'] = $users_shippings[$k];
					}else if($users_shippings[$k]['type_user'] == 2){
						$shippings[$j]['helpers'][] = $users_shippings[$k];
					}
				}
			}
		}
		$shippingsDates = array();
		for($i=0;$i < count($shippings);$i++){
			if($dateActual >= $shippings[$i]['start_date'] and $shippings[$i]['end_date'] == 0000-00-00){
				$shippingsDates[] = $shippings[$i];
			}
		}
		return $shippings;
	}
	public function  validatedShipping($user){
		$dateActual = date("Y")."-".date("m")."-".date("d");
		$shippings = $this->getShippingsModel()->fetchAllShippings();
		$shippingsDates = array();
		$shippingVal = null;
		for($i=0;$i < count($shippings);$i++){
			if($dateActual >= $shippings[$i]['start_date'] and $shippings[$i]['end_date'] == 0000-00-00){
				$shippingsDates[] = $shippings[$i];
			}
		}
		if(count($shippingsDates) > 0){
			foreach($shippingsDates as $shippingDate){
				$users_shippings = $this->getShippingsModel()->getUsersShipping($shippingDate['id_shipping']);
				if($users_shippings){
					foreach($users_shippings as $userShipping){
						if($userShipping['id_user'] == $user){
							$shippingVal = $userShipping['id_shipping'];
						}
					}
				}
			}
		}
		return $shippingVal;
	}
	public function validateTripActual($id_viaje){
		$dateActual = date("Y")."-".date("m")."-".date("d");
		$shippings = $this->getShippingsModel()->fetchAllShippings();
		$shippingsDates = array();
		$shippingVal = null;
		for($i=0;$i < count($shippings);$i++){
			if($dateActual >= $shippings[$i]['start_date'] and $shippings[$i]['end_date'] == 0000-00-00){
				$shippingsDates[] = $shippings[$i];
			}
		}
		if(count($shippingsDates) > 0){
			foreach($shippingsDates as $shippingDate){
				if($shippingDate['id_shipping'] == $id_viaje){
					$shippingVal = $shippingDate['id_shipping'];
				}
			}
		}
		return $shippingVal;
	}
	public function addEndKm($id_viaje,$endingkm){
		$data = array(
				'id_shipping' => $id_viaje,
				'end_mileage' => $endingkm
		);
		$end_mileage = $this->getShippingsModel()->addEndKm($data);
		return $end_mileage;
	}
	public function consultEndKm($id_viaje){
		$end_mileage = $this->getShippingsModel()->consultEndKm($id_viaje);
		return $end_mileage;
	}
	public function editEndKm($id_viaje,$endingkm){
		$data = array(
				'id_shipping' => $id_viaje,
				'end_mileage' => $endingkm
		);
		$end_mileage = $this->getShippingsModel()->addEndKm($data);
		return $end_mileage;
	}
	public function addStartingGasoline($id_viaje,$startingGasoline){
		$data = array(
				'id_shipping' => $id_viaje,
				'start_gasoline' => $startingGasoline
		);
		$start_gasoline = $this->getShippingsModel()->addStartingGasoline($data);
		return $start_gasoline;
	}
	public function consultStartingGasoline($id_viaje){
		$start_gasoline = $this->getShippingsModel()->consultStartingGasoline($id_viaje);
		return $start_gasoline;
	}
	public function editStartingGasoline($id_viaje,$startingGasoline){
		$data = array(
				'id_shipping' => $id_viaje,
				'start_gasoline' => $startingGasoline
		);
		$start_gasoline = $this->getShippingsModel()->addStartingGasoline($data);
		return $start_gasoline;
	}
	public function addEndingGasoline($id_viaje,$endingGasoline){
		$data = array(
				'id_shipping' => $id_viaje,
				'end_gasoline' => $endingGasoline
		);
		$end_gasoline = $this->getShippingsModel()->addEndingGasoline($data);
		return $end_gasoline;
	}
	public function consultEndingGasoline($id_viaje){
		$end_gasoline = $this->getShippingsModel()->consultEndingGasoline($id_viaje);
		return $end_gasoline;
	}
	public function editEndingGasoline($id_viaje,$endingGasoline){
		$data = array(
				'id_shipping' => $id_viaje,
				'end_gasoline' => $endingGasoline
		);
		$end_gasoline = $this->getShippingsModel()->addEndingGasoline($data);
		return $end_gasoline;
	}
	public function addEndingGasolineEndJourney($id_viaje,$endingGasolineEndJourney){
		$data = array(
				'id_shipping' => $id_viaje,
				'end_gasoline_end_journey' => $endingGasolineEndJourney
		);
		$end_gasoline_end_journey = $this->getShippingsModel()->addEndingGasolineEndJourney($data);
		return $end_gasoline_end_journey;
	}
	public function consultEndingGasolineEndJourney($id_viaje){
		$end_gasoline_end_journey = $this->getShippingsModel()->consultEndingGasolineEndJourney($id_viaje);
		return $end_gasoline_end_journey;
	}
	public function editEndingGasolineEndJourney($id_viaje,$endingGasolineEndJourney){
		$data = array(
				'id_shipping' => $id_viaje,
				'end_gasoline_end_journey' => $endingGasolineEndJourney
		);
		$end_gasoline_end_journey = $this->getShippingsModel()->addEndingGasolineEndJourney($data);
		return $end_gasoline_end_journey;
	}
	public function addBondDiscountStatus($id_viaje,$bonoDescuento,$operator_id,$dateShipping,$rendimiento,$cambio,$initialDiferentExpenses,$deletingExpenses){
		if($deletingExpenses){
			foreach($deletingExpenses as $exp){
				$deleteExpense = $this->getExpensesTripModel()->deleteExpense($exp,$id_viaje);
			}
		}
		$dataShipping = array(
				'id_shipping' => $id_viaje,
				'status_saveExpenses' => 1,
				'rendimiento' => $rendimiento
		);
		$shipping = $this->getShippingsModel()->addSatatusExpense($dataShipping);
		$folio = null;
		if($shipping[0]['client_folio'] != ''){
			$folio = $shipping[0]['client_folio'];
		}else{
			$folio = $shipping[0]['internal_folio'];
		}
		$descripcion = null;
		$typeAmount = null;
		$newAmount = null;;
		$amount = abs($bonoDescuento);
		if($bonoDescuento <= 0){
			$typeAmount = 'BONO';
			$descripcion = 'Gasto de su dinero en el viaje '.$folio;
		}else if($bonoDescuento > 0){
			if($cambio == 0){
				$typeAmount = 'DESCUENTO';
				$descripcion = 'Se quedo el cambio de los viaticos en el viaje '.$folio;
			}else if($cambio == 1){
				if($initialDiferentExpenses < $bonoDescuento){
					$newAmount = $bonoDescuento - $initialDiferentExpenses;
					$typeAmount = 'DESCUENTO';
					$descripcion = 'Gasto en lo que no debia en el viaje '.$folio;
					$amount = $newAmount;
				}
			}
		}
		if($typeAmount != null){
			$dataPay_payroll = array(
					'amount' => $amount,
					'date' => $dateShipping,
					'description' => $descripcion,
					'type' => $typeAmount,
					'id_user' => $operator_id
			);
			$pay_payroll = $this->getPay_payrollModel()->addPayroll($dataPay_payroll);
			$save_statusPayroll = array(
					'shipping' => $shipping,
					'pay_payroll' => $pay_payroll
			);
			return $save_statusPayroll;
		}
		return $shipping;
	}
	public function historyShippings(){
		$dateActual = date("Y")."-".date("m")."-".date("d");
		$shippings = $this->getShippingsModel()->fetchAllShippings();
		for($j=0;$j<count($shippings);$j++){
			$users_shippings = $this->getShippingsModel()->getUsersShipping($shippings[$j]['id_shipping']);
			if($users_shippings){
				for($k=0;$k < count($users_shippings);$k++){
					if($users_shippings[$k]['type_user'] == 1){
						$shippings[$j]['operator'] = $users_shippings[$k];
					}else if($users_shippings[$k]['type_user'] == 2){
						$shippings[$j]['helpers'][] = $users_shippings[$k];
					}
				}
			}
		}
		$shippingsDates = array();
		for($i=0;$i < count($shippings);$i++){
			if($shippings[$i]['end_date'] != 0000-00-00 && $shippings[$i]['status_saveExpenses'] != null){
				$shippingsDates[] = $shippings[$i];
			}
		}
		return $shippingsDates;
	}
	public function historyShippingsaddkm(){
		$dateActual = date("Y")."-".date("m")."-".date("d");
		$shippings = $this->getShippingsModel()->fetchAllShippings();
		for($j=0;$j<count($shippings);$j++){
			$users_shippings = $this->getShippingsModel()->getUsersShipping($shippings[$j]['id_shipping']);
			if($users_shippings){
				for($k=0;$k < count($users_shippings);$k++){
					if($users_shippings[$k]['type_user'] == 1){
						$shippings[$j]['operator'] = $users_shippings[$k];
					}else if($users_shippings[$k]['type_user'] == 2){
						$shippings[$j]['helpers'][] = $users_shippings[$k];
					}
				}
			}
		}
		$shippingsDatesAddKm = array();
		for($i=0;$i < count($shippings);$i++){
			$sinister = $this->getExpensesTripModel()->getSinister($shippings[$i]['id_shipping']);
			if(!$sinister){
				if($shippings[$i]['end_date'] != 0000-00-00 && ($shippings[$i]['end_mileage'] == null || $shippings[$i]['end_gasoline'] == null || $shippings[$i]['end_gasoline_end_journey'] == null)){
					$shippingsDatesAddKm[] = $shippings[$i];
				}	
			}
		}
		return $shippingsDatesAddKm;
	}
	public function historyShippingsValidatingExpenses(){
		$dateActual = date("Y")."-".date("m")."-".date("d");
		$shippings = $this->getShippingsModel()->fetchAllShippings();
		for($j=0;$j<count($shippings);$j++){
			$users_shippings = $this->getShippingsModel()->getUsersShipping($shippings[$j]['id_shipping']);
			if($users_shippings){
				for($k=0;$k < count($users_shippings);$k++){
					if($users_shippings[$k]['type_user'] == 1){
						$shippings[$j]['operator'] = $users_shippings[$k];
					}else if($users_shippings[$k]['type_user'] == 2){
						$shippings[$j]['helpers'][] = $users_shippings[$k];
					}
				}
			}
		}
		$shippingsDatesValidating = array();
		for($i=0;$i < count($shippings);$i++){
			if(!$shippings[$i]['id_sinister']){
				if($shippings[$i]['status_full_data'] == 1){
					$shippingsDatesValidating[] = $shippings[$i];
				}
			}
		}
		return $shippingsDatesValidating;
	}
	public function historyShippingsValidatingExpensesSinister(){
		$dateActual = date("Y")."-".date("m")."-".date("d");
		$shippings = $this->getShippingsModel()->fetchAllShippings();
		for($j=0;$j<count($shippings);$j++){
			$users_shippings = $this->getShippingsModel()->getUsersShipping($shippings[$j]['id_shipping']);
			if($users_shippings){
				for($k=0;$k < count($users_shippings);$k++){
					if($users_shippings[$k]['type_user'] == 1){
						$shippings[$j]['operator'] = $users_shippings[$k];
					}else if($users_shippings[$k]['type_user'] == 2){
						$shippings[$j]['helpers'][] = $users_shippings[$k];
					}
				}
			}
		}
		$shippingsDatesSinister = array();
		for($i=0;$i < count($shippings);$i++){
			$sinister = $this->getExpensesTripModel()->getSinister($shippings[$i]['id_shipping']);
			if($sinister){
				if($shippings[$i]['end_date'] != 0000-00-00 && $shippings[$i]['end_mileage'] == null && $shippings[$i]['end_gasoline'] == null && $shippings[$i]['end_gasoline_end_journey'] == null && $shippings[$i]['status_saveExpenses'] == null){
					$shippingsDatesSinister[] = $shippings[$i];
				}	
			}
		}
		return $shippingsDatesSinister;
	}
	public function addTypeGasoline($id_gasoline,$id_viaje){
		$data = array(
				'id_shipping' => $id_viaje,
				'type_gasoline' => $id_gasoline
		);
		return $updateTypeGasoline = $this->getShippingsModel()->addTypeGasoline($data);
	}
	public function getDatasById($id){
		return $datas = $this->getShippingsModel()->getDatasById($id);
	}
	public function updateShippingDates($data){
		$dataJouney = null;
		if($data['modaltype_destination'] != 1){
			$dataJouney = array(
					'id_shipping' => $data['modalidshipping'],
					'end_mileage' => $data['modalendmileage'],
					'end_gasoline_end_journey' => $data['modalendgasolinejourney'],
					'return_viatic_rest' => $data['returnViatic']
			);
		}else{
			$dataJouney = array(
					'id_shipping' => $data['modalidshipping'],
					'end_mileage' => $data['modalendmileage'],
					'end_gasoline_end_journey' => $data['modalendgasolinejourney']
			);
		}
		return $journey = $this->getShippingsModel()->updateShippingDates($dataJouney);
	}
	public function updateConfirmExpenses($id_ship,$type_destination){
		$data = null;
		if($type_destination != 1){
			$data = array(
					'id_shipping' => $id_ship,
					'status_full_data' => 1
			);
		}else{
			$shippingsDate = $this->getShippingsModel()->getDatasById($id_ship);
			$redimiento = ($shippingsDate[0]['end_mileage']-$shippingsDate[0]['starting_mileage'])/($shippingsDate[0]['end_gasoline_end_journey']/$shippingsDate[0]['value']);
			$data = array(
					'id_shipping' => $id_ship,
					'rendimiento' => $redimiento,
					'status_full_data' => 1
			);
		}
		return $journey = $this->getShippingsModel()->updateShippingDates($data);
	}
}