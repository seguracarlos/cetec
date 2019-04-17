<?php
namespace System\Services;

use System\Model\ProfileModel;

class ProfileService
{
	private $profileModel;
	
	public function getProfileModel()
	{
		return $this->profileModel = new ProfileModel();
	}
	
	//Agregamos un nuevo role
	public function addProfileInfo($formData)
	{
		$data = array(
			"job" => $formData['job'],
			"interest" => $formData['interest'],
			"music"    => $formData['music'],
			"hobbies"  => $formData['hobbies'],
			"about"	   => $formData['about'],
			"id_user" => $formData['id_user'],
 		);
		
		$profileInfo = $this->getProfileModel()->addProfileInfo($data);
		return $profileInfo;
	}
	
	//Editamos un rol
	public function updateProfileInfo($formData)
	{
		$data = array(
			"job" => $formData['job'],
			"interest" => $formData['interest'],
			"music"    => $formData['music'],
			"hobbies"  => $formData['hobbies'],
			"about"	   => $formData['about'],
			"id_user" => $formData['id_user'],
 		);
		
		//echo "<pre>"; print_r($data); exit;

		$profileInfo = $this->getProfileModel()->updateProfileInfo($data);
		return $profileInfo;
	}
	
	
	public function getProfileByUser($id_user)
	{
		$profileInfo = $this->getProfileModel()->getProfileByUser($id_user);
		return $profileInfo;
	}
	
}