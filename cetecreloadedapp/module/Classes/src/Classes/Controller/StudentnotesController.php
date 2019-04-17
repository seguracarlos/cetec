<?php
namespace Classes\Controller;

use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Iofractal\Controller\BaseController;
use Classes\Services\StudentNotesService;
use Classes\Services\TopicsService;

class StudentnotesController extends BaseController
{
	private $notesService;
	private $type = 2;
	private $user =15;
	
	public function __construct(){
		$this->notesService = new StudentNotesService();
	}

	public function indexAction(){
		$topicsService = new TopicsService();
		$session = new Container('User');
		$id_user = $session->offsetGet('userId');
		$trim = $session->offsetGet('trim');
		$topicsIndex = $topicsService->getTrim($this->type, $trim, $this->user,$id_user );
		$data = array('topics' => $topicsIndex);
		return new ViewModel($data);
	}
	
	public function viewnotesAction(){
		$notesService = new StudentNotesService();
		$topicsService = new TopicsService();
		$session = new Container('User');
		$id_user = $session->offsetGet('userId');
		$idTopic = $this->params()->fromRoute("id",null);
		$idSubtopics = $topicsService->getSubtopics($this->type, $idTopic, $this->user);
		$topicName = $topicsService->getParentName($this->type,$idSubtopics[0]['parent'],$this->user);
		$notes = $notesService->getNotesByTopicAndUser($idSubtopics,$id_user);
		$data = array('notes' => $notes,'topic_name'=>$topicName[0]['topic_name']);
		return new ViewModel($data);
	}
	
	public function addAction(){

		$session = new Container('User');
        $id_user = $session->offsetGet('userId');
		$request  = $this->getRequest();
		$response = $this->getResponse();
		if($request->isPost()){
			$formData = $request->getPost();
			$formData['id_user'] = $id_user;
			$note = $this->notesService->addNote($formData);
			
			if($note){
				$response->setContent(\Zend\Json\Json::encode(array('response' => true, 'data' => $note)));
			}else{
				$response->setContent(\Zend\Json\Json::encode(array('response' => false, "data" => "Error desconocido, consulta al administrador *.*")));
			}
		}
		return $response;
		exit;
	}
	
	public function updateAction(){
		
		$request  = $this->getRequest();
		$response = $this->getResponse();

		if($request->isPost()){
			$formData = $request->getPost();
			$note = $this->notesService->updateNote($formData);
				
			if($note){
				$response->setContent(\Zend\Json\Json::encode(array('response' => true, 'data' => $note)));
			}else{
				$response->setContent(\Zend\Json\Json::encode(array('response' => false, "data" => "Error desconocido, consulta al administrador *.*")));
			}
		}
		return $response;
		exit;
	}
	
	public function deleteAction(){
		$request  = $this->getRequest();
		$response = $this->getResponse();
		
		if($request->isPost()){
			$formData = $request->getPost();
			$note = $this->notesService->deleteNote($formData);
		
			if($note){
				$response->setContent(\Zend\Json\Json::encode(array('response' => true, 'data' => $note)));
			}else{
				$response->setContent(\Zend\Json\Json::encode(array('response' => false, "data" => "Error desconocido, consulta al administrador *.*")));
			}
		}
		return $response;
		exit;
	}
	
	

}