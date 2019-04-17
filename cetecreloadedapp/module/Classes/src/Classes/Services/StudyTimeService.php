<?php
namespace Classes\Services;

use Classes\Model\StudyTimeModel;

class StudyTimeService
{
	
	public function getTimeByUser($id_user)
	{
		$studyModel = new StudyTimeModel();
		$time = $studyModel->getTimeByUser($id_user);	
		return $time;
	}

	public function updateTime($id_user,$sumTime)
	{

		$studyModel = new StudyTimeModel();
		$data = array(
				'id_student' => $id_user,
				'time' => $sumTime,
						
		);
		$updateTime = $studyModel->updateTimeByUser($data);
		return $updateTime;
	}
	
	public function addTime($formData){
		$studyModel = new StudyTimeModel();
		$data = array(
				'id_student' => $formData['id_user'],
				'time'       => $formData['time']
		);
		$addTime = $studyModel->addTime($data);
		return $data;
	}
	
	public function updateGlobalTime($id_user, $studyTime){
		$studyModel = new StudyTimeModel();
		$currentTime = $studyModel->getTimeByUser($id_user);
		if($studyTime['hours']<10){
			$hours = "0".$studyTime['hours'];
		}else{
			$studyTime['hours'];
		}
		if($studyTime['min']<10){
			$min ="0".$studyTime['min'];
		}else{
			$min = $studyTime['min'];
		}
		if($studyTime['seg']<10){
			$seg ="0".$studyTime['seg'];
		}else{
			$seg = $studyTime['seg'];
		}
		
		$onlineTime = $hours.":".$min.":".$seg;
		$totalTime = $this->sumTime($onlineTime, $currentTime['time']);
		
		if($totalTime['hours']<10){
			$totalTime['hours']="0".$totalTime['hours'];
		}
		if($totalTime['minutes']<10){
			$totalTime['minutes']="0".$totalTime['minutes'];
		}
		if($totalTime['seconds']<10){
			$totalTime['seconds']="0".$totalTime['seconds'];
		}
		
		$updateTime = $totalTime['hours'].":".$totalTime['minutes'].":".$totalTime['seconds'];
		$data = array(
				"id_student" => $id_user,
				"time"       => $updateTime
		);
		$updateTime = $studyModel->updateTimeByUser($data);
		return $updateTime;
	}
	
	function sumTime($time1, $time2) {
		$times = array($time1, $time2);
		$seconds = 0;
		foreach ($times as $time)
		{
			list($hour,$minute,$second) = explode(':', $time);
			$seconds += $hour*3600;
			$seconds += $minute*60;
			$seconds += $second;
		}
		$hours = floor($seconds/3600);
		$seconds -= $hours*3600;
		$minutes  = floor($seconds/60);
		$seconds -= $minutes*60;
		$total = array(
				"hours" => $hours,
				"minutes" => $minutes,
				"seconds" => $seconds,
		);
		return ($total); 
	}
	
}