<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'Cash\Controller\Index' => 'Cash\Controller\IndexController'
        ),
    ),  
    'router' => array(
        'routes' => array(
            'cash' => array(
                'type' => 'Segment',
                    'options' => array(     
                        'route' => '/cash[/[:action]][/[:id]]',
                        'constraints' => array(
                            'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        ),
                        'defaults'  =>  array(
                            'controller' => 'Cash\Controller\Index',
                            'action'     => 'index'
                        ),
                    ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_map' => array(
            'cash/index/index' => __DIR__ . '/../view/cash/index/index.phtml'
        ),
        'template_path_stack' => array(
        	'cash' => __DIR__ . '/../view'
        )
    )
);
