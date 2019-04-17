<?php
namespace Exams\Services;

// include_once APPLICATION_PATH . '/entities/TopicsEntity.php';
//use Usermanual\Entities\TopicsEntity;
use Exams\Model\ExamModelImpl;
// include_once APPLICATION_PATH . '/daos/impl/ExamDaoImpl.php';

class ExamServiceImpl{
	
	public function getAllTreeByType($type,$user){
		$ExamDao=new ExamModelImpl();
		$row = $ExamDao->getAllTreeByType($type,$user);
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
					$row[$i]['id'].")'  href='#'>".$row[$i]['text']."</a>";	
				}
			}
	 	}
		return $row;
	}
	
	public function getAllParents($type,$user){
		$exm = new ExamModelImpl();
		$ExamDao = $exm->getAllParents($type,$user);
		for($i = 0; $i < count($ExamDao); $i++){
			$startD = $ExamDao[$i]['startDate'];
			$startDat = explode("-", $startD);
			$dStart = array_reverse($startDat, true);
			$startDate = implode("/", $dStart);
			$endD = $ExamDao[$i]['endDate'];
			$endDat = explode("-", $endD);
			$dEnd = array_reverse($endDat, true);
			$endDate = implode("/", $dEnd);
			$ExamDao[$i]['startDate'] = $startDate;
			$ExamDao[$i]['endDate'] = $endDate;
			if($ExamDao[$i]['time'] == null){
				unset($ExamDao[$i]);
			}	
		}
		
		return $ExamDao;
	}
	
	public function getExamByTrim($trim,$roleId){
		$exm = new ExamModelImpl();

		if($trim == 1){
			$parent = 97;
		}elseif($trim == 2){
			$parent = 146;
		}elseif($trim == 3){
			$parent = 194;
		}elseif($trim == 4){
			$parent = 198;
		}elseif($trim == 5){
			$parent = 210;
		}elseif($trim == 6){
			$parent = 324;
		}
		if($roleId == 1){
			$ExamDao = $exm->getAllExams();
		}elseif($roleId == 4){
			$ExamDao = $exm->getExamByTrim($parent);
		}
		
		for($i = 0; $i < count($ExamDao); $i++){
			$startD = $ExamDao[$i]['startDate'];
			$startDat = explode("-", $startD);
			$dStart = array_reverse($startDat, true);
			$startDate = implode("/", $dStart);
			$endD = $ExamDao[$i]['endDate'];
			$endDat = explode("-", $endD);
			$dEnd = array_reverse($endDat, true);
			$endDate = implode("/", $dEnd);
			$ExamDao[$i]['startDate'] = $startDate;
			$ExamDao[$i]['endDate'] = $endDate;
			if($ExamDao[$i]['time'] == null){
				unset($ExamDao[$i]);
			}
		}
		
		return $ExamDao;
		
	}
	
	public function validarFecha($fech)
	{
		
		$fecha = explode("/", $fech);//Separo por diagonal
		
		$fechaInversa = array_reverse($fecha, true); //invierto posiciones
		
		$fechafinal = implode("-", $fechaInversa); //agrego un guion
		
		return $fechafinal;
	}
	
	public function getExamById($id){
		$exm = new ExamModelImpl();
		$exam = $exm->getExamById($id);
		return $exam;
	}
	
	public function addExam($formData,$type,$user){
		$ExamDao=new ExamModelImpl();
		$formData['startDate'] = self::validarFecha($formData['startDate']);
		$formData['endDate'] = self::validarFecha($formData['endDate']);
		
		$data=array('id_user'=>$user,
				'topic_name'=>$formData['topic_name'],
				'parent'=>"5",
				'type'=>"folder",
				'project_name'=>$formData['project_name'],
				'description'=> $formData['description'],
				'artefact_type'=>$type,
				'startDate'=>$formData['startDate'],
				'endDate'=>$formData['endDate'],
				'time'=>$formData['time']);
		
		return $ExamDao->addExam($data);
	}
	
	public function getParentBy($id, $type,$user){
		$examDao=new ExamModelImpl();
		return $examDao->getParentBy($id,$type,$user);
	}
	
	public function editExam($formData){
		$examDao=new ExamModelImpl();
		$formData['startDate'] = self::validarFecha($formData['startDate']);
		$formData['endDate'] = self::validarFecha($formData['endDate']);
		
		$data = array(
				'id_topic' => $formData['id_topic'],
				'topic_name' => $formData['topic_name'],
				'project_name' => $formData['project_name'],
				'description'  => $formData['description'],
				'startDate'    => $formData['startDate'],
				'endDate'      => $formData['endDate'],
				'time'         => $formData['time']
			
		);
		
		$examUpdate = $examDao->editParent($data);
		return $examUpdate;
	}
	
	public function deleteParent($deleteParam){
		$examDao = new ExamModelImpl();
		return $examDao->deleteParent($deleteParam);
	}
	
	public function getAllExamsActivated($type,$user){
		$exm = new ExamModelImpl();
		$ExamDao = $exm->getAllParents($type,$user);
		for($i = 0; $i < count($ExamDao); $i++){
			$startD = $ExamDao[$i]['startDate'];
			$startDat = explode("-", $startD);
			$dStart = array_reverse($startDat, true);
			$startDate = implode("/", $dStart);
			$endD = $ExamDao[$i]['endDate'];
			$endDat = explode("-", $endD);
			$dEnd = array_reverse($endDat, true);
			$endDate = implode("/", $dEnd);
			$ExamDao[$i]['startDate'] = $startDate;
			$ExamDao[$i]['endDate'] = $endDate;	
		}
		return $ExamDao;
	}
	
	public  function generateExam($id, $type,$user,$initScore,$idStudent){
		$serviceQuestions = new ExamquestionsServiceImpl();
		$serviceOpciones = new ExamopcionesServiceImpl();
		$parent = $this->getParentBy($id, $type,$user);
		$questions = $serviceQuestions->getQuestionsByThemeForExams($id);
		for($j=0; $j < count($questions); $j++){
			$options[$j] = $serviceOpciones->getOpcionOfQuestion($questions[$j]['id_question']);
		}
	
		$time = explode(":",$parent[0]['time']);
		$hh = $time[0];
		$mm = $time[1];
		 
		$data = array(
				"user" => $idStudent,
				"question" => $questions,
				"options"  => $options,
				"parent"   => $parent,
				"initScore" => $initScore['id_score'],
				"hour"      => $hh,
				"min"       => $mm
		);
		
		return $data;
	}
}
?>