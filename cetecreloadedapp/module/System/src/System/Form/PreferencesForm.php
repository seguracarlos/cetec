<?php

class PreferencesForm extends Zend_Form {

	private $vScript = array(
                'ViewHelper','Errors'
            );
	
    public function __construct($prefer) {
  
        $this->setDecorators(array(
			array('ViewScript', array('viewScript' => 'preferences/viewFormPreferences/ViewForm.phtml')),
							'Form'
		));
    	
    	$id = new Zend_Form_Element_Hidden('id');
    
    	if($prefer == 1){
    		$valueSelect = new Zend_Form_Element_Select('value');
    		$valueSelect->setDecorators($this->vScript);
    		$valueSelect->setMultiOptions(array(0 => 'selecciona',
    											"Activo" => 'Activo',
    											"Inactivo" => 'Inactivo'));
    		$this->addElement($valueSelect);
    	}else{
    		$value = new Zend_Form_Element_Text('value');
    		$value->setLabel("* Valor nuevo")
    		  ->setDecorators($this->vScript)
    		  ->setRequired(true)
    		  //->setAttrib('onkeydown', 'return numeric(event)')
    		  ->setAttrib('maxlength', '5');
    		$this->addElement($value);
    	}
    	
    	$submit = new Zend_Form_Element_Button('guardar');
    	$submit
	    	->setDecorators($this->vScript)
	    	->setAttrib("class", "btn btn-primary")
	    	->setLabel('Guardar');
    	 
		$cancel = new Zend_Form_Element_Button('Cancelar');
		$cancel->setDecorators($this->vScript);
		$cancel->setAttrib("class", "btn btn-danger");
		$this->addElement($cancel);
    	
    	$this->addElements(array($submit));
    	
   		$this->addElements(array($id));
   		$this->addAttribs(array("id"=>"preferencesForm"));
   		
    }
}

