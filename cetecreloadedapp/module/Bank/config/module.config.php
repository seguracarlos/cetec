<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'Inventory\Controller\Index' => 'Inventory\Controller\IndexController'
        ),
    ),  
    'router' => array(
        'routes' => array(
            'inventory' => array(
                'type' => 'Segment',
                    'options' => array(     
                        'route' => '/inventory[/[:action]][/[:id]]',
                        'constraints' => array(
                            'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        ),
                        'defaults'  =>  array(
                            'controller' => 'Inventory\Controller\Index',
                            'action'     => 'index'
                        ),
                    ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_map' => array(
            'inventory/index/index' => __DIR__ . '/../view/inventory/index/index.phtml'
        ),
        'template_path_stack' => array(
        	'inventory' => __DIR__ . '/../view'
        )
    )
);
