<?php

namespace Horus\Controller;

use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Iofractal\Controller\BaseController;
use System\Services\UsersService;
use System\Services\UsersRolesServices;
use System\Form\UserForm;
use Registrationusers\Model\AdressModel;
use Auth\Utility\Mail2;
use Registrationusers\Model\BillDetailsModel;
use Horus\Services\PaymentServices;
use Classes\Services\StudyTimeService;
use Classes\Services\LoginHistoryService;
use Registrationusers\Service\RegisterService;
use Registrationusers\Service\FichaRegistroService;


class EmployeeController extends BaseController 
{
	
	private $users_services;
	
	public function __construct()
	{
	}
	
	public function getUsersService()
	{
		return $this->users_services = new UsersService();
	}
	


    public function indexAction() 
    {
    	$paymentService = new PaymentServices();
    	$usersData = $this->getUsersService()->fetchAll(1);
    	$data = array(
    		"users" => $usersData
    	);
    	return new ViewModel($data);
    }
   
    
    public function addAction()
    {
    	/*$version = \Zend\Version\Version::VERSION;
    	print_r($version);exit;*/
    	$form            = new UserForm("userForm");
    	$view            = array("form" => $form);
    	$addressModel = new AdressModel();
    	$billModel = new BillDetailsModel();
    	$mail = new Mail2();
    	$request         = $this->getRequest();
    	
    	if($request->isPost()){
    		$formData   = $request->getPost()->toArray();
    		$addUsers   = $this->getUsersService()->addUser($formData);
    		$dataAdress = array(
    				'calle' => $formData['calle'],
    				'numInt' => $formData['numInt'],
    				'numExt' => $formData['numExt'],
    				'colonia' => $formData['colonia'],
    				'delegacion' => $formData['delegacion'],
    				'ciudad'=> $formData['ciudad'],
    				'cp' => $formData['cp'],
    				'pais'  => $formData['pais'],
    				'id_user' => $addUsers,
    				'trim'    => "1"
    		);
    		$addAddress = $addressModel->addRow($dataAdress);
    		
    		if($formData['numIntFact']!=""){
    			$numInt = $formData['numIntFact'];
    		}else{
    			$numInt = "0";
    		}
    		
    		$billingDetails = array(
    				'name' => $formData['nombreFact'],
    				'surname' => $formData['apellidomFact'],
    				'lastname' => $formData['apellidopFact'],
    				'rfc'      => $formData['rfcFact'],
    				'calle' => $formData['calleFact'],
    				'num_ext' => $formData['numExtFact'],
    				'num_int' => $numInt,
    				'colonia' => $formData['coloniaFact'],
    				'cp'      => $formData['cpFact'],
    				'delegacion'=> $formData['delegacionFact'],
    				'ciudad' => $formData['ciudadFact'],
    				'pais'  => $formData['paisFact'],
    				'id_student' => $addUsers
    		);
    		
    		$flag=0;
    		foreach($billingDetails as $details){
    			if($details ==""){
    				$flag=1;
    			}
    		}
    		
    		if($flag==0){
    			$addbillDetails = $billModel->addRow($billingDetails);
    		}
    		
    		if($addUsers){
    			$mail->sendInfoMail($formData['email']);
    			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/horus/employee/index');
    		}
    	}
    	
    	return new ViewModel($view);
    }
    
