<?php
namespace Horus\Services;

use Horus\Model\PaymentModel;
use Exams\Services\ExamscoreServiceImpl;
use System\Model\UsersModel;
use Classes\Services\StudyTimeService;

class PaymentServices
{

	public function getPaymentInfoByUser($id_user)
	{
		$paymentModel = new PaymentModel();
		$checkStudent = $paymentModel->getPaymentByUser($id_user);
		return $checkStudent;
	}
	
	public function addPaymentInfo($formData){
		$paymentModel = new PaymentModel();
		$data = array(
				"inscription" => "1",
				"month_1"     => "0",
				"month_2"     => "0",
				"month_3"     => "0",
				"expiration_date"  => "",
				"id_student"  => $formData['id_user']
		);
		$addPaymentInfo = $paymentModel->addRow($data);
		return $addPaymentInfo;
	}
	
	public function updatePaymentInfo($formData){
		$paymentModel = new PaymentModel();
		$examScore    = new ExamscoreServiceImpl();
		$usersModel   = new UsersModel();

		if($formData['payment']=="month_1"){
			if($formData['status'] == 0){
				$data = array(
						"month_1" => "0",
						"id_student" => $formData['id_user']
				);
			}elseif($formData['status'] == 1){
				$data = array(
						"month_1" => "1",
						"id_student" => $formData['id_user']
				);
			}
			
			$paymentUpdate = $paymentModel->updateRow($data);
			return $status = "month";
		}elseif($formData['payment']=="month_2"){
			if($formData['status'] == 0){
				$data = array(
					"month_2" => "0",
					"id_student" => $formData['id_user']
				);
			}elseif($formData['status'] == 1){
				$data = array(
						"month_2" => "1",
						"id_student" => $formData['id_user']
				);
			}
			$paymentUpdate = $paymentModel->updateRow($data);
			return $status = "month";
		}elseif($formData['payment']=="month_3"){
			if($formData['status'] == 0){
				$data = array(
					"month_3" => "0",
					"id_student" => $formData['id_user']
				);
			}else{
				$data = array(
						"month_3" => "1",
						"id_student" => $formData['id_user']
				);
			}
			$paymentUpdate = $paymentModel->updateRow($data);
			return $status = "month";
		}elseif($formData['payment']=="nextTrim"){
			$userInfo = $usersModel->getUserById($formData['id_user']);
			$validateExamScore = $examScore->getApprovedScoreByTrim($formData['id_user'],$userInfo[0]['trim']);
			$inscription = 0;
			if($validateExamScore){
				$studyTimeService = new StudyTimeService();
				$updateHours = $studyTimeService->updateTime($formData['id_user'], "00:00:00");
				if($userInfo[0]['trim']!=5){
					$inscription = 1;
				}
				$data = array(
						"inscription" => $inscription,
						"month_1"     => "0",
						"month_2"     => "0",
						"month_3"     => "0",
						"expiration_date"  => "",
						"id_student"  => $formData['id_user']
				);
				

				$paymentUpdate = $paymentModel->updateRow($data);
				
				if($userInfo[0]['trim']<=5){
					$trimInfo = array(
							"trim" => $userInfo[0]['trim']+1,
							"user_id" => $formData['id_user']
					);
					$userUpdate = $usersModel->editUser($trimInfo);
					return $status = $trimInfo['trim'];						
				}else{
					return $status = "6"; 
				}
			}else{
				return $status = "Score";
			}
		}		
	}
	
	
}