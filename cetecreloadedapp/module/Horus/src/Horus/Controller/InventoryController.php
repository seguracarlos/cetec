<?php


namespace Horus\Controller;

use Zend\View\Model\ViewModel;
use Iofractal\Controller\BaseController;
use Horus\Form\InventoryForm;
use Horus\Services\InventoryService;
use Zend\Session\Container;

//Autores: Lucero y kristian
/*include_once APPLICATION_PATH . '/controllers/BaseController.php';
include_once APPLICATION_PATH . '/services/impl/InventoryServiceImpl.php';
include_once APPLICATION_PATH . '/services/InventoryService.php';*/

class InventoryController extends BaseController
{
	private $invetory_services;
	
	public function __construct()
	{
	}
	
	/* Instanciamos el servicio de inventarios */
	protected function getInventoryService()
	{
		return $this->invetory_services = new InventoryService();
	}

	public function indexAction()
	{
		$inventory = $this->getInventoryService()->fetchAll();
		$view      = array(
			"inventory" => $inventory
		);
		//echo "<pre>"; print_r($inventory); exit;
		return new ViewModel($view);
	}
	
	public function addAction()
	{
		$session = new Container('User');
		$form = new InventoryForm("inventoryForm");
		$view = array("form" => $form);
		
		if($this->getRequest()->isPost()){
				$formData = $this->getRequest()->getPost()->toArray();
				$formData['user_id']       = $session->offsetGet('userId');
				//echo "<pre>"; print_r($formData); exit;
				$addInventory = $this->getInventoryService()->addInventory($formData);
				if($addInventory){
					return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/horus/inventory/index');
				}
		}
		
		return new ViewModel($view);
	}
	
	public function editAction()
	{
		$form            = new InventoryForm("inventoryForm");
		$id_inventory    = $this->params()->fromRoute("id",null);
		$inventory       = $this->getInventoryService()->getInventoryById($id_inventory);
		//echo "<pre>"; print_r($inventory); exit;
		$form->setData($inventory[0]);
		$view            = array("form" => $form, "photo" => $inventory[0]['name_photo']);
		
		if($this->getRequest()->isPost()){
			$formData = $this->getRequest()->getPost()->toArray();
			$edit = $this->getInventoryService()->editInventory($formData,$inventory[0]['name_photo']);
			//if($edit){
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/horus/inventory/index');
			//}
		}
		
		return new ViewModel($view);
	}
	
	public function deleteAction()
	{
		$request = $this->getRequest();
		$id      = (int) $this->params()->fromRoute('id', 0);
		
		if (!$id) {
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/horus/inventory/index');
		}
		
		if ($request->isPost()) {
			$del = $request->getPost()->toArray();
		
			if ($del['del'] == 'Aceptar'){
				$id = (int) $del['id'];
				$this->getInventoryService()->deleteInventory($id);
			}
		
			// Redirect to list of inventory
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/horus/inventory/index');
		}
		
		$data = $this->getInventoryService()->getInventoryById($id);

		return array(
				'id'    => $id,
				'data'  => $data[0]
		);
	}
	
	public function detailAction()
	{
		if($this->getRequest()->isPost()){
			$formData = $this->getRequest()->getPost()->toArray();
			$row = $this->getInventoryService()->getInventoryById($formData['id_inv']);
			$response = $this->getResponse()->setContent(\Zend\Json\Json::encode(array("response"=>"ok", "data"=>$row)));
			return $response;
		}
		exit;
	}

	/*public function historyAction(){
		$inv = new InventoryServiceImpl();
		$invent = $inv->listarHistorial();

		$this->view->inventory = $invent;

		$id_role = $this->getCurrentUserIdRole();
		$this->view->roleId = $id_role;
	}*/

	/*public function addAction(){
		
		$form = new Application_Form_InventoriesForm();

		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			if ($form->isValid($formData)) {
				
				$formData['registered_by'] = $this->getCurrentUserName();
				$formData['user_id'] = $this->getCurrentUserId();

				$activiti = new InventoryServiceImpl();
				$activiti->addInventory($formData);
				
				$this->_redirect('/Horus/inventory/index');

			} else {
				$form->populate($formData);
			}
		}
		$this->view->form = $form;
	}*/

	/*public function updateAction()
	{
		$form = new Application_Form_InventoriesForm();

		$id = $this->_getParam('id', 0);

		if ($this->getRequest()->isPost()) {
				
			$this->_setParam('types_id_types', $form->getValue('types_id_types'));
			$this->loadLists($form);
				
			$formData = $this->getRequest()->getPost();
			$formData['id_inventories'] = $id;
			$formData['registered_by'] = $this->getCurrentUserName();
			$formData['user_id'] = $this->getCurrentUserId();
			
			if ($form->isValid($this->_getAllParams())) {
				$inventoryService = new InventoryServiceImpl();

				$inventoryService->updateInventory($formData);
				$this->_redirect('/Horus/inventory/index');

			} else {
				$form->populate($formData);
			}
		} else {
			if($id > 0) {
				$inventoryService = new InventoryServiceImpl();
				$inventoryData = $inventoryService->getIdInventoryById($id);
				// we load the "Article" combo to pre fill it
				$typeInventory = $inventoryData["types_id_types"];
				$articleId = $inventoryData["object"];
				$articles = $inventoryService->getObjectByType($typeInventory);
				$form->getElement('object')->addMultiOption("0","-- Seleccione --");
				foreach (json_decode($articles) as $values){
					$form->getElement('object')->addMultiOption($values->id,$values->name_article);
				}
				$form->populate($inventoryData);
			}
		}
		$this->view->form = $form;
	}*/

	/*public function deleteAction()
	{
		$deleteParam = $this->_getParam("erase");
		$service = new InventoryServiceImpl();
		$service->deleteInventory($deleteParam);
		exit;
	}*/

	/*public function getobjectbytypeAction(){
			
		$service = new InventoryServiceImpl();
		$typeParam = $this->_getParam('types_id_types');
		$id = $typeParam;
		$data = $service->getObjectByType($id);
		print_r($data);
		exit;
	}*/

	/*public function loadLists(Zend_Form $form){
		$service = new InventoryServiceImpl();

		$typesParam = $this->_getParam('types_id_types');
		if($typesParam){
			foreach (json_decode($service->getObjectByType($typesParam)) as $values){
				$form->getElement('object')->addMultiOption($values->id,$values->name_article);
			}
		}
	}*/
}
