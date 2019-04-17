<?php
namespace Expenses\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Expenses\Form\ExpensesForm;
use Zend\Session\Container;
use Expenses\Services\ExpensesServices;

class IndexController extends AbstractActionController
{
	private $expensesServices;
	
	public function __construct()
	{	
	}
	
	/*Instanciamos el servicio de gastos*/
	protected function getExpensesServices()
	{
		return $this->expensesServices = new ExpensesServices();
	}
	
	public function indexAction()
	{
		$expenses = $this->getExpensesServices()->fetchAll();
		$view     = array("expenses" => $expenses);
		return new ViewModel($view);
	}
	
	public function addAction()
	{
		$session = new Container('User');
		$form = new ExpensesForm("expensesForm");
		$view = array("form" => $form);
		
		if($this->getRequest()->isPost()){
			$form->setData($this->getRequest()->getPost());
			if($form->isValid()){
				$formData                  = $form->getData();
				$formData['registered_by'] = $session->offsetGet('name');
				$formData['user_id']       = $session->offsetGet('userId');
				//echo "<pre>"; print_r($formData); exit;
				$addInventory = $this->getExpensesServices()->addExpenses($formData);
				
			}
		}
		
		return new ViewModel($view);
	}
	
	public function editAction()
	{
		$form       = new ExpensesForm("expensesForm");
		$id_expense = $this->params()->fromRoute("id",null);
		$expense    = $this->getExpensesServices()->getExpensesById($id_expense);
		$form->setData($expense[0]);
		$view       = array("form" => $form);
		
		if($this->getRequest()->isPost()){
			$form->setData($this->getRequest()->getPost());
			if($form->isValid()){
				$formData = $form->getData();
				$edit_expenses = $this->getExpensesServices()->editExpenses($formData, $id_expense);
				if($edit_expenses){
					return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/expenses');
				}/*else{
					return $this->redirect()->refresh();
				}*/
			}
		}
		//print_r($expense);exit;
		return new ViewModel($view);
	}
	
	public function deleteAction()
	{
	}
}