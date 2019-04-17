<?php
namespace Customers\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

//use Costumers\Form\CostumersForm;
use Zend\Session\Container;
//use Costumers\Services\CostumersService;

class IndexController extends AbstractActionController
{
	/*private $customersServices;
	
	public function __construct()
	{	
	}
	
	/*Instanciamos el servicio de inventarios
	protected function getCostumersService()
	{
		return $this->costumersServices = new CostumersService();
	}*/
	
	public function indexAction()
	{
		return new ViewModel();
	}
}