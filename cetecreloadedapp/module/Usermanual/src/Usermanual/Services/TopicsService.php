<?php
namespace Usermanual\Services;

interface TopicsService{
	public function getAllTreeByType($type,$user);
	public function getExportTree($id);
	public function getAllParents($type,$user);
	public function addParent($formData,$type,$user);
	public function getParentBy($id, $type,$user);
	public function editParent($formData,$topic,$user);
	public function deleteParent($deleteParam);
	public function ValidateParent($type,$id,$user);
	public function getTopics($type,$id,$user);
	public function getTopicBy($id, $type,$user);
	public function addTopic($formData,$type,$user);
	public function editTopic($topic,$user);
	public function getAllUserTreeByType($type);
} 
