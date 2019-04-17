<?php

namespace Gutenberg\Docs;

use BaseController;
use Zend_Auth_Storage_Session;
use Application_Service_Impl_ThemesServiceImpl;
use Application_Form_SubthemesForm;

 APPLICATION_PATH .'/forms/SubthemesForm.php';
include_once APPLICATION_PATH . '/entities/ThemesEntity.php';
include_once APPLICATION_PATH .'/services/impl/ThemesServiceImpl.php';
include_once 'BaseController.php';
class SubThemesController extends BaseController
{

	public function init()
	{
		/* Initialize action controller here */
	}

	public function homeAction()
	{

		$storage = new Zend_Auth_Storage_Session();
		$data = $storage->read();
		if(!$data)
		{
			$this->_redirect('login/login');
		}
		$user = $data->id_user;
		$t = $this->_getParam("idtheme");
		if(!$t){
			$this->redirect('users/home');
		}else if($t==0){
			$this->redirect('users/home');
		}
		$themeId = (int)$t;
		$subthemes= new Application_Service_Impl_ThemesServiceImpl();
		$subthemm = $subthemes->getAllThemesMemory($themeId);
		$subthem=$subthemes->getAllThemesMemory($themeId);
		$this->view->subthemees = $subthemm;
		$this->view->subthemes = $subthem;
		$this->view->themeId = $themeId;
	}

	public function addsubthemeAction(){

		$storage = new Zend_Auth_Storage_Session();
		$data = $storage->read();
		if(!$data)
		{
			$this->_redirect('login/login');
		}
		$themes=new Application_Service_Impl_ThemesServiceImpl();
		$themeId = $this->_getParam("m");
		$user = $data->id_user;
		$subthems = $themes->getSubthemeVal($themeId,$user);
		if(!$subthems){
			$this->_redirect('users/home');
		}
		$form=new Application_Form_SubthemesForm();
		$formData=$this->getRequest()->getPost();
		$formData['parent']=$themeId;
		$formData['id_user']=$user;
		if($this->getRequest()->isPost()){
			if($form->isValid($formData)){
				$themesSave=$themes->addThemes($formData);
				$this->_redirect('subthemes/home?idtheme='.$themeId);

			}else{
				$form->populate($formData);
			}
		}
		$this->view->form=$form;
	}

	public function editsubthemeAction(){

		$storage = new Zend_Auth_Storage_Session();
		$data = $storage->read();
		$rol = $data->rol;
		if(!$data){
			$this->_redirect('login/login');
		}

		$form=new Application_Form_SubthemesForm();
		$this->view->form=$form;
		$themesServ=new Application_Service_Impl_ThemesServiceImpl();
		$themeId = $this->_getParam("m");
		$id = $this->_getParam("id");

		if($this->getRequest()->isPost()){
			$them = $this->getRequest()->getPost();
			$them['id_theme'] = $id;
			$themeEdit = $themesServ->editTheme($them);
			if($thematicEdit!= null){
				$this->_redirect("subthemes/edittheme?id=".$id);
			}else{
				$this->_redirect("subthemes/home?idtheme=".$themeId);
			}

		}else{
			$themeEdit = $themesServ->getThemesBy($id);
			if(!$themeEdit){
				print hola;
			}else{
				$form->populate($themeEdit[0]);
			}
			$this->view->info = $themeEdit[0]['theme_name'];
			$this->view->form = $form;
		}
	}

	public function deletesubthemeAction(){

		$storage = new Zend_Auth_Storage_Session();
		$data = $storage->read();
		$deleteParam = $this->_getParam('id');
		$themeId = $this->_getParam("m");
		if(!$data)
		{
			$this->_redirect('login/login');
		}
		$serviceThemes = new Application_Service_Impl_ThemesServiceImpl();
		$serviceThemes->deleteTheme($deleteParam);

		$this->_redirect('subthemes/home?idtheme='.$themeId);
	}


}

?>