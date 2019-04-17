<?php
namespace System\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

//Incluimos controller base
use Iofractal\Controller\BaseController;
use System\Services\UsersService;
use System\Form\UserForm;
use System\Services\UsersRolesServices;
use Zend\Session\Container;
use System\Form\EditProfileForm;
use System\Services\RoleService;

class UsersController extends AbstractActionController
{
	private $usersService;
	private $session;
	
	public function __construct()
	{	
		$this->session = new Container('User');
	}
	
	public function getUsersService()
	{
		return $this->usersService = new UsersService();	
	}
	
	public function indexAction()
	{
		$users = $this->getUsersService()->fetchAll($type_user = null);
		//echo "<pre>"; print_r($users);exit;
		$data  = array(
			"users" => $users
		);
		
		return new ViewModel($data);
	}
	
	public function addAction()
	{
    	$form            = new UserForm("userForm");
    	$view            = array("form" => $form);
    	$request         = $this->getRequest();
    	
    	if($request->isPost()){
    		$formData   = $request->getPost()->toArray();
    		$addUsers   = $this->getUsersService()->addUser($formData);
    		
    		if($addUsers){
    			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/system/users/index');
    		}
    	}
    	
    	return new ViewModel($view);
	}
	
	public function editAction()
	{
		$form            = new UserForm("userForm");
		$id_user         = $this->params()->fromRoute("id",null);
		$user            = $this->getUsersService()->getUserById($id_user);
		//echo "<pre>"; print_r($user); exit;
		$form->setData($user[0]);
		$view            = array("form" => $form, "role_user" => $user[0]['role']);
		
		if($this->getRequest()->isPost()){
			$formData  = $this->getRequest()->getPost()->toArray();
			$edit_user = $this->getUsersService()->editUser($formData);
			
				if($edit_user){
					return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/system/users/index');
				}else{
					$this->flashMessenger()->setNamespace("duplicado")->addMessage("El usuario no se ha modificado");
					return $this->redirect()->refresh();
				}
		}
		return new ViewModel($view);
	}
	
/*
     * DELETEACTION
     */
    public function deleteAction()
    {
    	$request = $this->getRequest();
    	$id      = (int) $this->params()->fromRoute('id', 0);
    
    	if (!$id) {
    		return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/system/users/index');
    	}
    
    	if ($request->isPost()) {
    		$del = $request->getPost()->toArray();
    
    		if ($del['del'] == 'Aceptar'){
    			$id = (int) $del['id'];
    			$this->getUsersService()->deleteUser($id);
    		}
    
    		// Redirect to list of customers
    		return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/system/users/index');
    	}
    
    	$data = $this->getUsersService()->getUserById($id);
    	 
    	return array(
    			'id'    => $id,
    			'data'  => $data[0]
    	);
    }
	
