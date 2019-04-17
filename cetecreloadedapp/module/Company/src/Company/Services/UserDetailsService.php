<?php
namespace Company\services;

use Company\Model\UserDetailsModel;

class UserDetailsService
{
	private $userDetailsModel;
	
	public function getUserDetailsModel()
	{
		return $this->userDetailsModel = new UserDetailsModel();
	}
	
	public function updateDetailsUser($user_id,$formData){
		$data = array(
				'acl_users_id' => $user_id,
				'local_phone' => $formData['userf-phone_local'],
				'cellphone' => $formData['userf-cell_phone']
		);
		$detailUser = $this->getUserDetailsModel()->updateDetailsUser($data);
		return $detailUser;
	}
}