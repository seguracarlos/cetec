<?php

namespace Horus\Controller;

use Application_Service_Impl_BankServiceImpl;
use Application_Service_Impl_AccountManagerServiceImpl;
use Zend_Json;
use TypeAmountAccount;
use TypeAmountBank;
use Application_Util_Tools;
use Application_Form_Bank;

use Application\Controller\BaseController;

class BankController extends BaseController{

    public function init(){
    	BaseController::init();
    }

    public function indexAction(){
//     	$bankService = new Application_Service_Impl_BankServiceImpl();
//     	$serviceAccount = new Application_Service_Impl_AccountManagerServiceImpl();
    	
//     	$initialParams = $this->_getParam("ini");
//     	$movements = $this->_getParam("move");
//     	$form = $this->_getParam("form");
//     	$allB = $this->_getParam("allB");
    	
//     	if(isset($initialParams)){
//     		$this->mixParameters($initialParams);//invoco el monto inicial de todos los bancos
//     	}else if(isset($movements)){
//     		$this->mixParameters($movements);//invoco los movimientos de los bancos
//     	}else if(isset($form)){
//     		$this->mixParameters($form);//invoco un formulario para guardar el banco
//     	}else if(isset($allB)){
//     		$this->mixParameters($allB);
//     	}else{
// 		    $accountsBank = $bankService->getAllAccountsBank();//invoco todos los bancos (menos la cuenta mayor)
		   
// 		    if($accountsBank != null){
// 		    	$this->view->totalBanks = $accountsBank['totalAmount'];
// 		    	unset($accountsBank['totalAmount']);
// 		    	$this->view->banks = $accountsBank;
// 		   }else{
// 		   		$this->view->totalBanks = 0;
// 		    	$this->view->banks = 0;
// 		   }
//     	}
    }  
    public function addAction(){

    	
    	$this->_helper->viewRenderer->setNoRender();
    	
    	if ($this->getRequest()->isPost()) {
    		
    		$formData = $this->getRequest()->getPost();
    		
    		
    		print_r($formData);
    		
    		$type = $this->_getParam("t");
    		$addAcB = $this->_getParam("addAcB");
    		$ini = $this->_getParam("ini");
    		$set = $this->_getParam("set");
    		
    		
	    	if(isset($type)){
	    		$this->mixParametersAdd($type, $formData);//tipo 1 -> guarda transaccion del banco, tipo 2 -> guarda un banco
	    	}
// 	    	else if(isset($addAcB)){
// 	    		$this->mixParametersAdd($addAcB, $formData);//guardar transaccion en la ceunta de banco general
// 	    	}else if(isset($ini)){
// 	    		$this->mixParametersAdd($ini, $formData);//guarda el monto inicial de todas la cuentas de banco por id
// 	    	}else if(isset($set)){
// 	    		$this->mixParametersAdd($set, $formData);//hacer transaccion de banco a caja o banco a banco
// 	    	}
    	}
    	
//     	echo "xxxxxx fafaf";
    }
    
