<?php
namespace Horus\Form;

use Zend\InputFilter\InputFilter;

class InventoryFormValidator extends InputFilter
{

    public function __construct()
    {
        //$isEmpty      = \Zend\Validator\NotEmpty::IS_EMPTY;
        
        $this->add(array(
            'name' => 'serialnumber',
            //'required' => 'required',
            'filters' => array(
			//Cuidado con StripTags y HtmlEntities puede ser que no nos validen texto con tildes o eñes
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            /*'validators' => array(
                array (
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => '1',
                        'max' => '11',
                        'messages' => array(
                        \Zend\Validator\StringLength::INVALID=>'El no. de serie esta mal',
                        \Zend\Validator\StringLength::TOO_SHORT=>'El no. de serie debe ser de más de 1 letras',
                        \Zend\Validator\StringLength::TOO_LONG=>'El no. de serie debe ser de menos de 11 letras',
                        ),
                    ),
                )  
        	)*/
        ));
    }
}