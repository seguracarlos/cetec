<?php


namespace In;

use BaseController;
use Application_Service_Impl_ProposalsServiceImpl;
use CustomersServices;
use Zend_Json;
use Privilege;
use Application_Form_ProposalForm;
use Application_Entity_ProposalsEntity;
use Application_Entity_PurchaseEntity;
use Application_Form_ArticleForm;
use Application_Service_Impl_StockServiceImpl;
use Application_Entity_ProductEntity;
use Application_Entity_PreferencesEntity;
use Application_Service_Impl_PreferencesServiceImpl;
use Preferences;
use Application_Util_Tools;
use Application_Entity_DatePaymentOrder;
use SeparatorString;
use Zend_Loader_Autoloader;
use Zend_Pdf;
use Zend_Pdf_Page;
use Zend_Pdf_Image;
use Zend_Pdf_Style;
use Zend_Pdf_Color_RGB;
use Zend_Pdf_Font;
use Zend_Pdf_Exception;
use Exception;
use Zend_Mail_Transport_Smtp;
use Zend_Mail;
use Zend_Mime;
use Zend_Exception;


include_once APPLICATION_PATH . '/controllers/BaseController.php';
include_once APPLICATION_PATH . '/Constants/Constants.php';
include_once APPLICATION_PATH . '/services/impl/PreferencesServiceImpl.php';

class ProposalsController extends BaseController{

	function init(){
		BaseController::init();
	}
	
	public function priceAction(){
		
		$idClient = $this->_getParam("idClient");
		$cust = $this->_getParam("cust_type");	
		/*print_r($cust);
		exit;*/

	    if(isset($idClient) && isset($cust)){
			$this->view->idClient = $idClient;
			$this->view->cust_type = $cust;
		}else{
			$this->view->idClient = "0";
			$this->view->cust_type = "nulo";
		}
    }

    public function changestoprojectAction(){
		$projectIdParam = $this->_getParam("projectId");
		$serviceProposals = new Application_Service_Impl_ProposalsServiceImpl();
		$serviceProposals->changesToProject($projectIdParam);
		exit;
	}

