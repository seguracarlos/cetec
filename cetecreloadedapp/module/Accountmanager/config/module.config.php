<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'Accountmanager\Controller\Index' => 'Accountmanager\Controller\IndexController'
        ),
    ),  
    'router' => array(
        'routes' => array(
            'accountmanager' => array(
                'type' => 'Segment',
                    'options' => array(     
                        'route' => '/accountmanager[/[:action]][/[:id]]',
                        'constraints' => array(
                            'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        ),
                        'defaults'  =>  array(
                            'controller' => 'Accountmanager\Controller\Index',
                            'action'     => 'index'
                        ),
                    ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_map' => array(
            'accountmanager/index/index' => __DIR__ . '/../view/accountmanager/index/index.phtml'
        ),
        'template_path_stack' => array(
        	'accountmanager' => __DIR__ . '/../view'
        )
    )
);