	//Ver un usuario
	public function seeAction()
	{
		$id_user = (int) $this->params()->fromRoute("id", null);
		$user    = $this->getUsersService()->getUserById($id_user);
		//echo "<pre>"; print_r($user);exit;
		if($user){
			return new ViewModel(array("id_user" => $id_user, "user" => $user));
		}else{
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl(). '/system/users/index');
		}
	}
	
	public function profileAction()
	{
		//print_r('hola');exit();
		$session = new Container('User');
		$data    = array(
				"userId"     => $session->offsetGet('userId'),
				"user_email" => $session->offsetGet('email'),
				"rol_name"   => $session->offsetGet('roleName'),
				"user_name"  => $session->offsetGet('name'),
				"surname"    => $session->offsetGet('surname'),
				"lastname"   => $session->offsetGet('lastname')
		);
		return new ViewModel($data);
	}
	
	//Editar perfil de usuario
	public function editprofileAction()
	{
		$id   = $this->params()->fromRoute("id", null);
		$form = new EditProfileForm("formEditProfile");
		$user = $this->getUsersService()->getUserById($id);
		
		if($this->getRequest()->isPost()){
			$formData = $this->getRequest()->getPost();
			//Validamos si se va a editar el perfil o la contraseï¿½a
			if(isset($formData['checkPass']) && $formData['checkPass'] == "ok"){
				//print_r($formData);exit;
				$validatePass = $this->getUsersService()->validatePassword($formData);
				if($validatePass[0]['count'] != 0){
					$response = $this->getResponse()->setContent(\Zend\Json\Json::encode(array('response' => "true", "data" => $validatePass)));
				}else{
					$response = $this->getResponse()->setContent(\Zend\Json\Json::encode(array('response' => "false", "data" => $validatePass)));
				}
				return $response;
				
			}elseif(isset($formData['password1'])){
				$chagePassword = $this->getUsersService()->changePassword($formData, $id);
				print_r( $chagePassword );exit;
			}else{
				$editProfile = $this->getUsersService()->editUser($formData);
				print_r( $editProfile );exit;
			}
		}
		
		$form->setData($user[0]);
		return new ViewModel(array("form" => $form));
	}
	
	// Metodo que obtiene los roles disponibles
	public function getAllRolesAction()
	{
		$roleService = new RoleService();
		$roles       = $roleService->fetchAll();
		
		if($this->getRequest()->isPost()){
			$response = $this->getResponse()->setContent(\Zend\Json\Json::encode(array($roles)));
		}
		
		return $response;
	}
	
	// Cambiar contraseña de usuario
	public function changepassAction()
	{
		$id   = $this->params()->fromRoute("id", null);
		$form = new EditProfileForm("formEditProfile");
		$user = $this->getUsersService()->getUserById($id);
		
		if($this->getRequest()->isPost()){
			$formData = $this->getRequest()->getPost();
			//Validamos si se va a editar el perfil o la contraseï¿½a
			if(isset($formData['checkPass']) && $formData['checkPass'] == "ok"){
				//print_r($formData);exit;
				$validatePass = $this->getUsersService()->validatePassword($formData);
				if($validatePass[0]['count'] != 0){
					$response = $this->getResponse()->setContent(\Zend\Json\Json::encode(array('response' => "true", "data" => $validatePass)));
				}else{
					$response = $this->getResponse()->setContent(\Zend\Json\Json::encode(array('response' => "false", "data" => $validatePass)));
				}
				return $response;
		
			}elseif(isset($formData['password1'])){
				$chagePassword = $this->getUsersService()->changePassword($formData, $id);
				//print_r( $chagePassword );exit;
				$this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/system/users/index');
			}else{
				$editProfile = $this->getUsersService()->editUser($formData);
				print_r( $editProfile );exit;
			}
		}
		
		$form->setData($user[0]);
		return new ViewModel(array("form" => $form));
	}
	
	/*
	 * Ver detalle de usuario
	 */
	public function seedetailAction()
	{
		$id_user = (int) $this->params()->fromRoute("id", null);
		$user    = $this->getUsersService()->getUserById($id_user);
		//echo "<pre>"; print_r($user);exit;
		if($user){
			return new ViewModel(array("id_user" => $id_user, "user" => $user));
		}else{
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl(). '/system/users/index');
		}
	}
	
	/*
	 * Editar detalle de empleado
	 */
	public function editdetailAction()
	{
		$form            = new UserForm("userForm",1);
		$id_user         = $this->params()->fromRoute("id",null);
		$user            = $this->getUsersService()->getUserById($id_user);
		//echo "<pre>"; print_r($user); exit;
		$form->setData($user[0]);
		$view            = array("form" => $form, "district" => $user[0]['district_id'], "neighborhood" => $user[0]['neighborhood']);
		$request         = $this->getRequest();
	
		if($request->isPost()){
			$formData   = $request->getPost()->toArray();
			//echo "<pre>"; print_r($formData); exit;
			$addUsers   = $this->getUsersService()->editDetailUser($formData);
			 
			//if($addUsers){
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/system/users/index');
			//}
		}
		 
		return new ViewModel($view);
	}
}