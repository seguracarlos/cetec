<?php
namespace Usermanual\Services\Impl;

use Usermanual\Services\TopicsService;
// include_once APPLICATION_PATH .'/daos/impl/TopicsDaoImpl.php';---¬
use Usermanual\Model\Impl\TopicsModelImpl;
use Usermanual\Entities\TopicsEntity;
use Zend\Crypt\PublicKey\Rsa\PublicKey;

//Application_Service_Impl_TopicsServiceImpl
class TopicsServiceImpl implements TopicsService/*Application_Service_TopicsService*/{
	
	public function getAllTreeByType($type,$user){
		$TopicsDao		=	new TopicsModelImpl();
		$row 			=	$TopicsDao->getAllTreeByType($type,$user);
		
		for($i = 0; $i < count($row); $i++){
			if($row[$i]['parent'] == NULL){
				$row[$i]['parent']="#";
			}
			if($row[$i]['text'] != null){
				if($row[$i]['parent'] == "#"){
					$row[$i]['text'] = "<a style='color:#000000' id='".
					$row[$i]['id']."' class='export' onclick='parentLink()'  href='#'>".
					$row[$i]['text']."</a>";
				}
				if($row[$i]['type'] == "folder" && $row[$i]['parent'] != "#"){
					$row[$i]['text'] = "<a style='color:#000000' id='".
					$row[$i]['id']."' class='export' onclick='linkContentTopic(".
					$row[$i]['id'].")'  href='#'>".$row[$i]['text']."</a>";
				}
				if($row[$i]['type'] == "file"){
					$row[$i]['text'] = "<a style='color:#000000' id='".
					$row[$i]['id']."' class='export' onclick='linkContent(".
					$row[$i]['id'].")'  href='#'>".
					$row[$i]['text']."</a>";	
				}
			}
	 	}
			return $row;
		}
	
	
	public function getExportTree($id){
		$TopicsDao		=	new TopicsModelImpl();
		$exp 			=	array();
		$row 			=	$TopicsDao->getExportTree($id);
		return $exp;
	}
	
	public function getAllParents($type,$user){
		$TopicsDao		=	new TopicsModelImpl();
		return $TopicsDao->getAllParents($type,$user);
	}
	
	public function getAllUserParents($type){
		$TopicsDao		=	new TopicsModelImpl();
		return $TopicsDao->getAllUserParents($type);
	}
	
	public function addParent($formData,$type,$user){
		$TopicsDao		=	new TopicsModelImpl();
		$topicEntity	=	new TopicsEntity();
		$topicEntity->setIdUser($user);
		$topicEntity->setTopicName($formData['topic_name']);
		$topicEntity->setParent($type);
		$topicEntity->setType("folder");
		$topicEntity->setProjectName($formData['project_name']);
		$topicEntity->setDescription($formData['description']);
		$topicEntity->setArtefact_type($type);
		return $TopicsDao->addParent($topicEntity);
	}
	
	public function getParentBy($id, $type,$user){
		$TopicsDao		=	new TopicsModelImpl();
		return $TopicsDao->getParentBy($id,$type,$user);
	}
	
	public function editParent($formData,$type,$user){
		
		$TopicsDao		=	new TopicsModelImpl();
		$topicEntity	=	new TopicsEntity();
		$topicEntity->setIdTopic($formData['id_topic']);
		$topicEntity->setIdUser($user);
		$topicEntity->setTopicName($formData['topic_name']);
		$topicEntity->setProjectName($formData['project_name']);
		$topicEntity->setDescription($formData['description']);
		
		return $TopicsDao->editParent($topicEntity);
	}
	
	public function deleteParent($deleteParam){
		$TopicsDao = new TopicsModelImpl();
		$deleteParam = array('id_topic' => $deleteParam);
		return $TopicsDao->deleteParent($deleteParam);
	}
	
	public function ValidateParent($type,$id,$user){
		$TopicsDao = new TopicsModelImpl();
		return $TopicsDao->ValidateParent($type,$id,$user);
	}

