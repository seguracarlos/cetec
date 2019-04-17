<?php
namespace Exams\Controller;

use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Iofractal\Controller\BaseController;

use Exams\Services\ExamServiceImpl;
use Exams\Services\ExamquestionsServiceImpl;
use Exams\Services\ExamopcionesServiceImpl;
use Exams\Services\ExamanswersServiceImpl;
use Exams\Services\ExamscoreServiceImpl;
use Classes\Services\StudyTimeService;
use Exams\Form\ExamForm;
use Auth\Utility\Mail2;
use Exams\Services\ExtraAttempsService;

class ExamController extends BaseController
{

	private $expensesServices;
	private $user = 15;
	private $type = 5;
	
	public function indexAction()
    {	
    	$session = new Container('User');
    	$role_id =  $session->offsetGet('roleId');
    	$idUser  =$session->offsetGet('userId');
    	$trim    =  $session->offsetGet('trim');
    	$examValidation = 0;
    	$exams		= new ExamServiceImpl();
		if($role_id == 4){
			$studyTimeService = new StudyTimeService();
			$serviceScore = new ExamscoreServiceImpl();
			$extraAttempsService = new ExtraAttempsService();
			$timeByUser = $studyTimeService->getTimeByUser($idUser);
			$hours = explode(":",$timeByUser['time']);
			$timeValidation = 0;
			if($hours[0]>=80){
				$timeValidation = 1;
			}
			
			$idExam = 0;
			
			if($trim == 1){
				$idExam = 269;
			}elseif($trim ==2){
				$idExam = 340;
			}elseif($trim == 3){
				$idExam = 341;
			}elseif($trim == 4){
				$idExam = 342;
			}elseif($trim == 5){
				$idExam = 343;
			}elseif($trim == 6){
				$idExam = 344;
			}
			$normalScoresByUser = $serviceScore->getNormalScoreByUser($idUser,$idExam);
			$extraScoresByUser  = $serviceScore->getExtraScoreByUser($idUser,$idExam);
			$extraAttemps = $extraAttempsService->getExtraAttempsActiveByUser($idUser,$idExam);
				
			$scoreValidation = 0;
			$extraScoreValidaton = 0;
			foreach($normalScoresByUser as $score){
				if($score['totalscore']>=6){
					$scoreValidation = $score['totalscore'];
				}
			}			
			foreach($extraScoresByUser as $extraScore){
				if($extraScore['totalscore']>=6){
					$extraScoreValidaton = $extraScore['totalscore'];
				}
			}
			
			
			$data = array(
					'exams'			=> $exams->getExamByTrim($trim,$role_id),
					'role'			=> $role_id,
					'scoreValidation' => $scoreValidation,
					'attemps'         => 3-count($normalScoresByUser),
					'time'            => $timeValidation,
					'extraAttemps'    => $extraAttemps,
					'extraScoreValidation' => $extraScoreValidaton
			);
							
		}else{
			$data = array(
				'exams'			=> $exams->getExamByTrim($trim,$role_id),
				'role'			=> $role_id,
			);
		}
		
		return new ViewModel($data);
    }

    /*
     * Metodo add, agrega un examen
     */
    public function addAction()
    {

    	$exams= new ExamServiceImpl();
    	$form = new ExamForm();
    	
    	if($this->getRequest()->isPost()){
    		$formData=$this->getRequest()->getPost();
    		$examSave=$exams->addExam($formData,$this->type,$this->user);
    		$this->flashMessenger()->addMessage('Examen Creado Correctamente');
    		return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/exams/exam/index');
    		
    	}
    	
    	$data = array(
    			'exams'			=> $exams->getAllParents($this->type,$this->user),
    			'formparam'		=> $form,
    	);
    	return new ViewModel($data);
    }

    /*
     * Metodo edit, modifica un proyecto
     */
    public function editAction()
    {
    	$examService = new ExamServiceImpl();
    	$form = new ExamForm();
    	$idExam = $this->params()->fromRoute("id",null);
    	$exam = $examService->getExamById($idExam);
    	$form->setData($exam[0]);
    	$startDate = $exam[0]['startDate'];
    	$endDate   = $exam[0]['endDate'];
    	
    	if($this->getRequest()->isPost()){
    		$formData=$this->getRequest()->getPost();
    		$examSave=$examService->editExam($formData);
    		return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/exams/exam/index');
    		
    	}
    	
    	$view       = array("form" => $form, "startDate" => $startDate, "endDate" => $endDate);

    	return new ViewModel($view);
    }

