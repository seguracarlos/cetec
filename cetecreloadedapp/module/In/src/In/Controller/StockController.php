<?php


namespace In;

use BaseController;
use Application_Service_Impl_ProductServiceImpl;
use Application_Form_DiscountStockForm;
use Application_Service_Impl_CategoryServiceImpl;
use Application_Form_CategoryForm;
use Application_Service_Impl_StockServiceImpl;
use Application_Form_ProductForm;
use Application_Entity_ProductEntity;
use Application_Entity_StockEntity;
use Application_Util_Tools;
use StatusPurchaseOrder;
use Constants;
use Application_Entity_CategoryEntity;
use Application_Entity_EntryOutProductEntity;
use Application_Service_Impl_EntryOutProductServiceImpl;


include_once APPLICATION_PATH . '/controllers/BaseController.php';

class StockController extends BaseController{
	
	function init() {
		BaseController::init();
	}
	
	function createAction(){}	
	
	public function indexAction(){
		
		$productService = new Application_Service_Impl_ProductServiceImpl();
		$this->view->products = $productService->getAllProducts();
		
		$this->view->form = new Application_Form_DiscountStockForm();
		
		$categories = new Application_Service_Impl_CategoryServiceImpl();
		$this->view->categories = $categories->getCategories();
		
		$categoryForm = new Application_Form_CategoryForm();
		$this->view->categoryForm = $categoryForm;
	}
	