    // Actulizar empleado
    public function editAction()
    {
    	//$form            = new UserForm("userForm");
    	$address = new AdressModel();
    	$billModel = new BillDetailsModel();
    	$request         = $this->getRequest();
    	
    	if($request->isPost()){
    		$formData   = $request->getPost()->toArray();
    		$invert = explode("-",$formData['fecha']);
    		$fecha_invert = $invert[2]."-".$invert[1]."-".$invert[0];
    		
    		$invert2 = explode("-",$formData['fechaCert']);
    		$fecha_invert2 = $invert2[2]."-".$invert2[1]."-".$invert2[0];
    		
    		$dataUser = array(
    				'user_id' => $formData['id_user'],
    				'name' => $formData['nombre'],
    				'surname' => $formData['apellidom'],
    				'lastname' => $formData['apellidop'],
    				'sexo'     => $formData['sexo'],
    				'curp' => $formData['curp'],
    				'datebirth' => $fecha_invert,
    				'numSep'    => $formData['numSep'],
    				'email'     => $formData['email'],
    				'city_birth' => $formData['ciudad_nacimiento'],
    				'state_birth' => $formData['edo_nacimiento'],
    				'highschool'  => $formData['secundaria'],
    				'date_certificate' => $fecha_invert2,
    				'phone'            => $formData['telefonoCasa'],
    				'cellphone'        => $formData['celular']
    		);
    		
    		
    		if($formData['password']!=""){
    			$dataUser['password'] = md5(trim($formData['password']));
    		}

    		$editUser   = $this->getUsersService()->editUser($dataUser);
    		
    		$dataAdress = array(
    				'calle' => $formData['calle'],
    				'numInt' => $formData['numInt'],
    				'numExt' => $formData['numExt'],
    				'colonia' => $formData['colonia'],
    				'delegacion' => $formData['delegacion'],
    				'ciudad'=> $formData['ciudad'],
    				'cp' => $formData['cp'],
    				'pais'  => $formData['pais'],
    				'id_user' => $formData['id_user']
    		);
    		
    		$updateAdress = $address->editRow($dataAdress);

    		if($formData['numIntFact']!=""){
    			$numInt = $formData['numIntFact'];
    		}else{
    			$numInt = "0";
    		}
    		
    		$billingDetails = array(
    				'name' => $formData['nombreFact'],
    				'surname' => $formData['apellidomFact'],
    				'lastname' => $formData['apellidopFact'],
    				'rfc'      => $formData['rfcFact'],
    				'calle' => $formData['calleFact'],
    				'num_ext' => $formData['numExtFact'],
    				'num_int' => $numInt,
    				'colonia' => $formData['coloniaFact'],
    				'cp'      => $formData['cpFact'],
    				'delegacion'=> $formData['delegacionFact'],
    				'ciudad' => $formData['ciudadFact'],
    				'pais'  => $formData['paisFact'],
    				'id_student' => $formData['id_user']
    		);
    		
    		$flag=0;
    		foreach($billingDetails as $details){
    			if($details ==""){
    				$flag=1;
    			}
    		}
    		
    			$factDetails=$billModel->getRowById($formData['id_user']);
        		
        		if($factDetails){
        			$billModel->editRow($billingDetails);
        		}else{
        			$billModel->addRow($billingDetails);
        		}
    		
    		//echo "<pre>"; print_r($addUsers); exit;
    		if($editUser){
    			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/horus/employee/index');
    		}
    	}
    	
    	$id_user         = $this->params()->fromRoute("id",null);
    	$user    = $this->getUsersService()->getUserById($id_user);
    	$billDetails = $billModel->getRowById($id_user);
    	$addresInfo = $address->getRowById($user[0]['user_id']);
    	$view            = array("id_user" => $id_user, "user" => $user,"address" => $addresInfo,"billDetails" => $billDetails);
    	
    	return new ViewModel($view);
    }
    
    /*
     * Metodo eliminar
     */
    public function deleteAction()
    {
    	
    	$paymentService = new PaymentServices();
    	$tokenService = new FichaRegistroService();
    	if ($this->getRequest()->isPost()){
    		$formData  = $this->getRequest()->getPost()->toArray();
    		$deletedStudent = $this->getUsersService()->deleteUser($formData['id_user']);
    		$checkToken = $tokenService->getTokenByUser($formData['id_user']);
    		if($checkToken){
    			$tokenService->deleteToken($formData['id_user']);
    		}
    		if($deletedStudent){
    			$response = $this->getResponse()->setContent(\Zend\Json\Json::encode(array("response"=>"ok", "data"=>$deletedStudent)));
    		}else{
    			$response = $this->getResponse()->setContent(\Zend\Json\Json::encode(array("response"=>"fail", "data"=>array())));
    		}
    	}
    	return $response;
    	exit;
    }
    
    /*
     * Ver detalle de empleado
     */
    public function seedetailAction()
    {
    	$id_user = (int) $this->params()->fromRoute("id", null);
    	$user    = $this->getUsersService()->getUserById($id_user);
    	$address = new AdressModel();
    	$billModel = new BillDetailsModel();
    	$paymentService = new PaymentServices();
    	$studentTimeService = new StudyTimeService();
    	$loginHistoryService = new LoginHistoryService();
    	$loginHistory = $loginHistoryService->getLoginHistoryByUser($id_user);
    	$timeInfo = $studentTimeService->getTimeByUser($id_user);
    	if($timeInfo!= null){
    		$timeSplit = explode(":",$timeInfo['time']);
    	}else{;
    		$timeSplit = explode(":","00:00:00");
    	}
    	
    	$paymentInfo = $paymentService->getPaymentInfoByUser($id_user);
    	$billDetails = $billModel->getRowById($id_user);
    	$addresInfo = $address->getRowById($user[0]['user_id']);
    	if($user){
    		return new ViewModel(array("id_user" => $id_user, "user" => $user,"address" => $addresInfo,"billDetails" => $billDetails, "paymentInfo" => $paymentInfo, "timeInfo" => $timeSplit, "loginHistory" => $loginHistory));
    	}else{
    		return $this->redirect()->toUrl($this->getRequest()->getBaseUrl(). '/system/users/index');
    	}
    }
    
