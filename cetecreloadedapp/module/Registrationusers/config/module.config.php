<?php
return array(
		'controllers' => array(
				'invokables' => array(
						'Registrationusers\Controller\Register'      => 'Registrationusers\Controller\RegisterController',
				),
		),

		'router' => array(
				'routes' => array(

						'registrationusers' => array(
								'type'    => 'Literal',
								'options' => array(
										'route'    => '/registrationusers',
										'defaults' => array(
												'__NAMESPACE__' => 'Registrationusers\Controller',
												'controller'    => 'Register',
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
						'registrationusers' => __DIR__ . '/../view',
				),
		),

);