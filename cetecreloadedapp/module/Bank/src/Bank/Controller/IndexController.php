<?php
namespace Bank\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Bank\Form\BankForm;
use Zend\Session\Container;
use Bank\Services\BankServices;

class IndexController extends AbstractActionController
{
	private $bankServices;
	
	public function __construct()
	{	
	}
	
	/*Instanciamos el servicio de bancos*/
	protected function getBankServices()
	{
		return $this->bankServices = new BankServices();
	}
	
	public function indexAction()
	{
		return new ViewModel();
	}
	
	public function addAction()
	{
		$session = new Container('User');
		$form = new InventoryForm("inventoryForm");
		$view = array("form" => $form);
		
		if($this->getRequest()->isPost()){
			$form->setData($this->getRequest()->getPost());
			if($form->isValid()){
				$formData                  = $form->getData();
				$formData['registered_by'] = $session->offsetGet('name');
				$formData['user_id']       = $session->offsetGet('userId');
				//echo "<pre>"; print_r($formData); exit;
				$addInventory = $this->getBankServices()->addInventory($formData);
				
			}
		}
		
		return new ViewModel($view);
	}
	
	public function editAction()
	{
		return new ViewModel();
	}
	
	public function deleteAction()
	{
	}
}