<?php


namespace Horus;

use BaseController;
use Application_Service_Impl_AccountManagerServiceImpl;
use Zend_Json;
use Application_Form_AccountmanagerForm;
use Application_Util_Tools;
use TypeAmountAccount;


include_once APPLICATION_PATH . '/controllers/BaseController.php';

class AccountmanagerController extends BaseController{

    public function init(){
       BaseController::init();
    }

    public function indexAction(){
    	$serviceAccount = new Application_Service_Impl_AccountManagerServiceImpl();
    	$getCat = $this->_getParam("gCat");
    	$getAcc = $this->_getParam("gAcc");
    	$gFor = $this->_getParam("gFor");
    	$id_category = $this->_getParam("category");
    	$movements = $this->_getParam("m");
    	$getAccountsClients = $this->_getParam("gAc");
    	$getActualCurrentBalance = $this->_getParam("l");   	
    	$getAccBi = $this->_getParam("gaccBi");
    	$getInitTotal = $this->_getParam("inT");
    	
    	if(isset($getInitTotal) && $getInitTotal == "iniTc"){
    		
    		$totalInAccounts = $serviceAccount->getTotalInAccounts();
    		if($totalInAccounts){
    			echo Zend_Json::encode(array("response" => "ok", "data" => $totalInAccounts));
    		}else{
    			echo Zend_Json::encode(array("response" => "fail"));
    		}
    		exit;
    	}else if(isset($getCat) && $getCat == "t1"){
    		$categorys = $serviceAccount->getAllCategory();
    		
    		if($categorys != null){
    			echo Zend_Json::encode(array("response" => "ok", "data" => $categorys));
    		}else{
    			echo Zend_Json::encode(array("response" => "fail"));
    		}
    		exit;
    	}else if(isset($gFor)){
    		
    		if($gFor == "t2"){
    			$formulario = new Application_Form_AccountmanagerForm(1);
    		}else if($gFor == "t3"){
    			$formulario = new Application_Form_AccountmanagerForm(2);
    		}else if($gFor == "t4"){
    			$id_account = $this->_getParam('idAc');
    			$formulario = new Application_Form_AccountmanagerForm(1);
    			
    			$account = $serviceAccount->getAccountById($id_account);
    			$formulario->populate($account[0]);
    			$this->view->formulario = $formulario;
    		}else if($gFor == "t5"){
    			$formulario = new Application_Form_AccountmanagerForm(3);
    			$actionType = $this->_getParam("tA");
    			$id_tx = $this->_getParam('id_tx');
    			
    			if($actionType == "e"){
    				$tranx = $serviceAccount->getTransactionById($id_tx);
    				$formulario->populate($tranx[0]);	
    			}
    		}else if($gFor == "t6"){
    			$formulario = new Application_Form_AccountmanagerForm(2);
    			$idCategory = $this->_getParam("idC");
    			
    			$category = $serviceAccount->getCategoryById($idCategory);
   			
    			$formulario->populate($category[0]);
    		}
    		
    		echo $this->view->formulario = $formulario;
    		exit;
    	}else if(isset($getAcc) && $getAcc == "t3"){
    		
    		$accounts = $serviceAccount->getAllAccountsByCategory($id_category);
    		
    		if($accounts != null){
    			echo Zend_Json::encode(array("response" => "ok", "data" => $accounts));
    		}else{
    			echo Zend_Json::encode(array("response" => "fail"));
    		}
    		exit;
    	}else if(isset($movements) && $movements == "t1"){
    		$id_account = $this->_getParam("c");
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
    	
    		$movements = $serviceAccount->getAllmovementsByAccount($startDate, $endDate, $id_account);
    		
    		if($movements != null){
    			echo Zend_Json::encode(array("response" => "ok", "data" => $movements));
    		}else{
    			echo Zend_Json::encode(array("response" => "fail"));
    		}
    		exit;
    	}else if(isset($getAccountsClients) && $getAccountsClients = "gac1"){
    		$accountsClients = $serviceAccount->getAllAccountsClients();
    		if($accountsClients != null){
    			echo Zend_Json::encode(array("response" => "ok", "data" => $accountsClients));
    		}else{
    			echo Zend_Json::encode(array("response" => "fail"));
    		}
    		
    		exit;
    	}else if(isset($getActualCurrentBalance) && $getActualCurrentBalance == "1"){
    		$id_subaccount = $this->_getParam("a");
    		$currentBalance = $serviceAccount->getCurrentBalanceBySubaccount($id_subaccount);
    		
    		if($currentBalance != null){
    			echo Zend_Json::encode(array("amount" => $currentBalance[0]['amount']));
    		}else{
    			echo Zend_Json::encode(array("amount" => 0.00));
    		}
    		
    		exit;
    	}else if(isset($getAccBi) && $getAccBi == "acB1"){
    		$id_account = $this->_getParam("id_acc");
    		$account = $serviceAccount->getCurrentBalanceBySubaccount($id_account);
    		if($account != null){
    			echo Zend_Json::encode(array("response" => "ok", "data" => $account[0]));
    		}else{
    			echo Zend_Json::encode(array("response" => "fail"));
    		}
    		exit;
    	}
    	
    }
    
