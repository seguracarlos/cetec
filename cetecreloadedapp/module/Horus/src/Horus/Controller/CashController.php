<?php


namespace Horus\Controller;

use Zend\View\Model\ViewModel;
use Iofractal\Controller\BaseController;
use Horus\Services\CashServices;
/*use Application_Service_Impl_CashServiceImpl;
use Application_Form_Cash;
use Zend_Json;
use Application_Util_Tools;
use TypeAmountBank;
use TypeAmountAccount;*/


//include_once APPLICATION_PATH . '/controllers/BaseController.php';

class CashController extends BaseController
{
	private $cash_services;
	
	public function __construct()
	{
	}
	
	/*Instanciamos el servicio de caja*/
	protected function getCashServices()
	{
		return $this->cash_services = new CashServices();
	}
	
	 public function indexAction()
	 {
	 	$cash = $this->getCashServices()->fetchAll();
	 	$view = array("cash" => $cash);
	 	//echo "<pre>"; print_r($cash); exit;
	 	return new ViewModel($view);
    	/*$cashService = new Application_Service_Impl_CashServiceImpl();
    	
    	$initialParams = $this->_getParam("ini");
    	$formParams = $this->_getParam('form');
    	$movements = $this->_getParam("move");
    	$showAllCash = $this->_getParam("allC");
    	$allCashShow = $this->_getParam("ACs");
    	
    	if(isset($initialParams)){
    		$this->mixParams($initialParams);
    	}else if(isset($formParams)){
    		$this->mixParams($formParams);
    	}else if(isset($movements)){
    		$this->mixParams($movements);
    	}else if(isset($showAllCash)){
    		$this->mixParams($showAllCash);
    	}else if(isset($allCashShow)){
    		$this->mixParams($allCashShow);
    	}else{
    		//cuentas de caja
    		$accountsCash = $cashService->getAllAccountsCash();

    		if($accountsCash != null){
    			$this->view->totalCash = $accountsCash['totalAmount'];
    			unset($accountsCash['totalAmount']);
    			$this->view->cash = $accountsCash;
    		}else{
    			$this->view->cash = 0;
    		}
    	}*/
    }
    
    public function addAction()
    {
    	$view = new ViewModel();
    	$view->setTerminal(true);
    	return $view;
    	/*$type = $this->_getParam("t");
    	$typei = $this->_getParam("i");
    	$ini = $this->_getParam('ini');
    	$set = $this->_getParam('set');

        if ($this->getRequest()->isPost()) {
    		$formData = $this->getRequest()->getPost();
    		
    		if(isset($ini)){
    			$this->mixParamsAdd($ini, $formData);
    		}else if(isset($type)){
    			$this->mixParamsAdd($type, $formData);
    		}else if(isset($set)){
    			$this->mixParamsAdd($set, $formData);
    		}
    		
    	 }
    	
    	exit;*/
    }
    
    public function updateAction(){
    	$cash = new Application_Service_Impl_CashServiceImpl();
    	//{form: formData, eddSc: "e1", EsCc: id_tx, t: type_amount, m: manual, difer: difer, idAccoun: id_account}
    	
        if ($this->getRequest()->isPost()) {
    		$formData = $this->getRequest()->getPost();
        	$eddSc = $this->_getParam("eddSc");
        	
        	if(isset($eddSc)){
        		$this->mixParamsEdit($eddSc, $formData);
        	}else{
    			$this->mixParamsEdit("default", $formData);
    		}
    		exit;
        }
    }
    
