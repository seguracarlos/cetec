<?php
namespace In\Controller;

use Zend\View\Model\ViewModel;
use Iofractal\Controller\BaseController;
use In\Form\ProjectsForm;
use In\Services\ProjectsServices;

class ProjectsController extends BaseController
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
    	$projects = $this->getProjectsServices()->fetchAll(1);
    	//echo "<pre>"; print_r($projects); exit;
    	$view      = array("projects" => $projects);
        return new ViewModel($view);
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
    	$form    = new ProjectsForm("projects", 2, $id);
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
      * Metodo detail, detalle
      */
     public function detailAction()
     {
     	$id = (int) $this->params()->fromRoute('id', 0);
     	
     	if (!$id) {
     		return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/in/projects/index');
     	}
     	
     	$data = $this->getProjectsServices()->getProjectById($id);
     	$view = array(
     			'data'  => $data[0]
     	);
     	
     	return new ViewModel($view);
     	
     }

}