<?php
namespace In\Form;

use Zend\Form\Form;
use In\Services\CompaniesServices;
use System\Services\UsersService;
use In\Services\ServicesServices;
use In\Services\UsersProjectsServices;
use In\Services\ServicesProjectsServices;
use Horus\Services\InventoryService;

class ProjectsForm extends Form
{
	
	public function __construct($name = null, $type, $id_project = null)
	{
		
		parent::__construct($name);
		
		$this->setAttributes(array(
				'action'=>"",
				'method' => 'post'
		));
		
		/*********** VALIDACIONES ***********/
		
		/* 
		 * 1 - Agregar, 2 - Editar
		 */
		if($type == 1){
			$select_users_disp    = $this->getAllUsers();
			$select_users_asig    = array();
			$select_services_disp = $this->getAllServices();
			$select_services_asig = array();
		}elseif ($type == 2){
			$select_users_asig    = $this->getAllUsersAssignedToProject($id_project);
			$select_users_disp    = $this->getAllUsersNotAssignedToProject($id_project);
			$select_services_disp = $this->getAllServicesNotAssignedToProject($id_project);
			$select_services_asig = $this->getAllServicesAssignedToProject($id_project);
		}
		
		/*********** DATOS DE GENERALES ***********/
		
		/** TIPO PROYECTO **/
		$this->add(array(
				'name' => 'type_project',
				'type' => 'Hidden',
				'attributes' => array(
						'class'    => 'form-control input-lg',
				)
		));
		
		/** ID PROYECTO **/
		$this->add(array(
				'name' => 'ID',
				'type' => 'Hidden',
		));
		
		/** ID USUARIOS **/
		$this->add(array(
				'name' => 'developersIds',
				'type' => 'Hidden',
				'attributes' => array(
						'id'    => 'developersIds',
						'class' => 'form-control input-lg',
				)
		));
		
		/** ID SERVICIOS **/
		$this->add(array(
				'name' => 'servicesIds',
				'type' => 'Hidden',
				'attributes' => array(
						'id'    => 'servicesIds',
						'class' => 'form-control input-lg',
				)
		));
		
		/** FECHAS DE PAGO **/
		$this->add(array(
				'name' => 'dates',
				'type' => 'Hidden',
				'attributes' => array(
						'id'    => 'dates',
						'class' => 'form-control input-lg',
				)
		));
		
		/** TABLA CON DATOS DEL PROYECTO **/
		$this->add(array(
				'name' => 'costtable',
				'type' => 'Hidden',
				'attributes' => array(
						'id'    => 'costtable',
						'class' => 'form-control input-lg',
				)
		));
		
		/** NOMBRE PROYECTO **/
		$this->add(array(
				'name' => 'project_name',
				'type' => 'Text',
				'options' => array(
						'label' => 'Nombre del proyecto:',
				),
				'attributes' => array(
						'class'       => 'form-control input-lg input-required',
						'placeholder' => 'Viaje',
						'required'    => 'required',
				)
		));
		
		/** CLIENTE **/
		$this->add(array(
				'name' => 'company_ID',
				'type' => 'Select',
				'options' => array (
						'label' => 'Cliente:',
						'empty_option'  => '--selecciona--',
						'value_options' => $this->getAllClients(),
				),
				'attributes' => array(
						'id'    => 'company_ID',
						'class' => 'form-control input-lg input-required',
				)
		));
		
		/** ESTATUS **/
		$this->add(array(
				'name' => 'status',
				'type' => 'radio',
				'options' => array(
						'value_options' => array(
								'1' => 'Activo',
								'0' => 'Inactivo',
						),
				),
		));
		
		/** FECHA INICIO **/
		$this->add(array(
				'name' => 'start_date',
				'type' => 'Text',
				'options' => array(
						'label' => 'Fecha inicio:',
				),
				'attributes' => array(
						'id'          => 'start_date',
						'class'       => 'form-control input-lg input-required',
						'placeholder' => 'Inicio',
				)
		));
		/*$this->add(array(
				'type' => '\Zend\Form\Element\Date',
				'name' => 'start_date',
				'options' => array(
						'label' => 'Appointment Date',
						'format' => 'Y-m-d'
				),
				'attributes' => array(
						'min' => '2012-01-01',
						'max' => '2020-01-01',
						'step' => '1', // days; default step interval is 1 day
				)
		));*/
		
		/** FECHA FIN **/
		$this->add(array(
				'name' => 'end_date',
				'type' => 'Text',
				'options' => array(
						'label' => 'Fecha fin:',
				),
				'attributes' => array(
						'id'          => 'end_date',
						'class'       => 'form-control input-lg input-required',
						'placeholder' => 'Fin',
				)
		));
		
		/** DESCRIPCION **/
		$this->add(array(
				'name'      => 'description',
				'attributes'=> array(
						'type'  => 'textarea',
						'rows'  => 4,
						'class' => 'form-control input-lg',
				)
		));
		
		/** NUMERO DE PAGOS **/
		$this->add(array(
				'name' => 'numberofpayments',
				'type' => 'Text',
				'options' => array(
						'label' => 'Numero de pagos:',
				),
				'attributes' => array(
						'id'          => 'numberofpayments',
						'class'       => 'form-control input-lg input-required',
				)
		));
		
		/** REPETIR COBRANZA **/
		$this->add(array(
				'name' => 'repeat',
				'type' => 'Select',
				'options' => array (
						'label' => 'Repetir:',
						'empty_option'  => '--selecciona--',
						'value_options' => array(
							array('value' => 'no', 'label' => 'No repetir'),
							array('value' => 'si', 'label' => 'Mensualmente'),
						),
				),
				'attributes' => array(
						'id'    => 'company_ID',
						'class' => 'form-control input-lg input-required',
				)
		));
		
		/** COSTO **/
		$this->add(array(
				'name' => 'cost',
				'type' => 'Text',
				'options' => array(
						'label' => 'Costo:',
				),
				'attributes' => array(
						'id'          => 'cost',
						'class'       => 'form-control input-lg input-required',
						'value'       => 0
				)
		));
		
		/** IMPORTE **/
		$this->add(array(
				'name' => 'amount',
				'type' => 'Text',
				'options' => array(
						'label' => 'Importe:',
				),
				'attributes' => array(
						'id'          => 'amount',
						'class'       => 'form-control input-lg input-required',
						'value'       => 0
				)
		));
		
		/** ANTICIPO **/
		$this->add(array(
				'name' => 'advance',
				'type' => 'Text',
				'options' => array(
						'label' => 'Anticipo:',
				),
				'attributes' => array(
						'id'          => 'advance',
						'class'       => 'form-control input-lg input-required',
						'value'       => 0
				)
		));
		
		/** DESCUENTO **/
		$this->add(array(
				'name' => 'descto',
				'type' => 'Text',
				'options' => array(
						'label' => 'Descuento:',
				),
				'attributes' => array(
						'id'          => 'descto',
						'class'       => 'form-control input-lg input-required',
						'value'       => 0
				)
		));
		
		/** SUBTOTAL **/
		$this->add(array(
				'name' => 'subtotal',
				'type' => 'Text',
				'options' => array(
						'label' => 'Subtotal:',
				),
				'attributes' => array(
						'id'          => 'subtotal',
						'class'       => 'form-control input-lg input-required',
						'value'       => 0
				)
		));
		
		/** IVA **/
		$this->add(array(
				'name' => 'tax',
				'type' => 'Text',
				'options' => array(
						'label' => 'IVA:',
				),
				'attributes' => array(
						'id'          => 'tax',
						'class'       => 'form-control input-lg input-required',
						'value'       => 0
				)
		));
		
		/** TOTAL **/
		$this->add(array(
				'name' => 'total',
				'type' => 'Text',
				'options' => array(
						'label' => 'Total:',
				),
				'attributes' => array(
						'id'          => 'total',
						'class'       => 'form-control input-lg input-required',
						'value'       => 0
				)
		));
		
		/** CANTIDAD **/
		$this->add(array(
				'name' => 'quantity',
				'type' => 'Text',
				'options' => array(
						'label' => 'Cantidad:',
				),
				'attributes' => array(
						'id'          => 'quantity',
						'class'       => 'form-control input-lg input-required',
				)
		));
		
		/** CONCEPTO **/
		$this->add(array(
				'name' => 'concept',
				'type' => 'Text',
				'options' => array(
						'label' => 'Concepto:',
				),
				'attributes' => array(
						'id'          => 'concept',
						'class'       => 'form-control input-lg input-required',
				)
		));
		
		/** PRECIO **/
		$this->add(array(
				'name' => 'unityPrice',
				'type' => 'Text',
				'options' => array(
						'label' => 'Precio:',
				),
				'attributes' => array(
						'id'          => 'unityPrice',
						'class'       => 'form-control input-lg input-required',
				)
		));
		
		/** IMPORTE **/
		$this->add(array(
				'name' => 'price',
				'type' => 'Text',
				'options' => array(
						'label' => 'Precio:',
				),
				'attributes' => array(
						'id'          => 'price',
						'class'       => 'form-control input-lg input-required',
				)
		));
		
		/** USUARIOS DISPONIBLES **/
		$this->add(array(
				'name' => 'users_disp',
				'type' => 'Select',
				'options' => array (
						'label'         => 'Recursos disponibles:',
						'value_options' => $select_users_disp,
				),
				'attributes' => array(
						'id'    => 'users_disp',
						'class' => 'form-control',
						'size'  => '5'
				)
		));
		
		/** USUARIOS ASIGNADOS **/
		$this->add(array(
				'name' => 'users_asig',
				'type' => 'Select',
				'options' => array (
						'label'         => 'Recursos disponibles:',
						'value_options' => $select_users_asig,
				),
				'attributes' => array(
						'id'    => 'users_asig',
						'class' => 'form-control',
						'size'  => '5'
				)
		));
		
		/** SERVICIOS DISPONIBLES **/
		$this->add(array(
				'name' => 'services_disp',
				'type' => 'Select',
				'options' => array (
						'label' => 'Servicios disponibles:',
						'value_options' => $select_services_disp,
				),
				'attributes' => array(
						'id'    => 'services_disp',
						'class' => 'form-control',
						'size'  => '5'
				)
		));
		
		/** SERVICIOS ASIGNADOS **/
		$this->add(array(
				'name' => 'services_asig',
				'type' => 'Select',
				'options' => array (
						'label' => 'Servicios Asignados:',
						'value_options' => $select_services_asig,
				),
				'attributes' => array(
						'id'    => 'services_asig',
						'class' => 'form-control',
						'size'  => '5'
				)
		));
		
		/*
		 * Campos relacionados con los viajes 
		 */
		
		/* CAMION */
		$this->add(array(
				'name' => 'truck',
				'type' => 'Select',
				'options' => array (
						'label' => 'Unidad:',
						'empty_option'  => '--selecciona--',
						'value_options' => $this->getAllTruck(),
				),
				'attributes' => array(
						'id'    => 'truck',
						'class' => 'form-control input-lg input-required',
				)
		));
		
		/*********** BOTON GUARDAR ***********/
		
		/** BOTON SUBMIT **/
		$this->add(array(
				'name' => 'submitbutton',
				'type' => 'Submit',
				'attributes' => array(
						'value' => 'Guardar',
						'id'    => 'submitbutton',
						'class' => 'btn btn-info btn-lg btn-block',
				),
		));
		
	}
	
