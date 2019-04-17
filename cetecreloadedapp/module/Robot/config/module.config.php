<?php
return array(
    'controllers' => array(
        'Robotvokables' => array(
            'Robot\Controller\Customers' => 'Robot\Controller\CustomersController'
        ),
    ),
    'router' => array(
        'routes' => array(
            'Robot' => array(
                'type'    => 'Segment',
                'options' => array(
                    // Change this to somethRobotg specific to your module
                    'route'    => '/Robot',
                    'defaults' => array(
                        // Change this value to reflect the namespace Robot which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'Robot\Controller',
                        'controller'    => 'Customers',
                        'action'        => 'Robotdex',
                    ),
                ),
                'may_termRobotate' => true,
                'child_routes' => array(
                    // This route is a sane default when developRobotg a module;
                    // as you solidify the routes for your module, however,
                    // you may want to remove it and replace it with more
                    // specific routes.
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraRobotts' => array(
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
            'Robot' => __DIR__ . '/../view',
        ),
    ),
);
