<?php
namespace Horus\Controller;

use Zend\View\Model\ViewModel;
use Iofractal\Controller\BaseController;
use Horus\Services\PayrollServices;
use System\Services\UsersService;
use Horus\Form\HistoryPayroll;

use Horus\Form\LoanForm;

class PayrollController extends BaseController
{
	private $payrollService;
	private $userService;
	
	public function __construct(){}
	
	/*
	 * Servicio nomina
	 */
	private function getPayrollService()
	{
		return $this->payrollService = new PayrollServices();
	}
	
	/*
	 * Servicio de usuarios
	 */
	private function getUserService()
	{
		return $this->userService = new UsersService();
	}
	
	/*
	 * indexAction
	 */
	public function indexAction()
	{
		$rows = $this->getUserService()->getPayRollByUser();
		$view = array("rows" => $rows);
		return new ViewModel($view);			
	}
	
	/*
	 * addAction
	 */
	public function addAction(){}
	
	/*
	 * editAction
	 */
	public function editAction(){}
	
	/*
	 * deleteAction
	 */
	public function deleteAction(){}
	
	/*
	 * calendarAction
	 */
	public function calendarAction()
	{
		
		if($this->getRequest()->isPost()){
			// Valores post
			$data     = $this->getRequest()->getPost()->toArray();
			$calendar = $this->getPayrollService()->calendarPayroll($data);
			
			return $response = $this->getResponse()->setContent(\Zend\Json\Json::encode($calendar));
			exit();
		}
	}
	
	/*
	 * listpayrollAction
	 */
	public function listpayrollAction()
	{
		$params = $this->params()->fromRoute();
		
		if (isset($params['id']) && isset($params['date'])){
			$rows = $this->getPayrollService()->getPayRollByUserToDateEnlasa($params['id'], $params['date']);
			//echo "<pre>"; print_r($rows); exit;
		}
		
		$view = array("rows" => $rows,"type" => $params['id'], "date" => $params['date']);
		return new ViewModel($view);
	}
	
	/*
	 * Pagar Nomina
	 */
	public function addpayrollAction()
	{
		if ($this->getRequest()->isPost()){
			$data       = $this->getRequest()->getPost()->toArray();
			//print_r($data); exit;
			if(isset($data['idUsers'])){
				$addPayRoll = $this->getPayrollService()->addPayRoll($data);
				if($addPayRoll){
					$response = $this->getResponse()->setContent(\Zend\Json\Json::encode(array("response"=>"ok", "totalEmployee"=>count($data['idUsers']))));
				}
				return $response;
			}
		}
		exit;
	}
	
	/*
	 * historyAction
	 */
	public function historyAction()
	{
		$form = new HistoryPayroll("historyPayroll");
		$view = array("form" => $form);
		
		// Si la peticion es POST
		if ($this->getRequest()->isPost())
		{
			$data = $this->getRequest()->getPost()->toArray();
			if (isset($data['id_employee']))
			{
				$id   = (int) $data['id_employee'];
				$rows = $this->getUserService()->getPayrollsByUserId($id);
				
				if ($rows != null){
					$response = $this->getResponse()->setContent(\Zend\Json\Json::encode(array("response"=>"ok", "data"=>$rows)));
				}else{
					$response = $this->getResponse()->setContent(\Zend\Json\Json::encode(array("response"=>"false", "data"=>$rows)));
				}
				return $response;
			}
		}
		
		return new ViewModel($view);
	}
	
	/*
	 * DETAILPAYROLLACTION
	 */
	public function detailuserpayrollAction()
	{
		if ($this->getRequest()->isPost()){
			$data       = $this->getRequest()->getPost()->toArray();
			
			if(isset($data['id_employee'])){
				$rows = $this->getPayrollService()->detailPayrollByIdUser($data['id_employee'],$data['type'], $data['date']);
				//print_r($rows);exit;
				if($rows){
					$response = $this->getResponse()->setContent(\Zend\Json\Json::encode(array("response"=>"ok", "data"=>$rows)));
				}else {
					$response = $this->getResponse()->setContent(\Zend\Json\Json::encode(array("response"=>"false", "data"=>$rows)));
				}
				return $response;
			}
		}
		exit;
	}
	