	public function indexAction(){
	
		$service = new Application_Service_Impl_ProposalsServiceImpl();
		$clients = new CustomersServices();

		$idOrder = $this->_getParam('idOrder');
		$allPurchase = $this->_getParam('purbyord');
		$id_company = $this->_getParam('idSuplier');
		$gCP = $this->_getParam("gCP");
		//print_r($gCP);exit;
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
		}else if(isset($gCP) && $gCP == 1){
			$type = $this->_getParam("type");
			
			$listCP = $clients->getClientOrProspect($type);

			if($listCP != null){
				echo Zend_Json::encode(array("response" => "ok", "data" => $listCP));	
			}else{
				echo Zend_Json::encode(array('response' => "fail"));
			}
			exit;
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
		
		$formulario = new Application_Form_ProposalForm();
		$entityOrder = new Application_Entity_ProposalsEntity();
		$entityPurchase = new Application_Entity_PurchaseEntity();
		//entityDatePaymentOrder = new Application_Entity_DatePaymentOrder();
		$service = new Application_Service_Impl_ProposalsServiceImpl();
		$articleForm = new Application_Form_ArticleForm();
		$serviceCustomer = new CustomersServices();
		
		$stock = new Application_Service_Impl_StockServiceImpl();
		$entityStock = Array();
	    $entityStock = $stock->getAllStock();
	    $imgUpload = $this->_getParam('imgUpload');
	    $idClient = $this->_getParam("idClient");
	    $cust = $this->_getParam("cust_type");

	    if (isset($idClient) && isset($cust)) {
	    	if($cust != null){

				$cp = 0;
				
				if ($cust == 0) {
					$cp = 1;
				}else{
					$cp = 0;
				}
	    	$this->view->idCli = $idClient;
	    	$this->view->cust_type = $cp;
			}
	    }
		
		$stock->setStockStatus($entityStock);
		
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
			
 				$productItemsEncoded = explode("_+_", $formData['purchaseItem']);
 				if(count($productItemsEncoded) > 0){
		 				$entityOrder->setId_company($formData['id_company']);
		 				//$entityOrder->setCompany_id($formData['company_ID']);
		 				//$entityOrder->setId_project($formData['id_project']);
		 				//$entityOrder->setId_department($formData['ld_department']);
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
		 					$this->_redirect("/In/proposals/index?er=savOr");
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
	 							
 							/*$datesToPayments = explode(",", $formData['dates']);
							$amount_datePayment = $formData['total'] / $formData['numberofpayments'];
							for ($i = 0; $i < count($datesToPayments); $i++){
								$entityDatePaymentOrder->setAmount($amount_datePayment);
 								$entityDatePaymentOrder->setDatePayment(Application_Util_Tools::dateDBFormat("/",$datesToPayments[$i]));
 								$entityDatePaymentOrder->setOrderId($orderSave);
 								$entityDatePaymentOrder->setAmountShow($amount_datePayment);
 									
 								$datesSave = $service->saveDatesPaymentOrder($entityDatePaymentOrder);
							} */
		 				}

		 					$this->_redirect("/In/proposals/index");

 				}else{
 					$this->_redirect("/In/proposals/addorder?er=notProd");
 				}
        	}	
 	
		$this->view->formulario = $formulario;
		$this->view->articleForm = $articleForm;
		
	}
	
	public function editorderAction(){
		
		$formulario = new Application_Form_ProposalForm();//formulario de orden de compra
		$entityOrder = new Application_Entity_ProposalsEntity();//entiti de orden de compra
		$entityPurchase = new Application_Entity_PurchaseEntity();//entiti de compra
		$service = new Application_Service_Impl_ProposalsServiceImpl();//service de ordend e compra
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
		
			$this->_redirect("/In/proposals/index?er=notK");
			
		}else{//si no empieza edicion de orden de compra
			
						
			if ($this->getRequest()->isPost()) {//verifica que la peticion sea post

			$formData = $this->getRequest()->getPost();//obtiene los datos que vienen en el post
			
 		
 				$productItemsEncoded = explode(SeparatorString::SEPARATOR, $formData['purchaseItem']);
 				
 				if(count($productItemsEncoded) > 0){
 					
 					    $entityOrder->setId_company($formData['company']);
		 				//$entityOrder->setId_project($formData['id_project']);
		 				//$entityOrder->setAccount($formData['account']);
		 				//$entityOrder->setId_department($formData['ld_department']);
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
	 					
	 					/*$paymentDates = $service->deletePaymentDatesOld($idOrder);
 						
		 				$datesToPayments = explode(",", $formData['dates']);
		 					$amount_pay = $formData['total'] / $formData['numberofpayments'];
 							foreach($datesToPayments as $dates){
 									
 								$entityDatePaymentOrder->setAmount($amount_pay);
 								$entityDatePaymentOrder->setDatePayment(Application_Util_Tools::dateDBFormat("/",$dates));
 								$entityDatePaymentOrder->setOrderId($idOrder);
 									
 								$datesSave = $service->saveDatesPaymentOrder($entityDatePaymentOrder);
 							}*/
 							
	 					$this->_redirect("/In/proposals/index");
 					
 				}else{
 					$this->_redirect("/In/proposals/addorder?er=notProd");
 				}
 				
 				
 			
 			
		}elseif($idOrder != null && $purchaseOrder == 'all'){
			
			$productOrder = $service->getAllPurchaseByOrder($idOrder, "po");
			
			echo Zend_Json::encode($productOrder);
			exit;
			
		}else{
			$productsForm = $service->getOrderById($idOrder);
			/*echo "<pre>";
			print_r($productsForm);
			echo "</pre>";
			exit;*/
		
			if($productsForm != null){

				$cp = 0;
				
				if ($productsForm['cust_type'] == 0) {
					$cp = 1;
				}else{
					$cp = 0;
				}
				$this->view->id_company = $productsForm['id_company'];
				$this->view->cust_type = $cp;
				$formulario->populate($productsForm);
			}else{
				$this->_redirect("/In/proposals/index?er=MisIdProd");
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
			$service = new Application_Service_Impl_ProposalsServiceImpl(); //servicio de orden de compra
			
			$service->deleteProductPurchaseOld($idOrder);//elimina los productos de esa orden
			$service->deletePaymentDatesOld($idOrder);//elimina los pagos de esa orden
			
			$service->deleteOrder($idOrder);//elimina la orden
			exit;	
			
		}
		
	}
	
	public function historyAction(){
		$service = new Application_Service_Impl_ProposalsServiceImpl();
		
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

    /* ---------------------------------------------------------------------------
     * ----------------- Accion de exportar propuesta a pdf ----------------------
     * ---------------------------------------------------------------------------*/
    
    public function exportAction(){
    	
    	$entityPurchase = new Application_Entity_PurchaseEntity();//entiti de compra
    	
    	$idOrder = $this->_getParam('idOrder');
    	

    	if(isset($idOrder)){
    		$service = new Application_Service_Impl_ProposalsServiceImpl();
	    	$propuesta = $service->getOrderById($idOrder);
	    	$productOrder = $service->getAllPurchaseByOrder($idOrder, "po");
	    	
	    	if($propuesta == null){
	    		$this->_redirect('/In/proposals/index');
	    	}
	    	
	    	$propuestaRolTech = $service->getPurchaseByOrder();
	    	//*$jobsService = new JobUserServiceImpl();
     		$techService = new Application_Service_Impl_ProposalsServiceImpl(); 
     
    	    
			
	    	$this->view->dataProp = $propuesta;
	    	//$this->view->idOrder = $propuesta['order_id'];
	    	$this->view->idOrder = $productOrder;
    	}else{
    		$this->_redirect('/In/proposals/index');
    	}    	
    }//termina la accion de exportpdf
    
    public function pdfAction(){
    	
    	$idProd = $this->getParam('idProd');
    	    
    	if($idProd){
    		
    		$autoloader = Zend_Loader_Autoloader::getInstance();

    		// create PDF
    		$pdf = new Zend_Pdf();

    		
    		// create A4 page
    		$page = new Zend_Pdf_Page(Zend_Pdf_Page::SIZE_A4);
    		
    		$pdf->pages[] = $page;
    		
    		$service = new Application_Service_Impl_ProposalsServiceImpl();
    		$propuesta = $service->getOrderById($idProd);
    		$productOrder = $service->getAllPurchaseByOrder($idProd, "po");
    	
    		// Load image
    		
    		//$imageFile = dirname(__FILE__) . '/public/img/horusLogo.png';
    		//$stampImage = Zend_Pdf_Image::imageWithPath($imageFile);
    		//$imagePath='C:\xampp\htdocs\horusrobot\public\horus_robot.png';
    		//$image = Zend_Pdf_Image::imageWithPath('file:///C:/xampp/htdocs/horusrobot/public/img/horusLogo.png');
    		$image = Zend_Pdf_Image::imageWithPath('../horusrobot/public/img/horusLogo.png');
    		// Draw image
    		$page->drawImage($image, 50, 750, 170, 790);

    		
    		//letras de color verde
    		$letraVerde = new Zend_Pdf_Style();
    		$letraVerde->setFillColor(new Zend_Pdf_Color_RGB(0, 10, 0));
    		$letraVerde->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_COURIER_BOLD_ITALIC), 12);
    		//letras de color azul
    		$letraAzul = new Zend_Pdf_Style();
    		$letraAzul->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 10));
    		$letraAzul->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD), 16);
    		//letras de color negro
    		$letraNegra = new Zend_Pdf_Style();
    		$letraNegra->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
    		$letraNegra->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), 12);
    		//letras de titulo
    		$letraTitulo = new Zend_Pdf_Style();
    		$letraTitulo->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
    		$letraTitulo->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD), 20);
    		//letras de titulo
    		$letraSubtitulo = new Zend_Pdf_Style();
    		$letraSubtitulo->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
    		$letraSubtitulo->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD), 16);
    		
    		$page_width = $page->getWidth();   // A4 : 595
    		$page_height = $page->getHeight(); // A4 : 842
    		
    		$page->setStyle($letraNegra);
    		$page->drawText('Folio ', 465, 740);
    		$page->drawText($propuesta['order_id'], 495, 740);
    		
    		$wrap_txt = "Cotizaci�n de Productos";
    		$text = wordwrap($wrap_txt, 80, "\r\n", false);//"<br />\n"
    		$token = strtok($text, "\n");
    		
    		$y = $page_height - 60;
    		$page->setStyle($letraTitulo);
    		
    		while ($token != false) {
    			if ($y < 60) {
    				$pdf->pages[] = ($page = $pdf->newPage(Zend_Pdf_Page::SIZE_A4));
    				$page->setStyle($letraTitulo);
    				$y = $page_height - 60;
    			} else {
    				$page->drawText($token, ($page->getWidth()/3), $y);
    				$y-=40;
    			}
    			$token = strtok("\n");
    		
    		}
    		
    		//fecha del dia en que es creada la minuta en pdf
    		$fecha = date('M j Y');
    		$hora = date('h:i:s');
    		//dibuja fecha
    		$page->setStyle($letraNegra);
    		$page->drawText($fecha, 465, 790);
    		$page->drawText($hora, 465, 780);

    		foreach($pdf->pages As $key => $page)
    		{
    			$page->setLineWidth(1.0);
    			$page->drawLine(60, $page_height - 35, $page_width - 60, $page_height - 35);
    			$page->setStyle($letraVerde);
    			$page->drawText('GRAD - Green Rapid Application Development', 60, $page_height - 20);
    			$page->drawText('powered by IOFractal', 390, $page_height - 20);
    		}		
    		
    		$otroText = "INFORMACI�N GENERAL";
    		$text2 = wordwrap($otroText, 80, "\n", false);
    		$token2 = strtok($text2, "\n");
    		
    		$page->setStyle($letraAzul);
    		while ($token2 != false) {
    			if ($y < 60) {
    				$pdf->pages[] = ($page = $pdf->newPage(Zend_Pdf_Page::SIZE_A4));
    				$page->setStyle($letraAzul);
    				$y = $page_height - 60;
    			} else {
    				$page->drawText($token2, ($page->getWidth()/3), $y);
    				$page->setLineWidth(1.0);
    				$page->drawLine(60, $y-5, $page_width - 60, $y-5);
    				$y-=30;
    			}
    			$token2 = strtok("\n");
    		}
    
    		$page->setStyle($letraNegra);

    		//name of company
    		$nameCompany = $propuesta['name_company'];
    		$text3 = wordwrap($nameCompany, 80, "\n", false);
    		$token3 = strtok($text3, "\n");
    		
    		$page->setStyle($letraNegra);
    		while ($token3 != false) {
    			if ($y < 60) {
    				$pdf->pages[] = ($page = $pdf->newPage(Zend_Pdf_Page::SIZE_A4));
    				$page->setStyle($letraNegra);
    				$y = $page_height - 60;
    			} else {
    				$page->setStyle($letraSubtitulo);
    				$page->drawText('Cliente: ', 60, $y);
    				$page->setStyle($letraNegra);
    				$page->drawText($token3, 120, $y, 'UTF-8');
    				$y-=20;
    			}
    			$token3 = strtok("\n");
    		}
    		
    		//date of price
    		$datePrice = $propuesta['start_date'];
    		$text4 = wordwrap($datePrice, 80, "\n", false);
    		$token4 = strtok($text4, "\n");
    		
    		$page->setStyle($letraNegra);
    		while ($token4 != false) {
    			if ($y < 60) {
    				$pdf->pages[] = ($page = $pdf->newPage(Zend_Pdf_Page::SIZE_A4));
    				$page->setStyle($letraNegra);
    				$y = $page_height - 60;
    			} else {
    				$page->setStyle($letraSubtitulo);
    				$page->drawText('Fecha de Cotizaci�n: ', 60, $y);
    				$page->setStyle($letraNegra);
    				$page->drawText($token4, 210, $y, 'UTF-8');
    				$y-=20;
    			}
    			$token4 = strtok("\n");
    		}
    		
    		//date of send
    		$dateSend = $propuesta['end_date'];
    		$text5 = wordwrap($dateSend, 80, "\n", false);
    		$token5 = strtok($text5, "\n");
    		
    		$page->setStyle($letraNegra);
    		while ($token5 != false) {
    			if ($y < 60) {
    				$pdf->pages[] = ($page = $pdf->newPage(Zend_Pdf_Page::SIZE_A4));
    				$page->setStyle($letraNegra);
    				$y = $page_height - 60;
    			} else {
    				$page->setStyle($letraSubtitulo);
    				$page->drawText('Fecha de Envio: ', 60, $y);
    				$page->setStyle($letraNegra);
    				$page->drawText($token5, 210, $y, 'UTF-8');
    				$y-=20;
    			}
    			$token5 = strtok("\n");
    		}
    		
    		$page->setStyle($letraSubtitulo);
    		
    		if($y < 60){
    			$pdf->pages[] = ($page = $pdf->newPage(Zend_Pdf_Page::SIZE_A4));
    			$page->setStyle($letraSubtitulo);
    			$y = $page_height - 60;
    		}else{
    			$page->drawText('Condiciones: ', 60, $y);
    			$y-=20;
    		}
    		
    		$page->setStyle($letraNegra);
    		//Conditions
    		$conditions = $propuesta['conditions'];
    		$text7 = wordwrap($conditions, 80, "\n", false);
    		$token7 = strtok($text7, "\n");
    		 
    		$page->setStyle($letraNegra);
    		while ($token7 != false) {
    			if ($y < 60) {
    				$pdf->pages[] = ($page = $pdf->newPage(Zend_Pdf_Page::SIZE_A4));
    				$page->setStyle($letraNegra);
    				$y = $page_height - 60;
    			} else {
    				$page->drawText($token7, 100, $y, 'UTF-8');
    				$y-=20;
    			}
    			$token7 = strtok("\n");
    		}
    		
    		//Details of price
    		$details = "DETALLES DE LA COTIZACI�N";
    		$text6 = wordwrap($details, 80, "\n", false);
    		$token6 = strtok($text6, "\n");
    		
    		$page->setStyle($letraAzul);
    		while ($token6 != false) {
    			if ($y < 60) {
    				$pdf->pages[] = ($page = $pdf->newPage(Zend_Pdf_Page::SIZE_A4));
    				$page->setStyle($letraAzul);
    				$y = $page_height - 60;
    			} else {
    				$y-=20;
    				$page->drawText($token6, ($page->getWidth()/3), $y);
    				$page->setLineWidth(1.0);
    				$page->drawLine(60, $y-5, $page_width - 60, $y-5);
    				$y-=30;
    			}
    			$token6 = strtok("\n");
    		}

    		$page->setStyle($letraAzul);
    		
    		if($y < 60){
    			$pdf->pages[] = ($page = $pdf->newPage(Zend_Pdf_Page::SIZE_A4));
    			$page->setStyle($letraAzul);
    			$y = $page_height - 60;
    		}else{
    			$page->drawText('Nombre', 100, $y)
    			->drawText('Unidad', 260, $y)
    			->drawText('Cantidad', 350, $y)
    			->drawText('Precio', 425, $y);
    			$page->setLineWidth(2.0);
    			$page->drawLine(100, $y - 5, $page_width - 110, $y - 5);
    			$y-=20;
    		}

    		$page->setStyle($letraNegra);
    		
    			foreach ($productOrder as $details){
    				if ($y < 60) {
    					$pdf->pages[] = ($page = $pdf->newPage(Zend_Pdf_Page::SIZE_A4));
    					$page->setStyle($letraAzul);
    					$y = $page_height - 60;
    					$page->drawText('Nombre', 100, $y + 20)
    					->drawText('Unidad', 260, $y + 20)
    					->drawText('Cantidad', 350, $y + 20)
    					->drawText('Precio', 425, $y + 20);
    					$page->setStyle($letraNegra);
    					$page->setLineWidth(2.0);
    					$page->drawLine(100, $y + 15, $page_width - 110, $y + 15);
    				} else {
    					if(strlen($details['productName']) >= 20){
    						$e = substr($details['productName'], 0, 20)."(...)";
    					}else{
    						$e = $details['productName'];
    					}
    					$page->drawText($e, 100, $y);
	    				$page->drawText($details['unit'], 260, $y, 'UTF-8');
	    				$page->drawText($details['quantity'], 350, $y, 'UTF-8');
	    				$page->drawText('$' . number_format($details['price'], 2), 430, $y);
	    				$y -= 20;
    				}
    			}
    			
    			$page->setStyle($letraTitulo);
    			
    			if($y < 60){
    				$pdf->pages[] = ($page = $pdf->newPage(Zend_Pdf_Page::SIZE_A4));
    				$page->setStyle($letraTitulo);
    				$y = $page_height - 60;
    			}else{
    				$y -= 20;
    				$page->drawText('Subtotal: ', 100, $y);
    				$page->drawtext('$' . number_format($propuesta['subtotal'], 2), 200, $y);
    				$y -= 20;
    			}
    			
    			if($y < 60){
    				$pdf->pages[] = ($page = $pdf->newPage(Zend_Pdf_Page::SIZE_A4));
    				$page->setStyle($letraTitulo);
    				$y = $page_height - 60;
    			}else{
    				$y -= 10;
    				$page->drawText('Iva: ', 100, $y);
    				$page->drawtext('$' . number_format($propuesta['iva'], 2), 200, $y);
    				$y -= 20;
    			}
    			
    			if($y < 60){
    				$pdf->pages[] = ($page = $pdf->newPage(Zend_Pdf_Page::SIZE_A4));
    				$page->setStyle($letraTitulo);
    				$y = $page_height - 60;
    			}else{
    				$y -= 10;
    				$page->drawText('Total: ', 100, $y);
    				$page->drawText('$' . number_format($propuesta['total'], 2), 200, $y);
    				$y = 20;
    			}

    		//$page->drawText('Copyright My Company 2014. All rights reserved.', ($page->getWidth()/4), 15);
    		//footter
    
    		$page->setStyle($letraNegra);
    		
    		foreach($pdf->pages As $key => $page)
    		{
    			$page->drawText("Pagina " . ($key+1) . " de " . count($pdf->pages), 460, 10);
    		}
    		
    		foreach($pdf->pages As $key => $page)
    		{
    			$page->drawText("IOFractal Consultores en Desarrollo de Sistemas", 100, 10);
    		}

    		
    		header("content-type: application/pdf");
    		print($pdf->render());
    	}else{
    		$this->_redirect('/In/proposals/index');
    	}
    	exit();
    }
    
    public function createPdf($idSer)
    {
    	// create instance of Zend_Pdf() // crear instancia de Zend_Pdf ()
    	$pdf = new Zend_Pdf();
    	
    	$id = $idSer;   	
    	// create A4 page
    	$page = new Zend_Pdf_Page(Zend_Pdf_Page::SIZE_A4);
    	
    	$service = new Application_Service_Impl_ProposalsServiceImpl();
    	$propuesta = $service->getOrderById($id);
    	$productOrder = $service->getAllPurchaseByOrder($id, "po");
    	
    	$image = Zend_Pdf_Image::imageWithPath('../horusrobot/public/img/horusLogo.png');
    	// Draw image
    	$page->drawImage($image, 50, 750, 170, 790);
    	
    	//letras de color verde
    	$letraVerde = new Zend_Pdf_Style();
    	$letraVerde->setFillColor(new Zend_Pdf_Color_RGB(0, 10, 0));
    	$letraVerde->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_COURIER_BOLD_ITALIC), 12);
    	//letras de color azul
    	$letraAzul = new Zend_Pdf_Style();
    	$letraAzul->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 10));
    	$letraAzul->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD), 16);
    	//letras de color negro
    	$letraNegra = new Zend_Pdf_Style();
    	$letraNegra->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
    	$letraNegra->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), 12);
    	//letras de titulo
    	$letraTitulo = new Zend_Pdf_Style();
    	$letraTitulo->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
    	$letraTitulo->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD), 20);
    	//letras de titulo
    	$letraSubtitulo = new Zend_Pdf_Style();
    	$letraSubtitulo->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
    	$letraSubtitulo->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD), 16);
    	
    	$page_width = $page->getWidth();   // A4 : 595
    	$page_height = $page->getHeight(); // A4 : 842
    
    	try
    	{
 		
    		$page->setStyle($letraNegra);
    		$page->drawText('Folio ', 465, 740);
    		$page->drawText($propuesta['order_id'], 495, 740);
    		
    		$wrap_txt = "Cotizaci�n de Productos";
    		$text = wordwrap($wrap_txt, 80, "\r\n", false);//"<br />\n"
    		$token = strtok($text, "\n");
    		
    		$y = $page_height - 60;
    		$page->setStyle($letraTitulo);
    		
    		while ($token != false) {
    			if ($y < 60) {
    				$pdf->pages[] = ($page = $pdf->newPage(Zend_Pdf_Page::SIZE_A4));
    				$page->setStyle($letraTitulo);
    				$y = $page_height - 60;
    			} else {
    				$page->drawText($token, ($page->getWidth()/3), $y);
    				$y-=40;
    			}
    			$token = strtok("\n");
    		
    		}
    		
    		//fecha del dia en que es creada la minuta en pdf
    		$fecha = date('M j Y');
    		$hora = date('h:i:s');
    		//dibuja fecha
    		$page->setStyle($letraNegra);
    		$page->drawText($fecha, 465, 790);
    		$page->drawText($hora, 465, 780);

    		foreach($pdf->pages As $key => $page)
    		{
    			$page->setLineWidth(1.0);
    			$page->drawLine(60, $page_height - 35, $page_width - 60, $page_height - 35);
    			$page->setStyle($letraVerde);
    			$page->drawText('GRAD - Green Rapid Application Development', 60, $page_height - 20);
    			$page->drawText('powered by IOFractal', 390, $page_height - 20);
    		}		
    		
    		$otroText = "INFORMACI�N GENERAL";
    		$text2 = wordwrap($otroText, 80, "\n", false);
    		$token2 = strtok($text2, "\n");
    		
    		$page->setStyle($letraAzul);
    		while ($token2 != false) {
    			if ($y < 60) {
    				$pdf->pages[] = ($page = $pdf->newPage(Zend_Pdf_Page::SIZE_A4));
    				$page->setStyle($letraAzul);
    				$y = $page_height - 60;
    			} else {
    				$page->drawText($token2, ($page->getWidth()/3), $y);
    				$page->setLineWidth(1.0);
    				$page->drawLine(60, $y-5, $page_width - 60, $y-5);
    				$y-=30;
    			}
    			$token2 = strtok("\n");
    		}
    
    		$page->setStyle($letraNegra);

    		//name of company
    		$nameCompany = $propuesta['name_company'];
    		$text3 = wordwrap($nameCompany, 80, "\n", false);
    		$token3 = strtok($text3, "\n");
    		
    		$page->setStyle($letraNegra);
    		while ($token3 != false) {
    			if ($y < 60) {
    				$pdf->pages[] = ($page = $pdf->newPage(Zend_Pdf_Page::SIZE_A4));
    				$page->setStyle($letraNegra);
    				$y = $page_height - 60;
    			} else {
    				$page->setStyle($letraSubtitulo);
    				$page->drawText('Cliente: ', 60, $y);
    				$page->setStyle($letraNegra);
    				$page->drawText($token3, 120, $y, 'UTF-8');
    				$y-=20;
    			}
    			$token3 = strtok("\n");
    		}
    		
    		//date of price
    		$datePrice = $propuesta['start_date'];
    		$text4 = wordwrap($datePrice, 80, "\n", false);
    		$token4 = strtok($text4, "\n");
    		
    		$page->setStyle($letraNegra);
    		while ($token4 != false) {
    			if ($y < 60) {
    				$pdf->pages[] = ($page = $pdf->newPage(Zend_Pdf_Page::SIZE_A4));
    				$page->setStyle($letraNegra);
    				$y = $page_height - 60;
    			} else {
    				$page->setStyle($letraSubtitulo);
    				$page->drawText('Fecha de Cotizaci�n: ', 60, $y);
    				$page->setStyle($letraNegra);
    				$page->drawText($token4, 210, $y, 'UTF-8');
    				$y-=20;
    			}
    			$token4 = strtok("\n");
    		}
    		
    		//date of send
    		$dateSend = $propuesta['end_date'];
    		$text5 = wordwrap($dateSend, 80, "\n", false);
    		$token5 = strtok($text5, "\n");
    		
    		$page->setStyle($letraNegra);
    		while ($token5 != false) {
    			if ($y < 60) {
    				$pdf->pages[] = ($page = $pdf->newPage(Zend_Pdf_Page::SIZE_A4));
    				$page->setStyle($letraNegra);
    				$y = $page_height - 60;
    			} else {
    				$page->setStyle($letraSubtitulo);
    				$page->drawText('Fecha de Envio: ', 60, $y);
    				$page->setStyle($letraNegra);
    				$page->drawText($token5, 210, $y, 'UTF-8');
    				$y-=20;
    			}
    			$token5 = strtok("\n");
    		}
    		
    		$page->setStyle($letraSubtitulo);
    		
    		if($y < 60){
    			$pdf->pages[] = ($page = $pdf->newPage(Zend_Pdf_Page::SIZE_A4));
    			$page->setStyle($letraSubtitulo);
    			$y = $page_height - 60;
    		}else{
    			$page->drawText('Condiciones: ', 60, $y);
    			$y-=20;
    		}
    		
    		$page->setStyle($letraNegra);
    		//Conditions
    		$conditions = $propuesta['conditions'];
    		$text7 = wordwrap($conditions, 80, "\n", false);
    		$token7 = strtok($text7, "\n");
    		 
    		$page->setStyle($letraNegra);
    		while ($token7 != false) {
    			if ($y < 60) {
    				$pdf->pages[] = ($page = $pdf->newPage(Zend_Pdf_Page::SIZE_A4));
    				$page->setStyle($letraNegra);
    				$y = $page_height - 60;
    			} else {
    				$page->drawText($token7, 100, $y, 'UTF-8');
    				$y-=20;
    			}
    			$token7 = strtok("\n");
    		}
    		
    		//Details of price
    		$details = "DETALLES DE LA COTIZACI�N";
    		$text6 = wordwrap($details, 80, "\n", false);
    		$token6 = strtok($text6, "\n");
    		
    		$page->setStyle($letraAzul);
    		while ($token6 != false) {
    			if ($y < 60) {
    				$pdf->pages[] = ($page = $pdf->newPage(Zend_Pdf_Page::SIZE_A4));
    				$page->setStyle($letraAzul);
    				$y = $page_height - 60;
    			} else {
    				$y-=20;
    				$page->drawText($token6, ($page->getWidth()/3), $y);
    				$page->setLineWidth(1.0);
    				$page->drawLine(60, $y-5, $page_width - 60, $y-5);
    				$y-=30;
    			}
    			$token6 = strtok("\n");
    		}

    		$page->setStyle($letraAzul);
    		
    		if($y < 60){
    			$pdf->pages[] = ($page = $pdf->newPage(Zend_Pdf_Page::SIZE_A4));
    			$page->setStyle($letraAzul);
    			$y = $page_height - 60;
    		}else{
    			$page->drawText('Nombre', 100, $y)
    			->drawText('Unidad', 260, $y)
    			->drawText('Cantidad', 350, $y)
    			->drawText('Precio', 425, $y);
    			$page->setLineWidth(2.0);
    			$page->drawLine(100, $y - 5, $page_width - 110, $y - 5);
    			$y-=20;
    		}

    		$page->setStyle($letraNegra);
    		
    			foreach ($productOrder as $details){
    				if ($y < 60) {
    					$pdf->pages[] = ($page = $pdf->newPage(Zend_Pdf_Page::SIZE_A4));
    					$page->setStyle($letraAzul);
    					$y = $page_height - 60;
    					$page->drawText('Nombre', 100, $y + 20)
    					->drawText('Unidad', 260, $y + 20)
    					->drawText('Cantidad', 350, $y + 20)
    					->drawText('Precio', 425, $y + 20);
    					$page->setStyle($letraNegra);
    					$page->setLineWidth(2.0);
    					$page->drawLine(100, $y + 15, $page_width - 110, $y + 15);
    				} else {
    					if(strlen($details['productName']) >= 20){
    						$e = substr($details['productName'], 0, 20)."(...)";
    					}else{
    						$e = $details['productName'];
    					}
    					$page->drawText($e, 100, $y);
	    				$page->drawText($details['unit'], 260, $y, 'UTF-8');
	    				$page->drawText($details['quantity'], 350, $y, 'UTF-8');
	    				$page->drawText('$' . number_format($details['price'], 2), 430, $y);
	    				$y -= 20;
    				}
    			}
    			
    			$page->setStyle($letraTitulo);
    			
    			if($y < 60){
    				$pdf->pages[] = ($page = $pdf->newPage(Zend_Pdf_Page::SIZE_A4));
    				$page->setStyle($letraTitulo);
    				$y = $page_height - 60;
    			}else{
    				$y -= 20;
    				$page->drawText('Subtotal: ', 100, $y);
    				$page->drawtext('$' . number_format($propuesta['subtotal'], 2), 200, $y);
    				$y -= 20;
    			}
    			
    			if($y < 60){
    				$pdf->pages[] = ($page = $pdf->newPage(Zend_Pdf_Page::SIZE_A4));
    				$page->setStyle($letraTitulo);
    				$y = $page_height - 60;
    			}else{
    				$y -= 10;
    				$page->drawText('Iva: ', 100, $y);
    				$page->drawtext('$' . number_format($propuesta['iva'], 2), 200, $y);
    				$y -= 20;
    			}
    			
    			if($y < 60){
    				$pdf->pages[] = ($page = $pdf->newPage(Zend_Pdf_Page::SIZE_A4));
    				$page->setStyle($letraTitulo);
    				$y = $page_height - 60;
    			}else{
    				$y -= 10;
    				$page->drawText('Total: ', 100, $y);
    				$page->drawText('$' . number_format($propuesta['total'], 2), 200, $y);
    				$y = 20;
    			}

    		//$page->drawText('Copyright My Company 2014. All rights reserved.', ($page->getWidth()/4), 15);
    		//footter

    		// add current page to pages array // a�adir la p�gina actual a las p�ginas array
    		$pdf->pages[] = $page;
    		

    		$page->setStyle($letraNegra);
    		
    		foreach($pdf->pages As $key => $page)
    		{
    			$page->drawText("Pagina " . ($key+1) . " de " . count($pdf->pages), 460, 10);
    		}
    		
    		foreach($pdf->pages As $key => $page)
    		{
    			$page->drawText("IOFractal Consultores en Desarrollo de Sistemas", 100, 10);
    		}
    		
    		// save the document // guadar el documento
    		//$pdf->save("hola" . ".pdf");
    		 
    	} catch (Zend_Pdf_Exception $e) {
    		// log $e->getMessage() error
    	} catch (Exception $e) {
    		// log $e->getMessage() error
    	}
    	// return document contents to be mailed // regresar contenido del documento que se envia por correo
    	return $pdf->render();
    }
    
    /*public function sendmailAction(){
    	
    	$idSer = $this->_getParam('id');
    	$form = new Application_Form_EmailProposalForm();
    	$service = new Application_Service_Impl_ProposalsServiceImpl();
    	$propuesta = $service->getOrderById($idSer);
    	$nameCom = $propuesta['name_company'];
    	
    	if($idSer){
    		
    		if($this->getRequest()->isPost()){
    			$formData = $this->getRequest()->getPost();
    			
    			if($form->isValid($formData)){
    				
    				$config = array('auth' => 'login', 'username' => 'luisara18@gmail.com', 'password' => 'joseluis*araceli18', 'ssl' => 'tls', 'port' => 587);
    				$transport = new Zend_Mail_Transport_Smtp('smtp.gmail.com', $config);
    				
    				$mail = new Zend_Mail('utf-8');
    				
    				$pdf = $this->createPdf($idSer);
    				
    				/* adjuntar PDF y especificar el tipo MIME, codificaci�n y los datos de disposici�n
    				 para manejar archivos PDF*/
    				
    				/*$attachment = $mail->createAttachment($pdf);
    				$attachment->type = 'application/pdf';
    				$attachment->disposition = Zend_Mime::DISPOSITION_ATTACHMENT;
    				$attachment->encoding = Zend_Mime::ENCODING_BASE64;
    				$h = $attachment->filename = $nameCom.".pdf";//$nameCom
    				
    				// A�adimos un asunto
    				$mail->setSubject($form->getValue('asunto'));
    				//Cuenta de correo del remitente y nombre del remitente amostrar
    				$mail->setFrom($form->getValue('email'));
    				//A�adimos un destinatario
    				$mail->addTo($form->getValue('destino'));
    				// A�adimos cuerpo de mensaje
    				$mail->setBodyHtml(
    					"<h4>" . $form->getValue('mensaje') . "</h4>"
    				);
    				
    				try {
    					$mail->send($transport);
    					$this->_redirect('/proposals/index');
    				} catch (Zend_Exception $e) {
    					echo $e->getMessage();
    				}
    			}
    		}
    		
    	}else{
    		$this->_redirect('/proposals/index');
    	}
    	$this->view->form = $form;
    }*/
    
    public function sendmailAction(){
    	$id = $this->_getParam("id");
    	 
    	if(isset($id)){
    
    		$price = $id;
    
    		if($price != null){
    			header('Content-type: application/json; charset=iso-8859-1');
    			 
    			$config = array(
    					'auth' => 'login',
    					'username' => 'luisara18@gmail.com',
    					'password' => 'joseluis*araceli18',
    					'ssl' => 'tls',
    					'port' => 587);
    			 
    			$transport = new Zend_Mail_Transport_Smtp('smtp.gmail.com', $config);
    			 
    			$mail = new Zend_Mail("UTF-8"); //Usaremos codificaci�n UTF-8 para el mensaje
    			 
		    	$service = new Application_Service_Impl_ProposalsServiceImpl();
		    	$propuesta = $service->getOrderById($id);
		    	$nameCom = $propuesta['name_company'];
		    	$body = "Adjunto archivo PDF de la cotizaci&oacute;n generada el dia " . $propuesta['start_date'];
    			 
    			$pdfDocument = $this->createPdf($id);
    			 
    			$mail->setFrom("luisara18@gmail.com", "Calat Sistemas y Comunicaciones")
    			//Cuenta de correo del remitente y nombre del remitente amostrar
    			->setSubject("Cotizaci�n de Productos") //Asunto
    			->setBodyHtml("<div>
    								<h1>$body</h1>
    								<p>$body</p>
    							</div>")
        							//Cuerpo del mensaje indicando que ser� HTML y no texto plano. Usar�amos setBodyText() para texto plano
    			->addTo("luisara18@gmail.com"); //A�adimos un destinatario
    			 
    			$attachment = $mail->createAttachment($pdfDocument);
    			$attachment->type = 'application/pdf';
    			$attachment->disposition = Zend_Mime::DISPOSITION_ATTACHMENT;
    			$attachment->encoding = Zend_Mime::ENCODING_BASE64;
    			$h = $attachment->filename = $nameCom.".pdf";
    
    			try {
    				$mail->send($transport);
    			} catch (Zend_Exception $e) {
    				echo $e->getMessage();
    			}
    			 
    			echo Zend_Json::encode(array("response" => "ok" , "data" => $price));
    		}else{
    			echo Zend_Json::encode(array("response" => "false"));
    			 
    		}
    		exit;
    	}
    }
}




