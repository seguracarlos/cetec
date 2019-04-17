<?php
namespace System\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

//Incluimos controller base
use Iofractal\Controller\BaseController;
use Registrationusers\Model\AdressModel;
use System\Services\UsersService;
use Zend\Session\Container;
use System\Form\EditProfileForm;
use System\Services\ProfileService;
use Auth\Utility\UserPassword;


class ProfileController extends BaseController
{
	private $usersService;
	private $session;
	private $profileService;
	
	public function __construct()
	{	
		$this->session = new Container('User');
	}
	
	public function getUsersService()
	{
		return $this->usersService = new UsersService();	
	}
	
	public function getProfileService()
	{
		return $this->profileService = new ProfileService();
	}
	
	public function indexAction()
	{
		$session = new Container('User');
		
		$id_user = $session->offsetGet('userId');
		$user    = $this->getUsersService()->getUserById($id_user);
		$address = new AdressModel();
		$profileInfo = $this->getProfileService()->getProfileByUser($id_user);
		if($profileInfo){
			$action = 0;
		}else{
			$action = 1;
		}	
		$addresInfo = $address->getRowById($user[0]['user_id']);
		if($user){
			return new ViewModel(array("id_user" => $id_user, "user" => $user,"address" => $addresInfo,"profileInfo" => $profileInfo,"action" => $action));
		}else{
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl(). '/system/users/index');
		}
		return new ViewModel($data);
	}
	
	public function addAction()
	{
	}
	
	public function editAction()
	{
		$request  = $this->getRequest();
		$response = $this->getResponse();
		if($request->isPost()){
			$formData = $request->getPost();
			//addInfo
			if($formData['status']==1){
				$ProfileInfo = $this->getProfileService()->addProfileInfo($formData);	
			}else{
			//updateInfo
				$ProfileInfo = $this->getProfileService()->updateProfileInfo($formData);
			}
			
			$data = array(
					"user_id" => $formData['id_user'],
					"photofile" => $formData['photofile']
			);
			
			$updatePhoto = $this->getUsersService()->editUser($data);
			
			if($ProfileInfo){
				$response->setContent(\Zend\Json\Json::encode(array('response' => true, 'data' => $ProfileInfo)));
			}else{
				$response->setContent(\Zend\Json\Json::encode(array('response' => false, "data" => "Error desconosido, consulta al administrador *.*")));
			}
			//echo "<pre>"; print_r($inf); exit;
		}
		return $response;
		exit;
// 		$id   = $this->params()->fromRoute("id", null);
// 		$form = new EditProfileForm("formEditProfile");
// 		$user = $this->getUsersService()->getUserById($id);
		
// 		if($this->getRequest()->isPost()){
// 			$formData = $this->getRequest()->getPost();
// 			//Validamos si se va a editar el perfil o la contrase�a
// 			if(isset($formData['checkPass']) && $formData['checkPass'] == "ok"){
// 				//print_r($formData);exit;
// 				$validatePass = $this->getUsersService()->validatePassword($formData);
// 				if($validatePass[0]['count'] != 0){
// 					$response = $this->getResponse()->setContent(\Zend\Json\Json::encode(array('response' => "true", "data" => $validatePass)));
// 				}else{
// 					$response = $this->getResponse()->setContent(\Zend\Json\Json::encode(array('response' => "false", "data" => $validatePass)));
// 				}
// 				return $response;
		
// 			}elseif(isset($formData['password1'])){
// 				$chagePassword = $this->getUsersService()->changePassword($formData, $id);
// 				print_r( $chagePassword );exit;
// 			}else{
// 				$editProfile = $this->getUsersService()->editUser($formData);
// 				print_r( $editProfile );exit;
// 			}
// 		}
		
// 		$form->setData($user[0]);
// 		return new ViewModel(array("form" => $form));

		
	}
	
	public function deleteAction()
	{
	}
	
	public function changepasswordAction(){
		$session = new Container('User');
		$createPassword = new UserPassword();
		$id_user=$session->offsetGet('userId');
		$userPass = $this->getUsersService()->getUserById($id_user);
	
		if ($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost()->toArray();
			$actualPass = isset($data['actualpass']) ? $createPassword->securePassword(trim($data['actualpass'])) : false;
		if($userPass[0]['password'] == $actualPass){
				$updatePass = $this->getUsersService()->changePassword($data,$id_user);
				if($updatePass){
					$response = $this->getResponse()->setContent(\Zend\Json\Json::encode(array("response"=>"ok")));
				}else{
					$response = $this->getResponse()->setContent(\Zend\Json\Json::encode(array("response"=>"Intentalo de nuevo")));
				}
			}else{
				$response = $this->getResponse()->setContent(\Zend\Json\Json::encode(array("response"=>"Diferentes")));
			}
			return $response;
		}
	
		exit;
	}

}