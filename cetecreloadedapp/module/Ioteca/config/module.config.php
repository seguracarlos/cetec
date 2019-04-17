<?php
return array(
	'controllers' => array(
    	'invokables' => array(
        	'Ioteca\Controller\Books'      => 'Ioteca\Controller\BooksController',
    		'Ioteca\Controller\Components' => 'Ioteca\Controller\ComponentsController',
    		'Ioteca\Controller\Courses'    => 'Ioteca\Controller\CoursesController',
    		'Ioteca\Controller\Link'       => 'Ioteca\Controller\LinkController',
    		'Ioteca\Controller\Tutorial'   => 'Ioteca\Controller\TutorialController',
		),
	),
	
	'router' => array(
		'routes' => array(
		
			'ioteca' => array(
				'type'    => 'Literal',
				'options' => array(
					'route'    => '/ioteca',
					'defaults' => array(
						'__NAMESPACE__' => 'Ioteca\Controller',
						'controller'    => 'Books',
						'action'        => 'index',
					),
				),
				'may_terminate' => true,
				'child_routes' => array(
					'default' => array(
						'type'    => 'Segment',
						'options' => array(
							'route'    => '/[:controller[/:action]][/:id]',
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

	'view_manager' => array(
    	'template_path_stack' => array(
        	'ioteca' => __DIR__ . '/../view',
		),
	),
	
 );