    public function editAction(){
    	if ($this->getRequest()->isPost()) {
    		$formData = $this->getRequest()->getPost();
    		$editTx = $this->_getParam('eddSc');
    		if(isset($editTx)){
    			$this->mixParametersEdit($editTx, $formData);
    		}else{
    			$this->mixParametersEdit("default", $formData);	
    		}
    	}
    	exit;
    } 
    public function deleteAction(){
    	if ($this->getRequest()->isPost()) {
    		$formData = $this->getRequest()->getPost();
    		$deletTranc = $this->_getParam('tD');
    		if(isset($deletTranc)){
    			$this->mixParametersDelete($deletTranc, $formData);
    		}else{
    			$this->mixParametersDelete("default", $formData);
    		}
    	}
    	exit;
    }
    private function mixParametersDelete($parameters, $formData){
    		$bank = new Application_Service_Impl_BankServiceImpl();
    		if($parameters == "dI"){
    			$id_tx = $this->_getParam("id_tx");
    			$bankResult = $bank->deleteBankTx($id_tx);
	    		if($bankResult == true){
	    			echo Zend_Json::encode(array("response" => "Transacci&oacute;n Eliminada"));
	    		}else{
	    			echo Zend_Json::encode(array("response" => "fail"));
	    		}
    		}else{
	    		$bankResult = $bank->deleteBank($formData['idb']);
	    		if($bankResult == true){
	    			echo Zend_Json::encode(array("response" => "ok"));
	    		}else{
	    			echo Zend_Json::encode(array("response" => "fail"));
	    		}
    		} 		
    }
    private function mixParametersEdit($parameters, $formData){
	    	$bank = new Application_Service_Impl_BankServiceImpl();
	    	$id_account = $this->_getParam("edCC");
	    	$editTx = $this->_getParam('eddSc');
	    	$formPost = $this->_getParam('form');
	    	parse_str($formPost, $output);
   			if($parameters == "e1"){
    			$id_tx = $this->_getParam('EsCc');
    			$type_amount = $this->_getParam("t");
    			$manual = $this->_getParam("m");
    			$difer = $this->_getParam('difer');
    			$idAccoun = $this->_getParam('idAccount');
    			$output['id_tx'] = $id_tx;
    			if($type_amount == TypeAmountAccount::ABONO){
    				$output['deposit'] = $output['amount'];
    			}else{
    				$output['withdraw'] = $output['amount'];
    			}
    			$output['type_amount'] = $type_amount;
    			unset($output['type']);
    			$txEdit = $bank->editTxByIdTx($output);
    			if($txEdit != null){
	    				echo Zend_Json::encode(array("response" => "ok", "data" => $txEdit));
    			}else{
    				echo Zend_Json::encode(array("response" => "fail"));
    			}
    			exit;
    		}else{
	    		$bankResult = $bank->editBank($formData);
	    		if($bankResult != null){
	    			echo Zend_Json::encode(array("response" => "ok", "data" => $bankResult));
	    		}else{
	    			echo Zend_Json::encode(array("response" => "fail"));
	    		}	
    		}
    } 
    private function mixParametersAdd($parameters, $formData){
    	
    	$bank = new Application_Service_Impl_BankServiceImpl();
   	 	$serviceAccount = new Application_Service_Impl_AccountManagerServiceImpl();
    	
//    	 	echo "aqcas";
   	 	
    	if($parameters == "ini"){
    		$id_bank = $this->_getParam("ba");
    		$amount = $this->_getParam("amount");
    		$type = $this->_getParam('type');
    		$sumTotalAmountNew = 0.0;
    		$currentBalanceOld = $bank->getCurrentBalanceByBank($id_bank);
    		if($currentBalanceOld != null){
    			$amountOldTotal = $currentBalanceOld[0]['amount']; //obtengo el saldo anterior de la cuenta	
    				//type -> 1 = abono, type -> 2 = descuento
    				if($type == "1"){
    					$sumTotalAmountNew = (double)$amountOldTotal + (double)$amount;
    				}else{
    					$sumTotalAmountNew = (double)$amountOldTotal - (double)$amount;
    				}
    				$currentBalance = array(
    					'id_bank' => $id_bank,
    					'amount' => $sumTotalAmountNew,
						'time' => date('Y-m-d H:i:s G'),
    				);
    			$bank->saveCurrentBalance($currentBalance);
    			echo Zend_Json::encode(array("response" => "ok", "amount" => $currentBalance['amount']));
    		}else{
    			echo Zend_Json::encode(array("response" => "fail"));
    		}
    	}else if(isset($parameters) && $parameters == "1"){
    			$id_subaccount = $this->_getParam('b');
    			$type = $this->_getParam("ty");
    			//1 - bono 2 - cargo
    			if($type == "1"){
    				$type_amount = TypeAmountBank::CARGO;
    			}else if($type == "2"){
    				$type_amount = TypeAmountBank::ABONO;
    			}
    			$formData['typeAmount'] = $type_amount;
    			$subaccount = $serviceAccount->saveSubAccount($id_subaccount, $formData, "1");   			
	    		if($subaccount != null){
	    			echo Zend_Json::encode(array("response" => "ok", "data" => $subaccount));
	    		}else{
	    			echo Zend_Json::encode(array("response" => "fail"));
	    		}
    	}else if(isset($parameters) && $parameters == "2"){
    			$saveBank = $bank->saveAccountBank($formData);
    			
	    		if($saveBank != null){
	    			if($saveBank == "AcountRepeat"){
	    				echo Zend_Json::encode(array("response" => "AcountRepeat"));
	    			}else{
	    				echo Zend_Json::encode(array("response" => "ok", "data" => $saveBank));
	    			}	
	    		}else{
	    			echo Zend_Json::encode(array("response" => "fail"));
	    		}	
    	}else if($parameters == "aAb"){
    			$gral = $this->_getParam('gral');
    			$type_amount = "";
    			if(isset($gral) && $gral == "1"){
    				$type = $this->_getParam('type');
	    			//1 - bono 2 - cargo
	    			if($type == "1"){
	    				$type_amount = TypeAmountBank::CARGO;
	    			}else if($type == "2"){
	    				$type_amount = TypeAmountBank::ABONO;
	    			}
    			    $formData['id_bank'] = TypeAmountBank::ACCOUNTBANKGENERAL;
    			    $formData['manual'] = "1";
    			    
    				$insertTx = $bank->saveTxBank($formData, $type_amount);
	    			if($insertTx != null){
		    			echo Zend_Json::encode(array("response" => "ok", "data" => $insertTx));
		    		}else{
		    			echo Zend_Json::encode(array("response" => "fail"));
		    		}
    			}else if(isset($gral) && $gral == "0"){
    				$type = $this->_getParam('type');
    				$id_bank = $this->_getParam('idB');
	    			//1 - bono 2 - cargo
	    			if($type == "1"){
	    				$type_amount = TypeAmountBank::CARGO;
	    			}else if($type == "2"){
	    				$type_amount = TypeAmountBank::ABONO;
	    			}
	    			$formData['id_bank'] = $id_bank;
	    			$formData['manual'] = "1";
	    			
    				$insertTx = $bank->saveTxBank($formData, $type_amount);
	    			if($insertTx != null){
		    			echo Zend_Json::encode(array("response" => "ok", "data" => $insertTx));
		    		}else{
		    			echo Zend_Json::encode(array("response" => "fail"));
		    		}
    			}
    	}else if($parameters == "tras"){
    		
    		if(isset($formData['tbb']) && $formData['tbb'] == "bb"){
    				$bankBankTrans = $bank->setTransactionBankBank($formData);
    				if($bankBankTrans != null){
    					echo Zend_Json::encode(array("response" => "ok"));
    				}else{
    					echo Zend_Json::encode(array("response" => "fail"));
    				}
    		}else{
    			$bankTrans = $bank->setTransactionBankCash($formData);
    			if($bankTrans != null){
    				echo Zend_Json::encode(array("response" => "ok"));
    			}else{
    				echo Zend_Json::encode(array("response" => "fail"));
    			}
    		}
    			
    	}
    	exit;
    }
    private function mixParameters($parameters){
    	$bankService = new Application_Service_Impl_BankServiceImpl();
    	if($parameters == "ini"){
    		$id_bank = $this->_getParam("iniB");
    		if(isset($id_bank)){
    			$initialAmount = $bankService->getCurrentBalanceByBank($id_bank);

	    		if($initialAmount != null){
	    			echo Zend_Json::encode(array("amount" => $initialAmount[0]['amount']));
	    		}else{
	    			echo Zend_Json::encode(array("response" => "fail"));
	    		}
    		}
    		exit;
    	}else if($parameters == "movb"){
    		//primer dia y ultimo dia del mes actual
    		$id_accountBank = $this->_getParam("b");
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
    		$bankMovement = $bankService->getAllMovementsByIdBank($startDate, $endDate, $id_accountBank);
    		if($bankMovement != null){
	    		echo Zend_Json::encode(array("response" => "ok", "data" => $bankMovement));
	    	}else{
	    		echo Zend_Json::encode(array("response" => "fail"));
	    	}
	    	exit;
    	}else if($parameters == "bF"){
    		$type = $this->_getParam("type");	
    		$formulario = new Application_Form_Bank($type);
    		$et = $this->_getParam('eT');
    		$id_tx = $this->_getParam('id_tx');  		
    		if(isset($id_tx)){
	    		$bankTx = $bankService->getBankTx($id_tx);	    		
	    		echo $this->view->form = $formulario->populate($bankTx[0]);
	    	}else if(isset($et) && $et == "true"){
	    		$idBank = $this->_getParam('iB');
	    		$bank = $bankService->getBankById($idBank);
	    		echo $this->view->form = $formulario->populate($bank[0]);
	    	}else{
	    		echo $this->view->form = $formulario;
	    	}
    		exit;
    	}else if($parameters == "bk"){
    		$accountsBank = $bankService->getAllAccountsBank();//invoco todos los bancos (menos la cuenta mayor)
    		if($accountsBank != null){
    			unset($accountsBank['totalAmount']);
    			echo Zend_Json::encode(array("response" => "ok", "data" => $accountsBank));
    		}else{
    			echo Zend_Json::encode(array("response" => "fail"));
    		}
    		exit;
    	}
    }
}