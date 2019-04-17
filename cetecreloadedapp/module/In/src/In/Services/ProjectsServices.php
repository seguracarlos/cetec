<?php
namespace In\Services;

use In\Model\ProjectsModel;
use In\Model\DatesPaymentsModel;
use In\Model\UsersProjectsModel;
use In\Model\ServicesProjectsModel;

class ProjectsServices
{
	private $projectsModel;
	private $datesPayments;
	private $usersProjects;
	private $servicesProjects;
	
	// Instanciamos el modelo de proyectos
	public function getProjectsModel()
	{
		return $this->projectsModel = new ProjectsModel();
	}
	
	// Instanciamos el modelo de las fechas de pagos
	public function getDatesPaymentsModel()
	{
		return $this->datesPayments = new DatesPaymentsModel();
	}
	
	// Instanciamos el modelo de los usuarios por proyectos
	public function getUsersProjectsModel()
	{
		return $this->usersProjects = new UsersProjectsModel();
	}
	
	// Instanciamos el modelo de los servicios por proyectos
	public function getServicesProjectsModel()
	{
		return $this->servicesProjects = new ServicesProjectsModel();
	}
	
	// Obtemos los proyectos
	public function fetchAll($type)
	{
		$projects = $this->getProjectsModel()->fetchAll($type);
		return $projects;
	}
	
	// Obtemos un proyecto por id
	public function getProjectById($id)
	{
		$project = $this->getProjectsModel()->getProjectById($id);
		$project[0]['start_date'] = $this->dateFormat("-", $project[0]['start_date']);
		$project[0]['end_date']   = $this->dateFormat("-", $project[0]['end_date']);
		
		return $project;
	}
	
	/*
	 * Agregamos un proyecto 
	 */
	public function addProject($data)
	{
		// Creamos el arreglo con la informacion del proyecto
		$project      = $this->createProjectInfo($data);
		// Guardamos el proyecto
		$saveProject  = $this->getProjectsModel()->addProject($project);
		
		// Creamos el arreglo con las fechas de pagos
		$datePayment  = $this->createDatesOfPayment($data, $saveProject);
		// Guardamos las fechas de los pagos
		$savePayments = $this->getDatesPaymentsModel()->addPaymentsProject($datePayment);

		// Creamos arreglo de los recursos humanos
		$userProjects = $this->createUsersProject($data, $saveProject);
		// Guardamos los usuarios por proyectos
		$saveUsersPro = $this->getUsersProjectsModel()->addRow($userProjects);
		
		// Creamos arreglo de los servicios asignados al proyecto
		$servProjects = $this->createServicesProject($data, $saveProject);
		// Guardamos los usuarios por proyectos
		$saveServProj = $this->getServicesProjectsModel()->addRow($servProjects);
		
		return $saveProject;
	}
	
	// Modificamos un proyecto
	public function editProject($data)
	{	//echo "<pre>"; print_r($data);exit;
		// Creamos el arreglo con la informacion del proyecto
		$project     = $this->createProjectInfo($data); 
		//echo "<pre>"; print_r($project);exit;
		// Modificamos el proyecto
		$editProject = $this->getProjectsModel()->editProject($project);
			
		return $editProject;
	}
	
	/*
	 * Eliminar proyecto
	 */
	public function deleteProject($id)
	{
		$idProject = (int) $id;
		$delete    = $this->getProjectsModel()->deleteProject($idProject);
		return $delete; 
	}
	
	/*
	 * Obtenemos proyectos por id de compania
	 */
	public function getAllProjectsByIdCompany($id)
	{
		$idC  = (int) $id;
		$rows = $this->getProjectsModel()->getAllProjectsByIdCompany($idC);
		return $rows;
	}
	
