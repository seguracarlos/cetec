<?php
return array(
	'controllers' => array(
    	'invokables' => array(
        	'Exams\Controller\Exam' => 'Exams\Controller\ExamController',
    		'Exams\Controller\Examquestions' => 'Exams\Controller\ExamquestionsController',
		),
	),

	'router' => array(
		'routes' => array(

			'exams' => array(
				'type'    => 'Literal',
				'options' => array(
					'route'    => '/exams',
					'defaults' => array(
						'__NAMESPACE__' => 'Exams\Controller',
						'controller'    => 'Exam',
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
        	'exams' => __DIR__ . '/../view',
		),
	),

 );