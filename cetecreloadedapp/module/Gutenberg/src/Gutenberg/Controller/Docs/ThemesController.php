<?php
namespace Gutenberg\Docs;

use BaseController;
use Application_Form_ThemesForm;
use Zend_Auth_Storage_Session;
use Application_Service_Impl_ThemesServiceImpl;

 APPLICATION_PATH .'/forms/ThemesForm.php';
include_once APPLICATION_PATH . '/entities/ThemesEntity.php';
include_once APPLICATION_PATH .'/services/impl/ThemesServiceImpl.php';

include_once 'BaseController.php';
class ThemesController extends BaseController
{

	public function init()
	{
		/* Initialize action controller here */
	}

	public function homeAction()
	{
		$form=new Application_Form_ThemesForm();
		$this->view->form=$form;
		$storage = new Zend_Auth_Storage_Session();
		$data = $storage->read();
		if(!$data)
		{
			$this->_redirect('login/login');
		}
		$user = $data->id_user;
		$memoryId = $this->_getParam("m");
		if(!$memoryId){
			$memoryId = 0;
		}
		$themes= new Application_Service_Impl_ThemesServiceImpl();
		$themm = $themes->getMemory($memoryId,$user);
		if(!$themm){
			$this->_redirect('users/home');
		}
		$them=$themes->getAllThemesMemory($memoryId);
		$this->view->themees = $themm;
		$this->view->themes = $them;
		$this->view->memory = $memoryId;
	}
	public function addthemeAction()
	{
		$storage = new Zend_Auth_Storage_Session();
		$data = $storage->read();
		if(!$data)
		{
			$this->_redirect('login/login');
		}
		$memoryId = $this->_getParam("m");
		$user = $data->id_user;
		$form=new Application_Form_ThemesForm();
		$themes=new Application_Service_Impl_ThemesServiceImpl();
		$mems = $themes->getMemoryVal($memoryId,$user);
		if(!$mems){
			$this->_redirect('users/home');
		}
		$formData=$this->getRequest()->getPost();
		$formData['parent']=$memoryId;
		$formData['id_user']=$user;
		if($this->getRequest()->isPost()){
			if($form->isValid($formData)){
				$themesSave=$themes->addThemes($formData);
				$this->_redirect('themes/home?m='.$memoryId);

			}else{
				$form->populate($formData);
			}
		}
		$this->view->form=$form;
	}


	public function editthemeAction(){

		$storage = new Zend_Auth_Storage_Session();
		$data = $storage->read();
		$rol = $data->rol;
		if(!$data){
			$this->_redirect('login/login');
		}

		$form=new Application_Form_ThemesForm();
		$this->view->form=$form;
		$themesServ=new Application_Service_Impl_ThemesServiceImpl();
		$memoryId = $this->_getParam("m");
		$id = $this->_getParam("id");
		$user = $data->id_user;
		$mems = $themesServ->getMemoryVal($memoryId,$user);
		$thems = $themesServ->getThemeVal($id,$memoryId,$user);
		if(!$mems){
			$this->_redirect('users/home');
		}
		if(!$thems){
			$this->_redirect('themes/home?m='.$memoryId);
		}
		if($this->getRequest()->isPost()){
			$them = $this->getRequest()->getPost();
			$them['id_theme'] = $id;
			$themeEdit = $themesServ->editTheme($them);
			if($thematicEdit!= null){
				$this->_redirect("themes/edittheme?id=".$id);
			}else{
				$this->_redirect("themes/home?m=".$memoryId);
			}

		}else{
			$themeEdit = $themesServ->getThemesBy($id);
			if(!$themeEdit){
				print_r("Error");
			}else{
				$form->populate($themeEdit[0]);
			}
			$this->view->info = $themeEdit[0]['theme_name'];
			$this->view->form = $form;

		}
	}

	public function deletethemeAction(){
		$storage = new Zend_Auth_Storage_Session();
		$data = $storage->read();
		$memoryId = $this->_getParam("m");
		$deleteParam = $this->_getParam('id');
		if(!$data)
		{
			$this->_redirect('login/login');
		}
		$serviceThemes = new Application_Service_Impl_ThemesServiceImpl();
		$serviceThemes->deleteTheme($deleteParam);

		$this->_redirect("themes/home?m=".$memoryId);
	}

}

?>