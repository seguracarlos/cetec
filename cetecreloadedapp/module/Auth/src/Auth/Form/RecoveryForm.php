<?php
namespace Auth\Form;

use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Form\Element\Csrf;

class RecoveryForm extends Form
{

    public function __construct($name)
    {
        parent::__construct($name);
        $this->setAttribute('method', 'post');
        
        $this->add(array(
            'name' => 'password',
            'type' => 'password',
            'attributes' => array(
                'id' => 'password',
                'class' => 'form-control input-lg',
                'placeholder' => 'Nueva Contraseña',
            	'required'    => 'required'
            ),
            'options' => array(
                'label' => 'Contraseña',
            )
        ));
        
        $this->add(array(
            'name' => 'newpass',
            'type' => 'password',
            'attributes' => array(
                'id' => 'newpass',
                'class' => 'form-control input-lg',
                'placeholder' => 'Confirmar Contraseña',
            	'required'    => 'required'
            		
            ),
            'options' => array(
                'label' => 'Contraseña',                
            )
        ));
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Csrf',
            'name' => 'loginCsrf',
            'options' => array(
                'csrf_options' => array(
                    'timeout' => 3600
                )
            )
        ));
        
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Guardar',
                'class' => 'btn btn-lg btn-blue btn-block',
            	'id'    => 'send'
            )
        ));
    }
}