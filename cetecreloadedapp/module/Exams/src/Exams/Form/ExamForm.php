<?php

namespace Exams\Form;

use Zend\Form\Form;
use Exams\Model\ExamModelImpl;
	
	class ExamForm extends Form{

		public function __construct( $name=null ){
			parent::__construct($name);
			
			$this ->setName('addExam')
				  ->setAttributes(array(
				  		'id'		=> 'addExam',
				  		'method'	=> 'Post',
				  		'onsubmit'	=> 'return validatorForm()'
				  ));

			$this->add(array(
					'name' => 'id_topic',
					'options' => array(
							'label'		=> '',
					),
					'attributes' => array(
							'type'		=> 'hidden',
							'class'		=> 'form-control',
							'id'		=> 'id_topic',
							'name'		=> 'id_topic',
					)
			));
			
			
			$this->add(array(
					'name' => 'id_topic_update',
					'options' => array(
							'label'		=> '',
					),
					'attributes' => array(
							'type'		=> 'hidden',
							'class'		=> 'form-control',
							'id'		=> 'id_topic_update',
							'name'		=> 'id_topic_update',
					)
			));

			$this->add(array(
					'name' => 'topic_name',
					'options' => array(
							'label'		=> 'Nombre del examen:',
					),
					'attributes' => array(
							'type'		=> 'text',
							'class'		=> 'form-control',
							'id'		=> 'topic_name',
							'name'		=> 'topic_name',
							'required'	=> 'required',
					)
			));
            
// 			$this->add(array(
// 					'name' => 'project_name',
// 					'options' => array(
// 							'label'		=> 'Trimestre al que pertenece:',
// 					),
// 					'attributes' => array(
// 							'type'		=> 'text',
// 							'class'		=> 'form-control',
// 							'id'		=> 'project_name',
// 							'name'		=> 'project_name',
// 							'required'	=> 'required',
// 					)
// 			));
			
			$this->add(array(
					'type' => 'select',
					'tabindex' =>2,
					'options' => array(
							'label' => 'Trimestre al que pertenece',
							'empty_option' => 'Seleccione',
							'value_options' => $this->getAllTrim(),
					),
					'attributes' => array(
							'name' => 'project_name',
							'id'   => 'project_name',
							'required' => 'required',
							'class'  => 'form-control',
					),
			));
			
            $this->add(array(
            		'name' => 'description',
            		'options' => array(
            				'label'		=> 'Descripción',
            		),
            		'attributes' => array(
            				'type'		=> 'textarea',
            				'class'		=> 'form-control',
            				'name'		=> 'description',
            				'id'		=> 'description',
            				'required'	=> 'required',
            				'rows'      => '6',
            				'cols'      => '10'
            				
            		)
            ));
            
            $this->add(array(
            		'name' => 'startDate',
            		'options' => array(
            				//'label'		=> 'Desde:',
            		),
            		'attributes' => array(
            				'type'		=> 'text',
            				'class'		=> 'form-control',
            				'id'		=> 'startDate',
            				'name'		=> 'startDate',
            				'required'	=> 'required',
            		)
            ));
            
            $this->add(array(
            		'name' => 'endDate',
            		'options' => array(
            				//'label'		=> 'Desde:',
            		),
            		'attributes' => array(
            				'type'		=> 'text',
            				'class'		=> 'form-control',
            				'id'		=> 'endDate',
            				'name'		=> 'endDate',
            				'required'	=> 'required',
            		)
            ));
            
            $this->add(array(
            		'name' => 'time',
            		'options' => array(),
            		'attributes' => array(
            				'type'		=> 'text',
            				'class'		=> 'form-control',
            				'id'		=> 'time',
            				'name'		=> 'time',
            				'required'	=> 'required',
            		)
            ));
            
            
			$this->add(array(
					'name' => 'Guardar',
					'attributes' => array(
							'id'	=> 'submitbutton',
							'type'	=> 'submit',
							'class' => 'btn btn-lg btn-success btn-block',
							'value'	=> 'Guardar',
							'title'	=> 'Guardar'
					),
			));
			
			$this->add(array(
					'name' => 'Cancelar',
					'attributes' => array(
							'id'		=> 'cancel',
							'type'		=> 'button',
							'class' 	=> 'btn btn-lg btn-danger btn-block',
							'value'		=> 'Cancelar',
							'title'		=> 'Cancelar',
							'onclick'	=>'getBack()',
					),
			));

		}
		
		
		public function getAllTrim(){
			$parent = 17;
			$examModel = new ExamModelImpl();
			$result = $examModel->getAllTrim($parent);

			$trims = array();
			
			foreach ($result as $res) {
				$trims[$res['id_topic']] = $res['topic_name'];
			}
		
			return $trims;
		}

	}