    public function deleteAction(){
    	if ($this->getRequest()->isPost()) {
    		$cash = new Application_Service_Impl_CashServiceImpl();
    		$formData = $this->getRequest()->getPost();
			$deleteTx = $this->_getParam("tD");
    		
    		if(isset($deleteTx)){
    			$this->mixParamsDelete($deleteTx, $formData);
    		}else{
    			$this->mixParamsDelete("default", $formData);
    		}
    		
    	}
    	exit;
    }
    
    
    public function mixParams($params){
    	$cashService = new Application_Service_Impl_CashServiceImpl();
    	
    	if($params == "cF"){
    		$type = $this->_getParam('type');
    		$formualrio = new Application_Form_Cash($type);
    		
    		$editCash = $this->_getParam("eT");
    		$editTrans = $this->_getParam("tA");
    		
    		if(isset($editCash) && $editCash == "true"){
    			$iC = $this->_getParam("iC");
    			
    			$cash = $cashService->getCashById($iC);
    			$formualrio->populate($cash[0]);
    			echo $this->view->formulario = $formualrio;
    		}else if(isset($editTrans) && $editTrans == "true"){
    			$id_tx = $this->_getParam('id_tx');
    			
    			$cashTx = $cashService->getTransactionById($id_tx);
    			$formualrio->populate($cashTx[0]);
    			echo $this->view->formulario = $formualrio;  			
    		}else{
    			echo $this->view->formulario = $formualrio;
    		}
    		exit;
    	}else if($params == "ini"){
    		$cash_id = $this->_getParam("iniC");
    		
    		$initialBalnace = $cashService->getCurrentBalanceByCash($cash_id);
    		if($initialBalnace != null){
	    		echo Zend_Json::encode(array("amount" => $initialBalnace[0]['amount']));
	    	}else{
	    		echo Zend_Json::encode(array("response" => "fail"));
	    	}
    		exit;
    	}else if($params == "ck"){
    		$accountsCash = $cashService->getAllAccountsCash();
    		unset($accountsCash['totalAmount']);
    		if($accountsCash != null){
    			echo Zend_Json::encode(array("response" => "ok", "data" => $accountsCash));
    		}else{
    			echo Zend_Json::encode(array("response" => "fail"));
    		}
    		exit;
    	}else if($params == "all"){
    		$id_cash = $this->_getParam("c");
    		$iD = $this->_getParam('iD');
    		$fD = $this->_getParam('fD');
    		
    		$startDate = "";
    		$endDate = "";
    		
    		if(isset($iD) && $iD != "" && isset($fD) && $fD != ""){
    			$startDate = $iD;
    			$endDate = $fD;
    		}else{
    			$startDate = Application_Util_Tools::getFirstDayOfMonth();
    			$endDate = Application_Util_Tools::getLastDayOfMonth();
    		} 
    		
    		$movements = $cashService->getAllMovementsByIdCash($startDate, $endDate, $id_cash);
    		if($movements != null){
	    		echo Zend_Json::encode(array("response" => "ok", "data" => $movements));
	    	}else{
	    		echo Zend_Json::encode(array("response" => "fail"));
	    	}
	    	exit;
    	}else if($params == "allCash"){
    		$allCash = $cashService->getAllAccountsCash();
    		if($allCash != null){
    			unset($allCash['totalAmount']);
    			echo Zend_Json::encode(array("response" => "ok", "data" => $allCash));
    		}else{
    			echo Zend_Json::encode(array("response" => "fail"));
    		}
    		exit;
    	}
    	
    }
    
