<?php
namespace In\Form;

use Zend\Form\Form;
use Iofractal\Services\StatesServices;

class DestinationsForm extends Form
{
	
	public function __construct($name = null)
	{
		
		parent::__construct($name);
		
		$this->setAttributes(array(
				'action'=>"",
				'method' => 'post'
		));
		
		/*********** DATOS DE GENERALES ***********/
		
		/** ID DESTINO **/
		$this->add(array(
				'name' => 'id_destination',
				'type' => 'Hidden',
		));
		
		/** NOMBRE DESTINO **/
		$this->add(array(
				'name' => 'name_destination',
				'type' => 'Text',
				'options' => array(
						'label' => 'Nombre del destino:',
				),
				'attributes' => array(
						'class'       => 'form-control input-lg input-required',
						'placeholder' => 'Nombre destino',
				)
		));
		
		/** TIPO DESTINO **/
		$this->add(array(
				'name' => 'type_destination',
				'type' => 'Select',
				'options' => array (
						'label'         => 'Tipo destino:',
						'empty_option'  => '--selecciona--',
						'value_options' => array("1" => "Local", "2" => "Foraneo"),
				),
				'attributes' => array(
						'id'    => 'type_destination',
						'class' => 'form-control input-lg',
						//'size'  => '5'
				)
		));
		
		/** DESTINO / RUTA **/
		$this->add(array(
				'name' => 'direct_route',
				'type' => 'Select',
				'options' => array (
						'label'         => 'Directo / Ruta:',
						'empty_option'  => '--selecciona--',
						'value_options' => array("1" => "Directo", "2" => "Ruta"),
				),
				'attributes' => array(
						'id'    => 'direct_route',
						'class' => 'form-control input-lg',
						//'size'  => '5'
				)
		));
		
		/** DESCRIPCION **/
		$this->add(array(
				'name'      => 'description_destination',
				'attributes'=> array(
						'type'  => 'textarea',
						'rows'  => 5,
						'class' => 'form-control input-lg',
						'id'    => 'description_destination',
				)
		));
		
		/* CAMPOS DE DIRECCIONES */
		
		/** ESTADO **/
		$this->add(array(
				'name' => 'state_id',
				'type' => 'Select',
				'options' => array (
						'label' => 'Estado:',
						'empty_option'  => '--selecciona--',
						'value_options' => $this->getAllStatesOfMexico(),
				),
				'attributes' => array(
						'id'    => 'state_id',
						'class' => 'form-control input-lg',
				)
		));
		
		/** DELEGACION O MUNICIPIO **/
		$this->add(array(
				'name' => 'district',
				'type' => 'Select',
				'options' => array (
						'label' => 'Delegacion o municipio:',
						'empty_option' => '--selecciona--',
				),
				'attributes' => array(
						'id'    => 'district',
						'class' => 'form-control input-lg',
				)
		));
		
		/** COLONIA **/
		$this->add(array(
				'name' => 'neighborhood',
				'type' => 'Select',
				'options' => array (
						'label' => 'Colonia:',
						'empty_option' => '--selecciona--',
						/*'value_options' => array(
						 array('value' => 'alt', 'label' => 'Alternative'),
								array('value' => 'country', 'label' => 'Country'),
								array('value' => 'jazz', 'label' => 'Jazz'),
								array('value' => 'rock', 'label' => 'Rock'),
						),*/
				),
				'attributes' => array(
						'id' => 'neighborhood',
						'class' => 'form-control input-lg',
				)
		));
		
		/*********** SALARIOS: AYUDANTE/OPERADOR ***********/
		
		/* SALARIO OPERADOR */
		
		$this->add(array(
				'name' => 'operator_salary',
				'type' => 'Text',
				'options' => array(
						'label' => 'Salario operador:',
				),
				'attributes' => array(
						'id'     => 'operator_salary',
						'class'  => 'form-control input-lg input-required num',
						'value'  => 0,
				)
		));
		
		/* SALARIO AYUDANTE */
		$this->add(array(
				'name' => 'assistant_salary',
				'type' => 'Text',
				'options' => array(
						'label' => 'Salario ayudante:',
				),
				'attributes' => array(
						'id'          => 'assistant_salary',
						'class'       => 'form-control input-lg input-required num',
						'value'  => 0,
				)
		));
		
		/*********** PRECIOS TIPO UNIDAD ***********/
		
		/* CAMIONETA SECA */
		$this->add(array(
				'name' => 'dry_van',
				'type' => 'Text',
				'options' => array(
						'label' => 'Camioneta seca:',
				),
				'attributes' => array(
						'id'          => 'dry_van',
						'class'       => 'form-control input-lg input-required num',
						'value'  => 0,
				)
		));
		
		/* 4.5 TONELADAS SECA */
		$this->add(array(
				'name' => 'tonel_van',
				'type' => 'Text',
				'options' => array(
						'label' => '4.5 toneladas seca:',
				),
				'attributes' => array(
						'id'          => 'tonel_van',
						'class'       => 'form-control input-lg input-required num',
						'value'  => 0,
				)
		));
		
		/* CAMION */
		$this->add(array(
				'name' => 'truck',
				'type' => 'Text',
				'options' => array(
						'label' => 'Camion:',
				),
				'attributes' => array(
						'id'          => 'truck',
						'class'       => 'form-control input-lg input-required num',
						'value'  => 0,
				)
		));
		
		/* CAMIONETA REFRIGERADA */
		$this->add(array(
				'name' => 'refrigerated_truck',
				'type' => 'Text',
				'options' => array(
						'label' => 'Camioneta refrigerada:',
				),
				'attributes' => array(
						'id'          => 'refrigerated_truck',
						'class'       => 'form-control input-lg input-required num',
						'value'  => 0,
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
	
	// Funcion que trae todos los estados de Mexico disponibles
	public function getAllStatesOfMexico()
	{
		$statesServices = new StatesServices();
		$statesOfMexico = $statesServices->fetchAll();
		$result      = array();
	
		foreach ($statesOfMexico as $s_m){
			//$result[] = ['attributes'=> ['data-est'=>$s_m['id']], 'value' => $s_m['id'], 'label' => $s_m['state'] ];
			$result[$s_m['id']] = $s_m['state'];
		}
	
		return $result;
	}

}