	// Funcion que trae todos los clientes
	public function getAllClients()
	{
		$clientsServices = new CompaniesServices();
		$clients         = $clientsServices->fetchAll(1);
		$result          = array();
	
		foreach ($clients as $c){
			//$result[] = ['attributes'=> ['data-est'=>$s_m['id']], 'value' => $s_m['id'], 'label' => $s_m['state'] ];
			$result[$c['id_company']] = $c['name_company'];
		}
	
		return $result;
	}
	
	// Funcion que trae todos los usuarios
	public function getAllUsers()
	{
		$usersServices = new UsersService();
		$users         = $usersServices->fetchAll(1);
		$result          = array();
	
		foreach ($users as $u){
			$result[] = ['attributes'=> ['data-cost'=>$u['cost']], 'value' => $u['user_id'], 'label' => $u['name'] ];
			//$result[$u['user_id']] = $u['name'];
		}
	
		return $result;
	}
	
	// Funcion que trae todos los usuarios de un proyecto
	public function getAllUsersAssignedToProject($id_project)
	{
		$usersProjectsServices = new UsersProjectsServices();
		$users         = $usersProjectsServices->getAllUsersAssignedToProject($id_project);
		$result          = array();
		
		foreach ($users as $u){
			$result[] = ['attributes'=> ['data-cost'=>$u['cost']], 'value' => $u['user_id'], 'label' => $u['name'] ];
			//$result[$u['user_id']] = $u['name'];
		}
	
		return $result;
	}
	
