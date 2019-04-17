<?php


namespace Horus;

use BaseController;
use Privilege;
use ActivitiesServiceImpl;
use Acl_UserServiceImpl;
use ProjectsServiceImpl;
use Application_Form_ActivitiesForm;
use Zend_Json;
use Application_Service_Impl_ActivitiesDatesServiceImpl;
use WeekDayId;
use Application_Entity_ActivitiesDatesEntity;
use Application_Util_Tools;
use Application_Entity_ActivitiesEntity;
use WeekNames;
use User_ProjectServiceImpl;


include_once APPLICATION_PATH . '/services/impl/User_ProjectServiceImpl.php';
include_once APPLICATION_PATH . '/services/impl/ProjectsServiceImpl.php';
include_once APPLICATION_PATH . '/controllers/BaseController.php';
include_once APPLICATION_PATH . '/services/impl/Acl_UserServiceImpl.php';
include_once APPLICATION_PATH . '/services/impl/ActivitiesServiceImpl.php';
include_once APPLICATION_PATH . '/entities/UserProject.php';
include_once APPLICATION_PATH . '/Constants/Constants.php';

class ActivitiesController extends  BaseController {

	function init() {
		BaseController::init();
	}

	public function indexAction() {

		$id_role = $this->getCurrentUserIdRole();
		
		if ($id_role == Privilege::CONSULTOR ){

			$service = new ActivitiesServiceImpl ();
			$this->view->pro_id = $service->getProjectsByIdUser($this -> getCurrentUserId());
			$this->view->id_role =$id_role;

		}elseif ($id_role != Privilege::CONSULTOR){
			$service = new ActivitiesServiceImpl ();
			$serviceUsers = new Acl_UserServiceImpl();
			$serviceProject = new ProjectsServiceImpl();
			
			$users = $serviceUsers->getUserListActivities();
			
			$this->view->listUsers = $users;
			$this->view->listProyects = $serviceProject->showAllProjects();
			$this->view->id_role =$id_role;
		}

	}
	public function addAction(){
		$id_role = $this->getCurrentUserIdRole();
		$this->view->roleId = $id_role;
		
		$id_project = $this->_getParam('id');
		$this->view->projectId = $id_project;
		
		$form = null;
		
		$from = $this->_getParam("from");
		$to = $this->_getParam("to");
		
		$period = $this->weekperiodview($from, $to);
		
		$service = new ActivitiesServiceImpl ();
		$serviceProject = new ProjectsServiceImpl();
		
		if ($id_role == Privilege::CONSULTOR ){
				
			$currentId = $this -> getCurrentUserId();
			$this->view->idUser = $currentId;
			$form = new Application_Form_ActivitiesForm($currentId, $id_project);
			$this->view->activitiesDto = $service->getActivityByIdUser($currentId, $id_project, $period['from'], $period['to']);
			$pro = $serviceProject->getNameProjectById($id_project);
			$this->view->nameProject = $pro['project_name'];
		}else{
				
			$id_userParam = $this->_getParam('iduser');
			$this->view->idUser = $id_userParam;
			$form = new Application_Form_ActivitiesForm($id_userParam, $id_project);
// 			$this->view->activitiesDto = $service->getActivityByIdUser($id_userParam, $id_project, $period['from'], $period['to']);
// 			$pro = $serviceProject->getNameProjectById($id_project);
// 			$this->view->nameProject = $pro['project_name'];
		}
		
		$this->view->form = $form;		
	}
	
	public function activitiesviewAction() {

		$id_role = $this->getCurrentUserIdRole();
		$this->view->roleId = $id_role;
		
		$id_project = $this->_getParam('id');
		$this->view->projectId = $id_project;
		
		$form = null;
		
		$from = $this->_getParam("from");
		$to = $this->_getParam("to");
		
		$period = $this->weekperiodview($from, $to);
		
	
		$service = new ActivitiesServiceImpl ();
		$serviceProject = new ProjectsServiceImpl();
		
		if ($id_role == Privilege::CONSULTOR ){
				
			$currentId = $this -> getCurrentUserId();
			$this->view->idUser = $currentId;
			$form = new Application_Form_ActivitiesForm($currentId, $id_project);
			$this->view->activitiesDto = $service->getActivityByIdUser($currentId, $id_project, $period['from'], $period['to']);
			$pro = $serviceProject->getNameProjectById($id_project);
			$this->view->nameProject = $pro['project_name'];
		}else{
			
			$id_userParam = $this->_getParam('iduser');
			$this->view->idUser = $id_userParam;
			$form = new Application_Form_ActivitiesForm($id_userParam, $id_project);
			$this->view->activitiesDto = $service->getActivityByIdUser($id_userParam, $id_project, $period['from'], $period['to']);
			$pro = $serviceProject->getNameProjectById($id_project);
			$this->view->nameProject = $pro['project_name'];
		}
		
		$this->view->form = $form;

	}
	
