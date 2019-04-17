<?php


namespace Horus;

use BaseController;
use IHorusController;
use Application_Service_Impl_VitaeServiceImpl;
use UsersDetailsService;
use Zend_Json;
use Acl_UserServiceImpl;
use Application_Service_Impl_DepartmentServiceImpl;
use Application_Form_DepartmentForm;
use Application_Form_UsersForm;
use UsersServices;
use Application_Util_Tools;
use UserType;
use Privilege;
use Application_Form_Vitae_GeneralForm;
use Application_Form_Vitae_ExperienceForm;


require APPLICATION_PATH . '/controllers/BaseController.php';
require APPLICATION_PATH . '/forms/vitae/GeneralForm.php';
require APPLICATION_PATH . '/forms/vitae/ExperienceForm.php';
require APPLICATION_PATH . '/services/UsersServices.php';
require_once APPLICATION_PATH . '/services/impl/Acl_UserServiceImpl.php';

class VitaeController extends BaseController implements IHorusController{

	private $vitaeService = null;
	
    function init() {
        BaseController::init();
        $this->vitaeService = new Application_Service_Impl_VitaeServiceImpl();
    }
    
    public function indexAction() {
    	
    	$idUser = $this->_getParam('id');
    	$ifDetails = $this->_getParam('ifDetails');
    	$response = "";
    	
    	if(isset($idUser) != "" && isset($ifDetails) == "true"){
    		
    		echo "todo";
    		
    		$serviceUsersDetails = new UsersDetailsService();
    		$detail = $serviceUsersDetails->getUserDetailById($idUser);
    		
    		if($detail == null){
    			$response = "noDetails";
    		}else{
    			$response = "success";
    		}
    		
    		echo Zend_Json::encode(array('response' => $response));
    		exit;
    		
    	}else{
    		//Listar a todos los usuarios
    		$service  = new Acl_UserServiceImpl();
    		$userListService = new Acl_UserServiceImpl();
    		$this->view->users = $userListService->getDevelopersNames();
    		
    		$departments = new Application_Service_Impl_DepartmentServiceImpl();
    		$this->view->departments = $departments->getDepartments();
    		$departmentForm = new Application_Form_DepartmentForm();
    		$this->view->departmentForm = $departmentForm;
    	}
    }
    
    public function getcvAction(){
    	if ($this->getRequest()->isPost()) {
    		$id = $this->getRequest()->getPost()['id'];
    		$userForm = new Application_Form_UsersForm(2, "e");
    		$userService = new Acl_UserServiceImpl();
    		if ($id > 0) {
    			$userId = new UsersServices();
    			$user = $userId->getUser($id);
    		
    			$user['date_admission'] = Application_Util_Tools::dateFrontFormat("-", $user['date_admission']);
    			 
    			if($e == 1){
    				if($user['user_type'] == UserType::INTERNO && $user['id'] == $id && $user['role_id'] != Privilege::SUPERUSUARIO && $user['role_id'] != Privilege::SUPERADMINISTRADOR){
    					$this->view->show = true;
    					$this->view->userType = $user['user_type'];
    					$this->view->nameEmploy = $user['real_name'] . " " . $user['surname'] . " " . $user['lastname'];
    					$userForm->populate($user);
    				}else{
    					$this->view->show = false;
    				}
    			}else{
    				$this->view->show = true;
    				$this->view->userType = $user['user_type'];
    				$this->view->nameEmploy = $user['real_name'] . " " . $user['surname'] . " " . $user['lastname'];
    		
    				$userForm->populate($user);
    			}
    			
    			return $this->_redirect('/Horus/vitae/add');
    		}
    		
    	}    	
    	
    }
    
    public function addShowAction() {
    	$form = new Application_Form_Vitae_GeneralForm();
    	$this->view->form = $form;

    	if ($this->getRequest()->isPost() ) {
    	
    		$id = $this->getRequest()->getPost()['id']; //get user id
    		$cv = $this->vitaeService->getCV($id);
    		$val = $this->getRequest()->getParam('t');

    		print_r($cv);
    		
    		if(isset($val) && $val==1){ //getCV data para llenar formulario
    	
    			$formData['id_user'] = $id;
    			//         			$e = $this->_getParam('e');
    			if ($id > 0) { //Ya está insertado y se consulta
    				$userService = new UsersServices();
    				
    				$user = $userService->getUser($id);
    				$cv = $this->vitaeService->getCV($id);
    				$this->view->show = true;
    				$this->view->userType = $user['user_type'];
    				$this->view->nameEmploy = $user['real_name'] . " " . $user['surname'] . " " . $user['lastname'];
    				
    			}
    		}
    		
    		if($cv['idcvitae']!=""){ $form->getElement('hasCV')->setValue(1); }
    		    	
    		$form->populate($cv);
    	}
    	
    }
    
    
	/* 
	 * Add a new CVitae data
	 * @see IHorusController::addAction()
	 */
	public function addAction(){
		$form = new Application_Form_Vitae_GeneralForm();
		
        $this->view->form = $form;
        
        if ($this->getRequest()->isPost() ) {
        	
        	$id = $this->getRequest()->getPost()['id']; //get user id
        	
        	$cv = $this->vitaeService->getCV($id);
        	
        	print_r($this->getRequest()->getPost()	);	

        	$val = $this->getRequest()->getParam('t');
        	
        	if($this->getRequest()->getPost()['hasCV']==1){
        		$this->vitaeService->update($this->getRequest()->getPost());
        	}else{
        		$form->getElement('hasCV')->setValue(1);
        		$this->vitaeService->create($this->getRequest()->getPost());
        	}    			
        	
        	$form->populate($cv);

//         	}else { //envia los datos a cambiar del cv

//         		echo "estamos en el eslse";
        		
//         		print_r ($this->getRequest()->getPost());
        		 
//         		echo "! " . $this->getRequest()->getPost()['vision'] ;
//         		//         	$formData['userType'] = $userType;
//         		//         	$formData['role_id'] = $role_id;
//         		//         	$formData['cellphone_contact'] = "";
//         		//         	$formData['phone_contact'] = "";
        		
//         		$service =  new Application_Service_Impl_VitaeServiceImpl();
        		
//         		$service->update($this->getRequest()->getPost());
        		
        		//         	if($formData['canlogin'] == "1"){
        		//         		if($employe == "1"){
        		//         			echo Zend_Json::encode(array("response" => "ok" , "typeUri" => "/employee/index", "data" => "Empleado actualizado"));
        		//         		}else{
        		//         			echo Zend_Json::encode(array("response" => "ok" , "typeUri" => "/users/index", "data" => "Usuario actualizado"));
        		//         		}
        		//         	}else{
        		//         		if($formData['userType'] == "Externo"){
        		//         			echo Zend_Json::encode(array("response" => "ok" , "typeUri" => "/contact/index", "data" => "Empleado actualizado"));
        		//         		}else{
        		//         			echo Zend_Json::encode(array("response" => "ok" , "typeUri" => "/employee/index", "data" => "Usuario actualizado"));
        		//         		}
        		//         	}
        	//	exit;        		
        		
//         	}
        	

        
        }
        	
        $this->_redirect("/Horus/vitae/experience-add");
        
        
	}
	
    public function experienceAddAction()
    {
		$form = new Application_Form_Vitae_ExperienceForm();
        $this->view->form = $form;
    }
	


}