	// Genera array de proyecto
	public function createProjectInfo($data)
	{
		// Guardamos un proyecto (0)
		if($data['type_project'] == 1){
		}
		// Guardamos un proveedor (1)
		else if($data['type_project'] == 2){
		}
		
		// Arreglo con los datos del proyecto
		$project = array(
		    'type'             => $data['type_project'],
		    'ID'               => $data['ID'],
		    'company_ID'       => $data['company_ID'],
		    'project_name'     => trim($data['project_name']),
		    'status'           => $data['status'],
		    'start_date'       => $this->dateDBFormat("/" ,$data['start_date']),
		    'end_date'         => $this->dateDBFormat("/" ,$data['end_date']),
		    'description'      => trim($data['description']),
			'costtable'        => $data['costtable'],
		    'numberofpayments' => $data['numberofpayments'],
		    'cost'             => $this->notCurrencyOutPoint($data['cost']),
		    'amount'           => $this->notCurrencyOutPoint($data['amount']),
		    'advance'          => $this->notCurrencyOutPoint($data['advance']),
		    'discount'         => $this->notCurrencyOutPoint($data['descto']),
		    'subtotal'         => $this->notCurrencyOutPoint($data['subtotal']),
		    'tax'              => $this->notCurrencyOutPoint($data['tax']),
		    'total'            => $this->notCurrencyOutPoint($data['total']),
		);
		
		return $project;
	}
	
	/*
	 * Creamos arreglo con los datos de los pagos 
	 */
	private function createDatesOfPayment($data, $id_project)
	{
		// Se agregan las fechas de pago para el proyecto
		$datesOfPayment = explode("," ,trim($data['dates'],","));
		$amountTotal    = $this->notCurrencyOutPoint($data['total']) / $this->notCurrencyOutPoint($data['numberofpayments']);
		//echo "<pre>"; print_r($datesOfPayment); 
		//echo "<pre>"; print_r($this->notCurrencyOutPoint($data['total']));
		//echo "<pre>"; print_r($data); exit;
		foreach ($datesOfPayment as $datep){
			//echo "<pre>"; print_r($datep); exit;
			$payments[] = array(
					'datePayment' => $this->dateDBFormat("/" ,$datep),
					'projectId'   => $id_project,
					'amount'      => $amountTotal,
					'statusCxc'   => 0,
					'total'       => $amountTotal
			);
		}
		//echo "<pre>"; print_r($payments); exit;
		return $payments;
	}
	
	/*
	 * Creamos arreglo con los datos de los usuarios
	 */
	private function createUsersProject($data, $id_project)
	{
		$usersProject = explode("," ,trim($data['developersIds'],","));
		$start_date   = \Util_Tools::dateDBFormat("/", $data['start_date']);
		
		foreach ($usersProject as $users_ids){
			$up[] = array(
				'projects_ID'   => $id_project,
				'acl_users_id'  => $users_ids,
				'project_role'  => 4,
				'user_add_date' => $start_date,
			);
		}
		//echo "<pre>"; print_r($up); exit;
		return $up;
	}
	
	/*
	 * creamos arreglo con los datos de los usuarios
	 */
	private function createServicesProject($data, $id_project)
	{
		$serviProject = explode("," ,trim($data['servicesIds'],","));
		$start_date   = \Util_Tools::dateDBFormat("/", $data['start_date']);
		
		foreach ($serviProject as $serv_ids){
			$sp[] = array(
					'projects_ID'       => $id_project,
					'services_id'       => $serv_ids,
					'services_add_date' => $start_date,
			);
		}
		
		return $sp;
	}
	
	static function notCurrencyOutPoint($number){
		$sig[]=',';
		$sig[]='$';
		return str_replace($sig,'',$number);
	}
	
	static function dateDBFormat($separator, $dateSource) 
	{
		$explode = explode($separator, $dateSource);
		$anio    = $explode[2] . '-';
		$mes     = $explode[1] . '-';
		$dia     = $explode[0];
	
		$fullDBDate = $anio . $mes . $dia;
		return $fullDBDate;
	}
	
	static function dateFormat($separator, $dateSource)
	{
		$explode = explode($separator, $dateSource);
		$dia     = $explode[2] . '/';
		$mes     = $explode[1] . '/';
		$anio    = $explode[0];
	
		$fullDBDate = $dia . $mes . $anio;
		return $fullDBDate;
	}
}