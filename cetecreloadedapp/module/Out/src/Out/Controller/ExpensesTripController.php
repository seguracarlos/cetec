<?php
namespace Out\Controller;

use Zend\View\Model\ViewModel;
use Iofractal\Controller\BaseController;
use Zend\Session\Container;
use Zend\Mvc\View\Console\ViewManager;
use Out\Services\ExpensesTripService;
use Out\Services\ShippingsService;

class ExpensesTripController extends BaseController
{
	private $session;
	private $expensesTripService;
	private $shippingsService;
	
	public function __construct()
	{
		$this->session = new Container('User');
	}
	
	public function getExpensesTripService()
	{
		return $this->expensesTripService = new ExpensesTripService();
	}
	public function getShippingsService(){
		return $this->shippingsService = new ShippingsService();
	}
	
	public function indexAction()
	{
		$shippings = $this->getShippingsService()->fetchAllShippings();
		$data = array(
				'shippings' => $shippings
		);
		return new ViewModel($data);
	}
	public function addAction()
	{
		$role = $this->session->offsetGet('roleId');
		$user = $this->session->offsetGet('userId');
		$nameUserSession = $this->session->offsetGet('name').$this->session->offsetGet('surname').$this->session->offsetGet('lastname');
		if(\Privilege::SUPERUSUARIO == $role || \Privilege::ADMINISTRADOR == $role || \Privilege::SUPERVISOR == $role){
			$id_viaje = $this->params()->fromRoute("id");
			if($id_viaje){
				$expensesAll = $this->getExpensesTripService()->fetchAllExpensesTrip($id_viaje);
				$journey = $this->getExpensesTripService()->getJourney($id_viaje);
				$data = array(
						"expensesAll" => $expensesAll,"journey" => $journey,"nameUserSession" => $nameUserSession,'role'=>$role
				);
				return new ViewModel($data);
			}
		}else if(\Privilege::CONSULTOR == $role){
			$id_viaje = $this->getShippingsService()->validatedShipping($user);
			if($id_viaje != null){
				$expensesAll = $this->getExpensesTripService()->fetchAllExpensesTrip($id_viaje);
				$journey = $this->getExpensesTripService()->getJourney($id_viaje);
				$data = array(
						"expensesAll" => $expensesAll,"journey" => $journey,"nameUserSession" => $nameUserSession,'role'=>$role
				);
				return new ViewModel($data);
			}
		}
		return new ViewModel(array('role'=>$role));
	}
	public function validateexpensesokAction()
	{
		$role = $this->session->offsetGet('roleId');
		$user = $this->session->offsetGet('userId');
		$nameUserSession = $this->session->offsetGet('name').$this->session->offsetGet('surname').$this->session->offsetGet('lastname');
		$id_viaje = $this->params()->fromRoute("id");
		$gasoline = $this->getExpensesTripService()->getGasolineCoust();
		if($id_viaje){
			$expensesAll = $this->getExpensesTripService()->fetchAllExpensesTrip($id_viaje);
			$journey = $this->getExpensesTripService()->getJourney($id_viaje);
			if($journey['status_full_data'] == 1){
				$data = array(
						"expensesAll" => $expensesAll,"journey" => $journey,"nameUserSession" => $nameUserSession,'role'=>$role
				);
				return new ViewModel($data);
			}
		}
		return new ViewModel(array('role'=>$role));
	}
	public function addexpensestripAction(){
		$request = $this->getRequest();
		$response = $this->getResponse();
		if($request->isPost()){
			$folio = $request->getPost('folio');
			$monto = $request->getPost('monto');
			$tipo = $request->getPost('type');
			$id_viaje = $request->getPost('id_viaje');
			$description = $request->getPost('description');
			$addExpense = $this->getExpensesTripService()->addExpense($folio,$monto,$tipo,$id_viaje,$description);
			$response->setContent(\Zend\Json\Json::encode(array('response' => true, 'data' => $addExpense)));
			return $response;
		}
	}
	
	public function addviattripAction(){
		$request = $this->getRequest();
		$response = $this->getResponse();
		if($request->isPost()){
			$tipo = $request->getPost('type');
			$id_viaje = $request->getPost('id_viaje');
			$monto = $request->getPost('monto');
			$folio = $request->getPost('folio');
			$description = $request->getPost('description');
			$addExpense = $this->getExpensesTripService()->addExpense($folio,$monto,$tipo,$id_viaje,$description);
			$response->setContent(\Zend\Json\Json::encode(array('response' => true, 'data' => $addExpense)));
			return $response;
		}
	}
	
