<?php
namespace In\Controller;

use Zend\View\Model\ViewModel;
use Iofractal\Controller\BaseController;
use In\Form\ProjectsForm;
use In\Services\ProjectsServices;

class QuotesController extends BaseController
{
	
	private $projectsServices;
	
	// Instanciamos servicio de proyectos
	public function getProjectsServices()
	{
		return $this->projectsServices = new ProjectsServices();
	}
	
	/*
	 * Metodo index, muestra los registros de los proyectos
	 */
    public function indexAction()
    {
        return new ViewModel();
    }
    
    /*
     * Metodo add, agrega un proyecto
     */
    public function addAction()
    {
    	$form    = new ProjectsForm("projects", 1);
    	$view    = array("form" => $form);
    	$request = $this->getRequest();
    	
    	if($request->isPost()){
    		$formData   = $request->getPost()->toArray();
    		//echo "<pre>"; print_r($formData); exit;
    		$addProject = $this->getProjectsServices()->addProject($formData);
    		//echo "<pre>"; print_r($addProject); exit;
    		if($addProject){
    			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/in/projects/index');
    		}
    	}
    	
    	return new ViewModel($view);
    }
    
    /*
     * Metodo edit, modifica un proyecto
     */
    public function editAction()
    {
    	$id      = (int) $this->params()->fromRoute("id",null);
    	$form    = new ProjectsForm("projects", 1);
    	$project = $this->getProjectsServices()->getProjectById($id);
    	$form->setData($project[0]);
    	$view    = array("form" => $form);
    	$request = $this->getRequest();
    	
    	if($request->isPost()){
    		$formData    = $request->getPost()->toArray();
    		$editProject = $this->getProjectsServices()->editProject($formData);
    		if($editProject){
    			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/in/projects/index');
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
        	return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/in/customers/index');
        }
		
		if ($request->isPost()) {
        	$del = $request->getPost()->toArray();

       		if ($del['del'] == 'Si'){
            	$id = (int) $del['id'];
            	$this->getCompaniesServices()->deleteCompany($id);
            }

        	// Redirect to list of customers
            return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/in/customers/index');
         }
	
         $data = $this->getCompaniesServices()->getCompanyById($id);
         
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
     	//echo "<pre>"; print_r($data); exit;
     	$view = array(
     			'data'  => $data[0]
     	);
     	
     	return new ViewModel($view);
     	
     }

}