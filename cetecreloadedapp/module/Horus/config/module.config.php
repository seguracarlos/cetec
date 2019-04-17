<?php
return array(
	'controllers' => array(
    	'invokables' => array(
        	'Horus\Controller\Accountmanager' => 'Horus\Controller\AccountmanagerController',
    		'Horus\Controller\Activities'     => 'Horus\Controller\ActivitiesController',
    		'Horus\Controller\Bank'           => 'Horus\Controller\BankController',
    		'Horus\Controller\Inventory'      => 'Horus\Controller\InventoryController',
    		'Horus\Controller\Monitoring'     => 'Horus\Controller\MonitoringController',
    		'Horus\Controller\Employee'       => 'Horus\Controller\EmployeeController',
    		'Horus\Controller\Cash'           => 'Horus\Controller\CashController',
    		'Horus\Controller\Welcome'        => 'Horus\Controller\WelcomeController',
    		
    		'Horus\Controller\Payroll' => 'Horus\Controller\PayrollController',
		),
	),
	
	'router' => array(
		'routes' => array(
		
			'horus' => array(
				'type'    => 'Literal',
				'options' => array(
					'route'    => '/horus',
					'defaults' => array(
						'__NAMESPACE__' => 'Horus\Controller',
						'controller'    => '',
						'action'        => '',
					),
				),
				'may_terminate' => true,
				'child_routes' => array(
					'default' => array(
						'type'    => 'Segment',
						'options' => array(
							'route'    => '/[:controller[/:action]][/:id][/:date]',
							'constraints' => array(
								'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
								'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
								'id'         => '[a-zA-Z0-9\-_]+',
								'date'       => '[a-zA-Z0-9\-_]+',
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
        	'horus' => __DIR__ . '/../view',
		),
	),
	
 );