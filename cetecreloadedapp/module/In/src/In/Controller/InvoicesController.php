<?php

namespace In;

use BaseController;
use Application_Service_Impl_XmlInvoicesImpl;
use DOMDocument;
use zend_json;
use Zend_Json;
use InvoiceServiceImpl;
use Application_Service_Impl_PreferencesServiceImpl;
use InvoicesEntity;
use Application_Form_InvoiceForm;
use ElectronicInvocesServiceImpl;
use Application_Service_Impl_ProductServiceImpl;
use Concepto;
use DatosCFD;
use Application_Util_Tools;
use Receptor;
use Impuesto;
use CustomersServices;
use Application_Service_Impl_SalesServiceImpl;
use Zend_Session_Namespace;
use zend_Json;
use Zend_Pdf;
use Zend_Pdf_Font;
use Zend_Pdf_Image;
use InvoiceDao;
use Zend_Pdf_Color_HTML;
use InvoiceService;

 APPLICATION_PATH . '/controllers/BaseController.php';
require_once 'Zend/Pdf.php';
require_once 'Zend/Loader/Autoloader.php';
require APPLICATION_PATH . "/services/impl/InvoiceServiceImpl.php";
require APPLICATION_PATH . "/forms/Application_Form_Invoice.php";
require_once APPLICATION_PATH . "/entities/InvoicesEntity.php";
require APPLICATION_PATH . "/services/impl/ElectronicInvocesServiceImpl.php";
require APPLICATION_PATH . "/services/impl/ProductServiceImpl.php";
include_once APPLICATION_PATH . '/models/CxcDao.php';
include_once APPLICATION_PATH . '/daos/InvoiceDao.php';
include_once APPLICATION_PATH . '/util/StaticTools.php';
include_once APPLICATION_PATH . '/services/impl/PreferencesServiceImpl.php';
include_once APPLICATION_PATH . '/services/CustomersServices.php';
include_once APPLICATION_PATH . '/services/impl/CxcServiceImpl.php';
include_once APPLICATION_PATH . '/../libs/libNuSoap/nusoap.php';
include_once APPLICATION_PATH . '/../libs/libNuSoap/Concepto.php';
include_once APPLICATION_PATH .'/../libs/libNuSoap/DatosCFD.php';
include_once APPLICATION_PATH .'/../libs/libNuSoap/Impuesto.php';
include_once APPLICATION_PATH .'/../libs/libNuSoap/Receptor.php';

class InvoicesController extends BaseController{
		
	function init(){
		
		BaseController::init();
	}

