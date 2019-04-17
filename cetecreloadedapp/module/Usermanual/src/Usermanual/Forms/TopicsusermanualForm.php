<?php
	namespace Usermanual\Forms;
	
	use Zend\Form\Form;
use Zend\Form\Annotation\Attributes;
use Zend\Validator\Identical;
			
	class TopicsusermanualForm extends Form{

		public function __construct( $name=null ){
			parent::__construct($name);
			
// 			$this->setMethod('post')
// 			->setName('addTopic')
// 			->setAttrib('id', 'addTopic')
// 			->setAttrib('onsubmit', 'return validatorForm()');
			
			$this ->setName('paramform')
			->setAttributes(array(
					'id'		=> 'addTopic',
					'name'		=> 'addTopic',
					'method'	=> 'Post',
					'onsubmit'	=> 'return validatorForm()'
			));
			
			
// 			$id_topicAdd=new Zend_Form_Element_Hidden("id_topic_add");
// 			$id_topicAdd->setAttrib('id', 'id_topic_add')
// 			->setAttrib('name', 'id_topic_add')
// 			->setValue(0);
			
			$this->add(array(
					'name' => 'id_topic_add',
					'options' => array(
							'label'		=> '',
					),
					'attributes' => array(
							'type'		=> 'hidden',
							'class'		=> 'form-control',
							'id'		=> 'id_topic_add',
							'name'		=> 'id_topic_add',
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
							'type'		=> 'hidden',
							'class'		=> 'form-control',
							'id'		=> 'id_topic',
							'name'		=> 'id_topic',
					)
			));
			
			
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
			
			$this->add(array(
					'name' => 'id_topic_delete',
					'options' => array(
							'label'		=> 'Tipo de elemento:',
					),
					'attributes' => array(
							'type'		=> 'text',
							'class'		=> 'form-control',
							'disabled'	=> 'true',
							'required'	=> 'required',
							'id'		=> 'id_topic_delete',
							'name'		=> 'id_topic_delete',
					)
			));
			
			
// 			$topic_name=new Zend_Form_Element_Text("topic_name");
// 			$topic_name->setLabel('Nombre del elemento:')->setRequired(true)
// 			->addFilter('StripTags')->addFilter('StringTrim')->addValidator('NotEmpty')
// 			->setAttrib('class','form-control')
// 			->setAttrib('name','topic_name')
// 			->setAttrib('id','topic_name');
			
			$this->add(array(
					'name' => 'topic_name',
					'options' => array(
							'label'		=> 'Nombre:',
					),
					'attributes' => array(
							'type'		=> 'text',
							'class'		=> 'form-control',
							'id'		=> 'topic_name',
							'name'		=> 'topic_name',
							'required'	=> 'required'
					)
			));
			
// 			$id_parent=new Zend_Form_Element_Hidden("id_parent");
// 			$id_parent->setAttrib('id', 'id_parent');
			
			$this->add(array(
					'name' => 'id_parent',
					'options' => array(
							'label'		=> '',
					),
					'attributes' => array(
							'type'		=> 'hidden',
							'class'		=> 'form-control',
							'id'		=> 'id_parent',
							'name'		=> 'id_parent',
					)
			));
			
// 			$artefact_type = new Zend_Form_Element_Radio('type');
//     		$artefact_type->setLabel('Tipo de elemento:')
//     		->setAttrib('id','type')
//       		->setAttrib('name','type')
//     		->addValidator('NotEmpty')
//       		->addMultiOptions(array(
//         	'file' => 'Documento',
//         	'folder' => 'Carpeta'
//       		));
      		
			$this->add(array(
					'name' => 'type',
					'type' => 'Zend\Form\Element\Radio',
					'options' => array(
							'label'			=> 'Tipo de elemento:',
							'value_options' => array(
		      						'file'		=> 'Documento',
		      						'folder'	=> 'Carpeta',
							),
					),
					'attributes' => array(
							'id'		=> 'type',
							'required'	=> 'required'
					),
			));

// 			$submit=new Zend_Form_Element_Submit('Guardar');
// 			$submit->setAttrib('id','submitbutton')
// 			->setAttrib('class','btn btn-lg btn-success')
// 			->setAttrib('style','width: 100%;');

// 			$cancel=new Zend_Form_Element_Button('Cancelar');
// 			$cancel->setAttrib('id','cancel')
// 			->setAttrib('class','btn btn-lg btn-danger')
// 			->setAttrib('style','width: 100%;');
			
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
?>