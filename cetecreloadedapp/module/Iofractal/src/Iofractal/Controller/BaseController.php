<?php
/**
 * BaseController. This is base class for Osiris
 * @author IOFractal
 *
 * @version 1.0
 */
namespace Iofractal\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\EventManager\EventManagerInterface;
use Zend\Session\Container;

abstract class BaseController extends AbstractActionController
{
		
	public function setEventManager(EventManagerInterface $events)
	{
		parent::setEventManager($events);
	
		$controller = $this;
		$events->attach('dispatch', function ($e) use ($controller) {
	
			//Here you can get on with the business at hand
	
			/*
			 * can't do any action in this Controller if not logged
			 */
			/*if(  !$this->_notLoggedIn ){
				//todo implement messages
				return $this->redirect()->toURL('/controller/action/login'  );
			}*/
	
			/*
			 * Use the IOS layout for all actions in this Controller
			 */
			//$this->layout('layout/ios');
	
			/*
			 * This may come in useful later
			*/
			//$request = $e->getRequest();
			//$method  = $request->getMethod();
	
			return;
	
		}, 104);
	}
	
	
	
    public function logOsiris($className, $msg, $priority = 5)
    {
        $this->getEventManager()->trigger('log', $this, array(
            'target' => $className,
            'priority' => $priority,
            'message' => $msg
        ));
    }
    public function getSaludo(){
    	print_r("Hola");
    	exit();
    }
    
    /*
     * DATOS DE LA SESION DEL USUARIO
     */
    protected function getAllDataSesionUser()
    {
    	$session = new Container('User');
    	 
    	$data    = array(
    			"userId"     => $session->offsetGet('userId'),
    			"id_company" => $session->offsetGet('id_company'),
    			"user_email" => $session->offsetGet('email'),
    			"roleId"   => $session->offsetGet('roleId'),
    			"rol_name"   => $session->offsetGet('roleName'),
    			"user_name"  => $session->offsetGet('name'),
    			"surname"    => $session->offsetGet('surname'),
    			"lastname"   => $session->offsetGet('lastname'),
    			"brand"      => $session->offsetGet('brand'),
    			"logo"       => $session->offsetGet('logo'),
    			"photofile"  => $session->offsetGet('photofile'),
    			"name_job"   => $session->offsetGet('name_job'),
    			"sexo"       => $session->offsetGet('sexo'),
    			"trim"       => $session->offsetGet('trim')
    	);
    	
    	return  $data;
    }
}