<?php

namespace Ioteca\Controller;

use Iofractal\Controller\BaseController;
use Application_Service_Impl_ComponentsServiceImpl;
use Application_Form_ComponentForm;
use Application_Entity_ComponentsEntity;
use DaoComponents;

class ComponentsController extends BaseController{
	
    /*function init(){
    	BaseController::init();
	}*/
    
	public function indexAction()
	{
		echo "Controller Components - index"; exit;
		$serviceComponents = new Application_Service_Impl_ComponentsServiceImpl();
		
		$listado = $serviceComponents->listComponents();
		
		if($listado != null){
			$this->view->components = $listado;
		}else{
			$this->view->components = 0;
		}
     }

   	public function addAction(){
        $form = new Application_Form_ComponentForm();
        $serviceComponents = new Application_Service_Impl_ComponentsServiceImpl();

        if($this->getRequest()->isPost()){
        	$formData = $this->getRequest()->getPost();
        	$formData['acl_users_id'] = $this->view->userid;
        	
            if($form->isValid($formData)){
            	
            	$idComponent = $serviceComponents->saveComponent($formData);
            	if($idComponent != null){
            		$this->_redirect("/Ioteca/components/index");
            	}
            }
        }
        
         $this->view->form = $form;
     }
    	
	public function updateAction(){
		$form = new Application_Form_ComponentForm();
		$ComponentsService = new Application_Service_Impl_ComponentsServiceImpl();
		$components = new Application_Entity_ComponentsEntity();
		
    	$idcomponent = $this->_getParam('c');
    	if($this->getRequest()->isPost()){
    		$formData = $this->_getAllParams();
    		$formData['acl_users_id'] = $this->view->userid;
    		$formData['component_id'] = $idcomponent;
    		
    		if($form->isValid($formData)){
    			
    			$componentEdit = $ComponentsService->updateComponents($formData);	
	    		if($componentEdit == true){
	    			$this->_redirect("/Ioteca/components/index");
	    		}else{
	    			$this->_redirect("/Ioteca/components/update?c=".$idcomponent);
	    		}
    		}
    	}else{
    		
    		$component = $ComponentsService->getComponentById($idcomponent);
    		$form->populate($component[0]);
    		$this->view->form = $form;
    	}
	}
		
   public function viewComponentsAction(){
       
	 	$com = new DaoComponents();
		// Asignamos a la vista el resultado de consultar por todos los registros
	    $components = $components->getComponents();
	    
	    $this->view->components = $components;
	
   }
   
	public function deleteAction(){
		$deleteParam = $this->_getParam("erase");  
		
        if ($this->getRequest()->isPost()) {
         	$serviceComponents = new Application_Service_Impl_ComponentsServiceImpl();
         	$serviceComponents->deleteComponents($deleteParam);
        }
        exit();
    }
}