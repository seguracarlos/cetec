<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'Advertising\Controller\Index'		=> 'Advertising\Controller\IndexController',
        ),
    ),  
    'router' => array(
        'routes' => array(
            'advertising' => array(
                'type' => 'Segment',
                    'options' => array(     
                        'route' => '/advertising[/[:action]][/[:id]/[:m]]',
                        'constraints'  => array(
                            'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        ),
                        'defaults'  =>  array(
                            'controller' => 'Advertising\Controller\Index',
                            'action'     => 'index'
                        ),
                    ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_map' => array(
            'advertising/index/index'		=> __DIR__ . '/../view/advertising/index/index.phtml',
        	'advertising/class'				=> __DIR__ . '/../view/advertising/index/class.phtml',
//         	'advertising/advertising/advertising'	=> __DIR__ . '/../view/advertising/advertising/index.phtml'
        ),
        'template_path_stack' => array(
        	'advertising' => __DIR__ . '/../view'
        )
    )
);
