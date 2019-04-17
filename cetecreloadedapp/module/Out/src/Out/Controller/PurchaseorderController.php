<?php 

namespace Out;

use BaseController;
use Application_Service_Impl_PurchaseOrderServiceImpl;
use Zend_Json;
use Privilege;
use Application_Form_PurchaseOrderForm;
use Application_Entity_PurchaseOrderEntity;
use Application_Entity_PurchaseEntity;
use Application_Entity_DatePaymentOrder;
use Application_Form_ArticleForm;
use Application_Entity_ProductEntity;
use Application_Entity_PreferencesEntity;
use Application_Service_Impl_PreferencesServiceImpl;
use Preferences;
use Application_Util_Tools;
use SeparatorString;



include_once APPLICATION_PATH . '/controllers/BaseController.php';
include_once APPLICATION_PATH . '/Constants/Constants.php';
include_once APPLICATION_PATH . '/services/impl/PreferencesServiceImpl.php';

class PurchaseorderController extends BaseController{

	function init(){
		BaseController::init();
	}
	
	public function indexAction(){
	
		$service = new Application_Service_Impl_PurchaseOrderServiceImpl();
		
		$idOrder = $this->_getParam('idOrder');
		$allPurchase = $this->_getParam('purbyord');
		$id_company = $this->_getParam('idSuplier');
	    $this->view->id_role = $this->getCurrentUserIdRole();
	    
		if(isset($id_company)){
			$allOrdersSupplier = $service->getAllOrderSupplier($id_company);	
			if($allOrdersSupplier == null){
				$this->view->orders = 0;			
			}else{
				$this->view->orders = $allOrdersSupplier;
			}	
		}else if(isset($idOrder) && isset($allPurchase) == "true"){
			$purchaseByOrder = $service->getAllPurchaseByOrder($idOrder, "po");
			echo Zend_Json::encode(array('data' => $purchaseByOrder));
			exit;
		}elseif($this->getCurrentUserIdRole() == Privilege::PROVEEDOR){
			$idCompany = $this->getCompanyIdCurrent();
			
			$allOrdersSupplier = $service->getAllOrderSupplier($idCompany);	
			if($allOrdersSupplier == null){
				$this->view->orders = 0;			
			}else{
				$this->view->orders = $allOrdersSupplier;
			}
		}else{
			$allOrders = $service->getAllOpenOrder();					
			if($allOrders == null){
				$this->view->orders = 0;
			}else{
				$this->view->orders = $allOrders;
			}		
		}
	}
	
