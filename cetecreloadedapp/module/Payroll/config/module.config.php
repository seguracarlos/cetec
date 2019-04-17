<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'Payroll\Controller\Index' => 'Payroll\Controller\IndexController'
        ),
    ),  
    'router' => array(
        'routes' => array(
            'payroll' => array(
                'type' => 'Segment',
                    'options' => array(     
                        'route' => '/payroll[/[:action]][/[:id]]',
                        'constraints' => array(
                            'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        ),
                        'defaults'  =>  array(
                            'controller' => 'Payroll\Controller\Index',
                            'action'     => 'index'
                        ),
                    ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_map' => array(
            'payroll/index/index' => __DIR__ . '/../view/payroll/index/index.phtml'
        ),
        'template_path_stack' => array(
        	'payroll' => __DIR__ . '/../view'
        )
    )
);