    /*
     * Metodo delete, eliminar un proyecto
     */
     public function deleteAction()
     {
     	$exams= new ExamServiceImpl();
     	
     	$deleteParam = $this->params()->fromRoute("id",null);
     	$exams->deleteParent($deleteParam);
     	$this->flashMessenger()->addMessage('Examen Eliminado Correctamente');
     	return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/exams/exam/index');
     }


     public function userexamAction(){
     	$user = $this->user();
     	$exams= new ExamServiceImpl();
     	$examsuser = $exams->getAllExamsActivated($this->type,$user);
     }
     
     public function replyexamAction(){
     	$serviceTime 		 = new StudyTimeService();
     	$extraAttempsService = new ExtraAttempsService();
     	$exams				 = new ExamServiceImpl();
     	$serviceScore 	  	 = new ExamscoreServiceImpl();
     	$session 			 = new Container('User');
     	$userName  = $session->offsetGet('name')." ".$session->offsetGet('lastname')." ".$session->offsetGet('surname');
     	$idStudent = $session->offsetGet('userId');
     	$trim = $session->offsetGet('trim');
     	$idExam = 0;
     		
     	if($trim == 1){
     		$idExam = 269;
     	}elseif($trim ==2){
     		$idExam = 340;
     	}elseif($trim == 3){
     		$idExam = 341;
     	}elseif($trim == 4){
     		$idExam = 342;
     	}elseif($trim == 5){
     		$idExam = 343;
     	}elseif($trim == 6){
     		$idExam = 344;
     	}
     	$studentTime = $serviceTime->getTimeByUser($idStudent);
     	$attemps = $serviceScore->getNormalScoreByUser($idStudent,$idExam);
     	$extraAttemps = $extraAttempsService->getExtraAttempsActiveByUser($idStudent,$idExam);
     	$id = $this->params()->fromRoute("id",null);
     	$highestScore = "";
     	
     	foreach ($attemps as $value) {
     		if ($value['totalscore'] >= $highestScore)
     			$highestScore = $value['totalscore'];
     	}
     	     	
     	if($studentTime['time']>=80 && 3-count($attemps) >= 1 && $highestScore < 6){//Si el alumno tiene oportunidades normales de hacer examen y no ha aprobado
     		$type = 1;//Examen Normal
     		$initScore = $serviceScore->addNewScore($idStudent,$id,$type);
     		$generatedExam = $exams->generateExam($id, $this->type, $this->user, $initScore, $idStudent);
     		return new viewModel($generatedExam);
     	}else{//Sino valida si el alumno tiene asignado un examen extraordinario     		
			if($extraAttemps != null){//Si tiene
	     		$type = 2;//Extraordinario
    	 		$initScore = $serviceScore->addNewScore($idStudent,$id,$type);//Crea un Examen Extra
    	 		$generatedExam = $exams->generateExam($id, $this->type, $this->user, $initScore, $idStudent);
    	 		$useAttemp = $extraAttempsService->updateAttemp($idStudent, $id);
    	 		return new viewModel($generatedExam);
			}else{// Sino hace un redirect al index
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/exams/exam/index');
			}
     	}
     }
     
