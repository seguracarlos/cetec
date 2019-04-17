<?php
return array(
	'controllers' => array(
    	'invokables' => array(
        	'In\Controller\Customers'        => 'In\Controller\CustomersController',
    		'In\Controller\Crm'              => 'In\Controller\CrmController',
    		'In\Controller\Projects'         => 'In\Controller\ProjectsController',
    		'In\Controller\ProspectProjects' => 'In\Controller\ProspectProjectsController',
    		'In\Controller\Quotes'           => 'In\Controller\QuotesController',
    		'In\Controller\Cxc'              => 'In\Controller\CxcController',
    		'In\Controller\Services'         => 'In\Controller\ServicesController',
    		'In\Controller\Journey'          => 'In\Controller\JourneyController',
    		'In\Controller\Destinations'     => 'In\Controller\DestinationsController',
    	),
	),
	
	'router' => array(
		'routes' => array(
		
			'in' => array(
				'type'    => 'Literal',
				'options' => array(
					'route'    => '/in',
					'defaults' => array(
						'__NAMESPACE__' => 'In\Controller',
						'controller'    => '',
						'action'        => '',
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
        	'in' => __DIR__ . '/../view',
		),
	),
	
 );