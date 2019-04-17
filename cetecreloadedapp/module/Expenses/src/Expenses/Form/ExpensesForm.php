<?php
namespace Expenses\Form;
 
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Form\Factory;
 
class ExpensesForm extends Form
{
    public function __construct($name = null)
     {
        parent::__construct($name);
         
       //$this->setInputFilter(new \Expenses\Form\ExpensesFormValidator());
         
       $this->setAttributes(array(
       		'action'=>"",
            'method' => 'post'
        ));
       
       /*id de gasto*/
       $this->add(array(
       		'name' => 'idExpenses',
       		'attributes' => array(
       			'type' => 'hidden'
       		)
       ));
        
       	/*Fecha*/
		$this->add(array(
            'name' => 'date_of_expenses',
            'attributes' => array(
                'type'   => 'text',
                'class'  => 'form-control input-lg',
            	'id'     => 'dateExpenses'
            )
        ));
        
        /*Monto del gasto*/
        $this->add(array(
        		'name' => 'amount_of_expenses',
        		'attributes' => array(
        				'type'  => 'text',
        				'class' => 'form-control input-lg',
        				'id'    =>'amount_of_expenses'
        		)
        ));
       
        /*Tipo de gasto*/
        $this->add(array(
        	'name' => 'reference_of_expenses',
        	'type' => 'Select',
        	'options' => array (
        		'label' => 'Tipo:',
        		'empty_option' => '--seleccione--',
        		'value_options' => array(
        				"1" => "Gasto fijo",
        				"2" => "Gasto variable"
        		)
        	),
        	'attributes' => array(
        		'id'    =>'reference_of_expenses',
        		'class' => 'form-control input-lg'
        	)
        ));
        
       /*Causas de pago*/
        $this->add(array(
        		'name' => 'expenses_type',
        		'type' => 'Select',
        		'options' => array (
        				'label' => 'Causas de pago:',
        				'empty_option' => '--seleccione--',
        				'value_options' => array(
        					"1" => "Gastos administrativos",
        					"2" => "Gastos de producci�n",
        					"3" => "Gastos diversos"
        				)
        		),
        		'attributes' => array(
        				'id'       =>'expenses_type',
        				'class' => 'form-control input-lg'
        		)
        ));
        
        /*Comentarios*/
        $this->add(array(
        		'name'      => 'description_of_expenses',
        		'attributes'=> array(
        				'type'  => 'textarea',
        				'class' => 'form-control input-lg',
        		)
        ));
 
        $this->add(array(
            'name' => 'submitbutton',
            'attributes' => array(    
            	'id'    => 'saveExpenses',
                'type'  => 'submit',
                'value' => 'Guardar',
                'title' => 'Guardar',
                'class' => 'btn btn-success btn-lg btn-block'
            ),
        ));
     }
}
 
?>