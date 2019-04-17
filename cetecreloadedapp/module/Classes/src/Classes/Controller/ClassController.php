<?php

namespace Classes\Controller;

use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Iofractal\Controller\BaseController;
use Classes\Services\TopicsService;
use Classes\Services\ContentsService;
//use Classes\Services\SponsorshipService;
use Out\Services\SponsorshipService;
use Zend\Validator\File\Exists;


class ClassController extends BaseController
{
	private $type = 2;
	private $user = 15;
	private $parent = 17;

    public function indexAction()
    {
    	$session = new Container('User');
    	$idRole  = $session->offsetGet('roleId');
    	$trim    = $session->offsetGet('trim');
    	$topicsService = new TopicsService();
    	 
    	if($idRole == 1){
    		$courses = $topicsService->getCourses($this->parent);
    		$view     = array("themes" => $courses,
    				"role"   => $idRole,
    		);
    	}
    	if($idRole == 4){
    		$idParent      = $this->params()->fromRoute('id', 0);
    		$topicsIndex = $topicsService->getTopics($this->type, $trim, $this->user);
    		//$expenses = $this->getExpensesServices()->fetchAll();
    		$view     = array("themes" => $topicsIndex,
    				"usermanual"	=> $topicsService->getAllParents($this->type,$this->user),
    				"role"          => $idRole,
    				"parent"        => $idParent
    		);
    	}
    	 
    	return new ViewModel($view);
    }

    public function contentsAction()
    {
    	$topicsService = new TopicsService();
    	$id = $this->params()->fromRoute("id",null);
    	if($id == 97){
    		$trim = 1;
    	}elseif($id == 146){
    		$trim = 2;
    	}elseif($id == 194){
    		$trim = 3;
    	}elseif($id == 198){
    		$trim = 4;
    	}elseif($id == 210){
    		$trim = 5;
    	}elseif($id == 324){
    		$trim = 6;
    	}
    	$topics = $topicsService->getTopicsThree($this->type, $trim, $this->user);
    	return new ViewModel(array(
    			"themes" => $topics
    			)
    	);
    }
	
    public function getcontentAction()
    {
    	
    	$request = $this->getRequest();
    	$response = $this->getResponse();
    	 
    	if($request->isPost()){
    		$formData = $request->getPost();
    		$contentService = new ContentsService();
			$content = $contentService->getContentById($formData['idContent']);
    		if($content){
    			$response->setContent(\Zend\Json\Json::encode(array('response' => true, 'data' =>$content)));
    		}else{
    			$response->setContent(\Zend\Json\Json::encode(array('response' => false, "data" => "Error desconosido, consulta al administrador *.*")));
    		}
    		 
    	}
    	 
    	return $response;
    	exit;
    }
    
    public function addcontentAction()
    {
    	$request = $this->getRequest();
    	$response = $this->getResponse();
    	
    	if($request->isPost()){
    		$formData = $request->getPost();
    		$contentService = new ContentsService();
    		$content = $contentService->addContent($formData);
    		if($content){
    			$response->setContent(\Zend\Json\Json::encode(array('response' => true, 'data' =>$content)));
    		}else{
    			$response->setContent(\Zend\Json\Json::encode(array('response' => false, "data" => "Error desconosido, consulta al administrador *.*")));
    		}
    		 
    	}
    	
    	return $response;
    	exit;
    }
    
    public function updatecontentAction()
    {
    	$request = $this->getRequest();
    	$response = $this->getResponse();
    	 
    	if($request->isPost()){
    		$formData = $request->getPost();
    		$contentService = new ContentsService();
    		$content = $contentService->editContent($formData);
    		if($content){
    			$response->setContent(\Zend\Json\Json::encode(array('response' => true, 'data' =>$content)));
    		}else{
    			$response->setContent(\Zend\Json\Json::encode(array('response' => false, "data" => "Error desconosido, consulta al administrador *.*")));
    		}
    		 
    	}
    	 
    	return $response;
    	exit;
    }
    
    public function deletecontentAction()
    {
    	$request = $this->getRequest();
    	$response = $this->getResponse();
    	
    	if($request->isPost()){
    		$formData = $request->getPost();
    		$contentService = new ContentsService();
    		$content = $contentService->deleteContent($formData);
    		if($content){
    			$response->setContent(\Zend\Json\Json::encode(array('response' => true, 'data' =>$content)));
    		}else{
    			$response->setContent(\Zend\Json\Json::encode(array('response' => false, "data" => "Error desconocido, consulta al administrador *.*")));
    		}
    		 
    	}
    	
    	return $response;
    	exit;
    }
    