	public function addAction(){
		$ajaxRequest = $this->_getParam("ajaxRequest");
		$categoryForm = new Application_Form_CategoryForm();
		$productService = new Application_Service_Impl_ProductServiceImpl();
		$stockService = new Application_Service_Impl_StockServiceImpl();
		$categories = new Application_Service_Impl_CategoryServiceImpl();
		//modulo de producto terminado parametro para cambiar titulo y formulario
		$finished = $this->_getParam("finished");
		
		if(isset($finished)){
			$this->view->titulo = 'Productos Terminados';
			$form = new Application_Form_ProductForm($finished);
		}else{
			$this->view->titulo = 'Productos Materia Prima';
			$form = new Application_Form_ProductForm(false);
		}
		
		//modulo de producto terminado 
		
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
	          		$product->setType($this->_getParam("type_product"));
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
				$product->setPurchasePercentage($this->_getParam("purchasePercentage"));
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
	          	
	          	if(isset($finished)){
	          		$this->_redirect("/In/finishedproduct");
	          	}else{
	          		$this->_redirect("/In/stock/index");
	          	}
			}
		}
		$this->view->form = $form;
		$this->view->categoryForm = $categoryForm;
		$this->view->categories = $categories->getCategories();
		
	}
	
	public function updateAction(){
		$productService = new Application_Service_Impl_ProductServiceImpl();
		$stockService = new Application_Service_Impl_StockServiceImpl();
		$categoryService = new Application_Service_Impl_CategoryServiceImpl();
		$categoryForm = new Application_Form_CategoryForm();
		//modulo de producto terminado parametro para cambiar titulo y formulario
		$finished = $this->_getParam("finished");
		
		if(isset($finished)){
			$this->view->titulo = 'un producto terminado';
			$form = new Application_Form_ProductForm($finished);
		}else{
			$this->view->titulo = 'un producto';
			$form = new Application_Form_ProductForm(false);
		}
		
		//modulo de producto terminado
		
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
				$product->setPurchasePercentage($this->_getParam("purchasePercentage"));
				$product->setKey($this->_getParam("p_key"));
				$product->setType($this->_getParam("type_product"));
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
	          	
				if(isset($finished)){
	          		$this->_redirect("/In/finishedproduct");
	          	}else{
	          		$this->_redirect("/In/stock/index");
	          	}
          	
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
	
	public function catalogAction(){
		
	}
	
	public function deleteAction(){
		$deleteParam = $this->_getParam("erase");
		$service = new Application_Service_Impl_ProductServiceImpl();
		$service->deleteProduct($deleteParam);
		exit;
	}
	
	public function addcategoryAction(){

		$idCategory = $this->_getParam("idCategory");
		$categoryName = $this->_getParam("category");
		$description = $this->_getParam("comment");
		
		$categoryService = new Application_Service_Impl_CategoryServiceImpl();
		$category = new Application_Entity_CategoryEntity();
		
		$category->setCategoryId($idCategory);
		$category->setName($categoryName);
		$category->setDescription($description);
		$categoryService->addCategory($category);
		
//		$categoryService = new Application_Service_Impl_CategoryServiceImpl();
			print ($categoryService->getCategoriesJSON());		
		
		exit;
	}
	
	/*public function reloadAction(){
		
		if($this->_getParam("i")==1){
			$categoryService = new Application_Service_Impl_CategoryServiceImpl();
			print ($categoryService->getCategoriesJSON());
			exit;		
		}
		
		$isProduct = $this->_getParam("product");
		$idProduct = $this->_getParam('id_product');
		$id_Supplier = $this->_getParam('id_supplier');
		
		if($isProduct) {
			$service = new Application_Service_Impl_ProductServiceImpl();
			print ($service->getProductJSONById($idProduct));
		}else if($idProduct == 0){
			$service = new Application_Service_Impl_ProductServiceImpl();
			print ($service->getProductsJSON($id_Supplier));
		}

		exit;
	}*/

		public function reloadAction(){
		$service = new Application_Service_Impl_ProductServiceImpl();
		$isProduct = $this->_getParam("product");
		$idProduct = $this->_getParam('id_product');
		$id_Supplier = $this->_getParam('id_supplier');

		if($this->_getParam("i")==1){
			$categoryService = new Application_Service_Impl_CategoryServiceImpl();
			print ($categoryService->getCategoriesJSON());
			exit;		
		}else if($isProduct) {
			$type = $this->_getParam('type');
			if(isset($type) && $type == 1){//get finished product
				$productsType = $service->getMerchandizeJSONById($idProduct);
			}else if(isset($type) && $type == 2){//get products
				$productsType = $service->getProductJSONById($idProduct);
			}
			print ($productsType);
		}else if($idProduct == 0){
			$service = new Application_Service_Impl_ProductServiceImpl();
			print ($service->getProductsJSON($id_Supplier));
		}

		exit;
	}
	
	public function getstockAction(){
		
		$idProduct = $this->_getParam("product");
		
		$stockService = new Application_Service_Impl_StockServiceImpl();
		$stock = $stockService->getStockMerchindizeByProductId($idProduct);
		
		print json_encode($stock);
		exit;		
	}
	
	public function setnewstockAction(){
		$outProduct = new Application_Entity_EntryOutProductEntity();
		$outProductService = new Application_Service_Impl_EntryOutProductServiceImpl();
		$stockObject = new Application_Entity_StockEntity();
		$stockService = new Application_Service_Impl_StockServiceImpl();
		
		$idStock = $this->_getParam("idStock");
		$stock = $this->_getParam("stock");
		$amount = $this->_getParam("amount");
		$idProducts = $this->_getParam("idProducts");
		$typeProduct = $this->_getParam("typeProduct");
		$storage = $this->_getParam("storage");
		
		//inicio registro de las salidas tabla entry_out_product
		$outProduct->setAmount($amount);
		$outProduct->setDate(date('Y-m-d'));
		$outProduct->setTypeProduct($typeProduct);
		$outProduct->setTypeStorage($storage);
		$outProduct->setIdFkProduct($idProducts);
		
		$stockObject->setIdStock($idStock);
		$stockObject->setStock($stock);
		$stockObject->setStatus(StatusPurchaseOrder::NOT_DELIVERED);
		$stockObject->setFkProductsId($idProducts);

		$outProductService-> saveMerchandizeHistory($outProduct);
		$stockService->setNewStock($stockObject);
		
		exit;
	}
	
	
	public function historyAction(){
		$productService = new Application_Service_Impl_ProductServiceImpl();
		$this->view->products = $productService->getAllProducts();
		
		
	}
}