<?php
return array(
	// Controladores generados en cada modulo.
	'controllers' => array(
    	'invokables' => array(
        	'System\Controller\Permissions' => 'System\Controller\PermissionsController',
    		'System\Controller\Users'       => 'System\Controller\UsersController',
    		'System\Controller\Profile'     => 'System\Controller\ProfileController',
    		'System\Controller\Company'     => 'System\Controller\CompanyController',
    		'System\Controller\Preferences' => 'System\Controller\PreferencesController',
		),
	),
	
	// Lo siguiente es una ruta para simplificar la creaci�n de
	// Nuevos controladores y actions sin necesidad de crear una nueva para cada uno
	// Simplemente se coloquan los nuevos controladores , y se puede acceder a ellos
	// Usando la ruta  / modulo /: controller /: action
	// Se genera una ruta padre y se india que contendra hijos 
	'router' => array(
		'routes' => array(
		
			'system' => array(
				'type'    => 'Literal',
				'options' => array(
					'route'    => '/system',
					'defaults' => array(
						'__NAMESPACE__' => 'System\Controller',
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

	'view_manager' => array(
    	'template_path_stack' => array(
        	'system' => __DIR__ . '/../view',
		),
	),
	
 );