	public function historyAction(){
		$id_role = $this->getCurrentUserIdRole();
		$this->view->roleId = $id_role;
	
		$id_project = $this->_getParam('id');
		$this->view->projectId = $id_project;
	
		$form = null;
	
		$from = $this->_getParam("from");
		$to = $this->_getParam("to");
	
		$period = $this->weekperiodview($from, $to);
	
		$service = new ActivitiesServiceImpl ();
	
		if ($id_role == Privilege::SUPERADMINISTRADOR || $id_role == Privilege::ADMINISTRADOR || $id_role == Privilege::SUPERUSUARIO || $id_role == Privilege::VISUALIZADOR){
			$id_userParam = $this->_getParam('iduser');
			$this->view->idUser = $id_userParam;
			//echo $id_userParam;
			$form = new Application_Form_ActivitiesForm($id_userParam, $id_project);
			$this->view->activitiesDto = $service->getActivityByHistory($id_userParam, $id_project, $period['from'], $period['to']);
		}
	
		$this->view->form = $form;
	
	}
	
	public function addweekAction() {
		
		$activities = $this->_getParam("id_activities");
		$descriptions = $this->_getParam("description");
		$durations = $this->_getParam("duration");
		$log_dates = $this->_getParam("log_date");
		
		$user_id = $this -> getCurrentUserId();
		$userRole = $this -> getCurrentUserIdRole();
		
		$service = new ActivitiesServiceImpl ();
		$projects =$service->getProjectsByIdUser($user_id);
		$form = new Application_Form_ActivitiesForm($user_id,$projects);
		
		if ($this->getRequest()->isPost()) {

				$formData = $this->getRequest()->getPost();
				
				if ($formData != null) {
					
					$activityService = new ActivitiesServiceImpl();
					
					$activity = array(
					'projects_ID' => ($this->_getParam('projects_ID')),
					'user_id' =>  ($this->_getParam('user_id')),
					'descriptions' =>  $descriptions,
					'log_dates' =>  $log_dates,
					'durations' =>  $durations
					);
					
					
					
					$activityId = $activityService->createActivity($activity);
					//  @@user el service y guardar
					$response = array(
							"status"=>"ok",
							"activityId"=>$activityId,
							"rol" => $userRole
							);

				}else{
					
					$response = array("status"=>"fail");

				}
				
			echo Zend_Json::encode($response);
			exit;
		}
	}

	public function deleteAction() {
    	 $deleteParam = $this->_getParam("erase");
    	 $service = new ActivitiesServiceImpl();
         $service->deleteActivity($deleteParam);
         exit;
    }
	
	public function updateAction(){
		
		$id = $this->_getParam('id');
		$value = $this->_getParam('value');
		$columnName = $this->_getParam('columnName') ;
		$columnPosition = $this->_getParam('columnPosition') ;
		$columnId = $this->_getParam('columnId') ;
		$rowId = $this->_getParam('rowId') ;

		
		if ($value > 24) {
			echo "Su tiempo de trabajo no puede superar las 24 horas";
			exit;
		}

		$day = $this->getDayIdFromDayName($columnName);//numero del dia segun el caso
		$affectedRows = 0;
		
		if($day > 0 || $day == "0"){
			$activityDatesService = new Application_Service_Impl_ActivitiesDatesServiceImpl();
			$result = $activityDatesService->getActivityDateByIdAndDay($id, $day);
		}else{
			
			if($day == WeekDayId::NAME){
				$activityService = new ActivitiesServiceImpl();
				$result = $activityService->getActivitiesById($id);	
			}
		}
		
		if (empty($result) && $columnPosition > 0) {
			
			$activityService = new ActivitiesServiceImpl();
			$activityDates = new Application_Entity_ActivitiesDatesEntity();
			
			foreach ($activityService->getLogMonday($id) as $monday) {
				$activityDates->setLogDate(Application_Util_Tools::addTimeToDate( $monday->getLogMondayActivity(), ($columnPosition-1) ));
			}
			
			$activityDates->setFkIdActivities($id);
			$activityDates->setFKIdDay($day);
			$activityDates->setHours($value);
			$newId = $activityService->addDateActivity($activityDates);
			
			if($newId > 0){
				echo $value;
			}else{
				echo "Ha ocurrido alg&uacute;n problema en la acualizaci&oacute;n";
			}
			exit;
			
		}else{
			
			if ($columnPosition==0){
				$activityService = new ActivitiesServiceImpl();
				$activity = new Application_Entity_ActivitiesEntity();
				$activity ->setId_activities($id);
				$activity ->setDescription($value);
				$affectedRows = $activityService->updateActivity($activity);
				
			}else{
					$activityService = new ActivitiesServiceImpl();
					$activity = new Application_Entity_ActivitiesDatesEntity();
					$activity ->setFkIdActivities($id);
					$activity->setFKIdDay($day);
					$activity->setHours($value);
					$affectedRows = $activityService->updateActivityWeek($activity);
					
			}
				
			if($affectedRows >= 0){
				echo $value;
			}else{
				echo "Ha ocurrido alg&uacute;n problema, consulte al administrador";			
			}
			exit;
	   }
	}	
	
