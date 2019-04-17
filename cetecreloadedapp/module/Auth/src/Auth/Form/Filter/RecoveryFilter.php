<?php
namespace Auth\Form\Filter;

use Zend\InputFilter\InputFilter;

class RecoveryFilter extends InputFilter
{

    public function __construct()
    {
        $isEmpty = \Zend\Validator\NotEmpty::IS_EMPTY;
        
        
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
        ));
        
        $this->add(array(
        		'name' => 'repeatpassword',
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
        ));
    }
}