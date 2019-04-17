<?php
namespace Classes\Services;

use Classes\Model\LoginHistoryModel;

class LoginHistoryService
{
	
	public function getLoginHistoryByUser($id_user)
	{
		$loginHistoryModel = new LoginHistoryModel();
		$history = $loginHistoryModel->getLoginHistoryByUser($id_user);	
		return $history;
	}

	
	public function addTime($id_user,$InitialDate,$endDate,$sessionTime){
		$loginHistoryModel = new LoginHistoryModel();
		
		if($sessionTime['hours']<10){
			$hours = "0".$sessionTime['hours'];
		}else{
			$sessionTime['hours'];
		}
		if($sessionTime['min']<10){
			$min ="0".$sessionTime['min'];
		}else{
			$min = $sessionTime['min'];
		}
		if($sessionTime['seg']<10){
			$seg ="0".$sessionTime['seg'];
		}else{
			$seg = $sessionTime['seg'];
		}
		
		$onlineTime = $hours.":".$min.":".$seg;
		//$InitialDateFormat = implode('-', array_reverse(explode('-',$$InitialDate)));
		$data = array(
				'id_user' => $id_user,
				'login_date'   => $InitialDate,
				'logout_date'  => $endDate,
				'session_time' => $onlineTime
		);
		
		$addTime = $loginHistoryModel->addHistory($data);
		return $data;
	}
	
}