	public function getTopics($type, $id, $user){
		$TopicsDao=new TopicsModelImpl();
		return $TopicsDao->getTopics($type,$id,$user);
	}
	
	public function getAllTopics($type, $id, $user){
		$TopicsDao=new TopicsModelImpl();
		return $TopicsDao->getAllTopics($type,$id,$user);
	}
	
	public function getAllIdContents(){
		$TopicsDao=new TopicsModelImpl();
		return $TopicsDao->getIdContens($id, $id2, $id3);
	}
	public function getAllTopicsById($id, $id2, $id3){
		$TopicsDao=new TopicsModelImpl();
		return $TopicsDao->getTopicById($id, $id2, $id3);
	}
	
	public function getTopicsParents($type,$id,$user){
		$TopicsDao=new TopicsModelImpl();
		return $TopicsDao->getTopicsParents($type, $id, $user);
	}
	
	public function addTopic($formData,$type,$user){
		
		$TopicsDao= new TopicsModelImpl();
		$topicEntity= new TopicsEntity();
		
		$topicEntity->setIdUser($user);
		$topicEntity->setTopicName($formData['topic_name']);
		$topicEntity->setParent($formData['id_parent']);
		$topicEntity->setType($formData['type']);
		$topicEntity->setArtefact_type($type);
		
		return $TopicsDao->addTopic($topicEntity);
	}
	
	
	public function addTopicTest($formData,$type,$user){
		$TopicsDao= new TopicsModelImpl();
		$topicEntity= new TopicsEntity();
		$topicEntity->setIdUser($user);
		$topicEntity->setTopicName($formData['topic_name']);
		$topicEntity->setParent($formData['id_parent']);
		$topicEntity->setType($formData['type']);
		$topicEntity->setArtefact_type($type);
		$topicEntity->setPrerequisites($formData['prerequisites']);
		return $TopicsDao->addTopicTest($topicEntity);
	}
	public function getTopicBy($id, $type,$user){
		$TopicsDao=new TopicsModelImpl();
		$topic = $TopicsDao->getParentBy($id,$type,$user);
		if($topic[0]['type'] == "folder"){
			$topic[0]['type'] = "Carpeta";
		}else if($topic[0]['type'] == "file"){
			$topic[0]['type'] = "Archivo";
		}
		return $topic;
	}
	public function editTopic($topic,$user){
		$TopicsDao=new TopicsModelImpl();
		$topicEntity= new TopicsEntity();
		$topicEntity->setIdUser($user);
		$topicEntity->setIdTopic($topic['id_topic']);
		$topicEntity->setTopicName($topic['topic_name']);
		return $TopicsDao->editTopic($topicEntity);	
	}
	
	public function editTopicTest($topic,$user){
		$TopicsDao=new TopicsModelImpl();
		$topicEntity= new TopicsEntity();
		$topicEntity->setIdUser($user);
		$topicEntity->setIdTopic($topic['id_topic']);
		$topicEntity->setTopicName($topic['topic_name']);
		$topicEntity->setPrerequisites($topic['prerequisites']);
		return $TopicsDao->editTopicTest($topicEntity);	
	}
	
	public function getAllUserTreeByType($type){
		$TopicsDao=new TopicsModelImpl();
		$row = $TopicsDao->getAllUserTreeByType($type);
		for($i = 0; $i < count($row); $i++){
			if($row[$i]['parent'] == NULL){
				$row[$i]['parent']="#";
			}
			if($row[$i]['topic_name'] != null){
				if($row[$i]['parent'] == "#"){
					$row[$i]['topic_name'] = "<a style='color:#000000' onclick='parentLink()'  href='#'>".
					$row[$i]['topic_name']."</a>";
				}
				if(($row[$i]['type'] == "folder" || $row[$i]['type'] == "file") && $row[$i]['parent'] != "#"){
					$row[$i]['topic_name'] = "<a style='color:#000000' onclick='linkContentTopic(".
					$row[$i][id_topic].")'  href='#'>".$row[$i]['topic_name']."</a>";
				}
			}
	 	}
		return $row;
	}
}