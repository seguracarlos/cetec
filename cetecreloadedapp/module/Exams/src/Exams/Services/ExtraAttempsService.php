<?php
// include_once APPLICATION_PATH . '/daos/impl/ExamscoreDaoImpl.php';

namespace Exams\Services;

use Exams\Model\ExtraAttempsDao;

class ExtraAttempsService{
	
	public function getExtraAttempsByUser($id_user,$idExam){
		$extraAttempsDao = new ExtraAttempsDao();
		return $extraAttempsDao->getExtraAttempsByUser($id_user,$idExam);
	}
	
	public function getExtraAttempsActiveByUser($id_user,$idExam){
		$extraAttempsDao = new ExtraAttempsDao();
		return $extraAttempsDao->getExtraAttempsActiveByUser($id_user,$idExam);
	}
	
	public function getAllExtraAtempsByUser($id_user){
		$extraAttempsDao = new ExtraAttempsDao();
		return $extraAttempsDao->getExtraAttempsByUser($id_user);
	}
	
	public function addExtraAttemps($data){
		$extraAttempsDao = new ExtraAttempsDao();
		$extraAttemp = array(
					"id_exam" => $data['idExam'],
					"id_user" => $data['idUser'],
					"used" => 0,
					"comment" => $data['comment']
		);
		
		return $extraAttempsDao->addExtraAttemp($extraAttemp);
	}
	
	public function updateAttemp($id_user,$id_exam){
		$extraAttempsDao = new ExtraAttempsDao();
		$data = array(
				"id_user" => $id_user,
				"id_exam" => $id_exam,
				"used"    => 1
		);
		return $extraAttempsDao->updateAttemp($data);
	}
	
	
	
}