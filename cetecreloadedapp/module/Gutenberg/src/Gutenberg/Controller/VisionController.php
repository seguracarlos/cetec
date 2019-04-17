<?php 

namespace Gutenberg;

use BaseController;
use Application_Service_Impl_VisionServiceImpl;
use Application_Form_VisionForm;
use Application_Entity_VisionEntity;



include_once APPLICATION_PATH . '/services/VisionService.php';
include_once APPLICATION_PATH . '/services/ACLCheckerService.php';

require APPLICATION_PATH . '/controllers/BaseController.php';

class VisionController extends BaseController{
	
	public function init(){
		BaseController ::init();
	}
	
	public function indexAction(){
		$serviceVision = new Application_Service_Impl_VisionServiceImpl();
		$listado = $serviceVision->listVision();
		
		if($listado != null){
			$this->view->vision = $listado;
		}else{
			$this->view->vision = 0;
		}
	}
	
	public function addAction(){
		$form = new Application_Form_VisionForm();
		$serviceVision = new Application_Service_Impl_VisionServiceImpl();
		
		if($this->getRequest()->isPost()){
			$formData = $this->getRequest()->getPost();
			if($form->isValid($formData)){
				$idvision = $serviceVision->saveVision($formData);
				if ($idvision != null) {
					$this->_redirect("/Gutenberg/vision/index");
				}
			}
		}
		$this->view->form = $form;
	}
   
	public function updateAction(){
		$form = new Application_Form_VisionForm();
		$visionService = new Application_Service_Impl_VisionServiceImpl();
		$vision = new Application_Entity_VisionEntity();
		
		$idvision = $this->_getParam('c');
		if($this->getRequest()->isPost()){
			$formData = $this->_getAllParams();
			$formData['idvision'] = $idvision;
	
			if($form->isValid($formData)){
				$visionEdit = $visionService->updateVision($formData);
				if($visionEdit == true){
					$this->_redirect("/Gutenberg/vision/index");
				}else{
					$this->_redirect("/Gutenberg/vision/details?c=".$idvision);
				}
			}
		}else{
			$vision = $visionService->getVisiontById($idvision);
			$form->populate($vision[0]);
			$this->view->form = $form;
		}
	}
    
	public function deleteAction(){
		$deleteParam = $this->getParam("erase");
		
		if ($this->getRequest()->isPost()){
			$serviceVision = new Application_Service_Impl_VisionServiceImpl();
			$serviceVision->deleteVision($deleteParam);
		}
	}
   
	public function detailsAction() {
		$visionService = new Application_Service_Impl_VisionServiceImpl();
		$vision = new Application_Entity_VisionEntity();
		$id_vision = $this->_getParam('c');
		
		$vision = $visionService->getVisiontById($id_vision);
		$this->view->vision = $vision[0];
		
	}
}
        