    public function addSubAccountAction() {
    	$serviceAccount = new Application_Service_Impl_AccountManagerServiceImpl();
    	
    	$this->_helper->viewRenderer->setNoRender();
    	
//     	if ($this->getRequest()->isPost()) {
    		$formData = $this->getRequest()->getPost();
    		$data = array(
    				"parent" => 100,
    				"subaccount" => 2,
    				"name" => "demo jjesus",
    				"description" => "demo de",
    		);
    		$id_subaccount=100;
    		$subaccount = $serviceAccount->saveSubAccount($id_subaccount, $data);
    		 
    		if($subaccount != null){
    			echo Zend_Json::encode(array("response" => "ok", "data" => $subaccount));
    		}else{
    			echo Zend_Json::encode(array("response" => "fail"));
    		}
    		exit;
//     	}
    	
    }

    public function addSubAuxAccount() {
    	$serviceAccount = new Application_Service_Impl_AccountManagerServiceImpl();
    	 
    }    
    
    public function addAction(){
    	$serviceAccount = new Application_Service_Impl_AccountManagerServiceImpl();
    	
    	if ($this->getRequest()->isPost()) {
    		$addAccount = $this->_getParam("adCC");
    		$addCategory = $this->_getParam("addC");
    		$addSubAccount = $this->_getParam("addSc");
 
    		$initialAccount = $this->_getParam("i");
    		
    		$formData = $this->getRequest()->getPost();
    		
    		if(isset($initialAccount) && $initialAccount == "1"){
    			$id_subaccount = $this->_getParam('sa');
    			$amount = $this->_getParam('amount');
    			$type = $this->_getParam('type');
    			
    			$initial = array();
    			$sumTotalAmountNew = 0.0;
    		
    			$currentBalanceOld = $serviceAccount->getCurrentBalanceBySubaccount($id_subaccount);
    			
    			if($currentBalanceOld != null){
    				$amountOldTotal = $currentBalanceOld[0]['amount']; //obtengo el saldo anterior de la cuenta	
    				
    				//type -> 1 = abono, type -> 2 = descuento
    				if($type == "1"){
    					$sumTotalAmountNew = (double)$amountOldTotal + (double)$amount;
    				}else{
    					$sumTotalAmountNew = (double)$amountOldTotal - (double)$amount;
    				}
    				
    				$currentBalance = array(
	    				'id_account' => $id_subaccount,
	    				'amount' => $sumTotalAmountNew
    				);

    			
    				$initial = $serviceAccount->updateCurrentBalanceBySubAccount($currentBalance);
    			}else{
    				$currentBalance = array(
	    				'id_account' => $id_subaccount,
	    				'amount' => $amount
    				);
    				$initial = $serviceAccount->saveCurrentBalanceBySubAccount($currentBalance);
    			}

    			echo Zend_Json::encode(array("amount" => $initial['amount']));
    			exit;
    			
    		}else if(isset($addAccount) && $addAccount == "a1"){
    			   			
    			$saveAccount = $serviceAccount->saveAccount($formData);
    			
    			if($saveAccount != null){
    				echo Zend_Json::encode(array("response" => "ok", "data" => $saveAccount));
    			}else{
    				echo Zend_Json::encode(array("response" => "fail"));
    			}
    			exit;
    		}else if(isset($addCategory) && $addCategory == "c1"){
    			
    			$saveCategory = $serviceAccount->saveCategory($formData);
    			
				if($saveCategory != null){
					echo Zend_Json::encode(array("response" => "ok", "data" => $saveCategory));
				}else{
					echo Zend_Json::encode(array("response" => "fail"));
				}
				exit;
    		}else if(isset($addCategory) && $addCategory == "c2"){
    			
    			$idCategory = $this->_getParam("iC");
    			$editCategory = $serviceAccount->editCategory($formData, $idCategory);
    			
    			if($editCategory != null){
					echo Zend_Json::encode(array("response" => "ok", "data" => $editCategory));
				}else{
					echo Zend_Json::encode(array("response" => "fail"));
				}
				exit;
    		}else if(isset($addSubAccount)){
    			$id_subaccount = $this->_getParam("AsCc");
    			
    			if($addSubAccount == "s1"){
    				$formData['typeAmount'] = TypeAmountAccount::ABONO;
    			}else{
    				$formData['typeAmount'] = TypeAmountAccount::CARGO;
    			}
    			
    			$subaccount = $serviceAccount->saveSubAccountold($id_subaccount, $formData, "1");
    			
    			$currentBalance = array(
    					'id_account' =>  $id_subaccount,
    					'amount' => Application_Util_Tools::notCurrencyOutPoint($formData['amount'])
    			);
    			$currentAcount = $serviceAccount->saveCurrentBalanceBySubAccount($currentBalance, $formData['typeAmount']);//guarda el total de la cuenta
    						
    			if($subaccount != null){
    				echo Zend_Json::encode(array("response" => "ok", "data" => $subaccount));
    			}else{
    				echo Zend_Json::encode(array("response" => "fail"));
    			}
    			exit;
    		}
    	}	
    }
    