	public function getDayIdFromDayName($columnName) {
		$day = null;
		switch ($columnName) {
			case WeekNames::LUNES:
				$day = WeekDayId::LUNES;
				break;
			case WeekNames::MARTES:
				$day = WeekDayId::MARTES;
				break;
			case WeekNames::MIERCOLES:
				$day = WeekDayId::MIERCOLES;
				break;
			case WeekNames::JUEVES:
				$day = WeekDayId::JUEVES;
				break;
			case WeekNames::VIERNES:
				$day = WeekDayId::VIERNES;
				break;
			case WeekNames::SABADO:
				$day = WeekDayId::SABADO;
				break;
			case WeekNames::DOMINGO:
				$day = WeekDayId::DOMINGO;
				break;
			case WeekNames::ACTIVIDAD:
				$day = WeekDayId::NAME;
				break;
		}
		return $day;
	}

	private function weekperiodview($from, $to){
		
		if (isset($from) && isset($to)) {
			$weekBegin = $from;
			$weekEnd = $to;
			$this->view->week = Application_Util_Tools::getActualMondayByDate($from);
			$this->view->weekEnd = Application_Util_Tools::getActualSundayByDate($to);
			$this->view->mondayActualWeek = $from;
			$this->view->sundayActualWeek = $to;
			$this->view->month = Application_Util_Tools::getMonthNameByDates($from, $to);
		}else{
			$weekBegin = Application_Util_Tools::getActualMondayDate();
			$weekEnd = Application_Util_Tools::getActualSundayDate();
			$this->view->week = Application_Util_Tools::getActualMonday();
			$this->view->weekEnd = Application_Util_Tools::getActualSunday();
			$this->view->mondayActualWeek = $weekBegin;
			$this->view->sundayActualWeek = $weekEnd;
			$this->view->month = Application_Util_Tools::getMonthNameByDates($weekBegin, $weekEnd);;
		}
		return array("from" => $weekBegin, "to" => $weekEnd);
	}
	
	public function surfprevweekAction(){
		$mondayDate = $this->_getParam("mondayDate");
		$urlIdProject = $this->_getParam('project');
		$urlIdUser = $this->_getParam('user');
		
		$currentRol = $this -> getCurrentUserIdRole();
		
		$from = Application_Util_Tools::getPreviousMondayDate($mondayDate);
		$to = Application_Util_Tools::getPreviousSundayDate($mondayDate);
		
		$service = new ActivitiesServiceImpl();
		$activities = $service->getActivityByIdUserWithDayHourkeyOrder($urlIdUser, $urlIdProject, $from, $to);
		
		print_r(json_encode(array(
								"from" => $from,
								"to" => $to,
								"activities" =>	$activities,
								"rol" => $currentRol,
								"month" => Application_Util_Tools::getMonthNameByDates($from, $to)
						))
				);
		exit;	
	}
	
	public function surfnextweekAction(){
		$mondayDate = $this->_getParam("mondayDate");
		$urlIdProject = $this->_getParam('project');
		$urlIdUser = $this->_getParam('user');
		
		$currentRol = $this -> getCurrentUserIdRole();
		
		$from = Application_Util_Tools::getNextMondayDate($mondayDate);
		$to = Application_Util_Tools::getNextSundayDate($mondayDate);
		
		$service = new ActivitiesServiceImpl();
		$activities = $service->getActivityByIdUserWithDayHourkeyOrder($urlIdUser, $urlIdProject, $from, $to);
		
		print_r(json_encode(array(
								"from" => $from,
								"to" => $to,
								"activities" =>	$activities,
								"rol" => $currentRol,
								"month" => Application_Util_Tools::getMonthNameByDates($from, $to)							
						))
				);
		exit;
	}
	
