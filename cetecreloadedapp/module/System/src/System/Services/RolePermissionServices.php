<?php
namespace System\Services;

use System\Model\RolePermissionModel;
class RolePermissionServices
{
	private $rolePermissionModel;
	
	protected function getRolePermissionModel()
	{
		return $this->rolePermissionModel = new RolePermissionModel();
	}
	
	//Metodo que elimina los permisos a un rol
	public function deletePermissionsByIdRoleAndResource($id_role, $id_resource)
	{
		$deletePermissions = $this->getRolePermissionModel()->deletePermissionsByIdRoleAndResource($id_role, $id_resource);
		return $deletePermissions;
	}
}