    public function mixParamsAdd($params, $formData){
    	$cashService = new Application_Service_Impl_CashServiceImpl();
    	
   		 if(isset($params) && $params == "ini"){
    		$id_cash = $this->_getParam("ca");
    		$amount = $this->_getParam("amount");
    		$type = $this->_getParam('type');
    		
    		$sumTotalAmountNew = 0.0;
    		
    		$currentBalanceOld = $cashService->getCurrentBalanceByCash($id_cash);
    		
    		if($currentBalanceOld != null){
    			$amountOldTotal = $currentBalanceOld[0]['amount']; //obtengo el saldo anterior de la cuenta	
    				//type -> 1 = abono, type -> 2 = descuento
    				if($type == "1"){
    					$sumTotalAmountNew = (double)$amountOldTotal + (double)$amount;
    				}else{
    					$sumTotalAmountNew = (double)$amountOldTotal - (double)$amount;
    				}
    				$currentBalance = array(
							'id_cash' => $formData['ca'],
							'amount' => $sumTotalAmountNew,
							'time' => date('Y-m-d H:i:s G'),//fecha del dia actual
					);
    				
    			$cashService->saveCurrentBalance($currentBalance);
    			echo Zend_Json::encode(array("response" => "ok", "amount" => $currentBalance['amount']));
    			
    		}else{
    			echo Zend_Json::encode(array("response" => "fail"));
    			 
    		}
    		exit;
    	}else if(isset($params) && $params == "1"){
    		$id_cash = $this->_getParam('c');
    		$type = $this->_getParam("ty");
    		//1 - bono 2 - cargo
    		if($type == "1"){
    			$type_amount = TypeAmountBank::CARGO;
    		}else if($type == "2"){
    			$type_amount = TypeAmountBank::ABONO;
    		}
    		$formData['id_cash'] = $id_cash;
    		$formData['typeAmount'] = $type_amount;
    		$formData['manual'] = "1";
    		
    		$cashTxSave = $cashService->saveTxCash($formData, $type_amount);
    		 
    		if($cashTxSave != null){
		    	echo Zend_Json::encode(array("response" => "ok", "data" => $cashTxSave));
		    }else{
		    	echo Zend_Json::encode(array("response" => "fail"));
		    }
    		
    	}else if(isset($params) && $params == "2"){
    		$cash = $cashService->saveAccountCash($formData);
    		
    		if($cash != null){
    			echo Zend_Json::encode(array("response" => "ok", "data" => $cash));
    		}else{
    			echo Zend_Json::encode(array("response" => "fail"));
    		}
    	}else if($params == "tras"){
    		
    		$transCash = $cashService->setTransactionCashBank($formData);
    		if($transCash != null){
    			echo Zend_Json::encode(array("response" => "ok"));
    		}else{
    			echo Zend_Json::encode(array("response" => "fail"));
    		}
    		
    	}
    	
    	exit;
    }
    
    public function mixParamsEdit($param, $formData){
    	$cashService = new Application_Service_Impl_CashServiceImpl();
    	
   			if($param == "e1"){
   				$formPost = $this->_getParam('form');
   				parse_str($formPost, $output);
   				
    			$id_tx = $this->_getParam('EsCc');
    			$type_amount = $this->_getParam("t");
    			$manual = $this->_getParam("m");
    			$difer = $this->_getParam('difer');
    			$idCash = $this->_getParam('idAccount');
    			
    			$output['id_tx'] = $id_tx;
    			if($type_amount == TypeAmountAccount::ABONO){
    				$output['deposit'] = $output['amount'];
    			}else{
    				$output['withdraw'] = $output['amount'];
    			}
    			$output['type_amount'] = $type_amount;
    			unset($output['type']);
    			
    			$txEdit = $cashService->editTxByIdTx($output);
    			if($txEdit != null){
	    				echo Zend_Json::encode(array("response" => "ok", "data" => $txEdit));
    			}else{
    				echo Zend_Json::encode(array("response" => "fail"));
    			}
    			exit;
    			
    		}else{
		    	$cashResult = $cashService->editCash($formData); 
		    	if($cashResult != null){
		    		echo Zend_Json::encode(array("response" => "ok", "data" => $cashResult));
		    	}else{
		    		echo Zend_Json::encode(array("response" => "fail"));
		    	}
    	}
    }
    
    public function mixParamsDelete($params, $formData){
    	$cashService = new Application_Service_Impl_CashServiceImpl();
    	
    	if(isset($params) && $params == "dI"){
    		$id_tx = $this->_getParam("id_tx");
    		
    		$deleteTx = $cashService->deleteTxCash($id_tx);
    		if($deleteTx){
    			echo Zend_Json::encode(array("response" => "Transacción Eliminada"));
    		}else{
    			echo Zend_Json::encode(array("response" => "Error al eliminar transacción, intentalo de nuevo"));
    		}
    	}else{
    		$cashResult = $cashService->deleteCash($formData['idc']);
    		if($cashResult == true){
    			echo Zend_Json::encode(array("response" => "ok"));
    		}else{
    			echo Zend_Json::encode(array("response" => "fail"));
    		}
    	}	
    	exit;
    }
}
