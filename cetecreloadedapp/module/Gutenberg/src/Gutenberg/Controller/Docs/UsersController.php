<?php 

namespace Gutenberg\Docs;

use BaseController;
use Application_Form_UsersForm;
use Application_Service_Impl_UsersServiceImpl;
use Zend_Auth_Storage_Session;
use Application_Service_Impl_ThemesServiceImpl;
use Application_Service_Impl_ContaintsServiceImpl;
use Application_Form_PasswordForm;

include_once APPLICATION_PATH .'/forms/LoginForm.php';
    include_once APPLICATION_PATH .'/forms/PaswwordForm.php';
    include_once APPLICATION_PATH .'/models/DirectoryUsers.php';
	require_once APPLICATION_PATH .'/services/impl/UsersServiceImpl.php';
	require_once APPLICATION_PATH .'/entities/UsersEntity.php';
	require_once APPLICATION_PATH .'/forms/UserForm.php';
	require_once APPLICATION_PATH .'/entities/ContentsEntity.php';
	require_once APPLICATION_PATH .'/services/impl/ContentsServiceImpl.php';
	require_once APPLICATION_PATH .'/services/impl/ThemesServiceImpl.php';
	require_once APPLICATION_PATH .'/services/impl/UsersServiceImpl.php';
	include_once 'BaseController.php';
	require_once APPLICATION_PATH .'/dto/PublicationDto.php';
	class UsersController extends BaseController{
		
		public function init(){

		}
		
		public function registerAction(){
			$form=new Application_Form_UsersForm();
			$users=new Application_Service_Impl_UsersServiceImpl();
			
			$formData=$this->getRequest()->getPost();
			
			if($this->getRequest()->isPost()){
				if($form->isValid($formData)){
					$usersSave=$users->addUser($formData);
					$this->_redirect('login/login');
				
			}else{
				
					$form->populate($formData);
			}
			}
			$this->view->form=$form;
			
		}
		public function homeAction()
        {
            $storage = new Zend_Auth_Storage_Session();
            $data = $storage->read();
            $id = $data->id_user;
            if(!$data)
            {
                $this->_redirect('login/login');
            }
			$themes= new Application_Service_Impl_ThemesServiceImpl();
			$this->view->themes=$themes->getAllThemesUsers($id);
			$lala = $themes->getAllThemesUsers($id);
            $this->view->username = $data->username;
            $this->view->name_user = $data->name_user;
            $this->view->last_name = $data->last_name;
        }
        public function managerpublicationsAction(){
        	$storage = new Zend_Auth_Storage_Session();
            $data = $storage->read();
            $rol = $data->rol;
            if(!$data)
            {
            	$this->_redirect('login/login');
            }else if($rol != 1){
            	$this->_redirect('users/home');
            }
            $containts= new Application_Service_Impl_ContaintsServiceImpl();
            $this->view->containts=$containts->getAllContaintsG();

        }
        public function managerusersAction(){
        	$storage = new Zend_Auth_Storage_Session();
            $data = $storage->read();
            $rol = $data->rol;
            $id = $data->id_user;
            if(!$data)
            {
            	$this->_redirect('login/login');
            }else if($rol != 1){
            	$this->_redirect('users/home');
            }
            $users= new Application_Service_Impl_UsersServiceImpl();
			$this->view->users=$users->getAllUsers($id);
        }
        public function deleteuserAction(){
            $storage = new Zend_Auth_Storage_Session();
            $data = $storage->read();
            $rol = $data->rol;
            if(!$data)
            {
                $this->_redirect('login/login');
            }else if($rol != 1){
                $this->_redirect('users/home');
            }
			$deleteParam = $this->_getParam('u');
			$serviceUsers = new Application_Service_Impl_UsersServiceImpl();
        	$serviceUsers->deleteUsers($deleteParam);

        	$this->_redirect('/users/managerusers');
        }
        public function passwordAction(){
            $storage = new Zend_Auth_Storage_Session();
            $data = $storage->read();
            $iduser = $data->id_user;
            if(!$data){
                $this->_redirect('login/login');
            }
            $form=new Application_Form_PasswordForm();
            $this->view->form=$form;
            if($this->getRequest()->isPost()){
                if($form->isValid($_POST)){
                    $pass = $form->getValues();
                    if($pass['password'] != $pass['confirmpassword']){
                        $this->view->errorMessage = "New Password and confirm new password don't match.";
                        return;
                    }else{
                        $passwordServ = new Application_Service_Impl_UsersServiceImpl();
                        $passwordSave=$passwordServ->updatePassword($pass,$iduser);
                        $this->_redirect('users/home');
                    }
                }
            }
	   }
    }

?>
