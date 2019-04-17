<?php
return array(
	'controllers' => array(
    	'invokables' => array(
        	'Classes\Controller\Class' => 'Classes\Controller\ClassController',
    		'Classes\Controller\Studentnotes' => 'Classes\Controller\StudentnotesController',
		),
	),

	'router' => array(
		'routes' => array(

			'classes' => array(
				'type'    => 'Literal',
				'options' => array(
					'route'    => '/classes',
					'defaults' => array(
						'__NAMESPACE__' => 'Classes\Controller',
						'controller'    => 'Class',
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
        	'classes' => __DIR__ . '/../view',
		),
	),

 );