<?php


namespace In;

use BaseController;
use Application_Service_Impl_PurchaseOrderServiceImpl;
use Application_Service_Impl_ProductServiceImpl;
use Application_Form_DiscountStockForm;
use Application_Service_Impl_StockServiceImpl;
use Application_Service_Impl_CategoryServiceImpl;
use Application_Form_CategoryForm;
use Application_Form_ProductForm;
use Application_Entity_ProductEntity;
use Application_Entity_StockEntity;
use Application_Util_Tools;
use StatusPurchaseOrder;
use Constants;
use Application_Service_Impl_EntryOutProductServiceImpl;
use Application_Service_Impl_PurchaseServiceImpl;
use Zend_Json;


include_once APPLICATION_PATH . '/controllers/BaseController.php';

class WarehouseController extends BaseController{
	
	function init() {
		BaseController::init();
	}
	
	public function indexAction(){
		$purchaseOrderService = new Application_Service_Impl_PurchaseOrderServiceImpl();
		$purchaseOrders = $purchaseOrderService->getAllPurchaseForWareHouseNoMatterStatus();
		$this->view->purchaseOrders = $purchaseOrders;
		
		$serviceProduct = new Application_Service_Impl_ProductServiceImpl();
		$this->view->form = new Application_Form_DiscountStockForm();
		$this->view->products = $serviceProduct->getAllProductsInWareHouse();
	}
	
	public function createAction(){}
	
	public function updateAction(){
		$productService = new Application_Service_Impl_ProductServiceImpl();
		$stockService = new Application_Service_Impl_StockServiceImpl();
		$categoryService = new Application_Service_Impl_CategoryServiceImpl();
		$categoryForm = new Application_Form_CategoryForm();
		$form = new Application_Form_ProductForm(false);

		
		$idQueryString = $this->_getParam("id");
		
		if( $this->getRequest()->isPost() ){
			
			$formData = $this->getRequest()->getPost();
			
			if ($form->isValid($formData)){
				
				$product = new Application_Entity_ProductEntity();
				$stock = new Application_Entity_StockEntity();
				
				if($formData['mybrand'] == 0){
					$product->setId_Supplier($formData['id_company']);
				}else{
					$product->setId_Supplier(null);
				}
				
				$product->setIdProduct($idQueryString);
				$product->setName($this->_getParam("p_name"));
				$product->setDescription($this->_getParam("p_description"));
				$product->setPrice($this->_getParam("p_price"));
				$product->setKey($this->_getParam("p_key"));
				$product->setReview($this->_getParam("review_product"));
				$product->setUnit($this->_getParam("measuring_fk_id"));
				$product->setFkCategory($this->_getParam("id_category"));
				if($this->_getParam('expiration') != ""){
					$expiration = Application_Util_Tools::dateDBFormat('/',  $this->_getParam('expiration'));
				}else{
					$expiration = null;
				}
				$product->setExpiration($expiration);
				$product->setNamePhoto($this->_getParam("p_photo"));
	          	$lastProductId = $productService->updateProduct($product);
	          	
	          	$stock->setFkProductsId($idQueryString);
	          	$stock->setStock($this->_getParam("stock"));
	          	$stock->setMinStock($this->_getParam("min_stock"));
	          	$stock->setMaxStock($this->_getParam("max_stock"));
	          	
	          	$stockService->updatestock($stock);
	          	$this->_redirect("/In/warehouse/index");

			}
		}
		
		if ($idQueryString){
			$populateProduct = $productService->getProductById($idQueryString);
			
			$this->view->supplier = $populateProduct['id_company'];
			
			$form->populate($populateProduct);
			$form->populate($categoryService->getCategoryById($populateProduct['id_fk_category']));
			$form->populate($stockService->getStockByProductId($idQueryString));
		}
		$this->view->form = $form;
		$this->view->categoryForm = $categoryForm;
		$this->view->categories = $categoryService->getCategories();
	}
	
