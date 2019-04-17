<?php


namespace Gutenberg\Docs;

use BaseController;
use Zend_Auth_Storage_Session;
use Application_Form_MemoryForm;
use Application_Service_Impl_ThemesServiceImpl;


include_once APPLICATION_PATH . '/controllers/BaseController.php';

include_once APPLICATION_PATH .'/forms/MemoryForm.php';
include_once APPLICATION_PATH .'/services/impl/TopicServiceImpl.php';

class MemoryController extends BaseController
{

	public function init()
	{
		BaseController::init();
			
	}
	
	public function indexAction(){}

	public function addAction(){}
	
	public function addmemoryAction()
	{
		$storage = new Zend_Auth_Storage_Session();
		$data = $storage->read();
		if(!$data)
		{
			$this->_redirect('login/login');
		}
		$user = $data->id_user;
		$form=new Application_Form_MemoryForm();
		$memorys=new Application_Service_Impl_ThemesServiceImpl();
		$formData=$this->getRequest()->getPost();
		if($this->getRequest()->isPost()){
			if($form->isValid($formData)){
				$memorysSave=$memorys->addMemory($formData,$user);
				$this->_redirect('users/home');

			}else{

				$form->populate($formData);
			}
		}
		$this->view->form=$form;
	}

	public function deletememoryAction()
	{
		$storage = new Zend_Auth_Storage_Session();
		$data = $storage->read();
		if(!$data)
		{
			$this->_redirect('login/login');
		}
		$deleteParam = $this->_getParam('m');
		$serviceThemes = new Application_Service_Impl_ThemesServiceImpl();
		$serviceThemes->deleteMemory($deleteParam);
		$this->_redirect('users/home');
	}
	
	public function editmemoryAction()
	{
		$storage = new Zend_Auth_Storage_Session();
		$data = $storage->read();
		if(!$data)
		{
			$this->_redirect('login/login');
		}
		$form=new Application_Form_MemoryForm();
		$this->view->form=$form;
		$id = $this->_getParam("m");
		$usr = $data->id_user;
		$themesServ=new Application_Service_Impl_ThemesServiceImpl();
		if($this->getRequest()->isPost()){
			$them = $this->getRequest()->getPost();
			$them['id_theme'] = $id;
			$memoryEdit = $themesServ->edittheme($them);
			if($memoryEdit!= null){
				$this->_redirect("memory/editmemory?id=".$id);
			}else{
				$this->_redirect("users/home");
			}

		}else{
			$memoryEdit = $themesServ->getMemoryBy($id,$usr);
			if(!$memoryEdit){
				$this->_redirect('users/home');
			}else{
				$form->populate($memoryEdit[0]);
			}
			$this->view->info = $memoryEdit[0]['theme_name'];
			$this->view->form = $form;
		}

	}
}

