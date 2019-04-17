<?php
namespace Cash\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Cash\Form\BankForm;
use Zend\Session\Container;
use Cash\Services\CashServices;

class IndexController extends AbstractActionController
{
	private $cashServices;
	
	public function __construct()
	{	
	}
	
	/*Instanciamos el servicio de caja*/
	protected function getCashServices()
	{
		return $this->cashServices = new CashServices();
	}
	
	public function indexAction()
	{
		$cash = $this->getCashServices()->fetchAll();
		$view = array("cash" => $cash);
		//echo "<pre>"; print_r($cash); exit;
		return new ViewModel($view);
	}
	
	public function addAction()
	{
		/*$session = new Container('User');
		$form = new InventoryForm("inventoryForm");
		$view = array("form" => $form);*/
		
		/*if($this->getRequest()->isPost()){
			$form->setData($this->getRequest()->getPost());
			if($form->isValid()){
				$formData                  = $form->getData();
				$formData['registered_by'] = $session->offsetGet('name');
				$formData['user_id']       = $session->offsetGet('userId');
				//echo "<pre>"; print_r($formData); exit;
				$addInventory = $this->getBankServices()->addInventory($formData);
				
			}
		}*/
		
		$view = new ViewModel();
    	$view->setTerminal(true);
    	return $view;
	}
	
	public function editAction()
	{
		return new ViewModel();
	}
	
	public function deleteAction()
	{
	}
}