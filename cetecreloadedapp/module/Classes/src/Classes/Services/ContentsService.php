<?php
namespace Classes\Services;

use Classes\Model\ContentsModel;
//use Classes\Entities\ContentsEntity;

class ContentsService{
	

	//De un tema completo
// 	public function getContentById($id){
// 		$contentDao=new ContentsModel();
// 		return $contentDao->getContentByIdTopic($id);	
// 	}

	public function getContentById($id){
		$contentDao=new ContentsModel();
		return $contentDao->getContentById($id);
	}
	
	public function addContent($formData){
		$contentDao=new ContentsModel();

		$idTopic = $contentDao->getTopicId($formData['id_subtopic']);
		$data = array(
				"id_topic" => $idTopic,
				"id_subtopic" => $formData['id_subtopic'],
				"content"     => "",
				"type"        => $formData['type'],
				"display_order" => $formData['display_order']
		);
		$addContent = $contentDao->addContent($data);
		return $addContent;
	}
	
	public function editContent($formData){
		$contentsDao = new ContentsModel();
		$data = array(
				"id_content" => $formData['idContent'],
				"content"    => $formData['content']
		);
		$updateContent = $contentsDao->editContent($data);
		return $updateContent;

	}
	
	public  function deleteContent($formData){
		$contentsDao = new ContentsModel();
		$deleteContent = $contentsDao->deleteContent($formData);
		if($deleteContent){
			$contents = $contentsDao->getOnlySlidesTopic($formData['idParent']);
			if($contents){
				$order = 1;
				foreach($contents as $content){
					$data = array(
							'id_content' => $content['id_content'],
							'display_order' => $order++
					);
					$updateOrder = $contentsDao->editContent($data);
				}
			}
		}
		return $deleteContent;
		
	}
	//Solo de un tema en especifico
	public function getContentByIdTopic($idParentContent){
		$contentDao = new ContentsModel();
		$topics = $contentDao->getContentByIdTopic($idParentContent);
		return $topics;
	}
	
	public function getIdParent($idParent){
		$contentDao = new ContentsModel();
		$nameTopic = $contentDao->getIdParent($idParent);
		return $nameTopic;
	}
	
	public function updateDisplayOrder($formData){
		$contentDao = new ContentsModel();
		$order = 1;
		foreach($formData['contents'] as $content){
			$data = array(
					'id_content' => $content['id_content'],
					'display_order' => $order++
			);
			$updateOrder = $contentDao->editContent($data);
		}
		
		return $updateOrder;
	}
}

?>