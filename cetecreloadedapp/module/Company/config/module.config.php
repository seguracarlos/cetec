<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'Company\Controller\Index' => 'Company\Controller\IndexController'
        ),
    ),  
    'router' => array(
        'routes' => array(
            'company' => array(
                'type' => 'Segment',
                    'options' => array(     
                        'route' => '/company[/[:action]][/:id]',
                        'constraints' => array(
                            'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        ),
                        'defaults'  =>  array(
                            'controller' => 'Company\Controller\Index',
                            'action'     => 'index'
                        ),
                    ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_map' => array(
            'company/index/index' => __DIR__ . '/../view/company/index/index.phtml'
        ),
        'template_path_stack' => array(
        	'company' => __DIR__ . '/../view'
        )
    )
);
