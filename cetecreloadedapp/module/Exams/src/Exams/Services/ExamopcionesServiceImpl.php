<?php
//include_once APPLICATION_PATH . '/entities/SurveysEntity.php';
// include_once APPLICATION_PATH . '/daos/impl/ExamopcionesDaoImpl.php';

namespace Exams\Services;

use Exams\Model\ExamopcionesDaoImpl;

class ExamopcionesServiceImpl
{
	
public function addOpcion($formData, $id_question)
	{
		$daoQuestion = new ExamopcionesDaoImpl();
		
		if(isset($formData['image'])){
			$corrects = $formData['correct'];
			foreach (array_combine($formData['image'],$formData['id_resp']) as $image => $id_resp){
				$question = $daoQuestion->addOpcion($image,$id_resp,$id_question,$corrects);
			}
		}elseif($formData['typequestion'] == 1){
			$opc = "nulo";
			$question = $daoQuestion->addOpcion($opc, $id_question);
		}else{
			$corrects = $formData['correct'];
			foreach(array_combine($formData['opc'],$formData['id_resp']) as $opc => $id_resp){
				$question = $daoQuestion->addOpcion($opc, $id_resp, $id_question,$corrects);
			}
		}
		
		return $question;
		//echo "<pre>"; print_r($surveysEntity); exit;
	}

	public function getOpcionOfQuestion($id_question)
	{
		$daoOption = new ExamopcionesDaoImpl();
		return $surveys = $daoOption->getOpcionOfQuestion($id_question);
	}
	
	public function getOpcionById($id_opcion)
	{
		$daoOpciones = new ExamopcionesDaoImpl();
	
		$opcion = $daoOpciones->getOpcionById($id_opcion);
	
		return $opcion;
	}
	
	public function editOpcion($formData)
	{
		$daoOpciones = new ExamopcionesDaoImpl();
		if($formData['questypeImg'] != 4){
			$data = array(
				"id_opcion" => $formData['id_opcion'],
				"nombre" => $formData['title'],
				"correct" => $formData['correct']
			);
			
		}else if($formData['questypeImg'] == 4){
			$data = array(
				"id_opcion" => $formData['id_opcionImg'],
				"nombre" => $formData['baseImg'],
				"correct" => $formData['correct']
			);
		}
		//echo "<pre>"; print_r($surveysEntity); exit;
		$opcion = $daoOpciones->editOpcion($data);
		return $opcion;
	}
	
	public function editOpcionEx($formData)
	{
		$daoOpciones = new ExamopcionesDaoImpl();
		foreach($formData['questions'][0]['answers'] as $answers){
			$data = array(
				"id_opcion" => $answers['id'],
				"nombre" => $answers['answer'],
				"correct" => $answers['correct']	
			);
			
			$optionUpdate = $daoOpciones->editOpcionEx($data);
		}
		
		return $optionUpdate;
	}
	
	public function deleteOpcion($id_opcion)
	{
		$daoOpciones = new ExamopcionesDaoImpl();
	
		$opcion = $daoOpciones->deleteOpcion($id_opcion);
	
		return $opcion;
	}
	
	
}
?>