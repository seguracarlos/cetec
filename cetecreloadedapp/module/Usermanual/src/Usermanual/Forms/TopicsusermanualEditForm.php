<?php
	namespace Usermanual\Forms;
	
	use Zend\Form\Form;
	class TopicsusermanualEditForm extends Form{

		public function init(){
			$id_topic=new Zend_Form_Element_Hidden('id_topic');
			$id_topic->addFIlter('Int');
			
			$id_topicUpdate=new Zend_Form_Element_Hidden("id_topic_update");
			$id_topicUpdate->setAttrib('id', 'id_topic_update')
			->setAttrib('name', 'id_topic_update')
			->setValue(0);
			
			$topic_name=new Zend_Form_Element_Text("topic_name");
			$topic_name->setLabel('Nombre del elemtento:')->setRequired(true)
			->addFilter('StripTags')->addFilter('StringTrim')->addValidator('NotEmpty')
			->setAttrib('class','form-control');
			
			$type=new Zend_Form_Element_Text("type");
			$type->setLabel('Tipo de elemento:')
			->setAttrib('disabled','true')
			->setAttrib('class','form-control');

			$submit=new Zend_Form_Element_Submit('Guardar');
			$submit->setAttrib('id','submitbutton')
			->setAttrib('class','btn btn-lg btn-success')
			->setAttrib('style','width:90%');

			$cancel=new Zend_Form_Element_Button('Cancelar');
			$cancel->setAttrib('id','cancel')
			->setAttrib('class','btn btn-lg btn-danger')
			->setAttrib('style','width:90%');

// 			$this->addElements(array($id_topic,$id_topicUpdate,$topic_name,$type,$submit,$cancel));
		}
	}
?>