	public function xmlAction(){
		
		$xmlService = new Application_Service_Impl_XmlInvoicesImpl();
		
		 if ($this->getRequest()->isPost()) {
		 	
		 	$del = $this->getParam("del");
		 	
		 	if($del){
		 		 $id_xml = $this->getParam('erase');
		 		 
		 		$xmlService->deleteXml($id_xml);
		 		exit;
		 	}else{
		 		 	
					 	define("maxUpload", 129441);
					 	define("fileName", 'xml_');
				 		$msjError = "";
						$status = "fail";
						$arrayNameFiles = array();
		        		$er = "";
		        		$c = 0;
		        		$c2 = 0;
		        		$codeStat = 0;
		        		
				 		$files = $_FILES['files'];//archivos subidos
				 		
				 		
				 		if($files != null){//valida que haya seleccionado algun archivo
				 			$countErrors = 0;
				 			foreach($files['error'] as $file){//cuenta cuantos archivos tiener error
				 				if($file != 0){
				 					$countErrors++;
				 				}
				 			}
				 			
				 			if($countErrors > 0){//valida que los archivos no hayan tenido ningun error
				 				$msjError = "Algunos de los archivos tiene error";
				 				$codeStat = "02";//error ena lgun archivo al cargar
				 			}else{
				 				
				 				$countTamanios = 0;
				 				
				 				foreach($files['size'] as $file){//valido que cada archivo cumpla con el tamaño maximo
				 					if($file < 0 && $file > maxUpload){
				 						$countTamanios ++;
				 					}
				 				}
				 				
				 				if($countTamanios > 0){
				 					$msjError = "Algun archivo excede el limite maximo de subida, " + maxUpload;
				 					$codeStat = "03";//exsedio tamaño de carga
				 				}else{
				 					$conFilesNoXml = 0;
				 						
				 					foreach($files['type'] as $file){//valida que todos los archivos subidos sean xml
				 						if($file != "text/xml"){
				 							$conFilesNoXml ++;
				 						}
				 					}
				 						
				 					if($conFilesNoXml > 0){//valida que solo se hayan seleccionado archivos .xml
				 						$msjError = "Solo se permiten archivos .xml";
				 						$codeStat = "04";//no se selecciono solo archivos .xml
				 					}else{
				 						
				 						//$xml = file_get_contents($_FILES['userfile']['tmp_name']);//leer archivo
				 						//$xml = simplexml_load_file($_FILES['userfile']['tmp_name']);//transforma xml en array
		        						libxml_use_internal_errors(true);
		        						$countValidate = 0;
		        										
				 						foreach($files['tmp_name'] as $file){
				 							$feed = new DOMDocument();
		        							$feed->preserveWhitespace = false;
		        							$feed->load($file);
		        						 
		        					 		if(@($feed->schemaValidate(APPLICATION_PATH . "/modules/In/controllers/sat.xsd"))){
		        								$countValidate = $countValidate;
		        							}else{
		        								$countValidate ++;
		        								
		        								$errors = libxml_get_errors();
		        								foreach($errors as $error) {
			        								$mE = array("name" => $files['name'][$c], "error"  =>  $error->message);       								       								
			        								
				        							array_push($arrayNameFiles, $mE);
				        
		        								}
		        								$c++;
				 						  }
				 						}
				 						
				 						if($countValidate > 0){//valida que todos loarchivos subidos cumplan el contrato de xsd
				 							$msjError = "Algunos de los arhivos no cumple con el contrato del XSD: ";
				 							$codeStat = "05";//alguno de los archivos no cumple el contrato
				 						}else{
				 							
				 							$xmls = array();
				 							$xmlsArray = array();
				 							
				 							foreach($files['tmp_name'] as $file){
				 								
				 								$xmls['name'] = $files['name'][$c2];
				 								$xmls['xml'] = file_get_contents($file);
				 								$xmls['date'] = date("Y-m-d");
				 								
				 								$xmlsSave = $xmlService->saveXml($xmls);
				 								
				 								array_push($xmlsArray, $xmlsSave);
				 								
				 								$c2 ++;
				 							}
				 							
			 								$status = "ok";
			 								$codeStat = "00";
					 						$msjError = "Carga correcta de archivos";
					 						$arrayNameFiles = $xmlsArray;
				 						}
				 						
				 					}
				 				}
				 				
				 			}
				 			
				 		}else{
				 			$msjError = "Debes seleccionar al menos un archivo";
				 			$codeStat = "01";//no se selecciono ningun archivo
				 		}
				 		
					 	echo zend_json::encode(array(
					 			"response" => $status,
					 			"code" => $codeStat,
					 			"msj" => $msjError,
					 			"xmls" => $arrayNameFiles,
					 	));
					 	exit;
		 		}
		 }else{
		 	
		 	$gXi = $this->getParam("gXi");
		 	
		 	if(isset($gXi) && $gXi == "1"){
		 		$id_xml = $this->getParam("id_xml");
		 		
		 		$xmlById = $xmlService->getXmlById($id_xml);
		 		
		 		$xml = simplexml_load_string($xmlById[0]['xml']);//transforma xml en array
		 		echo Zend_Json::encode($xml);
		 		print_r($xmlById);
		 		print_r($xml);
		 		exit;
		 	}else{
		 		$xmlList = $xmlService->getAllXml();
		 		$this->view->xml = $xmlList;
		 	}

		 }
		
	}

