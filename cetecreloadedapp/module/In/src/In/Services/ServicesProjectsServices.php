<?php
namespace In\Services;

use In\Model\ServicesProjectsModel;

class ServicesProjectsServices
{
	private $servicesProjectsModel;
	
	/*
	 * Instanciamos el modelo de servicios de proyectos
	 */
	public function getServicesProjectsModel()
	{
		return $this->servicesProjectsModel = new ServicesProjectsModel();
	}
	
	
	/*
	 * Obtenemos todos los registros 
	 */
	public function fetchAll()
	{
		$rows = $this->getServicesProjectsModel()->fetchAll();
		return $rows;
	}
	
	/*
	 * Obtenemos los servicios asignados a un proyecto
	 */
	public function getAllServicesAssignedToProject($id_project)
	{
		$rows = $this->getServicesProjectsModel()->getAllServicesAssignedToProject($id_project);
		return $rows;
	}
	
	/*
	 * Obtenemos los servicios no asignados a un proyecto
	 */
	public function getAllServicesNotAssignedToProject($id_project)
	{
		$rows = $this->getServicesProjectsModel()->getAllServicesNotAssignedToProject($id_project);
		return $rows;
	}
	
	// Obtemos un registro por id
	public function getRowById($id)
	{
		$project = $this->getProjectsModel()->getProjectById($id);
		$project[0]['start_date'] = $this->dateFormat("-", $project[0]['start_date']);
		$project[0]['end_date']   = $this->dateFormat("-", $project[0]['end_date']);
		
		return $project;
	}
	
	// Agregamos
	public function addRow($data)
	{
		// Creamos el arreglo con la informacion
		$row     = $this->createProjectInfo($data);
		
		// Guardamos
		$saveRow = $this->getProjectsModel()->addProject($project);
	}
	
	// Modificamos
	public function editRow($data)
	{	
		// Creamos el arreglo con la informacion
		$row     = $this->createProjectInfo($data); 
	
		// Modificamos
		$editRow = $this->getProjectsModel()->editProject($project);
			
		return $editProject;
	}
	
	/*
	 * Eliminar
	 */
	public function deleteRow($id)
	{
		$idRow = (int) $id;
		$delete    = $this->getProjectsModel()->deleteProject($idRow);
		return $delete; 
	}
	
	// Genera array
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
}