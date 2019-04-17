<?php
namespace Inventory\Form;
 
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Form\Factory;
use Inventory\Services\TypesServices;
use Inventory\Services\InventoryStateServices;
 
class InventoryForm extends Form
{
    public function __construct($name = null)
     {
        parent::__construct($name);
         
       $this->setInputFilter(new \Inventory\Form\InventoryFormValidator());
         
       $this->setAttributes(array(
       		'action'=>"",
            'method' => 'post'
        ));
       
       /*$this->add(array(
       		'name' => 'id_inventory',
       		'attributes' => array(
       			'type' => 'hidden'
       		)
       ));*/
        
       	/*No serie*/
		$this->add(array(
            'name' => 'serialnumber',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control'
            )
        ));
        
        /*Monto*/
        $this->add(array(
        		'name' => 'amount',
        		'attributes' => array(
        				'type' => 'text',
        				'class' => 'form-control'
        		)
        ));
        
        /*Marca*/
        $this->add(array(
        		'name' => 'brand',
        		'attributes' => array(
        				'type' => 'text',
        				'class' => 'form-control'
        		)
        ));
        
        /*Material*/
        $this->add(array(
        		'name' => 'material',
        		'attributes' => array(
        				'type' => 'text',
        				'class' => 'form-control'
        		)
        ));
        
        /*Estado*/
        $this->add(array(
        	'name' => 'state',
        	'type' => 'Select',
        	'options' => array (
        		'label' => 'Estados Disponibles:',
        		'empty_option' => '--selecciona--',
        		'value_options' => $this->getAllStates()
        	),
        	'attributes' => array(
        		'id'       =>'state',
        		'class' => 'form-control'
        	)
        ));
        
        /*Comentarios*/
        $this->add(array(
        		'name'      => 'comments',
        		'attributes'=> array(
        				'type'  => 'textarea',
        				'class' => 'form-control',
        		)
        ));
        
        /*Tipo*/
        $this->add(array(
        		'name' => 'types_id_types',
        		'type' => 'Select',
        		'options' => array (
        				'label' => 'Tipos Disponibles:',
        				'empty_option' => '--selecciona--',
        				'value_options' => $this->getAllTypes()
        		),
        		'attributes' => array(
        				'id'       =>'state',
        				'class' => 'form-control'
        		)
        ));
 
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(    
            	'id'    => 'saveInventory',
                'type'  => 'submit',
                'value' => 'Guardar',
                'title' => 'Guardar',
                'class' => 'btn btn-success btn-lg btn-block'
            ),
        ));
     }
     
     //Funcion que trae todos los tipos disponibles
     public function getAllTypes()
     {
     	$typeService = new TypesServices();
     	$roles       = $typeService->fetchAll();
     	$result      = array();
     	foreach ($roles as $rol){
     		$result[$rol['id_types']] = $rol['description'];
     	}
     	return $result;
     }
     
     //Funcion que trae todos los estados disponibles
     public function getAllStates()
     {
     	$inventoryStateService = new InventoryStateServices();
     	$roles       = $inventoryStateService->fetchAll();
     	$result      = array();
     	foreach ($roles as $rol){
     		$result[$rol['id_state_Inventory']] = $rol['conditions'];
     	}
     	return $result;
     }
}
 
?>