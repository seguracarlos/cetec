<?php

namespace System\Controller;

//Incluimos controller base
use Application\Controller\BaseController;

class CompanyController extends BaseController{

	public function indexAction()
	{
		$customerService= new CustomersServices();
        $contactService = new ContactServiceImpl();
        $preferencesService =  new Application_Service_Impl_PreferencesServiceImpl();

		$preference = $preferencesService->getPreferencesById(Preferences::CODE_LOGO);
		$host = $customerService->getCustomerByHost();
		$contact = $contactService->getContactPrincipalByIdCompany($host[0]['id_company']);
    	$user = $customerService->getUserPrincipal($host[0]['id_company']);

		if($host != null){
			$this->view->preference = $preference;
			$this->view->host = $host;
	    	$this->view->contact = $contact;
	    	$this->view->user = $user;
		}else{
			$this->view->preference = $preference;
			$this->view->host = array();
    		$this->view->contact = array();
    		$this->view->user = array();
		}
	}

	public function updateAction(){
		$id = $this->_getParam('id');
		$customerForm = new Application_Form_Company();
        $customerService= new CustomersServices();
        $contactService = new ContactServiceImpl();
        $userService = new Acl_UserServiceImpl();
        $customer = new Customer();
        $address = new Address();
        $contact = new Contact();
        $user = new Users();
        $userDetails = new UsersDetails();
        $userDetailsService = new UsersDetailsService();
        $this->LoadLists($customerForm);

        if ($this->getRequest()->isPost()) {

    	 	$this->_setParam('state', $this->_getParam('state_id'));
    	 	$this->_setParam('district', $customerForm->getValue('district'));

    	 	$this->LoadLists($customerForm);

            $formData = $this->getRequest()->getPost();
            if ($customerForm->isValid($formData)) {

            	$isPricipal = $contactService->getContactPrincipalByIdCompany($id);

            	$arrayCompany = array(
            		"company_ID" => $id,
            		"name_company" => $formData['name_company'],
            		"rfc" => $formData['rfc_host'],
            		"website" => $formData['website'],
            		"business" => $formData['business'],
            		"brand" => $formData['brand'],

            		"state_id" => $formData['state_id'],
	            	"district" => $formData['district'],
	            	"phone" => $formData['phone'],
	            	"neighborhood" => $formData['neighborhood'],
	            	"postalcode" => $formData['postalcode'],
	            	"street" => $formData['street'],
	            	"number" => $formData['number'],
	            	"interior" => $formData['interior'],
	            	"url_map" => $formData['url_map'],
            		"ext" => $formData['ext'],

            		"name_bank" => $formData["name_bank"],
            		"number_acount" => $formData["number_acount"],
            		"interbank_clabe" => $formData["interbank_clabe"],
            		"sucursal_name" => $formData["sucursal_name"],

            		"id_user" => $formData['id_user'],
            		"numberEmployee" => $isPricipal[0]['numberEmployee'],
	            	"role_id" => Privilege::ADMINISTRADOR,
	            	"userType" => UserType::INTERNO,
	            	"user_principal" => "1",
	            	"real_name" => $formData['name_contact'],
	            	"surname" => $formData['surname_contact'],
	            	"lastname" => $formData['lastname_contact'],
	            	"mail" => $formData['mail_contact'],
	            	"cellphone_contact" => $formData['cellphone_contact'],
	            	"phone_contact" => $formData['phone_contact'],
	            	"contract_type" => '1',
	            	"id_job" => $isPricipal[0]['id_job'],
	            	"id_department" => $isPricipal[0]['id_department'],
	            	"date_admission" => Application_Util_Tools::dateFrontFormat("-", $isPricipal[0]['date_admission']),
            		"cost" => $isPricipal[0]['cost'],
            		"period" => $isPricipal[0]['period'],
            		"mannerofpayment" => $isPricipal[0]['mannerofpayment'],
	            	"canlogin" => $formData['canlogin'],
	            	"user_name" => $formData['user_name'],
	            	"password" => $formData['password'],
	            	"confirm_password" => $formData['password']
            	);

                //Metodo que actualiza un cliente-proveedor-icompany
                $customerService->UpdateCustomer($arrayCompany);
				$this->_redirect('/System/company/index');

            }
        } else {
            //consegimos el id de la direccion
            if ($id > 0) {
                //Metodo para mandar a llarmar todos los parametros de company
                $customer = $customerService->getCustomerById($id);
                $customerForm->getElement('rfc_host')->setValue($customer['rfc']);
                $customerForm->populate($customerService->getAddressById($id));
                $customerForm->populate($customer);

                $isPricipal = $contactService->getContactPrincipalByIdCompany($id);
                if($isPricipal != null){
                	 $customerForm->getElement('id_user')->setValue($isPricipal[0]['id']);
               		 $customerForm->populate($isPricipal[0]);
                }
            }
        }
        $this->view->formulario = $customerForm;
	}

  	//función para mantener cargados los combos basado en el querystring
    public function loadLists(Zend_Form $form){
    	$service = new DistrictsServiceImpl();
    	$neighborService = new NeighborServiceImpl();

    	$stateParam = $this->_getParam('state');
    	$districtParam = $this->_getParam('district');

    	if($stateParam){
    		foreach (json_decode($service->getDistricts($stateParam)) as $values){
    			$form->getElement('district')->addMultiOption($values->id,$values->name);
    		}
    	}
    	if($districtParam){
    		foreach (json_decode($neighborService->getNeighbors($districtParam)) as $values){
    			$form->getElement('neighborhood')->addMultiOption($values->id,$values->colony);
    		}
    	}
    }

    public function addAction(){}
}