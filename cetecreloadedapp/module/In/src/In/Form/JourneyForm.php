<?php
namespace In\Form;

use Zend\Form\Form;
use In\Services\CompaniesServices;
use System\Services\UsersService;
USE Horus\Services\InventoryService;
use In\Services\DestinationsServices;
use In\Services\ShippingServices;

class JourneyForm extends Form
{
	
	public function __construct($name = null, $type = null, $idShip = null)
	{
		$idShipping = (int) $idShip;
		
		parent::__construct($name);
		
		$this->setAttributes(array(
				'action'=>"",
				'method' => 'post'
		));
		
		/*********** VALIDACIONES ***********/
		//print_r($type); exit;
		if($type == 1){
			$employess = $this->getEmployeesAvailable();
			$truck     = $this->getAllTruck();
			$getNextFolioNumber = $this->getNextFolioNumber();
		}else{
			$employess = $this->getEmployeesAvailableAndAssigned($idShipping);
			$truck     = $this->getTrucksAvailableAndAssigned($idShipping);
			$getNextFolioNumber = "";
		}
		
		/*********** DATOS DE GENERALES ***********/
		
		/* ID PROYECTO */
		$this->add(array(
				'name' => 'id_shipping',
				'type' => 'Hidden',
		));
		
		/* TABLA CON EL DETALLE DE LOS PRODUCTOS CONSOLIDAD: SI/NO */
		$this->add(array(
				'name' => 'detail_table',
				'type' => 'Hidden',
				'attributes' => array(
						'id'    => 'detail_table',
						'class' => "form-control",
						'placeholder' => 'Clientes envia',
				)
		));
		
		/* TABLA CON EL DETALLE DE LOS CLIENTES A ENTREGAR */
		$this->add(array(
				'name' => 'detail_table2',
				'type' => 'Hidden',
				'attributes' => array(
						'id'    => 'detail_table2',
						'class' => "form-control",
						'placeholder' => 'Clientes entrega',
				)
		));
		
		/* CIUDADES ADICIONALES */
		$this->add(array(
				'name' => 'citys_delivery',
				'type' => 'Hidden',
				'attributes' => array(
						'id'    => 'citys_delivery',
						'class' => "form-control",
						'placeholder' => 'Ciudades adicionales',
				)
		));
		
		/* IDS DE LOS AYUDANTES */
		$this->add(array(
				'name' => 'ids_ayudantes',
				'type' => 'Hidden',
				'attributes' => array(
						'id'    => 'ids_ayudantes',
						'class' => "form-control input-lg",
						'placeholder' => 'Ids Ayudantes',
				)
		));
		
		$this->add(array(
				'name' => 'ids_assistants',
				'type' => 'Hidden',
				'attributes' => array(
						'id'    => 'ids_assistants',
						'class' => "form-control input-lg",
						'placeholder' => 'Ids Asistentes',
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
						'class' => 'form-control input-lg',
				)
		));
		
		/** CAMPO PARA LA TABLA DE CLIENTES **/
		$this->add(array(
				'name' => 'company_ID2',
				'type' => 'Select',
				'options' => array (
						'label' => 'Cliente:',
						'empty_option'  => "--selecciona--",
						'value_options' => $this->getAllClients(),
				),
				'attributes' => array(
						//'id'    => 'company_ID2',
						'class'    => 'form-control select-clients',
						'disabled' => 'disabled',
				)
		));
		
		/** CAMPO PARA LA TABLA DE CLIENTES 2 **/
		$this->add(array(
				'name' => 'company_ID3',
				'type' => 'Select',
				'options' => array (
						'label' => 'Cliente:',
						'empty_option'  => '--selecciona--',
						'value_options' => $this->getAllClients(),
				),
				'attributes' => array(
						//'id'    => 'company_ID3',
						'class' => 'form-control',
				)
		));
		
		/* FOLIO DE CLIENTE */
		$this->add(array(
				'name' => 'client_folio',
				'type' => 'Text',
				'attributes' => array(
						'id'          => 'client_folio',
						'class'       => 'form-control input-lg',
						'placeholder' => 'Folio Cliente',
				)
		));
		
		// FOLIO INTERNO DE LA EMPRESA
		$this->add(array(
				'name' => 'internal_folio',
				'type' => 'Text',
				'attributes' => array(
						'id' 		  => 'internal_folio',
						'class' 	  => 'form-control input-lg',
						'placeholder' => 'Folio interno',
						'readonly'	  => 'readonly',
						'value'       => $getNextFolioNumber
				)
		));
		
		/*
		 * LUGAR DE ORIGEN
		 */
		$this->add(array(
				'name' => 'place_origin',
				'type' => 'Text',
				'attributes' => array(
						'id' 		  => 'place_origin',
						'class' 	  => 'form-control input-lg',
						'placeholder' => 'Lugar de origen',
						'value'       => 'Mexico Distrito Federal'
				)
		));
		
		
		/* KILOMETRAJE INICIAL */
		$this->add(array(
				'name' => 'starting_mileage',
				'type' => 'Text',
				'attributes' => array(
						'id'          => 'starting_mileage',
						'class'       => 'form-control input-lg input-numeric',
						'placeholder' => 'Kilometraje Inicial',
				)
		));
		
		/** TIPO DE UNIDAD SECA/REFRIGERADA **/
		$this->add(array(
				'name' => 'chilled_dry',
				'type' => 'radio',
				'options' => array(
						'value_options' => array(
								'1' => 'Seca',
								'2' => 'Refrigerada',
						),
				),
		));
		
		/** LOCAL/FORANEO **/
		$this->add(array(
				'name' => 'type_destination',
				'type' => 'radio',
				'options' => array(
						'value_options' => array(
								'1' => 'local',
								'2' => 'Foraneo',
						),
				),
				'attributes' => array(
						//'id'        => 'type_destination',
						'onclick' => "javascript: return false;",
				)
		));
		
		/** DIRETO/RUTA **/
		$this->add(array(
				'name' => 'direct_route',
				'type' => 'radio',
				'options' => array(
						'value_options' => array(
								'1' => 'Directo',
								'2' => 'Ruta',
						),
				),
				'attributes' => array(
						//'id'        => 'type_destination',
						'onclick' => "javascript: return false;",
				)
		));
		
		/* CONSOLIDADO SI/NO */
		$this->add(array(
				'name' => 'consolidated',
				'type' => 'radio',
				'options' => array(
						'value_options' => array(
								'1' => 'Si',
								'2' => 'No',
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
						'class'       => 'form-control input-lg ',
						'placeholder' => 'Inicio',
				)
		));
		
		/** FECHA FIN **/
		$this->add(array(
				'name' => 'end_date',
				'type' => 'Hidden',
				'options' => array(
						'label' => 'Fecha fin:',
				),
				'attributes' => array(
						'id'          => 'end_date',
						'class'       => 'form-control input-lg ',
						'placeholder' => 'Fin',
				)
		));
		
		/* DESCRIPCION */
		$this->add(array(
				'name'      => 'description',
				'attributes'=> array(
						'type'  => 'textarea',
						'rows'  => 5,
						'class' => 'form-control input-lg',
				)
		));
		
		/* OPERADOR */
		$this->add(array(
				'name' => 'id_operator',
				'type' => 'Select',
				'options' => array (
						'label'         => 'Operador:',
						'empty_option'  => '--selecciona--',
						'value_options' => $employess,
				),
				'attributes' => array(
						'id'    => 'id_operator',
						'class' => 'form-control input-lg',
						//'size'  => '5'
				)
		));
		
		/* IMPORTE OPERADOR */
		$this->add(array(
				'name' => 'amount_operator',
				'type' => 'Hidden',
				'attributes' => array(
						'id'    => 'amount_operator',
						'class' => 'form-control input-lg',
						'placeholder' => "Tarifa operador",
				)
		));
		
		/** ASISTENTE */
		$this->add(array(
				'name' => 'id_assistant',
				'type' => 'Select',
				'options' => array (
						'label'         => 'Asistente:',
						'empty_option'  => '--selecciona--',
						'value_options' => $employess,
				),
				'attributes' => array(
						'id'    => 'id_assistant',
						'class' => 'form-control input-lg id_assistant',
						//'multiple' => 'multiple'
						//'size'  => '5'
				)
		));
		
		/* IMPORTE AYUDANTE */
		$this->add(array(
				'name' => 'amount_assistant',
				'type' => 'Hidden',
				'attributes' => array(
						'id'    => 'amount_assistant',
						'class' => 'form-control input-lg',
						'placeholder' => "Tarifa asistente",
				)
		));
		
		
		/** DESTINOS DISPONIBLES **/
		$this->add(array(
				'name' => 'id_destination',
				'type' => 'Select',
				'options' => array (
						'label' => 'Destinos disponibles:',
						'empty_option'  => '--selecciona--',
						'value_options' => $this->getAllDestinations(),
				),
				'attributes' => array(
						'id'    => 'id_destination',
						'class' => 'form-control input-lg',
						//'size'  => '5'
				)
		));

		/** PROMOCIONAL O TERMINADO **/
		$this->add(array(
				'name' => 'promotional_finished',
				'type' => 'Select',
				'options' => array (
						'label' => 'Destinos disponibles:',
						'empty_option'  => '--selecciona--',
						'value_options' => array("1" => "Promocional", "2" => "Terminado", "3" => "Mixto", "4" => "Trafico", "5" => "Insumos"),
				),
				'attributes' => array(
						'id'    => 'promotional_finished',
						'class' => 'form-control input-lg',
						//'size'  => '5'
				)
		));
		
		/* CAMION */
		$this->add(array(
				'name' => 'id_truck',
				'type' => 'Select',
				'options' => array (
						'label' => 'Unidad:',
						'empty_option'  => '--selecciona--',
						'value_options' => $truck,
				),
				'attributes' => array(
						'id'    => 'id_truck',
						'class' => 'form-control input-lg ',
				)
		));
		
		// TIPO DE GASOLINA
		$this->add(array(
				'name' => 'type_gasoline',
				'type' => 'Select',
				'options' => array (
						'label' => 'Tipo de gasolina:',
						'empty_option'  => '--selecciona--',
						'value_options' => array("6" => "MAGNA", "7" => "PREMIUM", "5" => "DIESEL"),
				),
				'attributes' => array(
						'id'    => 'type_gasoline',
						'class' => 'form-control input-lg',
						//'size'  => '5'
				)
		));
		
		/* COSTO VIAJE */
		$this->add(array(
				'name' => 'cost_journey',
				'type' => 'Text',
				'options' => array(
						'label' => 'Costo viaje:',
				),
				'attributes' => array(
						'id'          => 'cost_journey',
						'class'       => 'form-control input-lg',
						//'placeholder' => 'Inicio',
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
	
	// OBTENER LOS EMPLEADOS DISPONIBLES SIN VIAJES
	public function getEmployeesAvailable()
	{	
		$usersServices = new UsersService();
		$users         = $usersServices->getEmployeesAvailable();
		$result          = array();
		//echo "<pre>"; print_r($users); exit;
		foreach ($users as $u){
			//$result[] = ['attributes'=> ['data-cost'=>$u['cost']], 'value' => $u['user_id'], 'label' => $u['surname']." ".$u['lastname']." ".$u['name']." - ".$u['name_job'] ];
			$result[$u['user_id']] = $u['surname']." ".$u['lastname']." ".$u['name']." - ".$u['name_job'];
		}
	
		return $result;
	}
	
	/*
	 * OBTENER LOS EMPLEADOS DISPONIBLES Y ASIGNADOS A UN VIAJE
	 */
	public function getEmployeesAvailableAndAssigned($id)
	{
		//print_r($id); exit;
		$usersServices = new UsersService();
		$users         = $usersServices->getEmployeesAvailableAndAssigned(1,$id);
		$result          = array();
		//echo "<pre>"; print_r($users); exit;
		foreach ($users as $u){
			//$result[] = ['attributes'=> ['data-cost'=>$u['cost']], 'value' => $u['user_id'], 'label' => $u['surname']." ".$u['lastname']." ".$u['name']." - ".$u['name_job'] ];
			$result[$u['user_id']] = $u['surname']." ".$u['lastname']." ".$u['name']." - ".$u['name_job'];
		}
	
		return $result;
	}
	
	
	// Funcion que trae todos los destinos disponibles
	public function getAllDestinations()
	{
		$service = new DestinationsServices();
		$rows    = $service->fetchAll();
		$result  = array();
		
		$tipo  = "";
		$di_ru = "";
		//echo "<pre>"; print_r($rows); exit;
		
		foreach ($rows as $row){
			
			if ($row['type_destination'] == 1){
				$tipo = "Local";	
			}else{
				$tipo = "Foraneo";
			}
			
			if($row['direct_route'] == 1){
				$di_ru = "Directo";
			}else{
				$di_ru = "Ruta";
			}
			
			/*$tariif = array(
					"operator_salary"  => $row['operator_salary'],
					"assistant_salary" => $row['assistant_salary'],
			);*/
			
			$options = array(
					"type_destination" => $row['type_destination'],
					"direct_route"     => $row['direct_route'],
					"operator_salary"  => $row['operator_salary'],
					"assistant_salary" => $row['assistant_salary'],
			);
			
			$result[] = ['attributes'=> ['data-options'=> json_encode($options)], 'value' => $row['id_destination'], 'label' => $row['name_destination']." - ".$tipo." - ".$di_ru ];
			//$result[$u['user_id']] = $u['name'];
		}
		//echo "<pre>"; print_r($result); exit;
		return $result;
	}
	
	// Funcion para obtener los camiones
	protected function getAllTruck()
	{
		$serviceTruck = new InventoryService();
		$rows         = $serviceTruck->getAllTruckAvailable(5);
		$result       = array();
		//echo "<pre>"; print_r($rows); exit;
		foreach ($rows as $row){
			$options = array(
					"article"  => $row['article'],
					"brand"    => $row['brand'],
					"model"    => $row['model'],
					"capacity" => $row['capacity'],
					"id_product" => $row['id_product'],
			);
			$result[] = ['attributes'=> ['data-options'=> json_encode($options) ], 'value' => $row['id_inventories'], 'label' => $row['id_product'] ];
			//$result[$row['id_inventories']] = $row['id_product'];
		}
		
		return $result;
	}
	
	/*
	 * OBTENER LOS CAMIONES DISPONIBLES Y ASIGNADO A UN VIAJE 
	 */
	protected function getTrucksAvailableAndAssigned($id)
	{
		$serviceTruck = new InventoryService();
		$rows         = $serviceTruck->getTrucksAvailableAndAssigned(5, $id);
		$result       = array();
		//echo "<pre>"; print_r($rows); exit;
		foreach ($rows as $row){
			$options = array(
					"article"  => $row['article'],
					"brand"    => $row['brand'],
					"model"    => $row['model'],
					"capacity" => $row['capacity'],
					"id_product" => $row['id_product'],
			);
			$result[] = ['attributes'=> ['data-options'=> json_encode($options) ], 'value' => $row['id_inventories'], 'label' => $row['id_product'] ];
			//$result[$row['id_inventories']] = $row['id_product'];
		}
	
		return $result;
	}
	
	/*
	 * OBTENER EL SUIGUIENTE NUMERO DE FOLIO INTERNO
	 */
	private function getNextFolioNumber()
	{
		$shippingServices   = new ShippingServices();	
		$getNextFolioNumber = $shippingServices->getNextFolioNumber();
		//print_r(str_pad($getNextFolioNumber, 6, "0", STR_PAD_LEFT)); exit;
		return str_pad($getNextFolioNumber, 6, "0", STR_PAD_LEFT);
	}
	
}