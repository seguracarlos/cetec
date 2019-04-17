<?php

namespace Ioteca\Controller;

use Iofractal\Controller\BaseController;
use Application_Service_Impl_LinkServiceImpl;
use Application_Form_Link;
use Application_Entity_LinkEntity;
use DaoLinks;

class LinkController extends BaseController{
    
    /*function init(){
        BaseController::init();
    }*/
    
    public function indexAction()
    {
    	echo "Controller link - index"; exit;
        $serviceLinks = new Application_Service_Impl_LinkServiceImpl();
        
        $listado = $serviceLinks->listLinks();
        
        if($listado != null){
            $this->view->link = $listado;
        }else{
            $this->view->link = 0;
        }
     }

    public function addAction(){
        $form = new Application_Form_Link();
        $serviceLinks = new Application_Service_Impl_LinkServiceImpl();

        if($this->getRequest()->isPost()){
            $formData = $this->getRequest()->getPost();
            $formData['acl_users_id'] = $this->view->userid;
            
            if($form->isValid($formData)){
                
                $idComponent = $serviceLinks->saveLink($formData);
                if($idComponent != null){
                    $this->_redirect("/Ioteca/link");
                }
            }
        }
        
         $this->view->form = $form;
     }
        
    public function updateAction(){
        $form = new Application_Form_Link();
        $LinksService = new Application_Service_Impl_LinkServiceImpl();
        $links = new Application_Entity_LinkEntity();
        
        $idlink = $this->_getParam('l');
        if($this->getRequest()->isPost()){
            $formData = $this->_getAllParams();
            $formData['acl_users_id'] = $this->view->userid;
            $formData['idlink'] = $idlink;
            
            if($form->isValid($formData)){
                
                $linkEdit = $LinksService->updateLinks($formData);   
                if($linkEdit == true){
                    $this->_redirect("/Ioteca/link/index");
                }else{
                    $this->_redirect("/Ioteca/link/update?l=".$idlink);
                }
            }
        }else{
            
            $link = $LinksService->getLinkById($idlink);
            $form->populate($link[0]);
            $this->view->form = $form;
        }
    }
        
   public function viewLinksAction(){
       
        $com = new DaoLinks();
        // Asignamos a la vista el resultado de consultar por todos los registros
        $links = $links->getLinks();
        
        $this->view->links = $links;
    
   }
   
    public function deleteAction(){
        $deleteParam = $this->_getParam("erase");  
        
        if ($this->getRequest()->isPost()) {
            $serviceLinks = new Application_Service_Impl_LinkServiceImpl();
            $serviceLinks->deleteLinks($deleteParam);
        }
        exit();
    }
}

