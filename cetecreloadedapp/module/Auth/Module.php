<?php
namespace Auth;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Authentication\Adapter\DbTable as DbAuthAdapter;
use Zend\Session\Container;
use Auth\Model\User;
use Auth\Model\UserRole;
use Auth\Model\PermissionTable;
use Auth\Model\ResourceTable;
use Auth\Model\RolePermissionTable;
use Auth\Model\PreferencesTable;
use Zend\Authentication\AuthenticationService;
use Auth\Model\Role;
use Auth\Utility\Acl;
use System\Model\PreferencesModel;

class Module
{

    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $eventManager->attach(MvcEvent::EVENT_DISPATCH, array(
            $this,
            'boforeDispatch'
        ), 100);
        
    }

    function boforeDispatch(MvcEvent $event)
    {
        $request  = $event->getRequest();
        $response = $event->getResponse();
        $target   = $event->getTarget();

        $whiteList = array(
            'Auth\Controller\Index-index',
            'Auth\Controller\Index-logout',
        	'Registrationusers\Controller\Register-registrationform',
        	'Registrationusers\Controller\Register-index',
        	'Recoverypass\Controller\Recovery-validatemail',
        	'Recoverypass\Controller\Recovery-recoverypass'
        );

        $requestUri        = $request->getRequestUri();
        $controller        = $event->getRouteMatch()->getParam('controller');
        $action            = $event->getRouteMatch()->getParam('action');
        $requestedResourse = $controller . "-" . $action;
        $session           = new Container('User');

        //Comprueba que existe la clave en la sesion:
        if ($session->offsetExists('email')) {
            if ($requestedResourse == 'Auth\Controller\Index-index' || in_array($requestedResourse, $whiteList)) {
                $url = '/Ctec/cetecreloaded/system/profile/index';
                $response->setHeaders($response->getHeaders()
                    	 ->addHeaderLine('Location', $url));
                $response->setStatusCode(302);
            } else {
                $serviceManager = $event->getApplication()->getServiceManager();
                $userRole       = $session->offsetGet('roleName');
                $acl            = $serviceManager->get('Acl');
                $acl->initAcl();

                $status = $acl->isAccessAllowed($userRole, $controller, $action);

                if (!$status) {
                    die('Permiso denegado');
                }
            }
        } else {
        	if ($requestedResourse != 'Auth\Controller\Index-index' && ! in_array($requestedResourse, $whiteList)) {
                $url = '/Ctec/cetecreloaded/login';
                $response->setHeaders($response->getHeaders()
                    ->addHeaderLine('Location', $url));
                $response->setStatusCode(302);
            }
            $response->sendHeaders();
        }
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
                )
            )
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'AuthService' => function ($serviceManager)
                {
                    $adapter = $serviceManager->get('Zend\Db\Adapter\Adapter');
                    $dbAuthAdapter = new DbAuthAdapter($adapter, 'iof_users', 'email', 'password');
                    // get select object (by reference)
                    $select = $dbAuthAdapter->getDbSelect();
                    $select->where('canlogin = 1');
                    $auth = new AuthenticationService();
                    $auth->setAdapter($dbAuthAdapter);
                    return $auth;
                },
                'Acl' => function ($serviceManager)
                {
                    return new Acl();
                },
                'UserTable' => function ($serviceManager)
                {
                    return new User($serviceManager->get('Zend\Db\Adapter\Adapter'));
                },
                'RoleTable' => function ($serviceManager)
                {
                    return new Role($serviceManager->get('Zend\Db\Adapter\Adapter'));
                },
                'UserRoleTable' => function ($serviceManager)
                {
                    return new UserRole($serviceManager->get('Zend\Db\Adapter\Adapter'));
                },
                'PermissionTable' => function ($serviceManager)
                {
                    return new PermissionTable($serviceManager->get('Zend\Db\Adapter\Adapter'));
                },
                'ResourceTable' => function ($serviceManager)
                {
                    return new ResourceTable($serviceManager->get('Zend\Db\Adapter\Adapter'));
                },
                'RolePermissionTable' => function ($serviceManager)
                {
                    return new RolePermissionTable($serviceManager->get('Zend\Db\Adapter\Adapter'));
                },
                'PreferencesTable' => function ($serviceManager)
                {
                	return new PreferencesTable($serviceManager->get('Zend\Db\Adapter\Adapter'));
                }
            )
        );
    }
}