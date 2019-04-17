<?php
namespace System\Services;

use System\Model\UsersRolesModel;

class UsersRolesServices
{
	private $userRoleModel;
	
	protected function getUserRoleModel()
	{
		return $this->userRoleModel = new UsersRolesModel();
	}
	
	//Metodo que agrega un usuario relacionado con un rol
	public function addRoleToUser($user_id, $role_id)
	{
		$data = array(
			'user_id' => $user_id,
			'role_id' => $role_id
		);
		
		$user_role = $this->getUserRoleModel()->addRoleToUser($data);
		return $user_role;
	}
	
	//Metodo para editar un usuario relacionado con un rol
	public function editRoleToUser($user_id, $role_id)
	{
		$data = array(
				//'user_id' => $user_id,
				'role_id' => $role_id
		);
		
		$user_role = $this->getUserRoleModel()->editRoleToUser($data,$user_id);
		return $user_role;
	}
	
}