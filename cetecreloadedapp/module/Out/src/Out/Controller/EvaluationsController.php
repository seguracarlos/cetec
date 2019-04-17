<?php
namespace Out\Controller;

use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Iofractal\Controller\BaseController;
use Out\Form\ExpensesForm;
use Out\Services\ExpensesServices;

class EvaluationsController extends BaseController
{

	private $expensesServices;

	/*Instanciamos el servicio de gastos*/
	protected function getExpensesServices()
	{
		return $this->expensesServices = new ExpensesServices();
	}

	/*
	 * Metodo index, muestra los registros de los proyectos
	 */
    public function indexAction()
    {
    	$expenses = $this->getExpensesServices()->fetchAll();
    	$view     = array("expenses" => $expenses);
    	return new ViewModel($view);
    }

    /*
     * Metodo add, agrega un proyecto
     */
    public function addAction()
    {
    	$session = new Container('User');
    	$form    = new ExpensesForm("expenses");
    	$view    = array("form" => $form);
    	$request = $this->getRequest();

    	if($request->isPost()){
    		$formData = $request->getPost()->toArray();
    		$formData['registered_by'] = $session->offsetGet('name');
    		$formData['user_id']       = $session->offsetGet('userId');
    		//echo "<pre>"; print_r($formData); exit;
    		$add      = $this->getExpensesServices()->addExpenses($formData);
    		//echo "<pre>"; print_r($addProject); exit;
    		if($add){
    			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/out/expenses/index');
    		}
    	}

    	return new ViewModel($view);
    }

    /*
     * Metodo edit, modifica un proyecto
     */
    public function editAction()
    {
    	$id         = (int) $this->params()->fromRoute("id",null);
    	$form       = new ExpensesForm("expenses");
    	$expense    = $this->getExpensesServices()->getExpensesById($id);
    	$form->setData($expense[0]);
    	$view       = array("form" => $form);
    	$request    = $this->getRequest();

    	if($request->isPost()){
    		$formData    = $request->getPost()->toArray();
    		$edit = $this->getExpensesServices()->editExpenses($formData);
    		if($edit){
    			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/out/expenses/index');
    		}else{
				return $this->redirect()->refresh();
			}
    	}

    	return new ViewModel($view);
    }

    /*
     * Metodo delete, eliminar un proyecto
     */
     public function deleteAction()
     {
     	$request = $this->getRequest();
     	$id      = (int) $this->params()->fromRoute('id', 0);

        if (!$id) {
        	return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/in/projects/index');
        }

		if ($request->isPost()) {
        	$del = $request->getPost()->toArray();

       		if ($del['del'] == 'Si'){
            	$id = (int) $del['id'];
            	$this->getCompaniesServices()->deleteCompany($id);
            }

        	// Redirect to list of customers
            return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/in/projects/index');
         }

         $data = $this->getProjectsServices()->getProjectById($id);

         return array(
             'id'    => $id,
             'data'  => $data[0]
         );
     }

     /*
      * Metodo detail, detalle de un proyecto
      */
     public function detailAction()
     {
     	$id = (int) $this->params()->fromRoute('id', 0);

     	if (!$id) {
     		return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/in/customers/index');
     	}

     	$data = $this->getCompaniesServices()->getCompanyById($id);
     	$view = array(
     			'data'  => $data[0]
     	);

     	return new ViewModel($view);

     }

}