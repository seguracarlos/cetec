<?php
//include_once APPLICATION_PATH . '/entities/SurveysEntity.php';
// include_once APPLICATION_PATH . '/daos/impl/ExamquestionsDaoImpl.php';

namespace Exams\Services;

use Exams\Model\ExamquestionsDaoImpl;
use Exams\Model\ExamopcionesDaoImpl;
use Exams\Model\ExamanswersDaoImpl;


class ExamquestionsServiceImpl
{
	
public function getQuestionById($id_exam)
	{
		$daoExamquestion = new ExamquestionsDaoImpl();
		$questions = $daoExamquestion->getQuestionById($id_exam);
		return $questions;
	}
	
	public function getQuestionsByThemeForExams($id_exam){
		$daoExamquestion = new ExamquestionsDaoImpl();
		$questions = $daoExamquestion->getQuestionById($id_exam);
		shuffle($questions);
		
		for($i=0;$i<20;$i++){
			$examQuestions[$i] = array(
				"id_question" => $questions[$i]['id_question'],
				"id_topic"    => $questions[$i]['id_topic'],
				"id_subtopic"  => $questions[$i]['id_subtopic'],
				"question_name" =>$questions[$i]['question_name'],
				"question_description" =>$questions[$i]['question_description'],
				"type_question" => $questions[$i]['type_question'],
				"id_resp"       => $questions[$i]['id_resp'],
				"expected_answer" => $questions[$i]['expected_answer'],
				"value"			=> 	$questions[$i]['value'],
			);
		}
//*************** Preguntas Por Tema **************	
// 		$questionsByTheme = array();
// 		foreach($questions as $theme){
// 			$questionsByTheme[$theme['id_subtopic']][] = array(
// 					"id_question" => $theme['id_question'],
// 					"id_topic"    => $theme['id_topic'],
// 					"id_subtopic" => $theme['id_subtopic'],
// 					"question_name" => $theme['question_name'],
// 					"question_description" => $theme['question_description'],
// 					"type_question"  => $theme['type_question'],
// 					"id_resp"        => $theme['id_resp'],
// 					"expected_answer" => $theme['expected_answer'],
// 					"value"           => $theme['value'],
// 			);
// 		}
		
// 		$reIndex = array_values($questionsByTheme);
// 		$examQuestions = array();
// 		for($i=0;$i<count($reIndex);$i++){
// 			shuffle($reIndex[$i]);
// 			for($j=0;$j<=3;$j++){
// 				$examQuestions[] = array(
// 										"id_question"=>$reIndex[$i][$j]['id_question'],
// 										"id_topic" =>$reIndex[$i][$j]['id_topic'] ,
// 										"id_subtopic" =>$reIndex[$i][$j]['id_subtopic'] ,
// 										"question_name" =>$reIndex[$i][$j]['question_name'] ,
// 										"question_description" =>$reIndex[$i][$j]['question_description'] ,
// 										"type_question"=>$reIndex[$i][$j]['type_question'],
// 										"id_resp"    =>$reIndex[$i][$j]['id_resp'],
// 										"expected_answer" =>$reIndex[$i][$j]['expected_answer'],
// 										"value"          =>	$reIndex[$i][$j]['value']
// 				);
// 			}
			
// 		}
//************Fin preguntas por tema
		return $examQuestions;
	}
	
	public function addQuestion($formData)
	{
		$daoQuestion = new ExamquestionsDaoImpl();
		$optionsModel = new ExamopcionesDaoImpl();
		$question = array();
		foreach($formData['questions'] as $questions){
			$question = array(
					'id_topic' => $questions['id_topic'],
					'id_subtopic' => '0',
					'question_name' => $questions['question'],
					'question_description' => '',
					'type_question'        => $questions['type'],
					'expected_answer'      => '',
					'value'                => 1
			);
			$questionAdd = $daoQuestion->addQuestion($question);
			foreach($questions['answers'] as $answers){
				if($answers['correct'] == 1){
					$correctUpdate = array(
							'id_question' => $questionAdd['id_question'],
							'id_resp'     => $answers['id']
					);
					$questionUpdate = $daoQuestion->editQuestion($correctUpdate);
				}
				$answers = array(
						'id_question' => $questionAdd['id_question'],
						'id_resp'     => $answers['id'],
						'nombre'      => $answers['answer'],
						'valor'       => '',
						'correct'     => $answers['correct']
				);
				$optionsAdd = $optionsModel->addOpcion($answers);
				
				
			}
			
		}
		return $questionAdd;
	}
	
	public function getQuesById($id_question)
	{
		$daoQuestion = new ExamquestionsDaoImpl();
		return $question = $daoQuestion->getQuesById($id_question);
	}
	
	public function getTotalPoints($id_exam){
		$daoQuestion = new ExamquestionsDaoImpl();
		return $question = $daoQuestion->getTotalPoints($id_exam);
		
	}
	
	public function editQuestion($formData)
	{
		$daoQuestion = new ExamquestionsDaoImpl();
		
		foreach($formData['questions'] as $question){
			$question = array(
					'id_question' => $question['id_question'],
					'question_name' => $question['question'],
					'id_resp'       => $question['correct']
			);
			$question = $daoQuestion->editQuestion($question);
		}
		return $question;
		//echo "<pre>"; print_r($surveysEntity); exit;
	}
	
	public function deleteQuestion($data)
	{
		$daoQuestion = new ExamquestionsDaoImpl();		
		return $question = $daoQuestion->deleteQuestion($data);
	}
	
	
}
?>