	public function addorderAction(){
		
		$formulario = new Application_Form_PurchaseOrderForm();
		$entityOrder = new Application_Entity_PurchaseOrderEntity();
		$entityPurchase = new Application_Entity_PurchaseEntity();
		$entityDatePaymentOrder = new Application_Entity_DatePaymentOrder();
		$service = new Application_Service_Impl_PurchaseOrderServiceImpl();
		$articleForm = new Application_Form_ArticleForm();
		
// 		$stock = new Application_Service_Impl_StockServiceImpl();
// 		$entityStock = Array();
// 	    $entityStock = $stock->getAllStock();
// 		$stock->setStockStatus($entityStock);
		
		$product = new Application_Entity_ProductEntity();
		$preferences = new  Application_Entity_PreferencesEntity();
		$preferencesService =  new Application_Service_Impl_PreferencesServiceImpl();
		
		$preference = $preferencesService->getPreferencesById(Preferences::CODE_IVA);
		
		foreach ($preference as $valor){
			$formulario->getElement('value')->setValue($valor->getValue())->setAttrib('readonly','readonly');
		}
		
		$formulario->getElement('start_date')->setValue(date('d/m/Y'));
		
		if ($this->getRequest()->isPost()) {

			$formData = $this->getRequest()->getPost();
			
 			if ($formulario->isValid($formData)){	
 				$productItemsEncoded = explode("_+_", $formData['purchaseItem']);
 				if(count($productItemsEncoded) > 0){
		 				$entityOrder->setId_company($formData['id_company']);
		 				//$entityOrder->setId_project($formData['id_project']);
		 				$entityOrder->setId_department($formData['ld_department']);
		 				//$entityOrder->setAccount($formData['account']);
		 				$entityOrder->setNumberOfPayment($formData['numberofpayments']);
		 				$entityOrder->setStart_date(Application_Util_Tools::dateDBFormat("/",$formData['start_date']));
		 				if($formData['end_date'] == null){
		 					$entityOrder->setEnd_date($formData['end_date']);
		 				}else{
		 					$entityOrder->setEnd_date(Application_Util_Tools::dateDBFormat("/",$formData['end_date']));
		 				}
		 				$entityOrder->setConditions($formData['conditions']);
		 				$entityOrder->setTotal($formData['total']);
		 				$entityOrder->setSubTotal($formData['subtotal']);
		 				$entityOrder->setIva($formData['iva']);
		 				
		 				$orderSave = $service->saveOrder($entityOrder);
		 				
		 				if($orderSave == "noSaveOrder"){
		 					$this->_redirect("/Out/purchaseorder/index?er=savOr");
		 				}else{

			 				for ($i = 0; $i < count($productItemsEncoded)-1; $i++){
	 						
	 							$productItem = explode('&' , $productItemsEncoded[$i]);
		 						$productName = explode('=' , $productItem[1]);
		 						$productPrice = explode("=", $productItem[2]);
		 						$productQuantity = explode("=", $productItem[3]);
	 						 
		 						$entityPurchase->setName($productName[1]);
		 						$entityPurchase->setPrice($productPrice[1]);
		 						$entityPurchase->setQuantity($productQuantity[1]);
		 						$entityPurchase->setOrder_id($orderSave);
		 						
		 						$productSave = $service->saveProductPurchase($entityPurchase);
	 							}
	 							
 							$datesToPayments = explode(",", $formData['dates']);
							$amount_datePayment = $formData['total'] / $formData['numberofpayments'];
							for ($i = 0; $i < count($datesToPayments); $i++){
								$entityDatePaymentOrder->setAmount($amount_datePayment);
 								$entityDatePaymentOrder->setDatePayment(Application_Util_Tools::dateDBFormat("/",$datesToPayments[$i]));
 								$entityDatePaymentOrder->setOrderId($orderSave);
 								$entityDatePaymentOrder->setAmountShow($amount_datePayment);
 									
 								$datesSave = $service->saveDatesPaymentOrder($entityDatePaymentOrder);
							} 
		 				}

		 					$this->_redirect("/Out/purchaseorder/index");

 				}else{
 					$this->_redirect("/Out/purchaseorder/addorder?er=notProd");
 				}
 				
 			}
		}
		
		$this->view->formulario = $formulario;
		$this->view->articleForm = $articleForm;
		
	}
	
