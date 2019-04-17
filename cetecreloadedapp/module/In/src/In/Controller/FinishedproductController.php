<?php


namespace In;

use BaseController;
use Application_Service_Impl_FinishedProductServiceImpl;
use Application_Form_DiscountStockForm;
use Zend_Json;
use Application_Form_MerchandizeForm;
use Application_Form_CategoryForm;
use Application_Service_Impl_CategoryServiceImpl;


include_once APPLICATION_PATH . '/controllers/BaseController.php';
include_once APPLICATION_PATH . '/util/Tools.php';

class FinishedproductController extends BaseController{
	
	function init() {
		BaseController::init();
	}

	public function indexAction(){
		$productslist = new Application_Service_Impl_FinishedProductServiceImpl();
		$form = new Application_Form_DiscountStockForm();
		$vC = $this->_getParam('vC');
		
		if(isset($vC)){
			$clave = $this->_getParam('clave');
			$productExist = $productslist->checkExistClaveProduct($clave);
			echo Zend_Json::encode(array("response" => $productExist));
			exit;
		}else{
			$this->view->form = $form;
			$this->view->products = $productslist->getFinishedProducts();
		}
	}
	
	public function addAction(){
		
		$formulario = new Application_Form_MerchandizeForm();
		$categoryForm = new Application_Form_CategoryForm();
		$categories = new Application_Service_Impl_CategoryServiceImpl();
    	$productService = new Application_Service_Impl_FinishedProductServiceImpl();
    	
    	if( $this->getRequest()->isPost() ){
			$formData = $this->getRequest()->getPost();
			
			if ($formulario->isValid($formData)){

				$formData['id_company'] = parent::getCompanyIdCurrent();
				$formData['id_user'] = parent::getCurrentUserId();
                //guarda producto
	          	$lastProductId = $productService->addFinishedProduct($formData);

	          	$this->_redirect("/In/finishedproduct/index");
			}
    	}else{
    		$categoriesList = $categories->getCategories();
			$this->view->formulario = $formulario;
			$this->view->categoryForm = $categoryForm;
			$this->view->categories = $categoriesList;	
    	}
    	
	}
	
	public function updateAction(){
		$formulario = new Application_Form_MerchandizeForm();
		$categoryForm = new Application_Form_CategoryForm();
		$categories = new Application_Service_Impl_CategoryServiceImpl();
		$productService = new Application_Service_Impl_FinishedProductServiceImpl();
		$idProd = $this->_getParam('p');
		
		if( $this->getRequest()->isPost() ){
			$formData = $this->getRequest()->getPost();
			
			if ($formulario->isValid($formData)){
				$formData['id_product'] = $idProd;
				
				$updateProduct = $productService->updateFinishedProduct($formData);
				
				$this->_redirect("/In/finishedproduct/index");
			}
		}else{
			$product = $productService->getFinishedProductById($idProd);
			$formulario->populate($product[0]);
			
			$categoriesList = $categories->getCategories();
		    $this->view->categoryForm = $categoryForm;
		    $this->view->categories = $categoriesList;
		    $this->view->formulario = $formulario;
		}
	}
	
	public function deleteAction(){
		$deleteParam = $this->_getParam("erase");
		$productService = new Application_Service_Impl_FinishedProductServiceImpl();
		$productService->deletefinishedProduct($deleteParam);
		exit;
	}
	
}