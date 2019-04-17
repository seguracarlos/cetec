<?php

namespace In\Controller;

use Zend\View\Model\ViewModel;
use Iofractal\Controller\BaseController;
use In\Form\DestinationsForm;
use In\Services\DestinationsServices;


class DestinationsController extends BaseController
{
	private $serviceDestinations;
	
	public function __construct()
	{	
	}
	
	/**
	 * Declaracion del servicio de destinos
	 */
	private function getService()
	{
		return $this->serviceDestinations = new DestinationsServices(); 
	}
	
	/**
	 * Action Index 
	 */
	public function indexAction()
	{
		$data = $this->getService()->fetchAll();
		$view = array("data" => $data);
		return new ViewModel($view);
	}
	
	/**
	 * Action Add 
	 */
	public function AddAction()
	{
		$form    = new DestinationsForm("destinations");
		$view    = array("form" => $form);
		$request = $this->getRequest();
		
		if($request->isPost())
		{
			$formData = $request->getPost()->toArray();
			//echo "<pre>"; print_r($formData); exit;
			$addRow   = $this->getService()->addRow($formData);
			
			if($addRow){
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/in/destinations/index');
			}
		}
		
		return new ViewModel($view);
	}
	
	/**
	 * Action Edit
	 */
	public function editAction()
	{
		$id      = (int) $this->params()->fromRoute("id", null);
		$form    = new DestinationsForm("destinations");
		$row     = $this->getService()->getRowById($id);
		 
		$form->setData($row[0]);
		$view    = array("form" => $form);
		$request = $this->getRequest();
		 
		if($request->isPost()){
			$formData = $request->getPost()->toArray();
			$editRow  = $this->getService()->editRow($formData);
		
			if($editRow){
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/in/destinations/index');
			}else{
				return $this->redirect()->refresh();
			}
		}
		 
		return new ViewModel($view);
	}
	
	/**
	 * Action Delete 
	 */
	public function deleteAction()
	{
		$request = $this->getRequest();
		$id      = (int) $this->params()->fromRoute('id', 0);
		
		if (!$id) {
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/in/destinations/index');
		}
		
		if ($request->isPost()) {
			$del = $request->getPost()->toArray();
		
			if ($del['del'] == 'Aceptar'){
				$id = (int) $del['id'];
				$this->getService()->deleteRow($id);
			}
		
			// Redirect to list of customers
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/in/destinations/index');
		}
		
		$data = $this->getService()->getRowById($id);
		 
		return array(
				'id'    => $id,
				'data'  => $data[0]
		);
	}
}