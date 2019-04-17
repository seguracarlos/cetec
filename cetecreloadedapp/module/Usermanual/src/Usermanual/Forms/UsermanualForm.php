<?php
	namespace Usermanual\Forms;
	
	use Zend\Form\Form;

	class UsermanualForm extends Form{

		public function __construct($name = null){
			parent::__construct($name);
			
			$this->add(array(
					'name' => 'addUsermanual',
					'options' => array(
					),
					'attributes' => array(
							'type'		=> 'text',
							'class' 	=> 'input',
							'id'		=> 'addUsermanual',
							'onsubmit'	=> 'return validatorForm()'
					)
			));
			
			$this->add(array(
					'name'		=> 'id_topic',
					'options'	=> array(
					),
						'attributes' => array(
						'id'		=>'id_topic',
						'type'		=> 'hidden',
						'class'		=> 'input',
					)
			));
			
			$this->add(array(
					'name' => 'topic_name',
					'options' => array(
							'label'		=> 'Nombre del tema:',
					),
					'attributes' => array(
							'type'		=> 'text',
							'class'		=> 'form-control',
							'required'	=> 'required',
							'id'		=> 'topic_name',
							'name'		=> 'topic_name',
					)
			));
			
			$this->add(array(
					'name' => 'project_name',
					'options' => array(
							'label'		=> 'Clase:',
					),
					'attributes' => array(
							'type'		=> 'text',
							'class'		=> 'form-control',
							'required'	=> 'required',
							'id'		=> 'project_name',
							'name'		=> 'project_name',
					)
			));
			
			$this->add(array(
					'name' => 'project_name',
					'options' => array(
							'label'		=> 'Descripción:',
					),
					'attributes' => array(
							'type'		=> 'textarea',
							'class'		=> 'form-control',
							'required'	=> 'required',
							'id'		=> 'description',
							'name'		=> 'description',
					)
			));
			
			$this->add(array(
					'name' => 'Guardar',
					'attributes' => array(
							'id'		=> 'submitbutton',
							'type'		=> 'submit',
							'class'		=> 'btn btn-lg btn-success',
							'value'		=> 'Guardar',
							'title'		=> 'Guardar'
					),
			));
			
			$this->add(array(
					'name' => 'Editar',
					'attributes' => array(
							'id'		=> 'submitbutton',
							'type'		=> 'submit',
							'class'		=> 'btn btn-lg btn-success',
							'value'		=> 'Guardar',
							'title'		=> 'Guardar'
					),
			));
			
			$this->add(array(
					'name' => 'Cancelar',
					'attributes' => array(
							'id'		=> 'cancel',
							'type'		=> 'submit',
							'class'		=> 'btn btn-lg btn-danger',
							'value'		=> 'Cancelar',
							'title'		=> 'Cancelar'
					),
			));
		}
	}