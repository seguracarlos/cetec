<?php
namespace Usermanual\Model;

use Usermanual\Entities\TopicsEntity;

interface TopicsModel{
	public function getAllTreeByType($type,$user);
	public function getExportTree($id);
	public function getAllParents($type,$user);
	public function addParent(TopicsEntity $topicEntity);
	public function getParentBy($id, $type,$user);
	public function editParent(TopicsEntity $topicEntity);
	public function deleteParent($deleteParam);
	public function ValidateParent($type,$id,$user);
	public function getTopics($type,$id,$user);
	public function addTopic(TopicsEntity $topicEntity);
	public function editTopic(TopicsEntity $topicEntity);
} 