	public function addAction(){
		$ajaxRequest = $this->_getParam("ajaxRequest");
		$categoryForm = new Application_Form_CategoryForm();
		$productService = new Application_Service_Impl_ProductServiceImpl();
		$stockService = new Application_Service_Impl_StockServiceImpl();
		$categories = new Application_Service_Impl_CategoryServiceImpl();
		$form = new Application_Form_ProductForm(false);
		
		if( $this->getRequest()->isPost() ){
			
			$formData = $this->getRequest()->getPost();
			
			if (isset($ajaxRequest) == "true"){
			
	          		$product = new Application_Entity_ProductEntity();
	          		$stock = new Application_Entity_StockEntity();
	          		
	          		$product->setId_Supplier($this->_getParam('id_Supplier'));
	          		$product->setName($this->_getParam("p_name"));
	          		$product->setDescription($this->_getParam("p_description"));
	          		$product->setRecordDate(Application_Util_Tools::getCurrentDate());
	          		$product->setPrice($this->_getParam("p_price"));
	          		$product->setKey($this->_getParam("p_key"));
	          		$product->setReview($this->_getParam("review_product"));
	          		$product->setUnit($this->_getParam("measuring_fk_id"));
	          		$product->setFkCategory($this->_getParam("id_category"));
	          		if($this->_getParam('expiration') != ""){
	          		   $expiration = Application_Util_Tools::dateDBFormat('/',  $this->_getParam('expiration'));
	          		}else{
	          		   $expiration = null;
	          		}
	          		$product->setExpiration($expiration);
	          		$product->setUserId(parent::getCurrentUserId());
	          		
	          		$lastProductId = $productService->addProduct($product);
	          		
	          		$stock->setStock($this->_getParam("stock"));
	          		$stock->setMinStock($this->_getParam("min_stock"));
	          		$stock->setMaxStock($this->_getParam("max_stock"));
	          		$stock->setStatus(StatusPurchaseOrder::NOT_DELIVERED);
	          		$stock->setFkProductsId($lastProductId);
	          		
	          		$stockLastId = $stockService->addstock($stock);
	          		if ($stockLastId) {
	          			echo json_encode(array("response"=> Constants::RESPONSE_CODE_OK));
	          		}
	          		exit;

	         }elseif ($form->isValid($formData)){
	         

				$product = new Application_Entity_ProductEntity();
				$stock = new Application_Entity_StockEntity();
				
				if($formData['mybrand'] == 0){
					$product->setId_Supplier($formData['id_company']);
				}else{
					$product->setId_Supplier(null);
				}
				
				$product->setName($this->_getParam("p_name"));
				$product->setDescription($this->_getParam("p_description"));
				$product->setRecordDate(Application_Util_Tools::getCurrentDate());
				$product->setPrice($this->_getParam("p_price"));
				$product->setKey($this->_getParam("p_key"));
				$product->setNamePhoto($this->_getParam("p_photo"));
				$product->setType($this->_getParam("type_product"));
				$product->setReview($this->_getParam("review_product"));
				$product->setUnit($this->_getParam("measuring_fk_id"));
				if($this->_getParam('expiration') != ""){
					$expiration = Application_Util_Tools::dateDBFormat('/',  $this->_getParam('expiration'));
				}else{
					$expiration = null;
				}
				$product->setExpiration($expiration);
				$product->setFkCategory($this->_getParam("id_category"));
				$product->setUserId(parent::getCurrentUserId());

	          //$productService->savePhoto($form, $product);
	          	$lastProductId = $productService->addProduct($product);
	          	
	          	$stock->setStock($this->_getParam("stock"));
	          	$stock->setMinStock($this->_getParam("min_stock"));
	          	$stock->setMaxStock($this->_getParam("max_stock"));
	          	$stock->setStatus(StatusPurchaseOrder::NOT_DELIVERED);
	          	$stock->setFkProductsId($lastProductId);
	          	
	          	$stockService->addstock($stock);
	          	

	          		$this->_redirect("/In/warehouse/index");
          	

			}
			
		}
		$this->view->form = $form;
		$this->view->categoryForm = $categoryForm;
		$this->view->categories = $categories->getCategories();
		
	}
	public function historyAction(){
		$purchaseOrder = new Application_Service_Impl_PurchaseOrderServiceImpl();
		$this->view->purchaseOrders = $purchaseOrder->getAllPurchaseComplete();	
	}
	
	public function historyproductAction(){
		$typeProduct= $this->_getParam('finishedproduct');
		$daoHistory = new Application_Service_Impl_EntryOutProductServiceImpl();
		$this->view->history = $daoHistory->getHistory($typeProduct);
	}
	
	public function inventoryAction(){
		$serviceProduct = new Application_Service_Impl_ProductServiceImpl();
		$this->view->form = new Application_Form_DiscountStockForm();
		$this->view->products = $serviceProduct->getAllProductsInWareHouse();
	}
	
	public function orderAction(){
		$purchaseOrderId = $this->_getParam("id");

		$purchaseOrderService = new Application_Service_Impl_PurchaseOrderServiceImpl();
		$purchaseOrder = $purchaseOrderService->getOrderById($purchaseOrderId);
		$purchaseOrderItems = $purchaseOrderService->getAllPurchaseByOrder($purchaseOrderId, 'warehouse');
		//print_r($purchaseOrderItems);exit;
		$this->view->purchaseOrder = $purchaseOrder;
		$this->view->purchaseOrderItems = $purchaseOrderItems;
	}
	
	public function entryorderAction(){
		$purchaseOrderService = new
		Application_Service_Impl_PurchaseOrderServiceImpl();
		// retreiving all the pruchase orders that are not in the completly received in the warehouse
		$purchaseOrders = $purchaseOrderService->getAllPurchaseForWareHouse();
		$this->view->purchaseOrders = $purchaseOrders;
	}
	
	public function articleentryAction(){
		if($this->getRequest()->isPost()){
			
			$array = array(
					"orderId" => $this->_getParam('orderId'),
					"idProductComplete" => $this->_getParam('idProductComplete'),
					"idProductInComplete" => $this->_getParam('idProductInComplete'),
					"purchaseIdsComplete" => $this->_getParam('purchaseIdsComplete'),
					"purchaseIdsInComplete" => $this->_getParam('purchaseIdsInComplete'),
					"valuesProductComplete" => $this->_getParam('valuesProductComplete'),
					"valuesProductInComplete" => $this->_getParam('valuesProductInComplete'),
					"remaining" => $this->_getParam('remaining'),
					"quantity" => $this->_getParam('quantity')
				);
			
			$servicePurchase = new Application_Service_Impl_PurchaseServiceImpl();
			$servicePurchase->updateStockWareHouse($array);
			
			$response = array("status"=>"ok");
			echo Zend_Json::encode($response);
			exit;
		}
	}
	
	//public function addAction(){}
	
		
}