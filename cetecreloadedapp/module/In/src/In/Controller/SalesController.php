<?php


namespace In;

use BaseController;
use Application_Form_SalesForm;
use Application_Service_Impl_SalesServiceImpl;
use Zend_Json;
use Application_Service_Impl_PreferencesServiceImpl;
use Application_Entity_SalesEntity;
use Application_Service_Impl_BankServiceImpl;
use Application_Service_Impl_AccountManagerServiceImpl;
use Preferences;
use Application_Util_Tools;
use Application_Service_Impl_CashServiceImpl;
use TypeAmountBank;
use TypeAmountAccount;
use Application_Service_Impl_StockServiceImpl;
use Application_Service_Impl_FinishedProductServiceImpl;
use Zend_Session_Namespace;
use zend_Json;


include APPLICATION_PATH . '/controllers/BaseController.php';
include_once APPLICATION_PATH . '/services/impl/PreferencesServiceImpl.php';
include_once APPLICATION_PATH . '/util/StaticTools.php';
include_once APPLICATION_PATH . '/Constants/Constants.php';

class SalesController extends BaseController{
	

	function init(){
		BaseController::init();
	}
	
	function createAction(){
		if ($this->getRequest()->isPost()) {
		$idSale = $this->_getParam('id');
		
		$formSales = new Application_Form_SalesForm();
		$salesService = new Application_Service_Impl_SalesServiceImpl();
		
			if(isset($idSale)){
					
				$idProducts = $salesService->getSalesProductsByIdSale($idSale);
								
				if($idProducts == null){
					echo Zend_Json::encode(array("response" => "fail", "data" => "No Existe el Producto o ya no hay en existencias."));
					exit;
				}else{
					echo Zend_Json::encode(array("response" => "ok", "data" => $idProducts));
					exit;
				}
			}
	   }
	}
	
	function indexAction(){
		$formSales = new Application_Form_SalesForm();
		$salesService = new Application_Service_Impl_SalesServiceImpl();
		$idSales = $this->_getParam('iS');
		
		if(isset($idSales)){
			$invoices = $salesService->getSalesByIdSales($idSales);
		}else{
			$invoices = $salesService->getAll();
		}
		$saleRowC = array();
		$saleTable = array();
		
		foreach($invoices as $key => $sale){
			$saleRowC['id_sale'] = $sale['id_sale'];
			$saleRowC['nameClient'] = $sale['nameClient'];
			$saleRowC['date'] =  $sale['date'];
			$saleRowC['subtotal'] = $sale['subtotal'];
			$saleRowC['tax'] = $sale['tax']; // . $sale->nPay . " / " . $sale->numberofpayments . " del proyecto " . $sale->name_project;
			$saleRowC['total'] = $sale['total'];
			$saleTable[] = $saleRowC;
		}
		
		$this->view->salesForm = $formSales;
		$this->view->sales = $saleTable;
	}
	
