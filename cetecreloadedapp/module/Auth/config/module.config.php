<?php
return array(
    'router' => array(
        'routes' => array(
            'login' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/login',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Auth\Controller',
                        'controller' => 'Index',
                        'action' => 'index'
                    )
                )
            ),
            'logout' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/logout',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Auth\Controller',
                        'controller' => 'Index',
                        'action' => 'logout'
                    )
                )
            )
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'Auth\Controller\Index' => 'Auth\Controller\IndexController'
        )
    ),
    'view_manager' => array(
        'template_map' => array(
        	//'layout/layout'           => __DIR__ . '/../view/layout/layoutLogin.phtml',
            'auth/index/index' => __DIR__ . '/../view/auth/index/index.phtml'
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view'
        )
    )
);

