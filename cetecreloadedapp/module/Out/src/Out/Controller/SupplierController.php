<?php
namespace Out\Controller;

use Zend\View\Model\ViewModel;
use Iofractal\Controller\BaseController;
use In\Form\CompaniesForm;
use In\Services\CompaniesServices;

class SupplierController extends BaseController
{
	
	private $companiesServices;
	
	// Instanciamos servicio de companias
	public function getCompaniesServices()
	{
		return $this->companiesServices = new CompaniesServices();
	}
	
	/*
	 * Metodo index, muestra los registros de los proveedores
	 */
    public function indexAction()
    {
    	$companies = $this->getCompaniesServices()->fetchAll(2);
    	//echo "<pre>"; print_r($companies); exit;
    	$view      = array("companies" => $companies);
        return new ViewModel($view);
    }
    
    /*
     * Metodo add, agrega un proveedor
     */
    public function addAction()
    {
    	$form    = new CompaniesForm("companies", 2);
    	$view    = array("form" => $form);
    	$request = $this->getRequest();
    	
    	if($request->isPost()){
    		$formData   = $request->getPost()->toArray();
    		//echo "<pre>"; print_r($formData); exit;
    		$addCompany = $this->getCompaniesServices()->addCompany($formData);
    		//echo "<pre>"; print_r($addCompany); exit;
    		if($addCompany){
    			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/out/supplier/index');
    		}
    	}
    	
    	return new ViewModel($view);
    }
    
    /*
     * Metodo edit, modifica un proveedor
     */
    public function editAction()
    {
    	$id      = (int) $this->params()->fromRoute("id",null);
    	$form    = new CompaniesForm("companies", 2);
    	$company = $this->getCompaniesServices()->getCompanyById($id);
    	//echo "<pre>"; print_r($company); exit;
    	$form->setData($company[0]);
    	$view    = array("form" => $form, "district" => $company[0]['district'], "neighborhood" => $company[0]['neighborhood'] );
    	$request = $this->getRequest();
    	
    	if($request->isPost()){
    		$formData   = $request->getPost()->toArray();
    		//echo "<pre>"; print_r($formData); exit;
    		$editCompany = $this->getCompaniesServices()->editCompany($formData);
    		//echo "<pre>"; print_r($editCompany); exit;
    		if($editCompany){
    			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/out/supplier/index');
    		}else{
				return $this->redirect()->refresh();
			}
    	}
    	
    	return new ViewModel($view);
    }
    
    /*
     * Metodo delete, eliminar un proveedor
     */
    public function deleteAction()
    {
    	$request = $this->getRequest();
    	$id      = (int) $this->params()->fromRoute('id', 0);
    
    	if (!$id) {
    		return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/out/supplier/index');
    	}
    
    	if ($request->isPost()) {
    		$del = $request->getPost()->toArray();
    
    		if ($del['del'] == 'Si'){
    			$id = (int) $del['id'];
    			$this->getCompaniesServices()->deleteCompany($id);
    		}
    
    		// Redirect to list of prospects
    		return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/out/supplier/index');
    	}
    
    	$data = $this->getCompaniesServices()->getCompanyById($id);
    
    	return array(
    			'id'    => $id,
    			'data'  => $data[0]
    	);
    }
    
    /*
     * Metodo detail, detalle de un proveedor
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