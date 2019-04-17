<?php


namespace Horus;

use BaseController;
use ContactServiceImpl;
use Zend_Json;
use Application_Form_ContactForm;
use Acl_UserServiceImpl;
use CustomersServices;
use UsersDetailsService;


include_once APPLICATION_PATH . '/controllers/BaseController.php';
include_once APPLICATION_PATH . '/services/impl/ContactServiceImpl.php';
include_once APPLICATION_PATH . '/services/UsersDetailsService.php';
include_once APPLICATION_PATH . '/entities/UsersDetails.php';

class ContactController extends BaseController{

    public function indexAction() {

	
		$contact = new ContactServiceImpl();
		$valEmail = $this->_getParam('valEmail');
		$idParam = $this->_getParam("c");
		
   		 if (isset($valEmail) && $valEmail == "1"){
			$mail = $this->_getParam('mail');
			$exist = $contact->checkExistEmail($mail);
			$msj = "";
			if($exist != null){
				$msj = "true";
			}else{
				$msj = "false";
			}
			echo Zend_Json::encode($msj);
			exit;
		}else	if (isset($idParam)){
			$contactsByCompany = $contact->getContactsByIdCompany($idParam);
			$this->view->contacts = $contactsByCompany;
		}else{
		    $this->view->contacts = $contact->getContactList();
		}
	
    }
    
    public function addAction(){
    	
    	$form = new Application_Form_ContactForm();
    	$createService = new Acl_UserServiceImpl();
    	$service = new CustomersServices();
    	$role_id = "";
    	if($this->getRequest()->isPost()){
    		$formData = $this->getRequest()->getPost();
    		
    		$saveUserExt = $this->_getParam('sue');
    		
    		if(isset($saveUserExt) && $saveUserExt == "true"){//registro de un contacto o un empleado desde otro modulo
    			
    			$saveCorrrect = $createService->createUser($formData, 3);
    			if($saveCorrrect != null){
    				$nameComplete = $formData['name_contact'] . " " . $formData['surname_contact'] . " " . $formData['lastname_contact'];
    				echo Zend_Json::encode(array("response" => "ok", "data" => array("name" =>  $nameComplete, "id" => $saveCorrrect)));
    			}else{
    				echo Zend_Json::encode(array("response" => "fail", "data" => "0"));
    			}
    			exit;
    		}else{
	    		if($form->isValid($this->_getAllParams())){
	    				$company = Zend_Json::decode($formData['company_ID']);
	    				$formData['company_ID'] = $company['id'];
	    				$formData['type'] = $company['type'];
		    			$saveCorrrect = $createService->createUser($formData, 3);
		    			$this->_redirect("/Horus/contact/index");
	    		}
    		}
    		
    	}else{
    	 	$this->view->form = $form;
    	 }
    }

    public function updateAction(){
		
    	$form = new  Application_Form_ContactForm();
    	$contacService = new ContactServiceImpl();
    	$userService = new Acl_UserServiceImpl();
    	$updateService = new Acl_UserServiceImpl();
    	
    	$privateKey = $this->_getParam('c');

    	if(isset($privateKey)){
    		$idUser = $userService->getRealIdByPrimaryKey($privateKey);

			if($idUser != null){
				$id = $idUser[0]['id'];
				$userType = $idUser[0]['user_type'];
				$role_id = $idUser[0]['role_id'];
				$name = $idUser[0]['real_name'];
				$surname = $idUser[0]['surname'];
				$lastname = $idUser[0]['lastname'];
				$uPrin = $idUser[0]['user_principal'];
			}else{
				echo "no existe el usuario";
				exit;
			}
    	}else{
	    		echo "no se envio la privateKey";
	    		exit;
    	}
    	
    	if($this->getRequest()->isPost()){
    		$formData = $this->getRequest()->getPost();
    		$formData['id_user'] = $id;
    		$formData['userType'] = $userType;
    		$formData['id_department'] = "1";
    		$formData['user_principal'] = $uPrin;
    		$formData['role_id'] = $role_id;
    		$formData['real_name'] = $formData['name_contact'];
    		$formData['surname'] = $formData['surname_contact'];
    		$formData['lastname'] = $formData['lastname_contact'];
    		$formData['mail'] = $formData['mail_contact'];
						
                $updateService->updateUsers($formData, 3);
                $this->_redirect("/Horus/contact/index");
    	}else{
    		$contact = $contacService->getContactByContactId($id);
    		$joson = Zend_Json::encode(array("id" => $contact[0]['company_ID'], "type" => $contact[0]['cust_type']));

    		$form->populate($contact[0]);
    		$form->getElement('company_ID')->setValue($joson);
    		$this->view->name = $name . " " . $surname . " " . $lastname;
    	}
    	$this->view->form = $form;
    }
    
    
    public function deleteAction(){
    
    	$deleteParam = $this->_getParam("erase");
    	$service =  new ContactServiceImpl();
    	$deleteDetails = new UsersDetailsService();
    	
    	$deleteDetails->deleteUserDetails($deleteParam);
    	$service->deleteContact($deleteParam);
    	exit;
    }
     

     public function getcontactAction() {
     	$contactService = new ContactServiceImpl();
     	$contactParam = $this->_getParam('contact');
     	 
     	$data = $contactService->getContactById($contactParam);
     
     	print $data; exit;
     	 
     	 
     }
   
     public function detailsAction (){
		$contactService = new ContactServiceImpl();
		$createService = new Acl_UserServiceImpl();
		
     	$contact = $this->_getParam('c');
     	
     		if(isset($contact)){
	    		$idUserR = $createService->getRealIdByPrimaryKey($contact);
	    		
	    		if($idUserR != null){
	    				$contacts = $contactService->getContactDetailsById($idUserR[0]['id']);
      					$this->view->contacts = $contacts;
	    		}else{
	    			echo "No existe el usaurio";
	    			exit;
	    		}
	    	}else{
	    		echo "No se envio la privateKey";
	    		exit;
	    	}
     	
     }
}

