<?php
namespace System\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
//Incluimos servicios
use System\Services\PermissionsService;
use System\Services\RoleService;
use System\Services\RolePermissionServices;
use System\Form\PermissionsForm;
//Incluimos controller base
use Iofractal\Controller\BaseController;

class PermissionsController extends BaseController
{
	private $permissionsService;
	private $roleService;
	
	//Metodo que instancia el servicio de roles
	public function getRoleService()
	{
		return $this->roleService = new RoleService();
	}
	
	//Metodo que instacia el servicio de permisos
	public function getPermissionsService()
	{
		return $this->permissionsService = new PermissionsService();	
	}
	
	public function indexAction()
	{
		//echo "Controller Permissions - index"; exit;
		$permissions = $this->getRoleService()->fetchAll();
		$data = array(
			"permissions" => $permissions
		);
		
		return new ViewModel($data);
	}
	
	public function addAction()
	{
		// Obtenemos el ultimo id de la tabla de roles
		$id_role = $this->getRoleService()->getLastId();
		//print_r($id_role[0]['role_id']); exit;
		$form  = new PermissionsForm("permissionsForm");
		$view  = array("form" => $form, "role_id" => $id_role[0]['role_id']);
		
		if($this->getRequest()->isPost()){
			$formData = $this->getRequest()->getPost();
			//echo "<pre>"; print_r($formData); exit;
			//Validamos si se van a agregar permisos o roles
			if(isset($formData['identifier']) && $formData['identifier'] == "ad"){
				$addPermissions = $this->getPermissionsService()->addOrUpdatePermissionsToRole($formData);
				if($addPermissions){
					$response = $this->getResponse()->setContent(\Zend\Json\Json::encode(array('response' => "true")));		
				}else{
					$response = $this->getResponse()->setContent(\Zend\Json\Json::encode(array('response' => "false")));
				}
				return $response;
			}else{
				//echo "<pre>"; print_r($formData); exit;
				$rol = $this->getRoleService()->addRole($formData);
				if($rol){
					return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/system/permissions/index');
				}
			}
			//echo "<pre>"; print_r($this->getRequest()->getPost()); exit;
		}
		
		return new ViewModel($view);
	}
	
	//Metodo para actualizar un rol
	public function editAction()
	{
		$id_role = $this->params()->fromRoute("id",null);
		$role    = $this->getRoleService()->getRoleById($id_role);
		//echo "<pre>"; print_r($role); exit;
		$form    = new PermissionsForm("permissionsForm", $role['role_status'], $id_role);
		$form->setData($role);
		$view    = array("form" => $form,"id_role" => $id_role);
		
		if($this->getRequest()->isPost()){
			$formData = $this->getRequest()->getPost();
			//echo "<pre>"; print_r($formData); exit;
			//Validamos si se van a editar permisos o roles
			if(isset($formData['identifier']) && $formData['identifier'] == "ed"){
				$addPermissions = $this->getPermissionsService()->addOrUpdatePermissionsToRole($formData);
				if($addPermissions){
					$response = $this->getResponse()->setContent(\Zend\Json\Json::encode(array('response' => "true")));
				}else{
					$response = $this->getResponse()->setContent(\Zend\Json\Json::encode(array('response' => "false")));
				}
				return $response;
			}else{
				$updateRole = $this->getRoleService()->editRole($formData);
				if($updateRole){
					return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/system/permissions/index');
				}
			}
		}
		return new ViewModel($view);
	}
	
	/*
	 * ELIMINAR ROL
	 */
	public function deleteAction()
	{
		$request = $this->getRequest();
		$id      = (int) $this->params()->fromRoute('id', 0);
		
		if (!$id) {
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/system/permissions/index');
		}
		
		if ($request->isPost()) {
			$del = $request->getPost()->toArray();
			//print_r($del); exit();
			if ($del['del'] == 'Aceptar'){
				$id = (int) $del['id'];
				//print_r($id); exit;
				$this->getRoleService()->deleteRole($id);
			}
		
			// Redirect to list of permissions
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/system/permissions/index');
		}
		
		$data = $this->getRoleService()->getRoleById($id);
		//echo "<pre>"; print_r($data); exit;
		return array(
				'id'    => $id,
				'data'  => $data
		);
	}
	
	/*
	 * ELIMINAR PERMISOS A UN ROL
	 */
	public function deletepermissionsroleAction()
	{
		$request  = $this->getRequest();
		
		if ($request->isPost()) {
			$formData = $request->getPost()->toArray();
			
			if(isset($formData['identifier']) && $formData['identifier'] == "del"){
				$role_permission_service = new RolePermissionServices();
				$delete_permissions      = $role_permission_service->deletePermissionsByIdRoleAndResource($formData['id_role'], $formData['id_resource']);
				return $response = $this->getResponse()->setContent(\Zend\Json\Json::encode(array('response' => "true")));
				//echo "<pre>"; print_r($formData); exit;
			}
		}
		exit;
	}
	
	//Metodos utilizados para llamdas ajax
	public function getpermissionbyidresourceAction()
	{
		$request = $this->getRequest();
        $response = $this->getResponse();

        if($request->isPost()){
            $inf = $request->getPost();
       	
            if(isset($inf['pbr']) && $inf['pbr'] == "ok"){
            	$permissionsByRol = $this->getPermissionsService()->getPermissionsByIdRole($inf['id_role'],$inf['id_resource']);
            	
            	if($permissionsByRol){
            		$response->setContent(\Zend\Json\Json::encode(array('response' => true, "permissionbyrole" => $permissionsByRol)));
            	}else{
            		$response->setContent(\Zend\Json\Json::encode(array('response' => false, "data" => "Error desconosido, consulta al administrador *.*")));
            	}
            }else{
            	$permissions = $this->getPermissionsService()->getPermissionsByIdResource($inf['id_resource']);
            	if($permissions){
            		$response->setContent(\Zend\Json\Json::encode(array('response' => true, 'data' => $permissions)));
            	}else{
            		$response->setContent(\Zend\Json\Json::encode(array('response' => false, "data" => "Error desconosido, consulta al administrador *.*")));
            	}
            }
            //echo "<pre>"; print_r($inf); exit;
        }
        return $response;
        exit;
	}
}