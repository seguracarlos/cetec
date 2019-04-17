<?php
namespace In\Form;

use Zend\Form\Form;

class ServicesForm extends Form
{
	
	public function __construct($name = null)
	{
		
		parent::__construct($name);
		
		$this->setAttributes(array(
				'action'=>"",
				'method' => 'post'
		));

		/*********** DATOS DE GENERALES ***********/
		
		/** ID SERVICIO **/
		$this->add(array(
				'name' => 'id_service',
				'type' => 'Hidden',
		));
		
		/** CLAVE **/
		$this->add(array(
				'name' => 'clave',
				'type' => 'Text',
				'options' => array(
						'label' => 'CLAVE:',
				),
				'attributes' => array(
						'class'       => 'form-control input-lg input-required',
						'placeholder' => 'Clave',
				)
		));
		
		/** NOMBRE SERVICIO **/
		$this->add(array(
				'name' => 'name_service',
				'type' => 'Text',
				'options' => array(
						'label' => 'Nombre:',
				),
				'attributes' => array(
						'class'       => 'form-control input-lg input-required',
						'placeholder' => 'Nombre',
				)
		));
		
		/** FECHA **/
		$this->add(array(
				'name' => 'dateService',
				'type' => 'Text',
				'options' => array(
						'label' => 'Fecha:',
				),
				'attributes' => array(
						'id'    => 'dateService',
						'class' => 'form-control input-lg input-required',
				)
		));
		
		/** DESCRIPCION **/
		$this->add(array(
				'name'      => 'description',
				'attributes'=> array(
						'type'  => 'textarea',
						'rows'  => 5,
						'class' => 'form-control input-lg',
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
						'class' => 'form-control input-lg input-required',
						'value' => 0,
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

}