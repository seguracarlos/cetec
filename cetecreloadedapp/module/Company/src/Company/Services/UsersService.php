<?php
namespace Company\services;

use Company\Model\UsersModel;

class UsersService
{
	private $usersModel;
	
	public function getUsersModel()
	{
		return $this->usersModel = new UsersModel();
	}
	
	public function getUserFavorite($company){
		$userfavorite = $this->getUsersModel()->getUserFavorite($company);
		return $userfavorite;
	}
	
	public function getJobs(){
		$jobs = $this->getUsersModel()->getJobs();
		return $jobs;
	}
	
	public function editUserFavorite($id_user,$formData){
		$data = array(
				'user_id' => $id_user,
				'name' => $formData['userf-name'],
				'surname' => $formData['userf-surname'],
				'lastname' => $formData['userf-lastname'],
				'email' => $formData['userf-email'],
				'user_name' => $formData['userf-user_name'],
				'id_job' => $formData['jobs'],
				'canlogin' => $formData['user_can_login']
		);
		$userFavorite = $this->getUsersModel()->editUserFavorite($data);
		return $userFavorite;
	}
}