    public function editAction(){
    	$serviceAccount = new Application_Service_Impl_AccountManagerServiceImpl();
    	
    	if ($this->getRequest()->isPost()) {    		
    		$formData = $this->getRequest()->getPost();
    		$id_account = $this->_getParam("edCC");
    		$editTx = $this->_getParam('eddSc');
    		$formPost = $this->_getParam('form');
    		parse_str($formPost, $output);
    		
    		if(isset($editTx) && $editTx == "e1"){
    			$id_tx = $this->_getParam('EsCc');
    			$type_amount = $this->_getParam("t");
    			$manual = $this->_getParam("m");
    			$difer = $this->_getParam('difer');
    			$idAccoun = $this->_getParam('idAccoun');
    			
    			$output['id_tx'] = $id_tx;
    			
    			if($type_amount == TypeAmountAccount::ABONO){
    				$output['deposit'] = $output['amount'];
    			}else{
    				$output['withdraw'] = $output['amount'];
    			}
    			
    			    			
    			$txEdit = $serviceAccount->editTxByIdTx($output);
    			
    			if($txEdit != null){
	    			echo Zend_Json::encode(array("response" => "ok", "data" => $txEdit));
    			}else{
    				echo Zend_Json::encode(array("response" => "fail"));
    			}
    			
    			exit;
    		}else{
	    		$account = $serviceAccount->editAccount($formData, $id_account);
	    		$account['id_account'] = $id_account;
	    		
	    		if($account != null){
	    			echo Zend_Json::encode(array("response" => "ok", "data" => $account));
	    		}else{
	    			echo Zend_Json::encode(array("response" => "fail"));
	    		}
    		}
    		
    	}
    	exit;
    }
    
    public function deleteAction(){
    	$deleteParam = $this->_getParam("erase");
    	$serviceAccount = new Application_Service_Impl_AccountManagerServiceImpl();
    	$delTx = $this->_getParam("tD");
    	$delCat = $this->_getParam("tC");
    	
    	if ($this->getRequest()->isPost()) {    		
    		$formData = $this->getRequest()->getPost();
    		
    		if(isset($delTx) && $delTx == "dI"){
	    		$id_tx = $this->_getParam("id_tx");
	    		$serviceAccount->deleteTransactionById($id_tx);
	    		echo Zend_Json::encode(array("response" => "Transacci&oacute;n eliminada correctamente"));
	    		exit;
    		}else if(isset($delCat) && $delCat == "dC"){
	    		$id_cat = $this->_getParam("idCat");
	    		
	    		$delete = $serviceAccount->deleteCategory($id_cat);
	    		if($delete){
	    			echo Zend_Json::encode(array("response" => "ok", "data" => "Transacci&oacute;n eliminada correctamente"));
	    		}else{
	    			echo Zend_Json::encode(array("response" => "block", "data" => "No se puede eliminar la cuenta raiz, es necesaria para Horus"));	
	    		}
	    		exit;
	    		
    		}else{
	    		$serviceAccount->deleteAccount($deleteParam);
    		}
    	}
    	exit;
    }

}

