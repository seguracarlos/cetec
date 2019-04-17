<?php
namespace In\Controller;

use Zend\View\Model\ViewModel;
use Iofractal\Controller\BaseController;
use In\Form\ServicesForm;
use In\Services\ServicesServices;

class ServicesController extends BaseController
{
	
	private $servicesServices;
	
	/*
	 * Instanciamos el servicio de servicios
	 */
	public function getServicesServices()
	{
		return $this->servicesServices = new ServicesServices();
	}
	
	/*
	 * Metodo index, muestra los registros disponibles
	 */
    public function indexAction()
    {
    	$data = $this->getServicesServices()->fetchAll();
    	
    	$view = array("data" => $data);
        return new ViewModel($view);
    }
    
    /*
     * Metodo add, agrega un registro
     */
    public function addAction()
    {
    	$form    = new ServicesForm("services");
    	$view    = array("form" => $form);
    	$request = $this->getRequest();
    	
    	if($request->isPost()){
    		$formData = $request->getPost()->toArray();
    		$addRow   = $this->getServicesServices()->addRow($formData);
    		
    		if($addRow){
    			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/in/services/index');
    		}
    	}
    	
    	return new ViewModel($view);
    }
    
    /*
     * Metodo edit, modifica un registro
     */
    public function editAction()
    {
    	$id      = (int) $this->params()->fromRoute("id", null);
    	$form    = new ServicesForm("services");
    	$row     = $this->getServicesServices()->getRowById($id);
    	
    	$form->setData($row[0]);
    	$view    = array("form" => $form);
    	$request = $this->getRequest();
    	
    	if($request->isPost()){
    		$formData = $request->getPost()->toArray();
    		$editRow  = $this->getServicesServices()->editRow($formData);
    		
    		if($editRow){
    			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/in/services/index');
    		}else{
				return $this->redirect()->refresh();
			}
    	}
    	
    	return new ViewModel($view);
    }
    
    /*
     * Metodo delete, eliminar un registro
     */
     public function deleteAction()
     {
     	$request = $this->getRequest();
     	$id      = (int) $this->params()->fromRoute('id', 0);
     	
        if (!$id) {
        	return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/in/services/index');
        }
		
		if ($request->isPost()) {
        	$del = $request->getPost()->toArray();

       		if ($del['del'] == 'Si'){
            	$id = (int) $del['id'];
            	$this->getServicesServices()->deleteRow($id);
            }

        	// Redirect to list of customers
            return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/in/services/index');
         }
	
         $data = $this->getServicesServices()->getRowById($id);
         
         return array(
             'id'    => $id,
             'data'  => $data[0]
         );
     }
     
}