<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'Student\Controller\Index'		=> 'Student\Controller\IndexController',
            'Student\Controller\Thesis'     => 'Student\Controller\ThesisController',
        ),
    ),
    'router' => array(
        'routes' => array(
        
            'student' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/student',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Student\Controller',
                        'controller'    => '',
                        'action'        => '',
                    ),
                ),
                'may_terminate' => true,
                'child_routes'  => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'       => '/[:controller[/:action]][/:id]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'         => '[0-9]+',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),


/*

return array(
    'router' => array(
        'routes' => array(
            'forecaster' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/forecaster',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Forecaster\Controller',
                        'controller'    => 'Forecaster',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'earnings-period' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/earnings/[:period]/[:type]',
                            'constraints' => array(
                                'period' => '(this|previous|last|next|current)',
                                'type' => '(week|month|quarter|year)'
                            ),
                            'defaults' => array(
                                'controller' => 'earnings',
                                'action' => 'view'
                            )
                        )
                    ),
                    'pipeline-period' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/pipeline/[:period]/[:type]',
                            'constraints' => array(
                                'period' => '(this|previous|last|next|current)',
                                'type' => '(week|month|quarter|year)'
                            ),
                            'defaults' => array(
                                'controller' => 'pipeline',
                                'action' => 'view'
                            )
                        )
                    ),
                ),
            ),
        ),
    ),


*/

    'view_manager' => array(
        'template_path_stack' => array(
            'student' => __DIR__ . '/../view',
        ),
          'template_map' => array(
          //  'layout/layout'        => __DIR__ . '/../view/layout/layout.phtml', 
        ),
/*        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),*/
    )
);

    /*
    'router' => array(
        'routes' => array(
            'student' => array(
                'type' => 'Segment',
                    'options' => array(     
                        'route' => '/student[/[:action]][/[:id]/[:m]]',
                        'constraints'  => array(
                            'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        ),
                        'defaults'  =>  array(
                            'controller' => 'Student\Controller\Index',
                            'action'     => 'index'
                        ),
                    ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_map' => array(
            'student/index/index'		=> __DIR__ . '/../view/student/index/index.phtml',
        	'student/class'				=> __DIR__ . '/../view/student/index/class.phtml',
         	'student/thesis/index'	=> __DIR__ . '/../view/student/thesis/index.phtml'
        ),
        'template_path_stack' => array(
        	'student' => __DIR__ . '/../view'
        )
    )
    */

