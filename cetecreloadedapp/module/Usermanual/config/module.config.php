<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'Usermanual\Controller\Index'		=> 'Usermanual\Controller\IndexController',
            'Usermanual\Controller\Contents'	=> 'Usermanual\Controller\ContentsController',
            'Usermanual\Controller\Topics'	=> 'Usermanual\Controller\TopicsController',
        ),
    ),  
    'router' => array(
        'routes' => array(
            'usermanual' => array(
                'type' => 'Segment',
                    'options' => array(     
                        'route' => '/usermanual[/[:action]][/[:m]]',
                        'constraints'  => array(
                            'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        ),
                        'defaults'  =>  array(
                            'controller' => 'Usermanual\Controller\Index',
                            'action'     => 'index'
                        ),
                    ),
            ),
        	'contents' => array(
                'type' => 'Segment',
                    'options' => array(     
                        'route' => '/contents[/[:action]][/[:id]]',
                        'constraints'  => array(
                            'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        ),
                        'defaults'  =>  array(
                            'controller' => 'Usermanual\Controller\Contents',
                            'action'     => 'contents'
                        ),
                    ),
            ),
        	'topics' => array(
                'type' => 'Segment',
                    'options' => array(     
                        'route' => '/topics[/[:action]][/[:id]]',
                        'constraints'  => array(
                            'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        ),
                        'defaults'  =>  array(
                            'controller' => 'Usermanual\Controller\Topics',
                            'action'     => 'index'
                        ),
                    ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_map' => array(
            'usermanual/index/index'		=> __DIR__ . '/../view/usermanual/index/index.phtml',
        	'usermanual/add'				=> __DIR__ . '/../view/usermanual/index/add.phtml',
        	'usermanual/contents/contents'	=> __DIR__ . '/../view/usermanual/contents/index.phtml'
        ),
        'template_path_stack' => array(
        	'usermanual' => __DIR__ . '/../view'
        )
    )
);
