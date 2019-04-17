<?php
namespace Classes\Services;

// include_once APPLICATION_PATH .'/daos/impl/TopicsDaoImpl.php';---¬
use Classes\Model\TopicsModel;
use Classes\Model\ContentsModel;
use Classes\Model\StudentNotesModel;
//use Usermanual\Entities\TopicsEntity;
use Zend\Crypt\PublicKey\Rsa\PublicKey;


class TopicsService{
	
	public function getAllTreeByType($type,$user){
		$TopicsDao		=	new TopicsModel();
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
		$TopicsDao		=	new TopicsModel();
		$exp 			=	array();
		$row 			=	$TopicsDao->getExportTree($id);
		return $exp;
	}
	
	public function getAllParents($type,$user){
		$TopicsDao		=	new TopicsModel();
		return $TopicsDao->getAllParents($type,$user);
	}
	
	public function getAllUserParents($type){
		$TopicsDao		=	new TopicsModel();
		return $TopicsDao->getAllUserParents($type);
	}
	
	public function addParent($formData){
		$TopicsDao		=	new TopicsModel();
		$topicEntity	=	new TopicsEntity();
		$topicEntity->setIdUser($user);
		$topicEntity->setTopicName($formData['topic_name']);
		$topicEntity->setParent($type);
		$topicEntity->setType("folder");
		$topicEntity->setProjectName($formData['project_name']);
		$topicEntity->setDescription($formData['description']);
		$topicEntity->setArtefact_type($type);
	
	}
	
	public function getCourses($parent){
		$TopicsDao		=	new TopicsModel();
		return $TopicsDao->getCourses($parent);
	}
	
	public function editParent($formData,$type,$user){
		
		$TopicsDao		=	new TopicsModel();
		$topicEntity	=	new TopicsEntity();
		$topicEntity->setIdTopic($formData['id_topic']);
		$topicEntity->setIdUser($user);
		$topicEntity->setTopicName($formData['topic_name']);
		$topicEntity->setProjectName($formData['project_name']);
		$topicEntity->setDescription($formData['description']);
		
		return $TopicsDao->editParent($topicEntity);
	}
	
	public function deleteParent($deleteParam){
		$TopicsDao = new TopicsModel();
		$deleteParam = array('id_topic' => $deleteParam);
		return $TopicsDao->deleteParent($deleteParam);
	}
	
	public function ValidateParent($type,$id,$user){
		$TopicsDao = new TopicsModel();
		return $TopicsDao->ValidateParent($type,$id,$user);
	}

	public function getTopics($type, $trim, $user){
		$TopicsDao=new TopicsModel();
		$topics = $TopicsDao->getTopics($type,$trim,$user);
		$topicsArray = array();
		
		foreach($topics as $topic){
			$subtopics = $TopicsDao->getTopics($type,$topic['id_topic'],$user);
			
			$topicsArray[] = array(
					'id_topic' => $topic['id_topic'],
					'topic_name' => $topic['topic_name'],
					'subtopics' => $subtopics
 			);
			
		}
			
		return $topicsArray;
	}
	
	public function getTopicsThree($type, $trim, $user)	{
		$TopicsDao=new TopicsModel();
		$ContentsModel = new ContentsModel();
		$topics = $TopicsDao->getTopics($type,$trim,$user);
		$topicsArray = array();
		
		foreach($topics as $key => $topic){
			$subtopics = $TopicsDao->getTopics($type,$topic['id_topic'],$user);
			$topicsArray[] = array(
					'id' => $topic['id_topic'],
					'text' => $topic['topic_name'],
					'children' => array()
			);
			
			foreach($subtopics as $key2 => $subtopic){
				$slides = $ContentsModel->getOnlySlidesTopic($subtopic['id_topic']);
				$topicsArray[$key]['children'][] = array(
					'id' => $subtopic['id_topic'],
					'text' => $subtopic['topic_name'],
					'children2' => array()
				);
				$cont = 1;
				foreach($slides as $slide){
					$text = "Slide";
					if($cont < 10){
						$text = $text.'0'.$cont;
					}else{
						$text = $text.$cont;
					}
					$topicsArray[$key]['children'][$key2]['children2'][] = array(
							'id' => $slide['id_content'],
							'text' => $text,
							'type' => $slide['type'],
							'display_order' => $slide['display_order']
					);
					$cont++;
				}
			}
				
		}
		return $topicsArray;
	}
	