	function addAction(){
		$formSales = new Application_Form_SalesForm();
		$preferencesService =  new Application_Service_Impl_PreferencesServiceImpl();
		$salesEntity = new Application_Entity_SalesEntity();
		$service = new Application_Service_Impl_SalesServiceImpl();
		$bankService = new Application_Service_Impl_BankServiceImpl();
		$serviceAccount = new Application_Service_Impl_AccountManagerServiceImpl();
		
		$preference = $preferencesService->getPreferencesById(Preferences::CODE_IVA);
		
		 if ($this->getRequest()->isPost()) {
        	   	$formData = $this->getRequest()->getPost();
        		
        		$txActive = 0;
        		$idSales = null;
        		
        		$tabActive = $this->_getParam("tAc");
        		$idsProds = $this->_getParam('idsProds');
        		
        		
        		$totalNoPoint = Application_Util_Tools::notCurrencyOutPoint($total = $this->_getParam('total'));
        		
        		$salesEntity->setDate(Date("Y-m-d"));
        		$salesEntity->setNameClient($this->_getParam('nameclient'));
        		$salesEntity->setSubtotal(Application_Util_Tools::notCurrencyOutPoint($this->_getParam('subtotal')));
        		$salesEntity->setTax(Application_Util_Tools::notCurrencyOutPoint($this->_getParam('tax')));
        		$salesEntity->setTotal($totalNoPoint);
        		
        		/************** metodos de pago ****************/
        		$autorization = $this->_getParam('autorization');
        		$last4numbertc = $this->_getParam('last4NumberTc');
        		$checkSheet = $this->_getParam('checkSheet');
        		$collectionDate = $this->_getParam('collectionDate');
        		$accountClient = $this->_getParam('accountClient');
        		
        		$salesEntity->setCash(Application_Util_Tools::notCurrencyOutPoint($this->_getParam('cash')));
        		$salesEntity->setAutorization($autorization);
        		$salesEntity->setLast4number($last4numbertc);
        		$salesEntity->setCheckSheet($checkSheet);
        		$salesEntity->setCollectionDate($collectionDate);
        		$salesEntity->setIdCountClient($accountClient);
        		
        		$salesEntity->setItemsSale($formData['salesTable']);
        		        		
        		$idSales = $service->addSales($salesEntity);
        		
        		$idSubAccount = "";
        		$data = array();
        		
        		if($idSales != null){
        			if($tabActive == 1){
        		
        				$cash = new Application_Service_Impl_CashServiceImpl();
        				$type_amount = TypeAmountBank::CARGO;
        				$descriptionC = "Venta " . $idSales . " pagada en efectivo el dia " . Date("Y-m-d");
        				$idSubAccount = "301";
        					
        				$link = 'javascript:location.href=BASE_URL + "/sales/index?iS='.$idSales.'";';
        				$descriptionA = "<a href='$link'>Venta $idSales</a> pagada en efectivo el dia " . Date("Y-m-d");
        		
        				$data = array(
        						"amount" => $totalNoPoint,
        						"description" => $descriptionA,
        						"typeAmount" => $type_amount
        				);
        		
        				$movement = array(
        						'id_cash' => "1", //id caja de ventas en efectivo
        						'deposit' => $totalNoPoint,
        						'withdraw' => 0,
        						'amount' => $totalNoPoint,
        						'date' => date('Y-m-d'),
        						'time' => date('H:i:s'),
        						'description' => $descriptionC,
        						'type_amount' => $type_amount,
        						'manual' => "0"
        				);
        		
        				$saveCash = $cash->saveTxCash($movement, $type_amount);
        				if($saveCash != null){
        					$txActive = 1;
        				}
        			}else if($tabActive == 2){
        				$idSubAccount = "301";
        		
        				$link = 'javascript:location.href=BASE_URL + "/sales/index?iS='.$idSales.'";';
        				$description = "<a href='$link'>Venta $idSales</a> realizada con Tarjeta de credito. Autorizacion: " . $autorization . " Ultimos 4 digitos " . $last4numbertc;
        		
        				$data = array(
        						"amount" => $totalNoPoint,
        						"description" => $description,
        						"typeAmount" => TypeAmountAccount::ABONO
        				);
        					
        			}else if($tabActive == 3){
        		
        				$idSubAccount = "701";
        		
        				$link = 'javascript:location.href=BASE_URL + "/sales/index?iS='.$idSales.'";';
        				$description = "<a href='$link'>Venta $idSales</a> pagada con cheque, el dia ". $collectionDate ." con folio  " . $checkSheet;
        		
        				$data = array(
        						"amount" => $totalNoPoint,
        						"description" => $description,
        						"typeAmount" => TypeAmountAccount::ABONO
        				);
        		
        			}else if($tabActive == 4){
        		
        				$idSubAccount = $this->_getParam('accountClient');
        		
        				$link = 'javascript:location.href=BASE_URL + "/sales/index?iS='.$idSales.'";';
        				$description = "<a href='$link'>Venta $idSales</a> con cargo a la cuenta " . $accountClient;
        		
        				$data = array(
        						"amount" => $totalNoPoint,
        						"description" => $description,
        						"typeAmount" => TypeAmountAccount::CARGO
        				);
        		
        			}
        			 
        			if($data != null && $idSubAccount != ""){
        				$accountInsert = $serviceAccount->saveSubAccount($idSubAccount, $data, "0");
        				$initial = array();
        				$sumTotalAmountNew = 0.0;
        				 
        				$currentBalanceOld = $serviceAccount->getCurrentBalanceBySubaccount($idSubAccount);
        				 
        				if($currentBalanceOld != null){
        					$amountOldTotal = $currentBalanceOld[0]['amount']; //obtengo el saldo anterior de la cuenta
        		
        					$sumTotalAmountNew = (double)$amountOldTotal + (double)$totalNoPoint;
        		
        					$currentBalance = array(
        							'id_account' => $idSubAccount,
        							'amount' => $sumTotalAmountNew
        					);
        		
        					 
        					$initial = $serviceAccount->updateCurrentBalanceBySubAccount($currentBalance);
        		
        				}else{
        					$currentBalance = array(
        							'id_account' => $idSubAccount,
        							'amount' => $totalNoPoint
        					);
        					$initial = $serviceAccount->saveCurrentBalanceBySubAccount($currentBalance);
        				}
        					
        				if($accountInsert != null){
        					$txActive = 1;
        				}
        			}else{
        				echo Zend_Json::encode(array("response" => "fail", "data" => "Fallo al registrar la venta, intentelo de nuevo."));
        				exit;
        			}
        		
        		}else{
        			echo Zend_Json::encode(array("response" => "fail", "data" => "Fallo al registrar la venta, intentelo de nuevo."));
        			exit;
        		}
        		
        		if($txActive == 1){
        			$ids = explode("," ,trim($formData['id_prods'] , ","));
        		
        			foreach ($ids as $id_prod){
        				$this->updateStockMerchandize($id_prod);
        			}
        			
        			echo Zend_Json::encode(array("response" => "ok", "data" => "Venta hecha correctamente."));
        		}else{
        			echo Zend_Json::encode(array("response" => "fail", "data" => "Fallo al registrar la venta, intentelo de nuevo."));
        		}
        		 
        		exit;

		 }
		
		$this->view->tax = $preference[0]->getValue();
		$this->view->salesForm = $formSales;
	}
	
