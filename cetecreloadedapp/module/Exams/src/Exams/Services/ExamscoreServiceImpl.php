<?php
// include_once APPLICATION_PATH . '/daos/impl/ExamscoreDaoImpl.php';

namespace Exams\Services;

use Exams\Model\ExamscoreDaoImpl;


class ExamscoreServiceImpl{
	

	public function addNewScore($id_user,$id_exam,$type){
		$examScoreDao=new ExamscoreDaoImpl();
		return $examScoreDao->addNewScore($id_user,$id_exam,$type);
	}
	
	public function updateScore($id_user,$id_version, $user_score){
		$examScoreDao=new ExamscoreDaoImpl();
		return $examScoreDao->updateScore($id_user,$id_version, $user_score);
	}
	
	public function getScore($id_version,$id_user){
		$examScoreDao=new ExamscoreDaoImpl();
		return $examScoreDao->getScore($id_user, $id_version);
	}
	
	public function addFinalScore($id_version,$id_user,$finalscore){
		$examScoreDao=new ExamscoreDaoImpl();
		return $examScoreDao->addFinalScore($id_user,$id_version,$finalscore);
	}
	
	public function getNormalScoreByUser($id_user,$idExam){
		$examScore = new ExamscoreDaoImpl();
		return $examScore->getNormalScoreByUser($id_user,$idExam);
	}
	
	public function getExtraScoreByUser($id_user,$idExam){
		$examScore = new ExamscoreDaoImpl();
		return $examScore->getExtraScoreByUser($id_user,$idExam);
	}
	
	public function getAllScoresByUser($id_user){
		$examScore = new ExamscoreDaoImpl();
		return $examScore->getAllScoresByUser($id_user);
	}
	
	public function getApprovedScoreByTrim($id_user,$trim){
		$idExam = "";
		if($trim == 1){
			$idExam = 269;
		}elseif($trim == 2){
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
		
		$examScore = new ExamscoreDaoImpl();
		return $examScore->getApprovedScore($id_user, $idExam);
		
	}
}