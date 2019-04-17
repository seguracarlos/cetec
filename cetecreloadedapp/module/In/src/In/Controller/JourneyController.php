<?php
namespace In\Controller;

use Zend\View\Model\ViewModel;
use Iofractal\Controller\BaseController;
use In\Form\JourneyForm;
use In\Services\ShippingServices;

class JourneyController extends BaseController
{
	private $shippingServices;
	
	/**
	 * 
	 * @return \In\Services\ShippingServices
	 */
	public function getShippingServices()
	{
		return $this->shippingServices = new ShippingServices();
	}
	
	/*
	 * indexAction()
	 */
    public function indexAction()
    {
    	$rows = $this->getShippingServices()->fetchAll();
    	//echo "<pre>"; print_r($rows); exit;
    	$view      = array("rows" => $rows);
        return new ViewModel($view);
    }
    
    /*
     * addAction()
     */
    public function addAction()
    {
    	$form    = new JourneyForm("shipping", 1);
    	$view    = array("form" => $form);
    	$request = $this->getRequest();
    	
    	if($request->isPost()){
    		$formData   = $request->getPost();
    		//echo "<pre>"; print_r($formData); exit;
    		$addRow = $this->getShippingServices()->addRow($formData);
    		//echo "<pre>"; print_r($addProject); exit;
    		if($addRow){
    			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/in/journey/index');
    		}
    	}
    	
    	return new ViewModel($view);
    }
    
    /*
     * editAction()
     */
    public function editAction()
    {
    	$id   = (int) $this->params()->fromRoute("id",null);
    	$form = new JourneyForm("shipping", 2, $id);
    	$row  = $this->getShippingServices()->getRowById($id);
    	//echo "<pre>"; print_r($row); exit;
    	$form->setData($row);
    	$view    = array("form" => $form);
    	$request = $this->getRequest();
    	
    	if($request->isPost()){
    		$formData    = $request->getPost()->toArray();
    		//echo "<pre>"; print_r($formData); exit;
    		$editRow = $this->getShippingServices()->edidtRow($formData);
    		//echo "<pre>"; print_r($editRow); exit;
    		if($editRow){
    			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/in/journey/index');
    		}else{
				return $this->redirect()->refresh();
			}
    	}
    	
    	return new ViewModel($view);
    }
    
    /*
     * deleteAction()
     */
     public function deleteAction()
     {
     	$request = $this->getRequest();
     	$id      = (int) $this->params()->fromRoute('id', 0);
     	
        if (!$id) {
        	return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/in/journey/index');
        }
		
		if ($request->isPost()) {
        	$del = $request->getPost()->toArray();

       		if ($del['del'] == 'Aceptar'){
            	$id = (int) $del['id'];
            	$this->getShippingServices()->deleteRow($id);
            }

        	// Redirect to list of customers
            return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/in/journey/index');
         }
	
         $data = $this->getShippingServices()->getRowById($id);
         
         return array(
             'id'    => $id,
             'data'  => $data
         );
     }
     
     /*
      * detailAction
      */
     public function detailAction()
     {
     	$id = (int) $this->params()->fromRoute('id', 0);
     	
     	if (!$id) {
     		return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/in/journey/index');
     	}
     	
     	$data = $this->getShippingServices()->getDetailRowById($id);
     	//echo "<pre>"; print_r($data); exit;
     	$view = array(
     			'data'  => $data
     	);
     	
     	return new ViewModel($view);
     	
     }
     
     /*
      * confirmshippingAction
      */
     public function confirmshippingAction()
     {
     	if ($this->getRequest()->isPost()){
     		//$em = $this->getShippingServices()->sendMailEnlasa("<h1>html</h1>", "texto", "asunto", "luisara18@gmail.com", "luisara18@gmail.com");
     		//$em = $this->getShippingServices()->mensajito();
     		//print_r($em);exit;
     		$data       = $this->getRequest()->getPost()->toArray();
			//print_r($data);exit;
			if(isset($data['key']) && $data['key'] == 1){
				$row = $this->getShippingServices()->confirmShipping($data);
				//print_r($row);exit;
				if($row){
					$response = $this->getResponse()->setContent(\Zend\Json\Json::encode(array("response"=>"ok", "data"=>$row)));
				}
				return $response;
			}
		}
		exit;
     }
     
     public function correoAction()
     {
     	$enviarCorreo = $this->getShippingServices()->mensajito();
     	print_r($enviarCorreo);
     	exit();
     }
     
     /*
      * AUTOCOMPLETAR CLIENTES DE ENLASA
      */
     public function autocompleteclientsAction()
     {
     	//$id = (int) $this->params()->fromRoute('q', 0);
     	//print_r($id);
		$request = $this->getRequest();
		
		if ($request->isPost()) {
        	$data 	  = $request->getPost()->toArray();
        	//print_r($data);exit;
           	$clientes = $this->getShippingServices()->autoCompleteClientsEnd($data);
           	//print_r($clientes);
           	if($clientes){
           		$response = $this->getResponse()->setContent(\Zend\Json\Json::encode(array("response"=>"ok", "data"=>$clientes)));
           	}else{
           		$response = $this->getResponse()->setContent(\Zend\Json\Json::encode(array()));
           	}
           	return $response;
		}
		exit;
	}

}