     public function checkanswersAction(){
     	$serviceQuestions = new ExamquestionsServiceImpl();
     	$serviceAnswers   =	new ExamanswersServiceImpl();
     	$serviceScore 	  = new ExamscoreServiceImpl();
     	$sendMail 		  = new Mail2();
     	$session 		  = new Container('User');
     	$userName =	$session->offsetGet('name')." ".$session->offsetGet('lastname')." ".$session->offsetGet('surname');
     	$mail     = $session->offsetGet('email');
     	$idStudent = $session->offsetGet('userId');
     	$trim = $session->offsetGet('trim');
     	$response = $this->getResponse();
     	
     	
     	if ($this->getRequest()->isPost()) {
     		$formData = $this->getRequest()->getPost();
     		$questions = $serviceQuestions->getQuestionById($formData->exam);
     		$userAnswer = $serviceAnswers->addUserAnswer($formData);
     		$version=$userAnswer['id_version'];
     		$id_user=$userAnswer['id_user'];
     		$checkAnswerValue=$serviceAnswers->checkAnswers($formData['idquestion'], $userAnswer['user_resp'],$formData['questionType']);
     		$score=$serviceScore->updateScore($id_user,$version, $checkAnswerValue);
     		if(!isset($formData['finalice'])){
     			if($userAnswer){
     				$response->setContent(\Zend\Json\Json::encode(array('response' => true, 'ok' => $score)));
     			}else{
     				$response->setContent(\Zend\Json\Json::encode(array('response' => false, 'fail' => "Error desconocido, consulta al administrador")));
     	
     			}
     			return $response;
     			exit();
     		}
     		if(isset($formData['finalice'])){
     			$total=$serviceQuestions->getTotalPoints($formData->exam);
     			$user_score = $serviceScore->getScore($version, $id_user);
     			$finalScore = ($user_score[0]['score']/24)*10;
     			$finalScoreDecimal = round($finalScore, 1);
     			$saveFinal=$serviceScore->addFinalScore($version, $id_user,$finalScoreDecimal);
     			$attemps = $serviceScore->getNormalScoreByUser($idStudent,$formData->exam);
     			$sendMail->sendEvaluationInfo($mail, $finalScoreDecimal,$trim,$attemps,$userName);
     			if($saveFinal){
     				$response->setContent(\Zend\Json\Json::encode(array('response' => true, 'ok' => "Voto Correcto","data" => "Voto Correcto","scorefinal" => $finalScoreDecimal)));
     			}else{
     				$response->setContent(\Zend\Json\Json::encode(array('response' => true, 'fail' => "Voto Correcto","data" => "Error desconosido, consulta al administrador")));
     			}
     		}
     		return $response;
     		exit();
     	}
     }
     
     
     public function getscoreAction(){
     	$response = $this->getResponse();
     	$exams= new ExamServiceImpl();
     	$serviceQuestions = new ExamquestionsServiceImpl();
     	$serviceScore = new ExamscoreServiceImpl();
     	$session = new Container('User');
     	$sendMail = new Mail2();
     	$user = $session->offsetGet('userId');
     	$userName = $session->offsetGet('name')." ".$session->offsetGet('lastname')." ".$session->offsetGet('surname');
     	$mail = $session->offsetGet('email');
     	$trim =	$session->offsetGet('trim');
     
     	if ($this->getRequest()->isPost()) {
     		if(isset($_POST['myData'])){
     			$formData = json_decode($_POST['myData']);
     		}
     		$version = $formData->version;
     		$exam = $formData->exam;
     		$id_user = $formData->user;
     		$total=$serviceQuestions->getTotalPoints($exam);
     		$user_score = $serviceScore->getScore($version, $id_user);
     		//$finalScore = ((((int)$user_score[0]['score'] * 100)/(int)$total[0]['total']))/10;
     		$finalScore = ($user_score[0]['score']/20)*10;
     		$finalScoreDecimal = round($finalScore, 1);
     		$saveFinal=$serviceScore->addFinalScore($version, $id_user,$finalScoreDecimal,$mail);
     		$attemps = $serviceScore->getNormalScoreByUser($id_user,$exam);
     		$sendMail->sendEvaluationInfo($mail, $finalScoreDecimal,$trim,$attemps,$userName);
     		    
     		if($saveFinal){
     			$response->setContent(\Zend\Json\Json::encode(array('response' => true, 'ok' => "Voto Correcto","data" => "Voto Correcto","scorefinal" => $finalScoreDecimal)));
     		}else{
     			$response->setContent(\Zend\Json\Json::encode(array('response' => true, 'fail' => "Voto Correcto","data" => "Error desconosido, consulta al administrador")));
     		}
     		return $response;
     		exit;
     	}
     
     	$data = Array(
     			"tree" => json_encode($exams->getAllTreeByType($this->type,$user))
     	);
     
     	return new viewModel($data);
     }
     