	public function editorderAction(){
		
		$formulario = new Application_Form_PurchaseOrderForm();//formulario de orden de compra
		$entityOrder = new Application_Entity_PurchaseOrderEntity();//entiti de orden de compra
		$entityPurchase = new Application_Entity_PurchaseEntity();//entiti de compra
		$service = new Application_Service_Impl_PurchaseOrderServiceImpl();//service de ordend e compra
		$entityDatePaymentOrder = new Application_Entity_DatePaymentOrder();//entiti de fecha de pagos
		$articleForm = new Application_Form_ArticleForm();
		
		$idOrder = $this->_getParam('idOrder');//id de la orden
		$supplier = $this->_getParam('supplier');//id de la orden
		$purchaseOrder = $this->_getParam('purchase');//parametro para ajax para mostrar las compras
		
		$preferences = new  Application_Entity_PreferencesEntity();//entiti de prefetrencias para sacar el iva
		$preferencesService =  new Application_Service_Impl_PreferencesServiceImpl();//sevice de preferencias para sacar el iva
		
		$preference = $preferencesService->getPreferencesById(Preferences::CODE_IVA);//obtiene el iva puesto por el usuario
		
		foreach ($preference as $valor){
			$formulario->getElement('value')->setValue($valor->getValue())->setAttrib('readonly','readonly');//agrega el iva en el campos de value (escondido)
		}
		
		if($idOrder == null){//si el id de la orden es null return al index con mensaje de error
		
			$this->_redirect("/Out/purchaseorder/index?er=notK");
			
		}else{//si no empieza edicion de orden de compra
			
						
			if ($this->getRequest()->isPost()) {//verifica que la peticion sea post

			$formData = $this->getRequest()->getPost();//obtiene los datos que vienen en el post
			
 				
 				$productItemsEncoded = explode(SeparatorString::SEPARATOR, $formData['purchaseItem']);
 				
 				if(count($productItemsEncoded) > 0){
 					
 					    $entityOrder->setId_company($formData['id_company']);
		 				//$entityOrder->setId_project($formData['id_project']);
		 				$entityOrder->setAccount($formData['account']);
		 				$entityOrder->setId_department($formData['ld_department']);
		 				$entityOrder->setNumberOfPayment($formData['numberofpayments']);
		 				$entityOrder->setStart_date(Application_Util_Tools::dateFrontFormat("/",$formData['start_date']));
		 				$entityOrder->setEnd_date(Application_Util_Tools::dateFrontFormat("/",$formData['end_date']));
		 				$entityOrder->setConditions($formData['conditions']);
		 				$entityOrder->setTotal($formData['total']);
		 				$entityOrder->setSubTotal($formData['subtotal']);
		 				$entityOrder->setIva($formData['iva']);
		 				
		 				$orderSave = $service->updateOrder($entityOrder, $idOrder);
		 				
		 				$service->deleteProductPurchaseOld($idOrder);
 						for ($i = 0; $i < count($productItemsEncoded)-1; $i++){
	 						
	 							$productItem = explode('&' , $productItemsEncoded[$i]);
	 							$idProduct = explode("=", $productItem[0]);
	 							$productName = explode('=' , $productItem[1]);
		 						$productPrice = explode("=", $productItem[2]);
		 						$productQuantity = explode("=", $productItem[3]);
		 						$entityPurchase->setName($idProduct[1]);
		 						$entityPurchase->setPrice($productPrice[1]);
		 						$entityPurchase->setQuantity($productQuantity[1]);
		 						$entityPurchase->setOrder_id($idOrder);
		 						
		 						$productSave = $service->saveProductPurchase($entityPurchase);
		 						
	 					}
	 					
	 					$paymentDates = $service->deletePaymentDatesOld($idOrder);
 						
		 				$datesToPayments = explode(",", $formData['dates']);
		 					$amount_pay = $formData['total'] / $formData['numberofpayments'];
 							foreach($datesToPayments as $dates){
 									
 								$entityDatePaymentOrder->setAmount($amount_pay);
 								$entityDatePaymentOrder->setDatePayment(Application_Util_Tools::dateDBFormat("/",$dates));
 								$entityDatePaymentOrder->setOrderId($idOrder);
 									
 								$datesSave = $service->saveDatesPaymentOrder($entityDatePaymentOrder);
 							}
 							
	 					$this->_redirect("/Out/purchaseorder/index");
 					
 				}else{
 					$this->_redirect("/Out/purchaseorder/addorder?er=notProd");
 				}
 			
		}elseif($idOrder != null && $purchaseOrder == 'all'){
			
			$productOrder = $service->getAllPurchaseByOrder($idOrder, "po");
			
			echo Zend_Json::encode($productOrder);
			exit;
			
		}else{
			$productsForm = $service->getOrderById($idOrder);
		
			if($productsForm != null){
				$formulario->populate($productsForm);
			}else{
				$this->_redirect("/Out/purchaseorder/index?er=MisIdProd");
			}
			
			
		}
				
		$this->view->formulario = $formulario;
		$this->view->supplier = $supplier;
		$this->view->articleForm = $articleForm;
		}
	}
	
	public function deleteorderAction(){
		
		if($this->getRequest()->isPost()){//si la peticion es para post elimina la orden
			
			$idOrder = $this->_getParam('erase');//parametro recibido para eliminar
			$service = new Application_Service_Impl_PurchaseOrderServiceImpl(); //servicio de orden de compra
			
			$service->deleteProductPurchaseOld($idOrder);//elimina los productos de esa orden
			$service->deletePaymentDatesOld($idOrder);//elimina los pagos de esa orden
			
			$service->deleteOrder($idOrder);//elimina la orden
			exit;	
			
		}
		
	}
	
	public function historyAction(){
		$service = new Application_Service_Impl_PurchaseOrderServiceImpl();
		
		$this->view->id_role = $this->getCurrentUserIdRole();
		
		if($this->getCurrentUserIdRole() == Privilege::PROVEEDOR){
			$idCompany = $this->getCompanyIdCurrent();
			$allOrders = $service->getAllOrderByCompany($idCompany);
		
			if($allOrders == null){
				$this->view->orders = 0;
			}else{
				$this->view->orders = $allOrders;
			}
		}else{
			$allOrders = $service->getAllOrder();
		
			if($allOrders == null){
				$this->view->orders = 0;
			}else{
				$this->view->orders = $allOrders;
			}
		}
		
	}	
	
	public function addAction(){}
	
}