    public function addtopicAction()
    {
    	$request = $this->getRequest();
    	$response = $this->getResponse();
    	 
    	if($request->isPost()){
    		$formData = $request->getPost();
    		$topicsService = new TopicsService();
    		$addTopic = $topicsService->addTopic($formData);
    		if($addTopic){
    			$response->setContent(\Zend\Json\Json::encode(array('response' => true, 'data' =>$addTopic)));
    		}else{
    			$response->setContent(\Zend\Json\Json::encode(array('response' => false, "data" => "Error desconocido, consulta al administrador *.*")));
    		}
    	}
    	 
    	return $response;
    	exit;
    }
    
    public function updatetopicAction()
    {
    	$request = $this->getRequest();
    	$response = $this->getResponse();
    	
    	if($request->isPost()){
    		$formData = $request->getPost();
    		$topicsService = new TopicsService();
    		$updateTopic = $topicsService->editTopic($formData);
    		if($updateTopic){
    			$response->setContent(\Zend\Json\Json::encode(array('response' => true, 'data' =>$updateTopic)));
    		}else{
    			$response->setContent(\Zend\Json\Json::encode(array('response' => false, "data" => "Error desconosido, consulta al administrador *.*")));
    		}
    		 
    	}
    	
    	return $response;
    	exit;
    }
    
    public function deletetopicAction()
    {
    	$request = $this->getRequest();
    	$response = $this->getResponse();
    	 
    	if($request->isPost()){
    		$formData = $request->getPost();
    		$topicsService = new TopicsService();
    		$updateTopic = $topicsService->deleteTopic($formData);
    		if($updateTopic){
    			$response->setContent(\Zend\Json\Json::encode(array('response' => true, 'data' =>$updateTopic)));
    		}else{
    			$response->setContent(\Zend\Json\Json::encode(array('response' => false, "data" => "Error desconocido, consulta al administrador *.*")));
    		}
    		 
    	}
    	 
    	return $response;
    	exit;
    }
    
    public function myclassAction()
    {
    	$contentsService = new ContentsService();
    	$objService = new SponsorshipService();
    	$idParentContent      = (int) $this->params()->fromRoute('id', 0);
    	$sponsors = $objService->getSponsors($idParentContent );
    	$topics = $contentsService->getContentByIdTopic($idParentContent );
    	$TopicInfo = $contentsService->getIdParent($idParentContent);
    	$ViewModel = new ViewModel();    	
    	$ViewModel->setVariables(array(
    			'topics'	=> $topics,
    			'sponsors'  => $sponsors,
    			'parent' => $TopicInfo['id_topic']
    	));
    	$ViewModel->setTerminal(true);
    	return $ViewModel;
    }
    
    public function displayorderAction()
    {
    	$contentsService = new ContentsService();
    	$request = $this->getRequest();
    	$response = $this->getResponse();
    	
    	if($request->isPost()){
    		$formData = $request->getPost();
    		$updateOrder = $contentsService->updateDisplayOrder($formData);
    		if($updateOrder){
    			$response->setContent(\Zend\Json\Json::encode(array('response' => true, 'data' =>$updateOrder)));
    		}else{
    			$response->setContent(\Zend\Json\Json::encode(array('response' => false, "data" => "Error desconocido, consulta al administrador *.*")));
    		}
    		 
    	}
    	
    	return $response;
    	exit;
    }
    
    public function getVimeoThumbAction()
    {
    	$request = $this->getRequest();
    	$response = $this->getResponse();
    	 
    	if($request->isPost()){
    		$formData = $request->getPost();
    		$id = $formData['idVideo'];
    		$data = file_get_contents("https://vimeo.com/api/v2/video/$id.json");
    		$data = json_decode($data);
    		if($data){
    			$response->setContent(\Zend\Json\Json::encode(array('response' => true, 'data' =>$data)));
    		}else{
    			$response->setContent(\Zend\Json\Json::encode(array('response' => false, "data" => "Error desconocido, consulta al administrador *.*")));
    		}
    		 
    	}
    	 
    	return $response;
    	exit;
    }
}

