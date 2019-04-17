<?php
namespace Payroll\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Payroll\Form\PayrollForm;
use Zend\Session\Container;
use Payroll\Services\PayrollServices;
use Users\Services\UsersService;

class IndexController extends AbstractActionController
{
	private $payrollServices;
	private $userServices;
	
	public function __construct()
	{	
	}
	
	/*Instanciamos el servicio de nomina*/
	protected function getPayrollServices()
	{
		return $this->payrollServices = new PayrollServices();
	}
	
	/*Instanciamos el servicio de usuarios*/
	protected function getUserServices()
	{
		return $this->userServices = new UsersService();
	}
	
	public function indexAction()
	{
		$employes = $this->getUserServices()->getPayRollByUser();
		$view     = array("employes" => $employes);
		echo "<pre>"; print_r($employes); exit;
		return new ViewModel($view);
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