<?php
namespace Auth\Form;

use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Form\Element\Csrf;

class LoginForm extends Form
{

    public function __construct($name)
    {
        parent::__construct($name);
        $this->setAttribute('method', 'post');
        
        $this->add(array(
            'name' => 'email',
            'type' => 'text',
            'attributes' => array(
                'id' => 'email',
                'class' => 'form-control input-lg input-required',
                'placeholder' => 'example@example.com'
            ),
            'options' => array(
                'label' => 'Email',
            )
        ));
        
        $this->add(array(
            'name' => 'password',
            'type' => 'password',
            'attributes' => array(
                'id' => 'password',
                'class' => 'form-control input-lg input-required',
                'placeholder' => '**********'
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
        		'name' => 'try',
        		'type' => 'hidden',
        		'attributes' => array(
        				'id' => 'try',
        				'class' => 'form-control input-lg',
        		),
        ));
        
        $this->add(array(
            'name' => 'btn_login',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Ingresar',
                'class' => 'btn btn-lg btn-blue btn-block',
            	'id' => 'btn_login',
            )
        ));
    }
}