<?php
	namespace Usermanual\Forms;
	
	use Zend\Form\Form;

	class TopicsUsermanualEditForm extends Form{

		public function init(){
			
			$this->setMethod('post')
			->setName('editTopic')
			->setAttrib('id', 'editTopic')
			->setAttrib('onsubmit', 'return validatorForm()');
			
			$id_topic=new Zend_Form_Element_Hidden('id_topic');
			$id_topic->addFIlter('Int');
			
			$this->add(array(
					'name' => 'id_topic_update',
					'options' => array(
							'label'		=> 'Tipo de elemento:',
					),
					'attributes' => array(
							'type'		=> 'text',
							'class'		=> 'form-control',
							'disabled'	=> 'true',
							'required'	=> 'required',
							'id'		=> 'id_topic_update',
							'name'		=> 'id_topic_update',
					)
			));
			
			$topic_name=new Zend_Form_Element_Text("topic_name");
			$topic_name->setLabel('Nombre del elemento:')->setRequired(true)
			->addFilter('StripTags')->addFilter('StringTrim')->addValidator('NotEmpty')
			->setAttrib('class','form-control');
			
			$this->add(array(
					'name' => 'type',
					'options' => array(
							'label'		=> 'Tipo de elemento:',
					),
					'attributes' => array(
							'type'		=> 'text',
							'class'		=> 'form-control',
							'disabled'	=> 'true',
							'required'	=> 'required',
							'id'		=> 'type',
							'name'		=> 'type',
					)
			));
			
			$this->add(array(
					'name' => 'Guardar',
					'attributes' => array(
							'id'	=> 'submitbutton',
							'type'	=> 'submit',
							'class' => 'btn btn-lg btn-success',
							'value'	=> 'Guardar',
							'title'	=> 'Guardar'
					),
			));
			

			$this->add(array(
					'name' => 'Cancelar',
					'attributes' => array(
							'id'		=> 'cancel',
							'type'		=> 'button',
							'class' 	=> 'btn btn-lg btn-danger',
							'value'		=> 'Cancelar',
							'title'		=> 'Cancelar',
							'onclick'	=>'getBack()',
					),
			));

		}
	}