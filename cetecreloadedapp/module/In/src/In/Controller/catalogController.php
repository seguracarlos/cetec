<?php


namespace In;

use BaseController;
use Application_Service_Impl_FinishedProductServiceImpl;
use Zend_Session_Namespace;
use zend_Json;
use Zend_Json;
use Application_Service_Impl_SalesServiceImpl;


include_once APPLICATION_PATH . '/controllers/BaseController.php';

class catalogController extends BaseController{

	public function init(){
		BaseController::init();
	}
	
	public function indexAction(){
	
		$m = $this->_getParam('m');
		$id_prod = $this->_getParam('iP');
		$add = $this->_getParam('add');
		$hiu = $this->_getParam('hiu');
		$car = $this->_getParam('car');
		$nProd = $this->_getParam('nProd');
		$getSes = $this->_getParam('getSes');
		
		$productslist = new Application_Service_Impl_FinishedProductServiceImpl();
		$idsProdSession = new Zend_Session_Namespace('idsProdSession');
		
		if ($this->getRequest()->isPost()) {
			$ids = $this->_getParam('ids');
			
				$products = explode("," ,trim($ids, ","));
				$priceTotalCart = 0;
				
				foreach ($products as $product){
					$box = $productslist->getFinishedProductById($product);
					
					$percentaje = $box[0]['purchase_percentage'] / 100;//porcentaje
					$priceSale = $box[0]['p_price'] * $percentaje;//ganancia
					$priceGain = $box[0]['p_price'] + $priceSale;//precio venta
					
					$priceTotalCart = $priceGain + $priceTotalCart;
 				}
			
			$idsProdSession->ids = $ids;
			$idsProdSession->totalCart = $priceTotalCart;
			
			echo $idsProdSession->ids;
			exit;
			/*$purchase = array(
					'id_user' => $this->getCurrentUserId(),
					'id_product' => $add,
					'date' => Date('Y-m-d')
			);
			
			
			$addShop = $productslist->addProductToCar($purchase);
			if($addShop == null){
				echo Zend_Json::encode(array('response' => 'fail', 'data' => 'Error al agregar producto al carrito, intentalo de nuevo'));
			}else{
				echo Zend_Json::encode(array('response' => 'ok', 'data' => 'Producto agregado al carrito :)'));
			}
			exit;*/
		}else{
			if(isset($m)){
			  $box = $productslist->getFinishedProductsByCatalog($m);
	  		  echo zend_Json::encode($box);
	  		  exit;
			}else if(isset($id_prod)){
				$box = $productslist->getFinishedProductById($id_prod);
				echo zend_Json::encode($box);
	  		    exit;
			}else if(isset($hiu)){
				$idUser = $this->getCurrentUserId();
				$purchase = $productslist->getPurchaseInCarByUser($idUser);
				echo zend_Json::encode($purchase);
	  		    exit;
			}else if(isset($car)){
			
				$id_products = $this->_getParam('id_products');
				$products = explode("," ,trim($id_products, ","));
				$listProd = array();
				$priceTotalCart = 0;
				
				foreach ($products as $product){
					$box = $productslist->getFinishedProductById($product);
					
					$percentaje = $box[0]['purchase_percentage'] / 100;//porcentaje
					$priceSale = $box[0]['p_price'] * $percentaje;//ganancia
					$priceGain = $box[0]['p_price'] + $priceSale;//precio venta
					
					$priceTotalCart = $priceGain + $priceTotalCart;
 					$listProd[] = $box;
 				}
				
 				echo zend_Json::encode(array("response" => $listProd, "totalCart" => $priceTotalCart));
	  		    exit;
				
				/*$idUser = $this->getCurrentUserId();
				$purchase = $productslist->getPurchaseFullInCarByUser($idUser);
				echo zend_Json::encode($purchase);
	  		    exit;*/
			}else if(isset($nProd)){
				$this->view->products = $productslist->getFinishedProductsBySearchName(0, $nProd);
			}else if(isset($getSes)){
				$t = 0;
				
				if(isset($idsProdSession->totalCart)){
					$t = $idsProdSession->totalCart;
				}
				
				echo Zend_Json::encode(array("ids" => $idsProdSession->ids, "totalCart" => $t));
				exit;
			
			}else{
				$this->view->products = $productslist->getFinishedProductsByCatalog(0);
			}	
		}
	}