    /*
     * Editar detalle de empleado
     */
    public function editdetailAction()
    {
    	$form            = new UserForm("userForm",1);
    	$id_user         = $this->params()->fromRoute("id",null);
    	$user            = $this->getUsersService()->getUserById($id_user);
    	$user[0]['birthday'] = \Util_Tools::dateFormat("-", $user[0]['birthday']); 
    	//echo "<pre>"; print_r($user); exit;
    	$form->setData($user[0]);
    	$view            = array("form" => $form, "district" => $user[0]['district_id'], "neighborhood" => $user[0]['neighborhood']);
    	$request         = $this->getRequest();
    	 
    	if($request->isPost()){
    		$formData   = $request->getPost()->toArray();
    		//echo "<pre>"; print_r($formData); exit;
    		$addUsers   = $this->getUsersService()->editDetailUser($formData);
    	
    		//if($addUsers){
    			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/horus/employee/index');
    		//}
    	}
    	
    	return new ViewModel($view);
    }
    
    /*
     * Detalle de empleados 
     */
    public function detailsAction()
    {
    	$id_user = (int) $this->params()->fromRoute("id", null);
    	$user    = $this->getUsersService()->getUserById($id_user);
    	//echo "<pre>"; print_r($user);exit;
    	if($user){
    		return new ViewModel(array("id_user" => $id_user, "user" => $user));
    	}else{
    		return $this->redirect()->toUrl($this->getRequest()->getBaseUrl(). '/system/users/index');
    	}
    }
    
    function showDetailsById($idUSer){
    	$serviceUsersDetails = new UsersDetailsService();
    	$detail = $serviceUsersDetails->getUserDetailById($idUser);
    	$response = "";
    	
    	if($detail == null){
    		$response = "noDetails";
    	}else{
    		$response = "success";
    	}
    	return $response;
    }
    
    /*
     * confirmshippingAction
     */
    public function confirmshippingAction()
    {
    	$paymentService = new PaymentServices();
    	$serviceTime = new StudyTimeService();
    	$mail = new Mail2();
    	
    	if ($this->getRequest()->isPost()){
    		$data       = $this->getRequest()->getPost()->toArray();
    		$studentTime = $serviceTime->getTimeByUser($data['id']);
    		$initial = 0;
    		if($data['trim'] == 1 && $studentTime['time']!='00:00:00'){
    			$initial = 1;
    		} 
    		if(isset($data['key']) && $data['key'] == 1){
    			if($data['status']==1){
    				$paymentInfo = $paymentService->getPaymentInfoByUser($data['id']);
    				if($paymentInfo['inscription'] == 0 || $paymentInfo['month_1'] == 0){
    					$response = $this->getResponse()->setContent(\Zend\Json\Json::encode(array("response"=>"paymentsFail")));
    				}else{
    					$row = $this->getUsersService()->confirmShipping($data);
    					$mail->sendActivationLink($data['status'],$data['email'],$initial);
    					$response = $this->getResponse()->setContent(\Zend\Json\Json::encode(array("response"=>"ok", "data"=>$row)));
    				}
    			}elseif($data['status']==0){
    				$row = $this->getUsersService()->confirmShipping($data);
    				$mail->sendActivationLink($data['status'],$data['email'],$initial);
    				$response = $this->getResponse()->setContent(\Zend\Json\Json::encode(array("response"=>"ok", "data"=>$row)));
    				
    			}
    			
    			return $response;
    		}
    	}
    	exit;
    }
    
    public function paymentsAction()
    {
    	$paymentService = new PaymentServices();
    	if ($this->getRequest()->isPost()){
    		$formData       = $this->getRequest()->getPost()->toArray();
    		$checkStudent = $paymentService->getPaymentInfoByUser($formData['id_user']);
    		if($checkStudent){
    		   $updatePayment = $paymentService->updatePaymentInfo($formData);
    		   if($updatePayment){
    		   	  $response = $this->getResponse()->setContent(\Zend\Json\Json::encode(array("response"=>"ok", "data"=>$updatePayment)));
    		   }else{
    		      $response = $this->getResponse()->setContent(\Zend\Json\Json::encode(array("response"=>"fail", "data"=>array())));
    		   }
    		}else{
    		   $addPayment = $paymentService->addPaymentInfo($formData);
    		   if($addPayment){
    		   	  $response = $this->getResponse()->setContent(\Zend\Json\Json::encode(array("response"=>"ok", "data"=>$addPayment)));
    		   }else{
    		   	$response = $this->getResponse()->setContent(\Zend\Json\Json::encode(array("response"=>"fail", "data"=>array())));
    		   }	
    		}
    	}
    	return $response;
    	exit;
    }
    
    public function documentsAction()
    {
    	$request         = $this->getRequest();
    	if($this->getRequest()->isPost()){
    		$formData = $this->getRequest()->getPost()->toArray();
 		   	$updateDocuments = $this->getUsersService()->editUser($formData);
 		   	if($updateDocuments){
 		   		$response = $this->getResponse()->setContent(\Zend\Json\Json::encode(array("response"=>"ok", "data"=>$updateDocuments)));
 		   	}else{
 		   		$response = $this->getResponse()->setContent(\Zend\Json\Json::encode(array("response"=>"fail", "data"=>array())));
 		   	}
    	}
    	return $response;
    	exit;
    }
}