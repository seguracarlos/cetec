<?php
namespace System\Services;

use System\Model\RoleModel;
use System\Services\ResourcesServices;

class RoleService
{
	private $roleModel;
	
	//Obtenemos la instancia de nuestro modelo
	public function getRoleModel()
	{
		return $this->roleModel = new RoleModel();
	}
	
	//Traemos todos los roles disponibles
	public function fetchAll()
	{
		$permissions = $this->getRoleModel()->fetchAll();
		return $permissions;
	}
	
	//Agregamos un nuevo role
	public function addRole($formData)
	{
		$data = array(
			"role_name" => $formData['rol_name'],
			"status"    => $formData['status']
		);
		$role = $this->getRoleModel()->addRole($data);
		return $role;
	}
	
	//Editamos un rol
	public function editRole($formData)
	{
		$data = array(
				"rid"       => $formData['id_role'],
				"role_name" => $formData['rol_name'],
				"status"    => $formData['status']
		);	
		//echo "<pre>"; print_r($data); exit;
		$updateRole = $this->getRoleModel()->editRole($data);
		return $updateRole;
	}
	
	//Eliminar un rol
	public function deleteRole($id_role)
	{
		$roleId = (int) $id_role;
		if($roleId){
			$deleteRole = $this->getRoleModel()->deleteRole($id_role);
		}
		return $deleteRole;
	} 
	
	//Obtenemos rol por id
	public function getRoleById($id_role)
	{
		$role = $this->getRoleModel()->getRoleById($id_role);
		
		return $role;
	}
	
	//Obtenemos los recursos asignados a un rol
	public function getAllResourcesAssignedToRole($id_role)
	{
		$resourcesToRole = $this->getRoleModel()->getAllResourcesAssignedToRole($id_role);
		return $resourcesToRole;
	}
	
	//Obtenemos los recursos que no han sido asignados a un rol
	public function getAllResourcesNoAssignedToRole($id_role)
	{
		$resourcesNoAssignedToRole = $this->getRoleModel()->getAllResourcesNoAssignedToRole($id_role);
		return $resourcesNoAssignedToRole;
	}
	
	//Metodo para obtener el ultimo id en la base
	public function getLastId()
	{
		$lastId = $this->getRoleModel()->getLastId();
		return $lastId;
	}

	public function getResourcesForMenu($id_role)
	{
		$resourcesToRole = $this->getRoleModel()->getResourcesForMenu($id_role);
		return $resourcesToRole;
	}
	
	public function getResourcesMenusite($id_role)
	{
		$resourcesToRole = $this->getRoleModel()->getResourcesMenusite($id_role);
		return $resourcesToRole;
	}
	
	public function getAllPermisions($id_role){
		$allResourcesToRole = $this->getRoleModel()->getAllPermisions($id_role);
		return $allResourcesToRole;
	}
}