	public function getSubtopics($type, $id, $user){
		$TopicsDao=new TopicsModel();
		$subtopics = $TopicsDao->getTopics($type,$id,$user);
		return $subtopics;
	}
	
	
	public function getAllTopics($type, $user){
		$TopicsDao=new TopicsModel();
		$themes = $TopicsDao->getAllTopics($type,$user);
		$dataThemes = array();
		$pattern = "/<h3>(.*?)<\/h3>/";
		$cont=0;
		foreach($themes as $theme){
			preg_match($pattern,$theme['content'], $matches);
			//Validamos Si la variable matches recupera un titulo
			if($matches){
				$TituloMultimedia = strstr($matches[1], 'MULTIMEDIA '); //Recuperamos los titulos de multimedia
				if($matches[1]!=$TituloMultimedia){ //Validamos si el titulo es diferente a multimedia
					$index[] = $matches[1];
					$dataThemes[$theme['id_topic']][] = array(
							'id_topic' => $theme['id_topic'],
							'parent'   => $theme['id_subtopic'],
							'indice'     => $matches[1], //Este indice contiene el titulo de una diapositiva, el cual sera relacionado en el indice
							'topic_name' => $theme['topic_name'],
							'type'       => $theme['type'],
								
					);
				}
			}
			 
		}
		
		//echo "<pre>";print_r($dataThemes);exit;
		
		return $dataThemes;
		
	}
	
	public function getAllTopicsById($id, $id2, $id3){
		$TopicsDao=new TopicsModel();
		return $TopicsDao->getTopicById($id, $id2, $id3);
	}
	
	public function getTrim($type,$id,$user,$user_id){
		$TopicsDao=new TopicsModel();
		$notesService = new StudentNotesService();
		$topics = $TopicsDao->getTrim($type,$id,$user);
		$topicsNotes = array();
		foreach($topics as $topic){
			$subtopics = $TopicsDao->getTopics($type,$topic['id_topic'],$user);
			$notesByTheme = $notesService->countNotes($subtopics, $user_id);
			$topicsNotes[] = array(
					"id_topic" => $topic['id_topic'],
					"topic_name" => $topic['topic_name'],
					"notes"      => $notesByTheme
			);
		}

		return $topicsNotes;
	}
	
	public function getTopicsParents($type,$id,$user){
		$TopicsDao=new TopicsModel();
		return $TopicsDao->getTopicsParents($type, $id, $user);
	}
	
	public function addTopic($formData){
		
		$TopicsDao= new TopicsModel();
// 		$topicEntity= new TopicsEntity();
		
// 		$topicEntity->setIdUser($user);
// 		$topicEntity->setTopicName($formData['topic_name']);
// 		$topicEntity->setParent($formData['id_parent']);
// 		$topicEntity->setType($formData['type']);
// 		$topicEntity->setArtefact_type($type);
		
// 		return $TopicsDao->addTopic($topicEntity);
		$data = Array(
				"id_user" => 15,
				"topic_name" => $formData['topic_name'],
				"parent" => $formData['id_parent'],
				"type" => "folder",
				"artefact_type" => 2,
		
		);
		$addTopic = $TopicsDao->addTopic($data);
		return $addTopic;
	}
	
	public function deleteTopic($formData){
		$TopicsDao = new TopicsModel();
		$deleteTopic = $TopicsDao->deleteTopic($formData['id_topic']);
		return $deleteTopic;
	}
	
	
	public function addTopicTest($formData,$type,$user){
		$TopicsDao= new TopicsModel();
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
		$TopicsDao=new TopicsModel();
		$topic = $TopicsDao->getParentBy($id,$type,$user);
		if($topic[0]['type'] == "folder"){
			$topic[0]['type'] = "Carpeta";
		}else if($topic[0]['type'] == "file"){
			$topic[0]['type'] = "Archivo";
		}
		return $topic;
	}
	public function editTopic($formData){
		$TopicsDao=new TopicsModel();
		$data = array(
				'id_topic' => $formData['id_topic'],
				'topic_name' => $formData['topic_name']
		);
		$updateTopic = $TopicsDao->editTopic($data);
		return $updateTopic;
// 		$topicEntity= new TopicsEntity();
// 		$topicEntity->setIdUser($user);
// 		$topicEntity->setIdTopic($topic['id_topic']);
// 		$topicEntity->setTopicName($topic['topic_name']);
// 		return $TopicsDao->editTopic($topicEntity);	
	}
	
	public function editTopicTest($topic,$user){
		$TopicsDao=new TopicsModel();
		$topicEntity= new TopicsEntity();
		$topicEntity->setIdUser($user);
		$topicEntity->setIdTopic($topic['id_topic']);
		$topicEntity->setTopicName($topic['topic_name']);
		$topicEntity->setPrerequisites($topic['prerequisites']);
		return $TopicsDao->editTopicTest($topicEntity);	
	}
	
	public function getAllUserTreeByType($type){
		$TopicsDao=new TopicsModel();
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
	
	public function getParentName($type,$idParent,$user){
		$topicsDao = new TopicsModel();
		$topicName = $topicsDao->getTopicsParents($type, $idParent, $user);
		return $topicName;
	}
}