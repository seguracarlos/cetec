<?php
	namespace Usermanual\Forms;
	
	use Zend\Form\Form;
	
	class ContentsusermanualForm extends Form{
		
		public function __construct( $name=null ){
			parent::__construct($name);
			
			$this ->setName('paramform')
				  ->setAttributes(array(
				  		'id'		=> 'contentform',
				  		'method'	=> 'Post'
				  ));
		
// 			$this->setMethod('post');
			
// 			$id_content=new Zend_Form_Element_Hidden('id_content');
// 			->addFIlter('Int');
			
			$this->add(array(
					'name' => 'id_content',
					'options' => array(
							'label'		=> '',
					),
					'attributes' => array(
							'type'		=> 'text',
							'class'		=> 'form-control',
							'id'		=> 'id_content',
							'name'		=> 'id_content',
					)
			));
			
// 			$id_topicContent=new Zend_Form_Element_Hidden("id_topic_content");
// 			$id_topicContent->setAttrib('id', 'id_topic_content')
// 			->setAttrib('name', 'id_topic_content')
// 			->setValue(0);
			
			$this->add(array(
					'name' => 'id_topic_content',
					'options' => array(
							'label'		=> '',
					),
					'attributes' => array(
							'type'		=> 'text',
							'class'		=> 'form-control',
							'id'		=> 'id_topic_content',
							'name'		=> 'id_topic_content',
					)
			));
			

// 			$content=new Zend_Form_Element_Textarea("content");
// 			$content->setLabel('')
// 			->setRequired(true)
// 			->addValidator('NotEmpty')
// 			->setAttrib('id','editor1')
// 			->setAttrib('rows=25','cols=90');
			
			$this->add(array(
					'name' => 'content',
					'options' => array(
							'label'		=> '',
					),
					'attributes' => array(
							'type'		=> 'textarea',
							'class'		=> 'form-control',
							'id'		=> 'editor1',
							'name'		=> 'content',
							'required'	=> 'required'
					)
			));
			
			
// 			$id_topic=new Zend_Form_Element_Hidden('id_topic');
// 			$id_topic->addFIlter('Int');
			
			$this->add(array(
					'name' => 'id_topic',
					'options' => array(
							'label'		=> '',
					),
					'attributes' => array(
							'type'		=> 'text',
							'class'		=> 'form-control',
							'id'		=> 'id_topic',
							'name'		=> 'id_topic',
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
					),
			));
			
		}
	}