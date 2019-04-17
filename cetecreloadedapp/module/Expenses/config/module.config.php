<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'Expenses\Controller\Index' => 'Expenses\Controller\IndexController'
        ),
    ),  
    'router' => array(
        'routes' => array(
            'expenses' => array(
                'type' => 'Segment',
                    'options' => array(     
                        'route' => '/expenses[/[:action]][/[:id]]',
                        'constraints' => array(
                            'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        ),
                        'defaults'  =>  array(
                            'controller' => 'Expenses\Controller\Index',
                            'action'     => 'index'
                        ),
                    ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_map' => array(
            'expenses/index/index' => __DIR__ . '/../view/expenses/index/index.phtml'
        ),
        'template_path_stack' => array(
        	'expenses' => __DIR__ . '/../view'
        )
    )
);
