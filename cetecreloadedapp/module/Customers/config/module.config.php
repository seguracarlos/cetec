<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'Customers\Controller\Index' => 'Customers\Controller\IndexController'
        ),
    ),  
    'router' => array(
        'routes' => array(
            'customers' => array(
                'type' => 'Segment',
                    'options' => array(     
                        'route' => '/customers[/[:action]][/[:id]]',
                        'constraints' => array(
                            'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        ),
                        'defaults'  =>  array(
                            'controller' => 'Customers\Controller\Index',
                            'action'     => 'index'
                        ),
                    ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_map' => array(
            'customers/index/index' => __DIR__ . '/../view/customers/index/index.phtml'
        ),
        'template_path_stack' => array(
        	'customers' => __DIR__ . '/../view'
        )
    )
);