	function updateStockMerchandize($id_prod){
		$servStock = new Application_Service_Impl_StockServiceImpl();
		
		$stock = $servStock->getStockMerchindizeByProductId($id_prod);
		$stokNew = $stock['stock'] - 1;
		
		$servStock->updateStockQuantity($stokNew, $id_prod);
	}
	
	function searchproductAction(){
		$article = $this->_getParam('article');
		$name_startsWith = $this->_getParam('term');
		$carrshop = $this->_getParam('carrshop');
		
		$productslist = new Application_Service_Impl_FinishedProductServiceImpl();
		$serviceSales = new Application_Service_Impl_SalesServiceImpl();
		$idsProdSession = new Zend_Session_Namespace('idsProdSession');
		
		if(isset($article)){
			$article = $productslist->getFinishedProductsByKey($article);
				
			if($article == null){
				echo Zend_Json::encode(array("response" => "fail", "data" => "No Existe el Producto o ya no hay en existencias."));
			}else{
				echo Zend_Json::encode(array("response" => "ok", "data" => $article));
			}
		}else if(isset($carrshop) && $carrshop == "2"){
			
			if(isset($idsProdSession->ids)){

				$id_products = $idsProdSession->ids;
				$products = explode("," ,trim($id_products, ","));	
				$listProd = array();
				
				foreach ($products as $product){
					$box = $productslist->getFinishedProductById($product);
					$listProd[] = $box;
				}
			
				echo zend_Json::encode($listProd);
				exit;
			}

		 }elseif (isset($name_startsWith)){
			$found = $productslist->getProductsStartWith($name_startsWith);
			echo json_encode($found);
			exit;
		}
		
		exit;
	}
}