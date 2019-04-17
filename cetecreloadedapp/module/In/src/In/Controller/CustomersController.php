<?php
namespace In\Controller;

use Zend\View\Model\ViewModel;
use Iofractal\Controller\BaseController;
use In\Form\CompaniesForm;
use In\Services\CompaniesServices;
use System\Services\UsersService;
use In\Services\ProjectsServices;

class CustomersController extends BaseController
{
	
	private $companiesServices;
	
	// Instanciamos servicio de companias
	public function getCompaniesServices()
	{
		return $this->companiesServices = new CompaniesServices();
	}
	
	/*
	 * Metodo index, muestra los registros de los clientes
	 */
    public function indexAction()
    {
    	$companies = $this->getCompaniesServices()->fetchAll(1);
    	//echo "<pre>"; print_r($companies); exit;
    	$view      = array("companies" => $companies);
        return new ViewModel($view);
    }
    
    /*
     * Metodo add, agrega un cliente
     */
    public function addAction()
    {
    	$form    = new CompaniesForm("companies", 1);
    	$view    = array("form" => $form);
    	$request = $this->getRequest();
    	
    	if($request->isPost()){
    		$formData   = $request->getPost()->toArray();
    		$addCompany = $this->getCompaniesServices()->addCompany($formData);
    		
    		if($addCompany){
    			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/in/customers/index');
    		}
    	}
    	
    	return new ViewModel($view);
    }
    
    /*
     * Metodo edit, modifica un cliente
     */
    public function editAction()
    {
    	$id      = (int) $this->params()->fromRoute("id",null);
    	$form    = new CompaniesForm("companies", 1);
    	$company = $this->getCompaniesServices()->getCompanyById($id);
    	//echo "<pre>"; print_r($company); exit;
    	$form->setData($company[0]);
    	$view    = array("form" => $form, "district" => $company[0]['district'], "neighborhood" => $company[0]['neighborhood'] );
    	$request = $this->getRequest();
    	
    	if($request->isPost()){
    		$formData   = $request->getPost()->toArray();
    		//echo "<pre>"; print_r($formData); exit;
    		$editCompany = $this->getCompaniesServices()->editCompany($formData);
    		
    		if($editCompany){
    			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/in/customers/index');
    		}else{
				return $this->redirect()->refresh();
			}
    	}
    	
    	return new ViewModel($view);
    }
    
    /*
     * Metodo delete, eliminar un cliente
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

       		if ($del['del'] == 'Aceptar'){
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
      * Metodo detail, detalle de un cliente
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
     
     /*
      * Metodo getcontact, obtiene contactos por cliente
      */
     public function getcontactsAction()
     {
     	$request  = $this->getRequest();
        $response = $this->getResponse();
        
        if($request->isPost()){
        	$servUsers =  new UsersService();
        	$data      = $this->getRequest()->getPost();
      		$rows      = $servUsers->getAllContactsByIdCompany($data['id']);
        	
        	if($rows){
        		$response->setContent(\Zend\Json\Json::encode(array('response' => true, "data" => $rows)));
        	}else{
        		$response->setContent(\Zend\Json\Json::encode(array('response' => false, "data" => "Error desconosido, consulta al administrador")));
        	}
        }
        return $response;
        exit;
     }
     
     /*
      * Metodo getprojects, obtiene proyectos por cliente
      */
     public function getprojectsAction()
     {
     	$request  = $this->getRequest();
     	$response = $this->getResponse();
     
     	if($request->isPost()){
     		$servProje =  new ProjectsServices();
     		$data      = $this->getRequest()->getPost();
     		$rows      = $servProje->getAllProjectsByIdCompany($data['id']);
     		
     		//if($rows){
     			$response->setContent(\Zend\Json\Json::encode(array('response' => true, "data" => $rows)));
     		/*}else{
     			$response->setContent(\Zend\Json\Json::encode(array('response' => false, "data" => "Error desconosido, consulta al administrador")));
     		}*/
     	}
     	return $response;
     	exit;
     }

}