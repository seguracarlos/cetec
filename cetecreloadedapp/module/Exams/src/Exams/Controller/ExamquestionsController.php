<?php 
namespace Exams\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Exams\Services\ExamquestionsServiceImpl;
use Exams\Services\ExamServiceImpl;
use Exams\Services\ExamopcionesServiceImpl;

class ExamquestionsController extends AbstractActionController{ 

	private $type = 5;
	private $user = 15;
// 	public function init()
// 	{
// 		BaseController::init();
// 		$exam = new Application_Service_Impl_ExamServiceImpl();
// 		$paramform = new Application_Form_ParamForm();
// 		$this->view->formparam = $paramform;
// 	}
	
	public function indexAction()
	{		
		
			$id_exam = $this->params()->fromRoute("id",null);
			
			$serviceQuestions = new ExamquestionsServiceImpl();
			$questions = $serviceQuestions->getQuestionById($id_exam);
			$exam = new ExamServiceImpl();
			$tree = json_encode($exam->getAllTreeByType($this->type,$this->user));
			$examen = $exam->getParentBy($id_exam, $this->type,$this->user);
			$data = array(
					"questions" => $questions,
					"examen"    => $examen[0]['topic_name'],
					"id_exam"   => $examen[0]['id_topic'],
					"tree"      => $tree
			);
			return new ViewModel($data);
		
	}
	
	public function addAction()
	{
		$user = $this->user;
		$exam = new ExamServiceImpl();
		//$baseUrl = $this->_request->getBaseUrl();
		$id_exam = $this->params()->fromRoute("id",null);
		$data = $exam->getExamById($id_exam);
		return new ViewModel(Array("exam" => $data[0]));
		
	}
	
	public function saveAction()
	{
		$serviceQuestions = new ExamquestionsServiceImpl();
		
		$request = $this->getRequest();
		$response = $this->getResponse();
		
		if($request->isPost()){
			$questions = $request->getPost()->toArray();
			$addQuestions = $serviceQuestions->addQuestion($questions);
				
			if($addQuestions){
				$response->setContent(\Zend\Json\Json::encode(array('response' => true, 'data' => $addQuestions)));
			}else{
				$response->setContent(\Zend\Json\Json::encode(array('response' => false, "data" => "Error desconosido, consulta al administrador *.*")));
			}
			//echo "<pre>"; print_r($inf); exit;
			return $response;
			exit;
		}
	}
	
	public function editAction(){
	$user = $this->user;
	$serviceOpciones = new ExamopcionesServiceImpl();
	$serviceQuestion = new ExamquestionsServiceImpl();
	$exam = new ExamServiceImpl();
	$response = $this->getResponse();
	
	if($this->getRequest()->isPost()){
		$formData = $this->getRequest()->getPost()->toArray();
		$editQuestion = $serviceQuestion->editQuestion($formData);
		$editOption = $serviceOpciones->editOpcionEx($formData);
		
		if($editQuestion){
			$response->setContent(\Zend\Json\Json::encode(array('response' => true, 'data' => $editQuestion)));
		}else{
			$response->setContent(\Zend\Json\Json::encode(array('response' => false, "data" => "Error desconosido, consulta al administrador *.*")));
		}
		//echo "<pre>"; print_r($inf); exit;
		return $response;
		exit;
	}
	

	$id_question = $this->params()->fromRoute("id",null);
	$question = $serviceQuestion->getQuesById($id_question);
	$answers = $serviceOpciones->getOpcionOfQuestion($id_question);
	$data = array(
		"id_question" => $this->params()->fromRoute("id",null),
		"question"    => $question[0],
		"answers"	  => $answers,			
	);
	
	return new ViewModel($data);
	}
	
	public function getquestionAction()
	{
		$serviceQuestion = new ExamquestionsServiceImpl();
		$id_question = $this->params()->fromRoute("id",null);
		$question = $serviceQuestion->getQuesById($id_question);
		if($question != null){
			echo Zend_Json::encode(array("response" => "ok", "data" => $question));
		}else{
			echo Zend_Json::encode(array("response" => "fail", "data" => "Error desconosido, consulta al administrador"));
		}
		exit;
	}
	
	public function editquestionAction()
	{
		$serviceQuestion = new Application_Service_Impl_ExamquestionsServiceImpl();
		$formData = $this->getRequest()->getPost();
		//echo "<pre>"; print_r($formData); exit;
		$question = $serviceQuestion->editQuestion($formData);
		
		if($question != null){
			echo Zend_Json::encode(array("response" => "ok", "data" => $question));
		}else{
			echo Zend_Json::encode(array("response" => "fail", "data" => "Error desconosido, consulta al administrador"));
		}
		exit;
	}
	
	public function deletequestionAction()
	{

		$serviceQuestion = new ExamquestionsServiceImpl();	
		$response = $this->getResponse();
		
		$data = $this->getRequest()->getPost();
		$deleteQuestion = $serviceQuestion->deleteQuestion($data);
		if($deleteQuestion){
			$response->setContent(\Zend\Json\Json::encode(array('response' => true, 'ok' => $deleteQuestion)));
		}else{
			$response->setContent(\Zend\Json\Json::encode(array('response' => true, 'fail' => "Error desconocido,Consulta al administrador")));				
		}
		return $response;
		exit;
	}
}