	/*
	 * downloadACTION
	 */
	public function downloadAction()
	{
		
		$params = $this->params()->fromRoute();
		
		if (isset($params['id']) && isset($params['date'])){
			//$rows = $this->getPayrollService()->getPayRollByUserToDateEnlasa($params['id'], $params['date']);
			$rows = $this->getPayrollService()->generateExcelPayroll($params['id'], $params['date']);
			//echo "<pre>"; print_r($rows); exit();
		}

		$view = new ViewModel();
		$view->setTemplate('horus/payroll/download')
		->setVariable('results', $rows)
		->setTerminal(true);
		
		$columnHeaders = array("# Empleado","Nombre","A. Paterno","A. Materno","Puesto","Num. Cuenta","Estatus", "Importe", "Fecha");
		if (!empty($columnHeaders)) {
			$view->setVariable(
					'columnHeaders', $columnHeaders
			);
		}
		//echo "<pre>"; print_r($rows);exit;
		$output   = $this->getServiceLocator()->get('viewrenderer')->render($view);
		//echo "<pre>"; print_r($output);exit;
		$response = $this->getResponse();
		$headers  = $response->getHeaders();
		$headers
			->addHeaderLine('Content-Type', 'text/csv')
			->addHeaderLine(
				'Content-Disposition',
				sprintf("attachment; filename=\"%s\"", "Nomina_".date("Y-m-d").".csv")
			)
			->addHeaderLine('Accept-Ranges', 'bytes')
			->addHeaderLine('Content-Length', strlen($output));
		
		$response->setContent($output);
		return $response;
	}
	
	/*
	 * AGREGAR UN BONO O DESCUENTO
	 */
	public function addamountAction()
	{
		if($this->getRequest()->isPost()){
			sleep(1);
			$formData = $this->getRequest()->getPost()->toArray();
			//print_r($formData);exit;
			$addRow   = $this->getPayrollService()->addLoans($formData);
			$response = $this->getResponse()->setContent(\Zend\Json\Json::encode(array("response"=>"ok", "data"=>$addRow)));
			return $response;
		}
		exit;
	}
	
	/*
	 * OBTENER BONOS Y DESCUENTOS POR EMPLEADO 
	 */
	public function amountuserpayrollAction()
	{
		if ($this->getRequest()->isPost()){
			$data       = $this->getRequest()->getPost()->toArray();
			
			if(isset($data['id_employee'])){
				$rows = $this->getPayrollService()->amountUserPayroll($data);
				//print_r($rows);exit;
				if($rows){
					$response = $this->getResponse()->setContent(\Zend\Json\Json::encode(array("response"=>"ok", "data"=>$rows)));
				}else{
					$response = $this->getResponse()->setContent(\Zend\Json\Json::encode(array("response"=>"fail", "data"=>$rows)));
				}
				return $response;
			}else{
				$amounts = $this->getPayrollService()->aplicateAmountByEmployee($data['bonos']);
				$response = $this->getResponse()->setContent(\Zend\Json\Json::encode(array("response"=>"ok", "data"=>$amounts)));
				//print_r($data['bonos']); exit;
				return $response;
			}
		}
		exit;
	}
	
	/*
	 * ELIMINAR BONOS O DESCUENTOS POR USUARIOS
	 */
	public function deleteamountuserAction()
	{
		if ($this->getRequest()->isPost()){
			$data      = $this->getRequest()->getPost()->toArray();
			if (isset($data['idAmount'])){
				$deleteRow = $this->getPayrollService()->deleteAmountByUser($data['idAmount']);
				if ($deleteRow){
					$response = $this->getResponse()->setContent(\Zend\Json\Json::encode(array("response"=>"ok", "data"=>$deleteRow)));
				}else{
					$response = $this->getResponse()->setContent(\Zend\Json\Json::encode(array("response"=>"fail", "data"=>$deleteRow)));
				}
				return $response;
				//print_r($data); exit;
			}
		}
		exit;
	}
	
	/*
	 * ADDLOANACTION
	 */
	/*public function addloanAction()
	{
		$viewmodel = new ViewModel();
        $form       = new LoanForm();
        $request = $this->getRequest();
         
        //disable layout if request by Ajax
        $viewmodel->setTerminal(true);
        
        if($this->getRequest()->isPost()){
        	$formData = $this->getRequest()->getPost()->toArray();
        	$loan     = $this->getPayrollService()->addLoans($formData);
        	//print_r($loan);exit;
        	$response = $this->getResponse()->setContent(\Zend\Json\Json::encode(array("response"=>"ok", "data"=>$loan)));
        	//print_r($formData);exit;
        	return $response;
        }
        
        $viewmodel->setVariables(array(
        		'form' => $form,
        		// is_xmlhttprequest is needed for check this form is in modal dialog or not
        		// in view
        		//'is_xmlhttprequest' => $is_xmlhttprequest
        ));
         
        return $viewmodel;
      
		if($this->getRequest()->isPost()){
			$formData = $this->getRequest()->getPost()->toArray();
			$loan     = $this->getPayrollService()->addLoans($formData);
			//print_r($loan);exit;
			$response = $this->getResponse()->setContent(\Zend\Json\Json::encode(array("response"=>"ok", "data"=>$loan)));
			//print_r($formData);exit;
			return $response;
		}
		//exit;
	}*/
	
}