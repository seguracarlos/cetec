<?php
namespace Out\Controller;

use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Iofractal\Controller\BaseController;
use Out\Services\StudyTimeService;
use Auth\Utility\Mail2;
class StudytimeController extends BaseController
{

	public function __construct(){
		
	}

	public function indexAction(){

	}
	
	public function updatetimeAction(){
		
		$session = new Container('User');
		$studyService = new StudyTimeService();
		$mail = new Mail2();
		$id_user = $session->offsetGet('userId');
		$email = $session->offsetGet('email');
		
		$request  = $this->getRequest();
		$response = $this->getResponse();
		if($request->isPost()){
			$formData = $request->getPost();
			$formData['id_user'] = $id_user;
			$time = $studyService->getTimeByUser($id_user);
			if($time){
				$sumTime = $this->sum_the_time($formData['time'], $time['time']);
				if($sumTime['hours']<10){
					$sumTime['hours']="0".$sumTime['hours'];
				}
				if($sumTime['minutes']<10){
					$sumTime['minutes']="0".$sumTime['minutes'];
				}
				if($sumTime['seconds']<10){
					$sumTime['seconds']="0".$sumTime['seconds'];
				}
				$totalTime = $sumTime['hours'].":".$sumTime['minutes'].":".$sumTime['seconds'];
				
				$studyTime = $studyService->updateTime($id_user,$totalTime);
				if($sumTime['hours']>=80){
					$mail->sendEvaluationInfo2($email);
				}		
			}else{
				$studyTime = $studyService->addTime($formData);
			}

			if($studyTime){
				$response->setContent(\Zend\Json\Json::encode(array('response' => true, 'data' => $studyTime)));
			}else{
				$response->setContent(\Zend\Json\Json::encode(array('response' => false, "data" => "Error desconosido, consulta al administrador *.*")));
			}
			//echo "<pre>"; print_r($inf); exit;
		}
		return $response;
		exit;
		
	}
	
	//time 1 study time
	//time 2 total time
	function sum_the_time($time1, $time2) {
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
		// return "{$hours}:{$minutes}:{$seconds}";
		return ($total); // Thanks to Patrick
	}
	

}