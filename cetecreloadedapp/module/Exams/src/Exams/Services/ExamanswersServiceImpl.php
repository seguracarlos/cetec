<?php
// include_once APPLICATION_PATH . '/daos/impl/ExamanswersDaoImpl.php';
// include_once APPLICATION_PATH . '/daos/impl/ExamquestionsDaoImpl.php';
// include_once APPLICATION_PATH . '/daos/impl/QuestionsDaoImpl.php';
namespace Exams\Services;


use Exams\Model\ExamanswersDaoImpl;
use Exams\Model\ExamquestionsDaoImpl;
use Exams\Model\ExamopcionesDaoImpl;
use System\Services\UsersService;
class ExamanswersServiceImpl{
	
	public function addUserAnswer($formData){
		$answerDao = new ExamanswersDaoImpl();
		$examQuestionDao = new ExamquestionsDaoImpl();
		
		if($formData['questionType'] == 1){
			$data = array(
				"id_preg" => $formData['idquestion'],
				"id_user" => $formData['user'],
				"id_version" => $formData['initScore'],
				"user_resp" => $formData['open'],
				"type_question" => $formData['questionType'],
				
			);	
			
		}
		else if($formData['questionType'] == 2){
			$data = array(
				"id_preg" => $formData['idquestion'],
				"id_user" => $formData['user'],
				"id_version" => $formData['initScore'],
				"user_resp" => $formData['resp'],
				"type_question" => $formData['questionType'],
				
					
			);
			
		}else if($formData['questionType'] == 3){
                                                                                                     
			$resp = json_encode($formData['respCheck']);
			$data = array(
					"id_preg" => $formData['idquestion'],
					"id_user" => $formData['user'],
					"id_version" => $formData['initScore'],
					"user_resp" => $resp,
					"type_question" => $formData['questionType'],
			);
			
		}else if($formData['questionType'] == 4){
			$resp=json_encode($formData['respCheckImg']);
			$data = array(
					"id_preg" => $formData['idquestion'],
					"id_user" => $formData['user'],
					"id_version" => $formData['initScore'],
					"user_resp" => $resp,
					"type_question" => $formData['questionType'],
			);
		}
		return $answerDao->addUserAnswer($data);
		
	}
	
	public function checkAnswers($id_preg,$id_resp,$questionType){
		$checkQuestionDao = new ExamquestionsDaoImpl();
		/*if($questionType == 3 || $questionType == 4){
			$id_resp = json_decode($id_resp);
			echo "<pre>";print_r($id_resp);exit();		
		}*/
		return $checkQuestionDao->checkAnswer($id_preg,$id_resp,$questionType);
	}
	
	public function getExamVersionById($idVersion){
		$answerDao = new ExamanswersDaoImpl();
		$examQuestionDao = new ExamquestionsDaoImpl();
		$optionsQuestionsDao = new ExamopcionesDaoImpl();
		$userService = new UsersService();
		$answersByVersion = $answerDao->getAnswersVersionById($idVersion);
		$exam = array();
		$examQuestions = array();
		$options = array();
		if($answersByVersion != null){
			$userInfo = $userService->getUserById($answersByVersion[0]['id_user']);
			$trim = 0;
			if($answersByVersion[0]['id_exam'] == 269){
				$trim = 1;
			}elseif($answersByVersion[0]['id_exam'] == 340){
				$trim = 2;
			}elseif($answersByVersion[0]['id_exam'] == 341){
				$trim = 3;
			}elseif($answersByVersion[0]['id_exam'] == 342){
				$trim = 4;
			}elseif($answersByVersion[0]['id_exam'] == 343){
				$trim = 5;
			}elseif($answersByVersion[0]['id_exam'] == 344){
				$trim = 6;
			}
			$exam['info'] = array(
					'name' => $userInfo[0]['name']." ".$userInfo[0]['lastname']." ".$userInfo[0]['surname'],
					'trim' => $trim,
					'date' => $answersByVersion[0]['date'],
					'score' => $answersByVersion[0]['totalscore'] != null ? $answersByVersion[0]['totalscore'] : 0,
					'successAnswers' => $answersByVersion[0]['score']
			);				
		}else{
			$versionInfo = $answerDao->getVersionInfoById($idVersion);
			$userInfo = $userService->getUserById($versionInfo['id_user']);
			$trim = 0;
			if($versionInfo['id_exam'] == 269){
				$trim = 1;
			}elseif($versionInfo['id_exam'] == 340){
				$trim = 2;
			}elseif($versionInfo['id_exam'] == 341){
				$trim = 3;
			}elseif($versionInfo['id_exam'] == 342){
				$trim = 4;
			}elseif($versionInfo['id_exam'] == 343){
				$trim = 5;
			}elseif($versionInfo['id_exam'] == 344){
				$trim = 6;
			}

			$exam['info'] = array(
					'name' => $userInfo[0]['name']." ".$userInfo[0]['lastname']." ".$userInfo[0]['surname'],
					'trim' => $trim,
					'date' => $versionInfo['date'],
					'score' => 0,
					'successAnswers' => 0
			);
		}

		foreach($answersByVersion as $answer){
			$examQuestions = $examQuestionDao->getQuestionByIdQuestion($answer['id_preg']);
			$options = $optionsQuestionsDao->getOpcionOfQuestion($answer['id_preg']);
			$exam['questions'][] = array(
					'id_question' => $examQuestions['id_question'],
					'id_exam'     => $examQuestions['id_topic'],
					'question_name' => $examQuestions['question_name'],
					'correctAnswer' => $examQuestions['id_resp'],
					'userAnswer'    => $answer['user_resp'],
					'options' => $options
			);
			
		}
		return $exam;
	}
}
?>