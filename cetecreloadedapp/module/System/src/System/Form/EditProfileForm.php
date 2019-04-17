<?php
namespace System\Form;

use Zend\Form\Form;

class EditProfileForm extends Form
{
	//Le ponemos un nombre al formulario
	public function __construct($name = null)
	{
		parent::__construct($name);
		
		//Definimos el atributo action y method
		$this->setAttributes(array(
				'action'=>"",
				'method' => 'post'
		));
		
		$this->add(array(
				'name'       => 'user_id',
				'attributes' => array(
						'type' => 'hidden',
						'id'   => 'user_id'
				)
		));
		
		$this->add(array(
				'name'       => 'name',
				'attributes' => array(
					'type'        => 'text',
					'class'       => 'form-control input-lg',
					'placeholder' => 'Nombre(s)'
				)
		));

		$this->add(array(
				'name'       => 'display_name',
				'attributes' => array(
						'type'        => 'text',
						'class'       => 'form-control input-lg',
						'placeholder' => 'Nombre a mostrar'
				)
		));
		
		$this->add(array(
				'name'       => 'surname',
				'attributes' => array(
						'type'  => 'text',
						'class' => 'form-control input-lg',
						'placeholder' => 'Apellido materno'
				)
		));
		
		$this->add(array(
				'name'       => 'lastname',
				'attributes' => array(
						'type'  => 'text',
						'class' => 'form-control input-lg',
						'placeholder' => 'Apellido paterno'
				)
		));
		
		$this->add(array(
				'name'       => 'email',
				'attributes' => array(
						'type'  => 'email',
						'class' => 'form-control input-lg',
						'placeholder' => 'Correo electronico'
				)
		));

		$this->add(array(
				'name'       => 'phone',
				'attributes' => array(
						'type'  => 'tel',
						'class' => 'form-control input-lg',
						'placeholder' => 'Telefono'
				)
		));
		
		//Creamos el boton submit
		$this->add(array(
				'name'      => 'editProfile',
				'attributes' => array(
					'type'  => 'submit',
					'value' => 'Guardar',
					'class' => 'btn btn-success btn-block btn-lg',
					'id'    => 'editProfile'
				)
		));
		
	}
}