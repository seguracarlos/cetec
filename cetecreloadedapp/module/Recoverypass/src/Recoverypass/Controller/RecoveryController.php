<?php
namespace Recoverypass\Controller;

use Iofractal\Controller\BaseController;
use Zend\View\Model\ViewModel;
use Auth\Form\LoginForm;
use Auth\Form\Filter\LoginFilter;
use Auth\Utility\UserPassword;
use Zend\Session\Container;
use Company\Model\CompanyModel;
use Company\Model\PreferencesModel;
use Registrationusers\Service\RegisterService;
use Registrationusers\Service\FichaRegistroService;
use Auth\Utility\Mail2;
use Auth\Form\RecoveryForm;
use System\Services\UsersService;

class RecoveryController extends BaseController
{
	
	public function validatemailAction(){
		$registerService = new RegisterService();
		$fichaRegistroService = new FichaRegistroService();
		$mail = new Mail2();
		$response = $this->getResponse();
		//echo "hola"; exit;
		if ($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost()->toArray();
			$validateMail = $registerService->getRowByMail($data['email']);
			if($validateMail){
				$validateToken = $fichaRegistroService->getTokenByUser($validateMail[0]['user_id']);
				if($validateToken){
					$token = $fichaRegistroService->updateToken($validateMail[0]['user_id']);
				}else{
					$token=$fichaRegistroService->addToken($validateMail[0]['user_id']);
				}
				 
				$url =  "https://www.horusrobot.mx/cetecreloaded/recoverypass/recovery/recoverypass/" . $token['token'];
				//$url =  "127.0.0.1/bachillerato/cetecreloaded/recoverypass/recovery/recoverypass/" . $token['token'];
				$mail->sendPassLink($data['email'], $url);
				$response = $this->getResponse()->setContent(\Zend\Json\Json::encode(array("response"=>"send")));
			}else{
				$response = $this->getResponse()->setContent(\Zend\Json\Json::encode(array("response"=>"unknow")));
			}
			return $response;
		}
		 
		exit;
	}
	
	public function recoverypassAction(){
		$layout = $this->layout();
		$layout->setTemplate('layout/layoutLogin');
		$form = new RecoveryForm('recoveryForm');
		$view      = new ViewModel();
		$fichaRegistro = new FichaRegistroService();
		$userDetails = new RegisterService();
		$userService = new UsersService();
		 
		if($this->getRequest()->isPost()){
			$formData = $this->getRequest()->getPost()->toArray();				
			$changePass = $userService->changePassword($formData, $formData['id_user']);
			if($changePass){
				$deleteToken =  $fichaRegistro->deleteToken($formData['id_user']);
				$this->flashMessenger()->setNamespace("add_correcto")->addMessage("La contraseña se cambio exitosamente");
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/login');
			}
		}else{
			$token=$this->params()->fromRoute("id");
			$validateToken = $fichaRegistro->getToken($token);
			if($validateToken){
				$user = $userDetails->getRowById($validateToken[0]['id_user']);
				 
				$view->setVariable('recoveryForm', $form);
				$view->setVariable('user_id', $user[0]['user_id']);
				return $view;
			}else{
				$this->flashMessenger()->setNamespace("add_correcto")->addMessage("Este link ha caducado");
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/login');
			}
		}
		 
	}
	

}