	// Funcion que trae todos los usuarios no asignados a un proyecto
	public function getAllUsersNotAssignedToProject($id_project)
	{
		$usersProjectsServices = new UsersProjectsServices();
		$users         = $usersProjectsServices->getAllUsersNotAssignedToProject($id_project);
		$result          = array();
		//echo "<pre>"; print_r($users); exit;
		foreach ($users as $u){
			$result[] = ['attributes'=> ['data-cost'=>$u['cost']], 'value' => $u['user_id'], 'label' => $u['name'] ];
			//$result[$u['user_id']] = $u['name'];
		}
	
		return $result;
	}
	
	// Funcion que trae todos los servicios
	public function getAllServices()
	{
		$servicesServices = new ServicesServices();
		$services         = $servicesServices->fetchAll();
		$result           = array();
		
		foreach ($services as $d){
			$result[] = ['attributes'=> ['data-cost'=>$d['cost']], 'value' => $d['id_service'], 'label' => $d['name'] ];
			//$result[$u['user_id']] = $u['name'];
		}
	
		return $result;
	}
	
	// Funcion que trae todos los servicios de un proyecto
	public function getAllServicesAssignedToProject($id_project)
	{
		$serviceSP = new ServicesProjectsServices();
		$rows      = $serviceSP->getAllServicesAssignedToProject($id_project);
		$result    = array();
		
		foreach ($rows as $row){
			$result[] = ['attributes'=> ['data-cost'=>$row['cost']], 'value' => $row['id_service'], 'label' => $row['name'] ];
			//$result[$u['user_id']] = $u['name'];
		}
	
		return $result;
	}
	
	// Funcion que trae todos los servicios no asignados a un proyecto
	public function getAllServicesNotAssignedToProject($id_project)
	{
		$serviceSP = new ServicesProjectsServices();
		$rows      = $serviceSP->getAllServicesNotAssignedToProject($id_project);
		$result    = array();
		
		foreach ($rows as $row){
			$result[] = ['attributes'=> ['data-cost'=>$row['cost']], 'value' => $row['id_service'], 'label' => $row['name'] ];
			//$result[$u['user_id']] = $u['name'];
		}
	
		return $result;
	}
	
	// Funcion para obtener los camiones
	protected function getAllTruck()
	{
		$serviceTruck = new InventoryService();
		$rows         = $serviceTruck->fetchAllById(5);
		$result       = array();
		
		foreach ($rows as $row){
			$result[$row['id_inventories']] = $row['article'];
		}
		
		return $result;
	}
	
}