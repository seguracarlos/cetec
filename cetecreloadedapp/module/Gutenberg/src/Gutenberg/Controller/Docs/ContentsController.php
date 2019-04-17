<?php


namespace Gutenberg\Docs;

use BaseController;
use Application_Service_Impl_TopicServiceImpl;
use Application_Service_Impl_ContentsServiceImpl;
use Zend_Auth_Storage_Session;
use Application_Form_ContentForm;
use Application_Service_Impl_ThemesServiceImpl;


include_once APPLICATION_PATH . '/forms/ContentForm.php';
include_once APPLICATION_PATH . '/entities/ContentsEntity.php';
include_once APPLICATION_PATH . '/services/impl/ContentsServiceImpl.php';
include_once APPLICATION_PATH . '/services/impl/TopicServiceImpl.php';
include_once APPLICATION_PATH . '/entities/TopicEntity.php';
include_once APPLICATION_PATH . '/controllers/BaseController.php';

class ContentsController extends BaseController {
	public function init() {
		BaseController::init();
	}
	
	public function indexAction(){
		$themes= new Application_Service_Impl_TopicServiceImpl();
		$this->view->consult=json_encode($themes->getAllthemes());
	}
	
	public function addAction(){}
	
	public function contentAction() {
		$id = $this->_getParam ( 'p' );
		$contents = new Application_Service_Impl_ContentsServiceImpl ();
		$this->view->contents = $contents->getAllContents ( $id );
	}
	public function tcontentsAction() {
		$id = $this->_getParam ( 't' );
		$contents = new Application_Service_Impl_ContentsServiceImpl ();
		$this->view->contents = $contents->getAllContentst ( $id );
	}
	public function addcontentAction() {
		$storage = new Zend_Auth_Storage_Session ();
		$data = $storage->read ();
		if (! $data) {
			$this->_redirect ( 'login/login' );
		}
		$form = new Application_Form_ContentForm ();
		$contents = new Application_Service_Impl_ContentsServiceImpl ();
		$id_theme = $this->_getParam ( "idtheme" );
		$sub = $this->_getParam ( "s" );
		$user = $data->id_user;
		if ($this->getRequest ()->isPost ()) {
			$formData = $this->getRequest ()->getPost ();
			$formData ['id_theme'] = $id_theme;
			$formData ['id_user'] = $user;
			if ($form->isValid ( $formData )) {
				if ($formData ['id_content'] == 0) {
					$contentSave = $contents->addContent ( $formData );
				} else {
					$contentEdit = $contents->editContent ( $formData );
				}
				$this->_redirect ( 'subthemes/home?idtheme=' . $sub );
			} else {
				
				$form->populate ( $formData );
			}
		} else {
			$contentEdit = $contents->getContentById ( $id_theme, $user );
		}
		if ($contentEdit) {
			$form->populate ( $contentEdit [0] );
			$this->view->info = $contentEdit [0] ['content'];
		}
		$this->view->form = $form;
	}
	public function addcontentmemoryAction() {
		$storage = new Zend_Auth_Storage_Session ();
		$data = $storage->read ();
		if (! $data) {
			$this->_redirect ( 'login/login' );
		}
		$form = new Application_Form_ContentForm ();
		$contents = new Application_Service_Impl_ContentsServiceImpl ();
		$memorys = new Application_Service_Impl_ThemesServiceImpl ();
		$id_theme = $this->_getParam ( "idtheme" );
		$user = $data->id_user;
		$mems = $memorys->getMemoryVal ( $id_theme, $user );
		if (! $mems) {
			$this->_redirect ( 'users/home' );
		}
		if ($this->getRequest ()->isPost ()) {
			$formData = $this->getRequest ()->getPost ();
			$formData ['id_theme'] = $id_theme;
			$formData ['id_user'] = $user;
			if ($form->isValid ( $formData )) {
				if ($formData ['id_content'] == 0) {
					$contentSave = $contents->addContent ( $formData );
				} else {
					$contentEdit = $contents->editContent ( $formData );
				}
				$this->_redirect ( 'users/home' );
			} else {
				
				$form->populate ( $formData );
			}
		} else {
			$contentEdit = $contents->getContentById ( $id_theme, $user );
		}
		if ($contentEdit) {
			$form->populate ( $contentEdit [0] );
			$this->view->info = $contentEdit [0] ['content'];
		}
		$this->view->form = $form;
	}
	public function addcontentthemesAction() {
		$storage = new Zend_Auth_Storage_Session ();
		$data = $storage->read ();
		if (! $data) {
			$this->_redirect ( 'login/login' );
		}
		$form = new Application_Form_ContentForm ();
		$contents = new Application_Service_Impl_ContentsServiceImpl ();
		$id_theme = $this->_getParam ( "idtheme" );
		$sub = $this->_getParam ( "m" );
		$user = $data->id_user;
		$memorys = new Application_Service_Impl_ThemesServiceImpl ();
		$mems = $memorys->getMemoryVal ( $sub, $user );
		$thems = $memorys->getThemeVal ( $id_theme, $sub, $user );
		if (! $mems) {
			$this->_redirect ( 'users/home' );
		}
		if (! $thems) {
			$this->_redirect ( 'themes/home?m=' . $sub );
		}
		if ($this->getRequest ()->isPost ()) {
			$formData = $this->getRequest ()->getPost ();
			$formData ['id_theme'] = $id_theme;
			$formData ['id_user'] = $user;
			if ($form->isValid ( $formData )) {
				if ($formData ['id_content'] == 0) {
					$contentSave = $contents->addContent ( $formData );
				} else {
					$contentEdit = $contents->editContent ( $formData );
				}
				$this->_redirect ( 'themes/home?m=' . $sub );
			} else {
				
				$form->populate ( $formData );
			}
		} else {
			$contentEdit = $contents->getContentById ( $id_theme, $user );
		}
		if ($contentEdit) {
			$form->populate ( $contentEdit [0] );
			$this->view->info = $contentEdit [0] ['content'];
		}
		$this->view->form = $form;
	}
	public function acontentAction() {
		$id = $this->_getParam ( 'a' );
		$contents = new Application_Service_Impl_ContentsServiceImpl ();
		$this->view->contents = $contents->getAllContentsa ( $id );
	}
	public function editcontentAction() {
		$form = new Application_Form_ContentForm ();
		$contents = new Application_Service_Impl_ContentsServiceImpl ();
		$id = $this->_getParam ( "id" );
		if (! $id) {
			$id = 0;
		}
		$storage = new Zend_Auth_Storage_Session ();
		$data = $storage->read ();
		$idus = $data->id_user;
		$rol = $data->rol;
		
		if (! $data) {
			$this->_redirect ( 'login/login' );
		}
		
		if ($this->getRequest ()->isPost ()) {
			$content = $this->getRequest ()->getPost ();
			$content ['id_content'] = $id;
			$contentEdit = $contents->editContent ( $content );
			if ($containtEdit != null) {
				
				$this->_redirect ( "contents/editcontent?id=" . $id );
			} else {
				$this->_redirect ( "users/home" );
			}
		} else {
			if ($rol == 1) {
				$contentEdit = $contents->getContentByIdA ( $id );
			} else {
				$contentEdit = $contents->getContentById ( $id, $idus );
			}
			
			if (! $contentEdit) {
				$this->_redirect ( 'users/home' );
			} else {
				$form->populate ( $contentEdit [0] );
			}
			$this->view->info = $contentEdit [0] ['content'];
			
			$this->view->form = $form;
		}
	}
	public function consultAction() {
		$id = $this->_getParam ( 'c' );
		$contents = new Application_Service_Impl_ContentsServiceImpl ();
		$themes = new Application_Service_Impl_ThemesServiceImpl ();
		$this->view->themes = $themes->getAllConsulThemes ( $id );
		$this->view->contents = $contents->getContent ( $id );
		$this->view->parents = $themes->getParent ( $id );
	}
}