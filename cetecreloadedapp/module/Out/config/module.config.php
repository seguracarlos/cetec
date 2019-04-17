<?php
return array(
	'controllers' => array(
    	'invokables' => array(
        	'Out\Controller\Expenses' => 'Out\Controller\ExpensesController'
		),
	),

	'router' => array(
		'routes' => array(

			'out' => array(
				'type'    => 'Literal',
				'options' => array(
					'route'    => '/out',
					'defaults' => array(
						'__NAMESPACE__' => 'Out\Controller',
						'controller'    => 'Expenses',
						'action'        => 'customsponsorship',
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
        	'out' => __DIR__ . '/../view',
		),
	),

 );