	public function getuserswithactivitiesAction(){
		$service = new ActivitiesServiceImpl ();
		$serviceUser = new User_ProjectServiceImpl();
		
		$iduserParam = $this->_getParam("user");
		$idProjectSelect = $this->_getParam("projSele");
		
		if(isset($iduserParam)){
			$projects = $service->getProjectsWithActivitiesByUserId($iduserParam);
			print_r($projects);
		}else if(isset($idProjectSelect)){
			$users	= $serviceUser->getTeam($idProjectSelect);
			print_r($users);
		}
		
		exit;
	}
	
	public function filltablebyuseridAction(){
		$service = new ActivitiesServiceImpl ();
		
		$type = $this->_getParam('type');
		$iduserParam = $this->_getParam("user");
		$idProjectParam = $this->_getParam("project");
	
		if(isset($type) && $type == "activProj"){
			if($idProjectParam == "t"){
				$projects = $service->getActivitiesProjectsByUserId($iduserParam, $idProjectParam, true);
			}else{
				$projects = $service->getActivitiesProjectsByUserId($iduserParam, $idProjectParam, false);
			}
		}else if(isset($type) && $type == "activeUser"){
			if($iduserParam == "t"){
				$projects = $service->getActivitiesByUserIdProject($iduserParam, $idProjectParam, true);
			}else{
				$projects = $service->getActivitiesByUserIdProject($iduserParam, $idProjectParam, false);
			}
		}
		
		print_r($projects);
		exit;
	}
	
	
	public function graphsAction(){
		$service = new ActivitiesServiceImpl ();
		
		$graphHourProj = $this->_getParam('ghproj');
		$graphHourProjUser = $this->_getParam('ghprojusr');
		$usermonth = $this->_getParam('usermonth');
		
		if(isset($graphHourProj) && $graphHourProj == "true"){
			$hours = $service->graphHoursByProjects();
			
			if($hours != null ){ 
				$tableGraph = "";
				$tableGraphSimple = "";
		
			$tableGraph .= "<table id='ProjectsGraphTable' border='0'>";
				$tableGraph .= "<caption>Horas por Proyectos</caption>";
					$tableGraph .= "<thead>";
						$tableGraph .= "<tr>";
							$tableGraph .= "<th></th>";
							$tableGraph .= "<th></th>";
						$tableGraph .= "</tr>";
					$tableGraph .= "</thead>";
					
						$tableGraph .= "<tbody>";
						
						foreach($hours as $projectHour){
							$tableGraph .= "<tr>";
								$tableGraph .= "<th>";
									$tableGraph .= "<span>". $projectHour['project_name'] ."</span>";
								$tableGraph .= "</th>";
								$tableGraph .= "<td>";
									$tableGraph .= "<span>". $projectHour['hours'] ."</span>";
								$tableGraph .= "</td>";
							$tableGraph .= "</tr>";
						}
					
						
					$tableGraph .= "</tbody>";
				$tableGraph .= "</table>";
				
				$tableGraphSimple .= "<table id='ProjectsTables' border='0' style='width: 30% float: left;' >";
					$tableGraphSimple .= "<caption>Horas en proyectos por consultor</caption>";
						$tableGraphSimple .= "<thead>";
							$tableGraphSimple .= "<tr>";
								$tableGraphSimple .= "<th></th>";
								$tableGraphSimple .= "<th></th>";
							$tableGraphSimple .= "</tr>";
						$tableGraphSimple .= "</thead>";
						
							$tableGraphSimple .= "<tbody>";
							
							foreach($hours as $projectHour){
								$tableGraphSimple .= "<tr>";
									$tableGraphSimple .= "<th style='width: 90%'>";
										$tableGraphSimple .= "<span>". $projectHour['project_name'] ."</span>";
									$tableGraphSimple .= "</th>";
									$tableGraphSimple .= "<td style='width: 10%'>";
										$tableGraphSimple .= "<span>". $projectHour['hours'] ."</span>";
									$tableGraphSimple .= "</td>";
								$tableGraphSimple .= "</tr>";
							}
						
							
						$tableGraphSimple .= "</tbody>";
					$tableGraphSimple .= "</table>";	
				
				echo Zend_Json::encode(array('response' => 'ok', 'graph' => $tableGraph, 'table' => $tableGraphSimple));
	   			exit;
			}else{
				echo Zend_Json::encode(array('response' => 'fail', 'graph' => array()));
	   			exit;
			}
		}elseif(isset($graphHourProjUser) && $graphHourProjUser == "true"){
			
			$months = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
			$year = $this->_getParam('year');
			$id_proyect = $this->_getParam('iPro');
			
			$userHours = array();
			
			$user = new Acl_UserServiceImpl();	
			$usuariosIds = $user->getUsersIdAndName($id_proyect);
			
			foreach($usuariosIds as $idUser){
				
				$hoursAllUserInProj = array();
				$inter = array();
				
				foreach($months as $month){
					$hpu = $service->hoursProjectInMonth($month, $year, $id_proyect, $idUser['acl_users_id']);
					$tHour = "";
					
					if($hpu == null){
						$tHour = 0;
					}else{
						$tHour = (int)$hpu[0]['hours'];
					}
					
					$hoursAllUserInProj['user_name'] = $idUser['user_name'];
					$inter[] = $tHour;
					$hoursAllUserInProj['hours'] = $inter;
					
				}

				$userHours[] = $hoursAllUserInProj;
			}
			
			
			echo Zend_Json::encode($userHours);
			exit;
			
		}else if(isset($usermonth) && $usermonth == "true"){
			$userid = $this->_getParam('userid');
			$month = $this->_getParam('month');
			$serviceUsers = new Acl_UserServiceImpl();
			
			$proyectsHours = $serviceUsers->getHoursProyectsByIdUser($userid, $month);

			if($proyectsHours != null){
				$tableGraph = "";
				$tableGraphSimple  = "";
		
				$tableGraph .= "<table id='ProjectsGraphTable' border='0'>";
				$tableGraph .= "<caption>Horas por Proyectos</caption>";
					$tableGraph .= "<thead>";
						$tableGraph .= "<tr>";
							$tableGraph .= "<th></th>";
							$tableGraph .= "<th></th>";
						$tableGraph .= "</tr>";
					$tableGraph .= "</thead>";
					
						$tableGraph .= "<tbody>";
						
						foreach($proyectsHours as $projectHour){
							$tableGraph .= "<tr>";
								$tableGraph .= "<th>";
									$tableGraph .= "<span>". $projectHour['project_name'] ."</span>";
								$tableGraph .= "</th>";
								$tableGraph .= "<td>";
									$tableGraph .= "<span>". $projectHour['hours'] ."</span>";
								$tableGraph .= "</td>";
							$tableGraph .= "</tr>";
						}
					
						
					$tableGraph .= "</tbody>";
				$tableGraph .= "</table>";
				
				$tableGraphSimple .= "<table id='ProjectsTables' border='0' style='width: 30% float: left;' >";
					$tableGraphSimple .= "<caption>Horas en proyectos por consultor</caption>";
						$tableGraphSimple .= "<thead>";
							$tableGraphSimple .= "<tr>";
								$tableGraphSimple .= "<th></th>";
								$tableGraphSimple .= "<th></th>";
							$tableGraphSimple .= "</tr>";
						$tableGraphSimple .= "</thead>";
						
							$tableGraphSimple .= "<tbody>";
							
							foreach($proyectsHours as $projectHour){
								$tableGraphSimple .= "<tr>";
									$tableGraphSimple .= "<th style='width: 90%'>";
										$tableGraphSimple .= "<span>". $projectHour['project_name'] ."</span>";
									$tableGraphSimple .= "</th>";
									$tableGraphSimple .= "<td style='width: 10%'>";
										$tableGraphSimple .= "<span>". $projectHour['hours'] ."</span>";
									$tableGraphSimple .= "</td>";
								$tableGraphSimple .= "</tr>";
							}
						
							
						$tableGraphSimple .= "</tbody>";
					$tableGraphSimple .= "</table>";	
					
					echo Zend_Json::encode(array('response' => 'ok', 'graph' => $tableGraph, 'table' => $tableGraphSimple));
	   				exit;
			}else{
					echo Zend_Json::encode(array('response' => 'fail', 'graph' => array()));
		   			exit;
			}
			
		}else{
			$serviceUsers = new Acl_UserServiceImpl();
			$listUSer = $serviceUsers->getUserListActivities();
			
			if($listUSer == null){
				$this->view->listUsers = 0;
			}else{
				$this->view->listUsers = $listUSer;
			}
			
			$hours = $service->graphHoursByProjects();
			
			if($hours != null){
				$this->view->hours = $hours;
			}else{
				$this->view->hours = 0;
			}
		}
	}
	
}