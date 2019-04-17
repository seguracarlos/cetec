<?php
namespace Registrationusers\Form;

use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Form\Factory;

class RegisterForm extends Form
{
	public function __construct($name = null)
	{
		parent::__construct($name);
		//$this->setInputFilter(new \Registrationusers\Form\RegisterValidator());
		$this->setAttributes(array(
				//'action' => $this->url.'/modulo/recibirformulario',
				'action'=>"",
				'method' => 'post'
		));
		
		$this->add(array(
				'name' => 'nombre',
				'options' => array(
						'label' => 'Nombre: ',
				),
				'attributes' => array(
						'type' => 'text',
						'class' => 'form-control input-lg',
						'required'=>'required',
						'placeholder' => 'Nombre(s)',
				)
		));

		$this->add(array(
				'name' => 'email',
				'options' => array(
						'label' => 'Correo: ',
				),
				'attributes' => array(
						'type' => 'email',
						'class' => 'form-control input-lg',
						'required'=>'required',
						'placeholder' => 'Correo electronico',
				)
		));

		$this->add(array(
				'name' => 'password',
				'options' => array(
						'label' => 'Contraseña: ',
				),
				'attributes' => array(
						'id'   => 'pass',
						'type' => 'password',
						'class' => 'form-control input-lg',
						'required'=>'required',
						'placeholder' => 'ContraseÃ±a',
				)
		));
		
		$this->add(array(
				'name' => 'passwordConfirm',
				'options' => array(
						'label' => 'Confirmar Contraseña: ',
				),
				'attributes' => array(
						'id'   => 'passConfirm',
						'type' => 'password',
						'class' => 'form-control input-lg',
						'required'=>'required',
						'placeholder' => 'Confirmar ContraseÃ±a',
				)
		));

		$this->add(array(
				'name' => 'lastname',
				'options' => array(
						'label' => 'Apellido paterno: ',
				),
				'attributes' => array(
						'type' => 'text',
						'class' => 'form-control input-lg',
						'required'=>'required',
						'placeholder' => 'Apellido paterno',
				)
		));
		
		$this->add(array(
				'name' => 'surname',
				'options' => array(
						'label' => 'Apellido materno: ',
				),
				'attributes' => array(
						'type' => 'text',
						'class' => 'form-control input-lg',
						'required'=>'required',
						'placeholder' => 'Apellido materno',
				)
		));
		
		
		$this->add(array(
				'name' => 'submitbtn',
				'attributes' => array(
						'type' => 'submit',
						'value' => 'Enviar',
						'title' => 'Enviar',
						'id'    => 'send',
						'class' => 'btn btn-blue btn-lg btn-block',
						'style' => 'width:100%;'
				),
		));
	}
}