	public function indexAction(){
		$invoiceService = new InvoiceServiceImpl();
		$servicePref = new Application_Service_Impl_PreferencesServiceImpl();
		$idInvoice = $this->_getParam('gIbI');
		$res = $this->_getParam('respond');
 		
 				
		if(isset($idInvoice) != null){
			$purchaseInvoice = $invoiceService->getPurchaseByIdInvoice($idInvoice);
			if($purchaseInvoice != null){
				echo Zend_Json::encode(array('response' => 'success', 'data' => $purchaseInvoice));
			}else{
				echo Zend_Json::encode(array('response' => 'fail', 'data' => 'No existen compras.'));
			}
			exit;
		}else{
			// este obtiene los pagos de el proyecto
			$payments = $invoiceService->listar();

			$invoiceRowC = array();
			//	$invoiceRowI = array();
			$invoiceTable = array();

			//obtienen datos de los proyectos.
			foreach($payments as $key => $payment){
				if($payment['state'] == 1 ){
					$invoiceRowC['ID'] = $payment['ID'] . "-C";
				}else{
					$invoiceRowC['ID'] = $payment['ID'] . "-F";
				}
				$invoiceRowC['date_invoice'] = $payment['date_invoice'];
				$invoiceRowC['amount'] = $payment['amount'];
				$invoiceRowC['tax'] =  ($payment['amount'] * $payment['taxpercentage']) / 100;
				$invoiceRowC['total'] = $payment['total'];
				$invoiceRowC['observation'] = $payment['observation']; // . $payment->nPay . " / " . $payment->numberofpayments . " del proyecto " . $payment->name_project;
				$invoiceRowC['concept'] = $payment['concept'];
				$invoiceTable[] = $invoiceRowC;
			}
			/*
			 foreach($payments as $key => $payment){
			 $invoiceRowC['ID'] = $payment['ID'] . "-F";
			 $invoiceRowC['date_invoice'] = $payment['date_invoice'];
			 $invoiceRowC['amount'] = $payment['amount'];
			 $invoiceRowC['taxpercentage'] = $ivaPer =  $payment['taxpercentage'];
			 $invoiceRowC['total'] = $payment['total'];
			 $invoiceRowC['observation'] = "pago "; // . $payment->nPay . " / " . $payment->numberofpayments . " del proyecto " . $payment->name_project;
			 $invoiceRowC['concept'] = $payment['concept'];
			 $invoiceTable[] = $invoiceRowC;
			 }*/
			/*
			 foreach($invoices as $key => $row){
			 $invoiceRowI['ID'] = $row['ID'] . "-F";
			 $invoiceRowI['date_invoice'] = $row['date_invoice'];
			 $invoiceRowI['amount'] = $row['amount'];
			 $invoiceRowI['taxpercentage'] = $row['taxpercentage'];
			 $invoiceRowI['total'] = $row['total'];
			 $invoiceRowI['observation'] = $row['observation'];
			 $invoiceRowI['concept'] = "";
			 $invoiceTable[] = $invoiceRowI;
			 }
			 */

			if(isset($res) && $res){
				$entity = new InvoicesEntity();
				$entity->setDate_invoice($this->_getParam('date'));
				$entity->setConcept("Pago de proyecto ".$this->_getParam('project'));
				$entity->setAmount($this->_getParam('amount'));
				$entity->setTax($this->_getParam('tax'));
				$entity->setTotal($this->_getParam('total'));
				$entity->setCustomer($this->_getParam('client'));
				$entity->setTaxpercentage($this->_getParam('taxpercent'));
				$entity->setState(1);
				$invoiceService->createInvoice($entity);
				echo Zend_Json::encode(array('request' => 'ok'));exit;
			}

			$this->view->invoices = $invoiceTable;
		}

	}

