<?php
namespace Usermanual\Services\Impl;

use Usermanual\Services\ContentsService;
// require_once APPLICATION_PATH .'/daos/impl/ContentsDaoImpl.php';
use Usermanual\Model\Impl\ContentsModelImpl;
// include_once APPLICATION_PATH .'/entities/ContentsEntity.php';
use Usermanual\Entities\ContentsEntity;
use Usermanual\Model\Impl\Usermanual\Model\Impl;



class ContentsServiceImpl implements ContentsService{

	public function getContentById($id){
		$contentDao=new ContentsModelImpl();
		return $contentDao->getContentById($id);	
	}
	
	public function getIdTopics(){
		$contentDao = new ContentsModelImpl();
		return $contentDao->getIdContents();
	}
	
	public function addContent($formData){
		$contentDao= new ContentsModelImpl();
		$contentEntity= new ContentsEntity();
		$contentEntity->setContent($formData['content']);
		$contentEntity->setIdTopic($formData['id_topic']);
		return $contentDao->addContent($contentEntity);
	}
	
	public function editContent($formData){
		$contentDao= new ContentsModelImpl();
		$contentEntity= new ContentsEntity();
		$contentEntity->setIdContent($formData['id_content']);
		$contentEntity->setContent($formData['content']);
		$contentEntity->setIdTopic($formData['id_topic']);
		return $contentDao->editContent($contentEntity);
	}
}

?>