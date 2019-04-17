<?php
namespace In\Services;

use In\Model\UsersProjectsModel;
use In\Model\DatesPaymentsModel;

class UsersProjectsServices
{
	private $usersprojectsModel;
	private $datesPayments;
	
	// Instanciamos el modelo de usuarios de proyectos
	public function getUsersProjectsModel()
	{
		return $this->usersprojectsModel = new UsersProjectsModel();
	}
	
	// Instanciamos el modelo de las fechas de pagos
	public function getDatesPaymentsModel()
	{
		return $this->datesPayments = new DatesPaymentsModel();
	}
	
	// Obtemos los proyectos
	public function fetchAll($type)
	{
		$projects = $this->getProjectsModel()->fetchAll($type);
		return $projects;
	}
	
	/*
	 * Obtenemos los usuarios asignados a un proyecto
	 */
	public function getAllUsersAssignedToProject($id_project)
	{
		$users_projects = $this->getUsersProjectsModel()->getAllUsersAssignedToProject($id_project);
		return $users_projects;
	}
	/*
	 * Obtenemos los usuarios no asignados a un proyecto
	 */
	public function getAllUsersNotAssignedToProject($id_project)
	{
		$users_projects = $this->getUsersProjectsModel()->getAllUsersNotAssignedToProject($id_project);
		return $users_projects;
	}
	
	// Obtemos un proyecto por id
	public function getProjectById($id)
	{
		$project = $this->getProjectsModel()->getProjectById($id);
		$project[0]['start_date'] = $this->dateFormat("-", $project[0]['start_date']);
		$project[0]['end_date']   = $this->dateFormat("-", $project[0]['end_date']);
		
		return $project;
	}
	
	// Agregamos una proyecto
	public function addProject($data)
	{
		//echo "<pre>"; print_r($data);
		// Creamos el arreglo con la informacion del proyecto
		$project     = $this->createProjectInfo($data);
		//echo "<pre>"; print_r($project);
		// Guardamos el proyecto
		$saveProject = $this->getProjectsModel()->addProject($project);
		
		// Creamos el arreglo con las fechas de pagos
		$datePayment  = $this->createDatesOfPayment($data, $saveProject); 
		//echo "<pre>"; print_r($datePayment); exit;
		// Guardamos las fechas de los pagos
		$savePayments = $this->getDatesPaymentsModel()->addPaymentsProject($datePayment); 

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
			'costtable' => $data['costtable'],
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
		foreach ($datesOfPayment as $date){
			$payments[] = array(
					'datePayment' => $this->dateDBFormat("/" ,$date),
					'projectId'   => $id_project,
					'amount'      => $amountTotal,
					'statusCxc'   => 0,
					'total'       => $amountTotal
			);
		}
		//echo "<pre>"; print_r($payments); exit;
		return $payments;
	}
	
	public static function notCurrencyOutPoint($number){
		$sig[]=',';
		$sig[]='$';
		return str_replace($sig,'',$number);
	}
	
	public static function dateDBFormat($separator, $dateSource) 
	{
		$explode = explode($separator, $dateSource);
		$anio    = $explode[2] . '-';
		$mes     = $explode[1] . '-';
		$dia     = $explode[0];
	
		$fullDBDate = $anio . $mes . $dia;
		return $fullDBDate;
	}
	
	public static function dateFormat($separator, $dateSource)
	{
		$explode = explode($separator, $dateSource);
		$dia     = $explode[2] . '/';
		$mes     = $explode[1] . '/';
		$anio    = $explode[0];
	
		$fullDBDate = $dia . $mes . $anio;
		return $fullDBDate;
	}
}