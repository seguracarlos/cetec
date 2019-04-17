<?php
	namespace Usermanual\Forms;
	
	use Zend\Form\Form;
	
	class ParamForm extends Form{
		
		public function __construct( $name=null ){
			parent::__construct($name);
			
			$this ->setName('paramform')
				  ->setAttributes(array(
				  		'id'		=> 'paramform',
				  		'method'	=> 'Post'
				  ));
			
			$this->add(array(
					'name' => 'id_topicSend',
					'options' => array(
							'label'		=> '',
					),
					'attributes' => array(
							'type'		=> 'input',
							'class'		=> 'form-control',
							'id'		=> 'id_topicSend',
							'name'		=> 'id_topicSend',
					)
			));
			
			$this->add(array(
					'name' => 'id_topic_add',
					'options' => array(
							'label'		=> '',
					),
					'attributes' => array(
							'type'		=> 'hidde',
							'class'		=> 'form-control',
							'id'		=> 'id_topic_add',
							'name'		=> 'id_topic_add',
					)
			));
			
			$this->add(array(
					'name' => 'id_topic_update',
					'options' => array(
							'label'		=> '',
					),
					'attributes' => array(
							'type'		=> 'hidde',
							'class'		=> 'form-control',
							'id'		=> 'id_topic_update',
							'name'		=> 'id_topic_update',
					)
			));
			
			$this->add(array(
					'name' => 'id_topic_delete',
					'options' => array(
							'label'		=> '',
					),
					'attributes' => array(
							'type'		=> 'hidde',
							'class'		=> 'form-control',
							'id'		=> 'id_topic_delete',
							'name'		=> 'id_topic_delete',
					)
			));
			
			$this->add(array(
					'name' => 'id_topic_content',
					'options' => array(
							'label'		=> '',
					),
					'attributes' => array(
							'type'		=> 'hidde',
							'class'		=> 'form-control',
							'id'		=> 'id_topic_content',
							'name'		=> 'id_topic_content',
					)
			));
			
			$this->add(array(
					'name' => 'id_topic',
					'options' => array(
							'label'		=> '',
					),
					'attributes' => array(
							'type'		=> 'hidde',
							'class'		=> 'form-control',
							'id'		=> 'id_topic',
							'name'		=> 'id_topic',
					)
			));
			
// 			/////////////////////   Extra Lines   ////////////////////

			$this->add(array(
					'name' => 'id_topic_export',
					'options' => array(
							'label'		=> '',
					),
					'attributes' => array(
							'type'		=> 'hidde',
							'class'		=> 'form-control',
							'id'		=> 'id_topic_export',
							'name'		=> 'id_topic_export',
					)
			));
			
			$this->add(array(
					'name' => 'id_topic_exportArray',
					'options' => array(
							'label'		=> '',
					),
					'attributes' => array(
							'type'		=> 'hidde',
							'class'		=> 'form-control',
							'id'		=> 'id_topic_exportArray',
							'name'		=> 'id_topic_exportArray',
					)
			));
			
			$this->add(array(
					'name' => 'id_topic_ExportParents',
					'options' => array(
							'label'		=> '',
					),
					'attributes' => array(
							'type'		=> 'hidde',
							'class'		=> 'form-control',
							'id'		=> 'id_topic_ExportParents',
							'name'		=> 'id_topic_ExportParents',
					)
			));
			
			$this->add(array(
					'name' => 'id_topic_ExportContents',
					'options' => array(
							'label'		=> '',
					),
					'attributes' => array(
							'type'		=> 'hidde',
							'class'		=> 'form-control',
							'id'		=> 'id_topic_ExportContents',
							'name'		=> 'id_topic_ExportContents',
					)
			));
			
		}
	}