     public function getscoresbyuserAction(){
     	$serviceScore = new ExamscoreServiceImpl();
     	
     	$request = $this->getRequest();
     	$response = $this->getResponse();
     	
     	if($request->isPost()){
     		$data = $request->getPost()->toArray();
     		$scores = $serviceScore->getAllScoresByUser($data['id_user']);
     		$scoresWithTrim = array();
     		$trim = "";
     		if($scores){
     			foreach($scores as $score){
     				if($score['id_exam'] == 269){
     					$trim = 1;
     				}elseif($score['id_exam'] == 340){
     					$trim = 2;
     				}elseif($score['id_exam'] == 341){
     					$trim = 3;
     				}elseif($score['id_exam'] == 342){
     					$trim = 4;
     				}elseif($score['id_exam'] == 343){
     					$trim = 5;
     				}elseif($score['id_exam'] == 344){
     					$trim = 6;
     				}
     				$scoresWithTrim[] = array(
     						'id_version' => $score['id_version'],
     						'date'       => $score['date'],
     						'trim'       => $trim,
     						'exam_type'  => $score['exam_type'],
     						'totalscore' => $score['totalscore']
     				);
     			}
     			$response->setContent(\Zend\Json\Json::encode(array('status' => true, 'data' => $scoresWithTrim)));
     		}else{
     			$response->setContent(\Zend\Json\Json::encode(array('status' => false, "data" => "Error desconocido, consulta al administrador *.*")));
     		}
     		//echo "<pre>"; print_r($inf); exit;
     		return $response;
     		exit;
     	}
     }
     
     public function addextraattempAction(){
     	$attempsService = new ExtraAttempsService();
     	$request = $this->getRequest();
     	$response = $this->getResponse();
     	
     	if($request->isPost()){
     		$data = $request->getPost()->toArray();
     		$addAttemp = $attempsService->addExtraAttemps($data);

     		if($addAttemp){
     			$response->setContent(\Zend\Json\Json::encode(array('status' => true, 'data' => $addAttemp)));
     		}else{
     			$response->setContent(\Zend\Json\Json::encode(array('status' => false, "data" => "Error desconocido, consulta al administrador *.*")));
     		}
     		return $response;
     		exit;
     	}
     }
     
     public function getextraattemplistAction(){
     	$attempsService = new ExtraAttempsService();
     	$request = $this->getRequest();
     	$response = $this->getResponse();
     	$extraScoresWithTrim = array();
     	$trim = "";
     	if($request->isPost()){
     		$data = $request->getPost()->toArray();
     		$attemps = $attempsService->getExtraAttempsByUser($data['id_user'],$data['idExam']);
     		if($attemps){
     			if($attemps){
     				foreach($attemps as $score){
     					if($score['id_exam'] == 269){
     						$trim = 1;
     					}elseif($score['id_exam'] == 340){
     						$trim = 2;
     					}elseif($score['id_exam'] == 341){
     						$trim = 3;
     					}elseif($score['id_exam'] == 342){
     						$trim = 4;
     					}elseif($score['id_exam'] == 343){
     						$trim = 5;
     					}elseif($score['id_exam'] == 344){
     						$trim = 6;
     					}
     					$extraScoresWithTrim[] = array(
     							'date'       => $score['date'],
     							'trim'       => $trim,
     							'comment' => $score['comment']
     					);
     				}
     			}
     			$response->setContent(\Zend\Json\Json::encode(array('status' => true, 'data' => $extraScoresWithTrim)));
     		}else{
     			$response->setContent(\Zend\Json\Json::encode(array('status' => false, "data" => "Error desconocido, consulta al administrador *.*")));
     		}
     		return $response;
     		exit;
     	}
     }
     
     public function viewresultAction(){
     	$serviceAnswers   =	new ExamanswersServiceImpl();
     	$idVersionExam = $this->params()->fromRoute("id",null);
     	$examAnswers = $serviceAnswers->getExamVersionById($idVersionExam);
     	$view = array('exam' => $examAnswers);
     	return new ViewModel($view);
     }

}