	public function jurneyendingAction(){
		$shippings = $this->getShippingsService()->historyShippings();
		return new ViewModel(array("shippings" => $shippings));
	}
	public function jurneyendingeditaddAction(){
		$shippings = $this->getShippingsService()->historyShippingsaddkm();
		return new ViewModel(array("shippings" => $shippings));
	}
	public function validatingexpensesAction(){
		$shippings = $this->getShippingsService()->historyShippingsValidatingExpenses();
		return new ViewModel(array("shippings" => $shippings));
	}
	public function jurneyendingsinisterAction(){
		$shippings = $this->getShippingsService()->historyShippingsValidatingExpensesSinister();
		return new ViewModel(array("shippings" => $shippings));
	}
	public function historyexpensestripAction(){
		$role = $this->session->offsetGet('roleId');
		$user = $this->session->offsetGet('userId');
		$id_viaje = $this->params()->fromRoute("id");
		if($id_viaje){
			$expensesAll = $this->getExpensesTripService()->fetchAllExpensesTrip($id_viaje);
			$journey = $this->getExpensesTripService()->getJourney($id_viaje);
			if($journey['end_date'] != 0000-00-00 && $journey['status_saveExpenses'] != null){
				$data = array(
						"expensesAll" => $expensesAll,"journey" => $journey,'role'=>$role
				);
				return new ViewModel($data);
			}
		}
		return new ViewModel(array('role'=>$role));
	}
	
	
	public function deleteexpensetripAction(){
		$request = $this->getRequest();
		$response = $this->getResponse();
		if($request->isPost()){
			$id_expense = $request->getPost('id_expense');
			$id_viaje = $request->getPost('id_viaje');
			$deleteExpense = $this->getExpensesTripService()->deleteExpense($id_expense,$id_viaje);
			$response->setContent(\Zend\Json\Json::encode(array('response' => true, 'data' => $deleteExpense)));
			return $response;
		}
	}
	public function addstatusbonddiscountAction(){
		$request = $this->getRequest();
		$response = $this->getResponse();
		if($request->isPost()){
			$bonoDescuento = $request->getPost('bonoDescuento');
			$id_viaje = $request->getPost('id_viaje');
			$operator_id = $request->getPost('operator');
			$dateShipping = $request->getPost('dateShipping');
			$rendimiento = $request->getPost('rend');
			$cambio = $request->getPost('cambio');
			$initialDiferentExpenses = $request->getPost('initialDiferentExpenses');
			$deletingExpenses = $request->getPost('deletingExpenses');
			$statusBondDiscount = $this->getShippingsService()->addBondDiscountStatus($id_viaje,$bonoDescuento,$operator_id,$dateShipping,$rendimiento,$cambio,$initialDiferentExpenses,$deletingExpenses);
			$response->setContent(\Zend\Json\Json::encode(array('response' => "Ok", 'data' => $statusBondDiscount)));
			return $response;
		}
	}
	public function addtypegasolineAction(){
		$request = $this->getRequest();
		$response = $this->getResponse();
		if($request->isPost()){
			$id_viaje = $request->getPost('id_viaje');
			$id_gasoline = $request->getPost('gasolineSelectVal');
			$addGasType = $this->getShippingsService()->addTypeGasoline($id_gasoline,$id_viaje);
			$response->setContent(\Zend\Json\Json::encode(array('response' => true, 'data' => $addGasType)));
			return $response;
		}
	}
	public function addeditdatasshippingAction()
	{
		if ($this->getRequest()->isPost()){
			$data  = $this->getRequest()->getPost()->toArray();
				
			if(isset($data['consult'])){
				$rows = $this->getShippingsService()->getDatasById($data['id_shipping']);
				if($rows){
					$response = $this->getResponse()->setContent(\Zend\Json\Json::encode(array("response"=>"ok", "data"=>$rows)));
				}else{
					$response = $this->getResponse()->setContent(\Zend\Json\Json::encode(array("response"=>"fail", "data"=>$rows)));
				}
				return $response;
			}else{
				$journey= $this->getShippingsService()->updateShippingDates($data);
				if($journey){
					$response = $this->getResponse()->setContent(\Zend\Json\Json::encode(array("response"=>"ok", "data"=>$journey)));
				}else{
					$response = $this->getResponse()->setContent(\Zend\Json\Json::encode(array("response"=>"fail", "data"=>$journey)));
				}
				return $response;
			}
		}
		exit;
	}
	public function confirmvalidatingexpensesAction(){
		$request = $this->getRequest();
		$response = $this->getResponse();
		if($request->isPost()){
			$id_ship = $request->getPost('id_shipping');
			$type_destination = $request->getPost('type_destination');
			$journey = $this->getShippingsService()->updateConfirmExpenses($id_ship,$type_destination);
			$response->setContent(\Zend\Json\Json::encode(array('response' => "ok", 'data' => $journey)));
			return $response;
		}
	}
}