	/*
	 * Metodo que manda a llamar el formulario para crear una factura
	 */
	public function createviewAction(){
		$form = new Application_Form_InvoiceForm("create");
		$service = new ElectronicInvocesServiceImpl();
		$productsService = new Application_Service_Impl_ProductServiceImpl();
		$invoiceService = new InvoiceServiceImpl();
		if ($this->getRequest()->isPost()) {		
		$formData = $this->getRequest()->getParams();
		
		if ($formData['hiden']!= 'yes'){
			
			if($formData != null){
		$tabla = explode('_+_',$formData['purchaseItems']);
						
		$conceptoDatosArray = array ();
		
		for($i = 1; $i < count($tabla); $i++){
			$concepto = new Concepto();
			$row = explode('&',$tabla[$i-1]);
			
			$values = array();
			for($j = 0; $j < count($row); $j++){
				$value = explode('=',$row[$j]);
				$values[$j]= $value[1];
			}
			$concepto->setCantidad($values[0]);
			$concepto->setDescripcion($values[1]);
			$concepto->setUnidad($values[2]);
			$concepto->setPrecio($values[3]);
			$concepto->setImporte($values[4]);
			$conceptoDatosArray[$i-1] = $concepto;
		}
		

		$service->buidConcepts($conceptoDatosArray);

		
		$datosCFD = new DatosCFD();
		
		$taxSign = Application_Util_Tools::notCurrency($formData['subtotal']);
		$totalP = Application_Util_Tools::notCurrency($formData['total']);
				
		$datosCFD->setFormadePago("Pago en una sola exhibicion");
		$datosCFD->setMoneda("MXP");
		$datosCFD->setSubtotal($taxSign);
		$datosCFD->setTotal($totalP);
		$datosCFD->setSerie("A");
		$datosCFD->setTipodeComprobante("F");
		$datosCFD->setMetododePago("Efectivo");
		$datosCFD->setLugarDeExpedicion("Mexico DF");
		$datosCFD->setFolioFiscalOriginal("654654654");
		$datosCFD->setSerieFolioFiscalOriginal("A");
		$datosCFD->setFechaFolioFiscalOriginal("2012-25-54");
		$datosCFD->setMontoFolioFiscalOriginal("1000.00");
		$datosCFD->setMensajePDF($formData['observations']);
		
		$service->buildDataCFD($datosCFD);
	
		$calleNumero = explode('-',$formData['streetAndNumber']); 
		$calle = $calleNumero[0];
		$numero = $calleNumero[1];
		// Datos del cliente receptor
		$Receptor = new Receptor();
		
		$Receptor->setRFC($formData['businessRFC']);
		$Receptor->setRazonSocial($formData['businessName']);
		$Receptor->setPais("MEXICO");
		$Receptor->setCalle($calle);
		$Receptor->setNumExt($numero);
		$Receptor->setColonia($formData['neighborhood']);
		$Receptor->setMunicipio($formData['delegation']);
		$Receptor->setCiudad($formData['delegation']);
		$Receptor->setCP($formData['postalCode']);
		$Receptor->setEstado($formData['state']);
		
		$service->buildReceiver($Receptor);		
		
			
		/*Impuestos*/
		$impuesto1 = new Impuesto();
		$impuesto1->setImporte($taxSign);
		$impuesto1->setTasa(16);
		$impuesto1->setTipoImpuesto("IVA");
		
		
		$impuestoDatosArray = array ($impuesto1);
		
		$service->buildTaxes($impuestoDatosArray);
		/*Metodo que llama al WS y crea los archivos PDF y XML*/
		
		$service->generateCFD();
		
		if(true){
			echo Zend_Json::encode(array('response' => 'ok'));
		}else{
			echo Zend_Json::encode(array('response' => 'fail'));
		}
			
		exit;
		
		}else{
		$this->view->form = $form;
		}
		}else{
			$form->subtotal->setValue($formData['subtotal']);
			$form->tax->setValue($formData['tax']);
			$form->total->setValue($formData['total']);
			$idProds = explode(',',$formData['idsProds']);
			
			$identifier = 0;
			$count = 1;
			$produtcs = array();
			$count2 = 0;
			$output = array();
			
			for($i=0;$i<count($idProds);$i++){
				$identifier = $idProds[$i];
				if($i > 0){
					if($identifier == $idProds[$i-1]){
						$count++;
					}else{
						$produtcs[$idProds[$i-1]]= array('cantidad'=> $count) ;
						$output[$count2++]=  array('id'=>$idProds[$i-1],
												   'cantidad'=> $count);
						$count = 1;
					}
				}
			}
			
			
			for($i=0;$i<count($output);$i++){
				$id = $output[$i]['id'];
				$cantidad = $output[$i]['cantidad'];
				$prodc = $productsService->getProductById($id);
				$measurig = $productsService->getMeasuringById($prodc['measuring_fk_id']);
				$total = $prodc['p_price'] * $cantidad;			
		
				echo"<script>
					$(document).ready(function(){
						$('#invoicedetail').dataTable(
 							{
 								bFilter:false,
 								bRetrieve:true
 							}).fnAddData( [
 								'".$cantidad."',
 								'".$prodc['p_name']."',
 								'".$measurig."',
 								'".$prodc['p_price']."',
 								'".$total."'
 							] );		
						   });
				</script>";
			}
			
		}
		}
		$this->view->form = $form;
	}