	function deleteAction(){
		$deleteParam = $this->_getParam("erase");
		$productslist = new Application_Service_Impl_SalesServiceImpl();
		
		if ($this->getRequest()->isPost()) {
			$productslist->deleteShopInCar($deleteParam);
			exit;
		}
	}
	
	
	
	function createAction(){}
	function addAction(){}

	function fullviewerAction(){
			$m = $this->_getParam('m');
		$id_prod = $this->_getParam('iP');
		$add = $this->_getParam('add');
		$hiu = $this->_getParam('hiu');
		$car = $this->_getParam('car');
		$nProd = $this->_getParam('nProd');
		$getSes = $this->_getParam('getSes');
		
		$productslist = new Application_Service_Impl_FinishedProductServiceImpl();
		$idsProdSession = new Zend_Session_Namespace('idsProdSession');
		
		if ($this->getRequest()->isPost()) {
			$ids = $this->_getParam('ids');
			
				$products = explode("," ,trim($ids, ","));
				$priceTotalCart = 0;
				
				foreach ($products as $product){
					$box = $productslist->getFinichedProductById($product);
					
					$percentaje = $box[0]['purchase_percentage'] / 100;//porcentaje
					$priceSale = $box[0]['p_price'] * $percentaje;//ganancia
					$priceGain = $box[0]['p_price'] + $priceSale;//precio venta
					
					$priceTotalCart = $priceGain + $priceTotalCart;
 				}
			
			$idsProdSession->ids = $ids;
			$idsProdSession->totalCart = $priceTotalCart;
			
			echo $idsProdSession->ids;
			exit;
			/*$purchase = array(
					'id_user' => $this->getCurrentUserId(),
					'id_product' => $add,
					'date' => Date('Y-m-d')
			);
			
			
			$addShop = $productslist->addProductToCar($purchase);
			if($addShop == null){
				echo Zend_Json::encode(array('response' => 'fail', 'data' => 'Error al agregar producto al carrito, intentalo de nuevo'));
			}else{
				echo Zend_Json::encode(array('response' => 'ok', 'data' => 'Producto agregado al carrito :)'));
			}
			exit;*/
		}else{
			if(isset($m)){
			  $box = $productslist->getFinishedProductsByCatalog($m);
	  		  echo zend_Json::encode($box);
	  		  exit;
			}else if(isset($id_prod)){
				$box = $productslist->getFinichedProductById($id_prod);
				echo zend_Json::encode($box);
	  		    exit;
			}else if(isset($hiu)){
				$idUser = $this->getCurrentUserId();
				$purchase = $productslist->getPurchaseInCarByUser($idUser);
				echo zend_Json::encode($purchase);
	  		    exit;
			}else if(isset($car)){
				
				$id_products = $this->_getParam('id_products');
				
				$products = explode("," ,trim($id_products, ","));
 				
				$listProd = array();
				
				$priceTotalCart = 0;
				
				foreach ($products as $product){
					$box = $productslist->getFinichedProductById($product);
					
					$percentaje = $box[0]['purchase_percentage'] / 100;//porcentaje
					$priceSale = $box[0]['p_price'] * $percentaje;//ganancia
					$priceGain = $box[0]['p_price'] + $priceSale;//precio venta
					
					$priceTotalCart = $priceGain + $priceTotalCart;
 					$listProd[] = $box;
 				}
				
 				echo zend_Json::encode(array("response" => $listProd, "totalCart" => $priceTotalCart));
	  		    exit;
				
				/*$idUser = $this->getCurrentUserId();
				$purchase = $productslist->getPurchaseFullInCarByUser($idUser);
				echo zend_Json::encode($purchase);
	  		    exit;*/
			}else if(isset($nProd)){
				$this->view->products = $productslist->getFinishedProductsBySearchName(0, $nProd);
			}else if(isset($getSes)){
				$t = 0;
				
				if(isset($idsProdSession->totalCart)){
					$t = $idsProdSession->totalCart;
				}
				
				echo Zend_Json::encode(array("ids" => $idsProdSession->ids, "totalCart" => $t));
				exit;
			
			}else{
				$this->view->products = $productslist->getFinishedProductsByCatalog(0);
			}	
		}		
	}
	
}