<?php
namespace Users\Form;

use Zend\InputFilter\InputFilter;

class UserFormValidator extends InputFilter
{

    public function __construct()
    {
        $isEmpty      = \Zend\Validator\NotEmpty::IS_EMPTY;
        $invalidEmail = \Zend\Validator\EmailAddress::INVALID_FORMAT;
        
        $this->add(array(
            'name' => 'name',
            'required' => 'required',
            'filters' => array(
//Cuidado con StripTags y HtmlEntities puede ser que no nos validen texto con tildes o eñes
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array (
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => '5',
                        'max' => '15',
                        'messages' => array(
                        \Zend\Validator\StringLength::INVALID=>'Tu nombre esta mal',
                        \Zend\Validator\StringLength::TOO_SHORT=>'Tu nombre debe ser de más de 5 letras',
                        \Zend\Validator\StringLength::TOO_LONG=>'Tu nombre debe ser de menos de 15 letras',
                        ),
                    ),
                )  
        )));
        /*$this->add(array(
            'name' => 'email',
            'required' => true,
            'filters' => array(
                array(
                    'name' => 'StripTags'
                ),
                array(
                    'name' => 'StringTrim'
                )
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            $isEmpty => 'Email no puede estar vacio.'
                        )
                    ),
                    'break_chain_on_failure' => true
                ),
                array(
                    'name' => 'EmailAddress',
                    'options' => array(
                        'messages' => array(
                            $invalidEmail => 'Ingrese Direccion de email valida.'
                        )
                    )
                )
            )
        ));
        
        $this->add(array(
            'name' => 'password',
            'required' => true,
            'filters' => array(
                array(
                    'name' => 'StripTags'
                ),
                array(
                    'name' => 'StringTrim'
                )
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            $isEmpty => 'La contraseña no puede estar vacia.'
                        )
                    )
                )
            )
        ));*/
    }
}