	public function updateviewAction(){
		$form = new Application_Form_InvoiceForm("update");
		$service = new InvoiceServiceImpl();
		 
		$id = $this->_getParam('ID');
		 
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			if($formData != null){
				$invoice = $service->updateInvoice($formData);
				if($invoice == "true"){
					echo Zend_Json::encode(array('response' => 'ok'));
				}else{
					echo Zend_Json::encode(array('response' => 'fail'));
				}
				exit;
			}
		} else {
			if (isset($id)) {
				$idReal = explode("-", $id);
				/*if($idReal[1] == "C"){
				 //echo "CXC";
				 }else*/
				if ($idReal[1] == "F"){
					//echo "INV";
					$invoicesF = $service->getInvoiceById($idReal[0]);
					$form->populate($invoicesF);
				}
			}else{
				echo Zend_Json::encode(array('response' => 'fail'));
				exit;
			}
			$this->view->form = $form;
		}
	}

	/**
	 * Method for delet a invoice
	 */
	public function deleteviewAction(){
		$id = $this->_getParam('erase');
		$idReal = explode("-", $id);
		$service = new InvoiceServiceImpl();
		$del = $service->deleteInvoice($idReal[0]);
		 
		echo $del;
		exit;
	}//end of delete
	/**
	*  Para modificar los valores de una entrada al hacer click en modificar
	*/

	public function detailsAction(){

		$invoiceService = new InvoiceServiceImpl();
		$service = new InvoiceServiceImpl();
		$id = $this->_getParam("ID");
		if (isset($id)) {
			$idReal = explode("-", $id);
			 
			if ($idReal[1] == "F"){
				//echo "INV";
				$facturas = $service->getInvoiceById($idReal[0]);
				$product = $service->getPurchaseByIdInvoice($idReal[0]);

				if($facturas['customer'] == "pC"){
					$this->view->customer = $facturas['otherCustomer'];
					$this->view->customerDirection = $facturas['address_customer'];
					$this->view->rfc = $facturas['rfc'];
					$this->view->dateof = $facturas['date_invoice'];
					$this->view->subtotal = $facturas['subtotal'];
					$this->view->tax = $facturas['tax'];
					$this->view->total = $facturas['total'];
				}else{
					$CustomerService = new CustomersServices();
					$customer = $CustomerService->getCustomerById($facturas['customer']);
					 
					$this->view->customer = $customer['name_company'];
					$this->view->customerDirection = $facturas['address_customer'];
					$this->view->rfc = $customer['rfc'];
					$this->view->dateof = $factutas['date_invoice'];
					$this->view->subtotal = $facturas['subtotal'];
					$this->view->tax = $facturas['tax'];
					$this->view->total = $facturas['total'];
				}

				$this->view->invoices = $facturas;
				$this->view->producto = $product;
					
			}elseif ($idReal[1] == "C"){
				/*$service = new CxcServiceImpl();
				 $pays = $service->getDatePaymentByProject();

				 $idProject = $pays['projectId'];
				 print_r($pays);
				 exit();

				 $this->view->producto = $pays[$idReal[0]];*/

				 
				 
			}

			 

		}
	}
	
	function searchcustomerAction(){
		$customer = $this->_getParam('customer');
		$name_startsWith = $this->_getParam('term');
		$carrshop = $this->_getParam('carrshop');
		$customerslist = new CustomersServices();
		$serviceSales = new Application_Service_Impl_SalesServiceImpl();
		$idsProdSession = new Zend_Session_Namespace('idsProdSession');
	
		if(isset($customer)){
				
			$customer = $customerslist->getCustomersByKey($customer);
				
			if($customer == null){
				echo Zend_Json::encode(array("response" => "fail", "data" => "No Existe el Producto o ya no hay en existencias."));
			}else{
				echo Zend_Json::encode(array("response" => "ok", "data" => $customer));
			}
		}else if(isset($carrshop) && $carrshop == "2"){
				
			if(isset($idsProdSession->ids)){
	
	
				$id_products = $idsProdSession->ids;
	
				$products = explode("," ,trim($id_products, ","));
					
				$listProd = array();
	
				foreach ($products as $product){
					$box = $customerslist->getCustomersByKey($product);
					$listProd[] = $box;
				}
	
				echo zend_Json::encode($listProd);
				exit;
			}
				
			/*$id_user = $this->getCurrentUserId();
				$inCarrShop = $serviceSales->getProductsInCar($id_user);
				
			if($inCarrShop != null){
			echo Zend_Json::encode(array('response' => 'ok', 'data' => $inCarrShop ));
			}else{
			echo Zend_Json::encode(array('response' => 'fail', 'data' => 'No hay productos en el carrito.' ));
			}
			exit;*/
		}elseif (isset($name_startsWith)){
				
			$found = $customerslist->getCustomersStartWith($name_startsWith);
			echo json_encode($found);
			exit;
		}
	
		exit;
	}
	

	public function pdfviewAction(){
		//habilitamos el layout para la factura
		$this->_helper->layout->disableLayout();
		//instanciamos un objeto de la calse Zend_Pdf para crear la factura
		$pdf = new Zend_Pdf();
		//creamos una pagina
		$pdf->pages[] = ($page = $pdf->newPage('A4'));
		//Establesemos el tipo de letra para el titulo
		$fontTitle = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_COURIER_BOLD_ITALIC);
		$page->setFont($fontTitle, 20);
		$page->drawText('IOFractal', 210, 820);
		$fontLema = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_ITALIC);
		$page->setFont($fontLema, 12);
		$page->drawText('Consultores en Desarrollo', 210, 800);
		$page->drawText('de Sistemas S.A. de C.V.', 240, 780);
		$page->setLineWidth(0.5)
		->drawLine(0, 769, 600, 769);
		$fontAddress = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_ITALIC);
		$page->setFont($fontAddress, 15);
		$page->drawText('Direccion:', 11, 745);
		$page->drawText('Av. de los Montes No. 71', 10, 705);
		$page->drawText('Col. Partales Suv.', 10, 685);
		$page->drawText('Del. Benito Juarez', 10, 665);
		$page->drawText('C.P. 03300 Mex. D.F.', 10, 645);

		$page->drawText('Fecha:', 412, 520);
		$page->drawText('No. de Factura:', 411, 745);

		$page->drawText('No. de Cliente:', 411, 725);
		$page->drawText('Cliente:', 50, 580);
		$page->drawText('___________Direccion:__________', 50, 550);
		//$page->drawText('RFC:', 312, 580);
		$page->drawText('Proyecto:', 312, 580);
		$page->drawText('Lugar:', 312, 520);
		$page->drawText('Mexico D.F.', 312, 500);
		$arbol = Zend_Pdf_Image::imageWithPath('img/IOFractal.png');
		$horus = Zend_Pdf_Image::imageWithPath('img/IOFractal.png');
		// write image to page
		/*
		* 1 param: de izquierda para derecha
		* 2 param: de abajo para arriba
		* 3 param: de derecha a izquierda
		* 4 param: de arriba para abajo
		*/
		$page->drawImage($arbol, 210, 690, 380, 770);
		$page->drawImage($horus, 40, 30, 140, 60);
		//Mandamos a llamar al parametro
		$id = $this->_getParam('ID');
		/*************************************************************
		 * Instanciamos los metodos que necesitamos para la consulta**
		 * ***********************************************************
		 */
		$cxc = new InvoiceDao();
		$Idfac = $cxc->getIdInvoiceByPayment($id);
		$concepto = $cxc->getConceptByIdPayment($id);
		$Namecustomer = $cxc->getCustomerByIdPayment($id);
		$idCustomer = $cxc->getIdCustomerByIdPayment($id);
		$amount = $cxc->getAmountByIdPayment($id);
		$iva = $cxc->getTaxByIdPayment($id);
		$total = $cxc->getTotalByIdPayment($id);
		$porcentage = $cxc->getTaxpercentageByIdPayment($id);
		$street = $cxc->getStreetOfCustomerByIdPayment($id);
		$date = $cxc->getDateInvoiceByPayment($id);
		$project = $cxc->getNameProjectByIdPayment($id);
		$number = $cxc->getNumberOfCustomerByIdPayment($id);
		$edo = $cxc->getStateOfCustomerByIdPayment($id);
		$distrito = $cxc->getDistrictOfCustomerByIdPayment($id);
		/*(00,00)
		 * parametro 1: de izquierda a derecha
		 * parametro 2: de abajo a arriba
		 */
		$d=20;
		$fecha=date('d/m/y');
		$fontT = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES);
		$page->setFont($fontT, 12)
		->setFillColor(new Zend_Pdf_Color_HTML('navy'))
		->drawText($fecha, 412, 500)//Fecha del sistema
		->drawText($Namecustomer, 100, 580)//Nombre del Cliente
		->drawText("Edo.", 50, 530)
		->drawText($edo.",    Municipio:", 80, 530)//estado del cliente (direccion)
		->drawText($distrito.",", 200, 530)//Municipio del cliente (direccion)
		->drawText("Calle:", 50, 510)
		->drawText($street."   Num:   ", 80, 510)//Calle del Cliente (direccion)
		->drawText($number, 170, 510)//Numero del cliente (direccion)
		//->drawText($rfc, 400, 580)//RFC del cliente (empresa)
		->drawText($project, 400, 580)//nombre del proyecto
		->drawText($Idfac, 511, 745)//Folio de la factura
		->drawText($idCustomer, 511, 725)//Número del cliente
		->drawText($date, 40, 390)//fecha del proyecto
		->drawText($concepto, 160, 390)//concepto
		->drawText($amount, 475, 390)//monto
		->drawText($amount, 475, 180)//Subtotal
		->drawText($iva, 475, 160)//iva
		->drawText($porcentage, 475, 130)//porcentaje
		->drawText("%", 487, 130)
		->drawText($total, 475, 110);//total
		//dibujando la table
		//lineas Horizontal superior para la tabla
		$page->setLineWidth(0.5)
		->drawLine(30, 475, 550, 475);
		//columns
		$fontTableTitle = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_ITALIC);
		$page->setFont($fontTableTitle, 12);
		$page->drawText('Fecha de factura:', 35, 450);
		$page->drawText('Concepto:', 225, 450);
		$page->drawText('Cantidad:', 455, 450);
		//linea Horizontal inferior para la tabla
		$page->setLineWidth(0.5)
		->drawLine(30, 200, 550, 200);
		//lineas Vertical lado izquierdo para la tabla
		$page->setLineWidth(0.5)
		->drawLine(30, 475, 30, 200);
		//linea Vertical lado derecho para la tabla
		$page->setLineWidth(0.5)
		->drawLine(550, 475, 550, 200);
		//agregando lineas a la tabla
		//Segunda linea horizontal
		$page->setLineWidth(0.5)
		->drawLine(30, 445, 550, 445);
		//Primera columna Vertical lado izquierdo para la tabla
		$page->setLineWidth(0.5)
		->drawLine(130, 475, 130, 200);
		//Segunda Columna Vertical lado izquierdo para la tabla
		$page->setLineWidth(0.5)
		->drawLine(450, 475, 450, 200);
		//Primera lena Vertical lado izquierdo para el total
		$page->setLineWidth(0.5)
		->drawLine(450, 475, 450, 100);
		//Segunda linea Vertical lado derecho para el total
		$page->setLineWidth(0.5)
		->drawLine(550, 475, 550, 100);
		//Ultima linea Horizontal de total
		$page->setLineWidth(0.5)
		->drawLine(450, 100, 550, 100);
		// primera linea Horizontal de iva
		$page->setLineWidth(0.5)
		->drawLine(450, 125, 550, 125);
		// primera linea Horizontal de iva
		$page->setLineWidth(0.5)
		->drawLine(450, 150, 550, 150);
		// primera linea Horizontal de Subtotal
		$page->setLineWidth(0.5)
		->drawLine(450, 175, 550, 175);
		//Sub Columns
		$InvoiceService = new InvoiceService();
		$TotalLeter=$InvoiceService->num2letras($total);
		$fontTableTitle = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_ITALIC);
		$page->setFont($fontTableTitle, 12);
		$page->drawText('Subtotal:     ', 400, 180);
		$page->drawText('IVA:', 422, 160);
		$page->drawText('Porcentaje:', 390, 135);
		$page->drawText('Total:      $', 417, 110);
		$page->drawText('Por este pagare nos obligamos a pagar incondicionalmente.', 50, 120);
		$page->drawText('a la orden de IOFRACTAL "Constructores en desarrollo de Sistemas".', 50, 100);
		$page->drawText('La cantidad exacta de:', 50, 80);
		$page->drawText($TotalLeter."   00/100 M.N.", 180, 80);
		$page->setLineWidth(0.5)
		->drawLine(0, 25, 650, 25);
		$fontFoot = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_ITALIC);
		$page->setFont($fontFoot, 10);
		$pdfData =  $pdf->render();
		//$pdf->save();
		$this->view->pdfData= $pdfData;

	}

	public function testAction(){
		$invoiceService = new InvoiceServiceImpl();
		print_r($invoiceService->testServicioweb());
	}

	public function addAction(){}



}

