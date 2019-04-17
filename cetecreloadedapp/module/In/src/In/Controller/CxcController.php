<?php

namespace In\Controller;

use Zend\View\Model\ViewModel;
use Iofractal\Controller\BaseController;
use In\Services\CxcServices;

class CxcController extends BaseController
{
	private $cxcServices;
	
	// Instanciamos servicio de cxc
	public function getCxcServices()
	{
		return $this->cxcServices = new CxcServices();
	}

	public function indexAction()
	{
		$data = $this->getCxcServices()->fetchAll();
		//echo "<pre>"; print_r($data); exit;
		$view = array("data" => $data);
		return new ViewModel($view);

	}
	 
	public function addpaymentAction(){
		$form = new Application_Form_CxcForm();
		$service = new CxcServiceImpl();
		
		if ($this->getRequest()->isPost()) {
			
		    $formData = $this->getRequest()->getPost();		    
		    $idProject = $this->_getParam("iP");
		    
		    $paymet = $service->saveMadePayment($idProject, $formData); //guarda pago de cxc
		  
		    if($paymet != null){
		    	if($paymet == "NoSavePayment"){
		    		echo Zend_Json::encode(array("response" => "fail",  "msj" => "Pago no guardado, intentalo mas tarde."));
		    	}else if($paymet == "NoSaveMovement"){
		    		echo Zend_Json::encode(array("response" => "fail",  "msj" => "Movimiento no guardado, intentalo mas tarde."));
		    	}else if($paymet == "NoSaveCurrent"){
		    		echo Zend_Json::encode(array("response" => "fail",  "msj" => "Saldo total no guardado, intentalo mas tarde."));
		    	}else{
		    		$service->updatePayStatus($formData['id_payment'], "1");//actualiza estatus de cxc a pagado
		    		echo Zend_Json::encode(array("response" => "ok"));
		    	}
		    }else{
		    	echo Zend_Json::encode(array("response" => "false", "msj" => "Erro desconocido, consulta al administrador"));
		    }
		    		   
		}
		exit;
	}
	 
	public function getpaymentsAction(){
		
		$service = new CxcServiceImpl();
		$sD = $this->_getParam("sD");
		
		if(isset($sD)){		
			if($sD == "Pm"){
				$allCxcPay = $service->getTotalCxcPay();
				if($allCxcPay != null){
					if($allCxcPay[0]['total'] != null){
						echo Zend_Json::encode(array("response" => "ok", "data" => $allCxcPay[0]['total']));
					}else{
						echo Zend_Json::encode(array("response" => "fail"));
					}
				}else{
					echo Zend_Json::encode(array("response" => "fail"));
				}		
			}else if($sD == "PnM"){
				$allCxcNoPay = $service->getTotalNoCxcPay();
				if($allCxcNoPay != null){
					if($allCxcNoPay[0]['total'] != null){
						echo Zend_Json::encode(array("response" => "ok", "data" => $allCxcNoPay[0]['total']));
					}else{
						echo Zend_Json::encode(array("response" => "fail"));
					}
				}else{
					echo Zend_Json::encode(array("response" => "fail"));
				}
			}
			
		}

		exit;
	}
	
	
	public function addAction(){
		


	}


	public function aboutprojectAction(){

		$id = $this->_getParam("id");
		$service = new ProjectsServiceImpl();
		$serviceTeam = new User_ProjectServiceImpl();
		$data = $service->getProjectById($id, null);
		$this->view->data = $data;

		foreach ($data as $companyId){
			$this->view->companyName = $service->getCompanyName($companyId->getCompany_id());
		}

		$this->view->team = json_decode($serviceTeam->getTeam($id),true);
	}

	public function calendarAction(){
	}

	public function invoicesAction(){

	}

	public function fillcalendarAction(){
		$service = new DatesOfPaymentServiceImpl();
		$startDate = $this->_getParam("start");
		$endDate = $this->_getParam("end");

		print_r($service->getDatesOfPayments($startDate, $endDate));
		exit;
	}

	public function movedateAction(){
		$id = $this->_getParam("idPayment");
		$days = $this->_getParam("newDate");
		$service = new DatesOfPaymentServiceImpl();
		$service->moveDate($id, $days);
		exit;
	}
	
	public function detailAction(){
		$idParam = $this->_getParam("id");
		$cxc = new CxcServiceImpl();
		$this->view->cxc = $cxc->getCxcById($idParam);
	}

}