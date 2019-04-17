<?php

namespace Iofractal;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Permissions\Model\RoleModel;
use Zend\Session\Container;
use Auth\Utility\Menu;
use Zend\Validator\Explode;
use System\Services\PreferencesService;
include __DIR__.'/src/Iofractal/Constants/Constants.php';

class Module
{
	/*
	 * Arreglos que contienen las rutas del menu, dependiendo permisos 
	 */
	private $pathsistema    = array();
	private $pathsingresos  = array();
	private $pathsegresos   = array();
	private $pathsenlasa    = array();
	private $pathshorus     = array();
	private $horusIconSpace = null;
	
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        $serviceManager = $e->getApplication()->getServiceManager();
        $dbAdapter      = $serviceManager->get('Zend\Db\Adapter\Adapter');
        \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::setStaticAdapter($dbAdapter);
        
        /*
         * Basicamente lo que hacen las siguientes lineas es cargar en cada metodo action de cada controlador 
         * del modulo actual una plantilla diferente consiguiendo listas de un archivo de configuracion con el 
         * array module_layouts
         */
        /*$eventManager->getSharedManager()->attach('Zend\Mvc\Controller\AbstractActionController', 'dispatch', function($e) {
        	$controller      = $e->getTarget();
        	$controllerClass = get_class($controller);
        	$moduleNamespace = substr($controllerClass, 0, strpos($controllerClass, '\\'));
        	$config          = $e->getApplication()->getServiceManager()->get('config');
        	if (isset($config['module_layouts'][$moduleNamespace])) {
        		$controller->layout($config['module_layouts'][$moduleNamespace]);
        	}
        }, 100);*/
        
        $eventManager->attach('dispatch', array($this, 'loadCommonViewVars'), 101);
        $eventManager->attach('dispatch', array($this, 'cargarDatosDePerfil'), 102);
        //$eventManager->attach('dispatch', array($this, 'getConstants'), 103);
        $eventManager->attach('dispatch', array($this, 'loadUtilTools'), 104);
    }
    
    

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
    
	/*public function getConstants(){
    	return include __DIR__.'/Constants/Constants.php';
    }*/
    
    /*
     * Clase Con metodos de ayuda
     */
    public function loadUtilTools(){
    	return include __DIR__.'/Tools/Util_Tools.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
    public function cargarDatosDePerfil(MvcEvent $e)
    {
    	$session = new Container('User');
    	
    	$data    = array(
    		"userId"     => $session->offsetGet('userId'),
    		"id_company" => $session->offsetGet('id_company'),
    		"user_email" => $session->offsetGet('email'),
    		"rol_name"   => $session->offsetGet('roleName'),
    		"user_name"  => $session->offsetGet('name'),
    		"surname"    => $session->offsetGet('surname'),
    		"lastname"   => $session->offsetGet('lastname'),
    		"brand"      => $session->offsetGet('brand'),
    		"logo"       => $session->offsetGet('logo'),
    			"photofile"       => $session->offsetGet('photofile'),
    			"name_job"       => $session->offsetGet('name_job')
    	);
    	
    	$e->getViewModel()->setVariables(
    		array(
    			'userProfile' => $data
    		)
    	);
    }
   
    public function loadCommonViewVars(MvcEvent $e)
    {
    	$session = new Container(
    			'User');
    	 
    	if ($session->offsetExists('email')) {
    		$id_role = $session->offsetGet('roleId');
    	} else {
    		return false;
    	}
    	 
    
    	$menu              = new \System\Services\RoleService();
    	//$permissions  = $menu->getResourcesForMenu($id_role);
    	$permissions       = $menu->getResourcesMenusite($id_role);
    	$allPermisions     = $menu->getAllPermisions($id_role);
    	$que               = $e->getRouteMatch()->getParams();
    	$controller        = $e->getRouteMatch()->getParam('controller');
    	$action            = $e->getRouteMatch()->getParam('action');
    	$requestedResourse = $controller . "-" . $action;
    	$menuHorus         = new \Zend\Session\Container('menuHorus');
    	$this->horusIconSpace = new \Zend\Session\Container('icons');
    	 
    	if (!isset($menuHorus->conlin)) {
    		$menuHorus->conlin = 1; // first time
    		$this->getHorusMenuConfig();
    		$this->getUserPreferences();
    	}
    
    	//$this->createPaths();
    	$currentMenu = "";
    	foreach($allPermisions as $tPermisos){
    		foreach($tPermisos as $curM){
    			if($curM->pathresource == $requestedResourse){
    				$currentMenu = $curM->app;
    			}
    		}
    	}
    	 
    	$mmenu = $menuHorus->horusMenu;
    	ksort($mmenu);
    	$active    = $currentMenu;
    	$iconset   = $this->horusIconSpace->icoset;
    	 
    	$e->getViewModel()->setVariables(
    			array(
    					'roleId'    => $id_role,
    					'active'    => $active,
    					'mmenu'     => $mmenu,
    					'iconset'   => $iconset,
    			)
    	);
    }
    
    private function createPaths()
    {
    	$session = new Container('User');
    	$id_role = $session->offsetGet('roleId');
    	$menu    = new \System\Model\RoleModel();
    	//$permissions = $menu->getResourcesForMenu($id_role);
    	$permissions = $menu->getResourcesMenusite($id_role);
    	$allAppsArray = $permissions;
    
    	echo "<pre>";print_r($allAppsArray);exit;
    	foreach ($allAppsArray['sistema'] as $menu){
    		$this->pathsistema[] = $menu->path;
    	}
    	
    	foreach ($allAppsArray['patrocinadores'] as $menu){
    		$this->pathsingresos[] = $menu->path;
    	}
    	
    	foreach ($allAppsArray['bachillerato'] as $menu){
    		$this->pathsegresos[] = $menu->path;
    	}
    	
    	foreach ($allAppsArray['enlasa'] as $menu){
    		$this->pathsenlasa[] = $menu->path;
    	}
    	
    	foreach ($allAppsArray['cetec'] as $menu){
    		$this->pathshorus[] = $menu->path;
    	}
    }
    
    private function getUserPreferences(){
    	$service = new PreferencesService();
    	$all = $service->getPreferencesById(\Preferences::CODE_ICONS);
    	$this->horusIconSpace->icoset = $all[0]->getValue();
    }
    
    private function getHorusMenuConfig()
    {
    	//		echo "Solo pasa una vez ++++" . date('timestamp') . "<br> ?";
    	$session = new \Zend\Session\Container('User');
    	$id_role=$session->offsetGet('roleId');
    	$adapter=$this->dbAdapter = \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter();
    	$rolepermission=new \System\Model\RoleModel();
    
    	$horusNameSpace = new \Zend\Session\Container('menuHorus');
    	
    	$horusNameSpace->conlin = $horusNameSpace->conlin + 1;
    
    	if(!isset($horusNameSpace->horusMenu)){
    		//$horusNameSpace->horusMenu =  $rolepermission->getResourcesForMenu($id_role);
    		$horusNameSpace->horusMenu =  $rolepermission->getResourcesMenusite($id_role);
    	}
    
    }
    public function valueResourceIof($resource){
    	$session = new Container('User');
    	$id_role = $session->offsetGet('roleId');
    	$menu    = new \System\Model\RoleModel();
    	$permissions = $menu->getResourcesActionsViews($id_role);
    	foreach($permissions as $permision){
    		if(count($permision) > 0){
    			foreach($permision as $permisionPath){
    				if($permisionPath->path == $resource){
    					return true;
    				}
    			}
    		}
    	}
    	return false;
    }
}
