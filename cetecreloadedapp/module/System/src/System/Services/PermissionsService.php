<?php
namespace System\Services;

use System\Model\PermissionsModel;
use System\Model\RolePermissionModel;

class PermissionsService
{
	private $permissionsModel;
	
	public function getPermissionsModel()
	{
		return $this->permissionsModel = new PermissionsModel();
	}
	
	public function fetchAll()
	{
		$permissions = $this->getPermissionsModel()->fetchAll();
		return $permissions;
	}
	
	/*
	 * OBTENEMOS LOS PERMISOS POR ID DEL RECURSO
	 */
	public function getPermissionsByIdResource($id_resource)
	{
		$permissions = $this->getPermissionsModel()->getPermissionsByIdResource($id_resource);
		return $permissions;
	}
	
	public function getPermissionsByIdRole($id_role,$id_resource)
	{
		$permissionsByRole = $this->getPermissionsModel()->getPermissionsByIdRole($id_role,$id_resource);
		return $permissionsByRole;
	}
	
	/*
	 * Agregamos o actualizamos los permisos para un rol en especifico
	 */
	public function addOrUpdatePermissionsToRole($formData)
	{
		//echo "<pre>"; print_r($formData); exit;
		//$rolePermissionModel = new \Permissions\Model\RolePermissionModel();
		$rolePermissionModel = new RolePermissionModel();
		
		//Recorremos los permisos
		foreach ($formData['permissions'] as $rows){
			$data[] = array(
				"id"            => $rows['id'],
				"role_id"       => $rows['id_role_permission'],
				"permission_id" => $rows['id_permission'],
				"status"        => $rows['status']
			);
		}
		
		$addPermissionToRole = $rolePermissionModel->addOrUpdatePermissionsToRole($formData['identifier'],$data);
		//echo "<pre>"; print_r($data); exit;
		return $addPermissionToRole;
	}
}