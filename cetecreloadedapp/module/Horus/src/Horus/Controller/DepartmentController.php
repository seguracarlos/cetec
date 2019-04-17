<?php


namespace Horus;

use BaseController;
use Application_Service_Impl_DepartmentServiceImpl;
use Zend_Json;
use Application_Form_DepartmentForm;


include_once APPLICATION_PATH . '/controllers/BaseController.php';
class DepartmentController extends BaseController{
	
	function init(){
		BaseController::init();
	}
	
	public function indexAction(){
		$showList = $this->_getParam("depart");
		$showForm = $this->_getParam("sF");
		
		$departmentService = new Application_Service_Impl_DepartmentServiceImpl();
		
		if(isset($showList) && $showList == "l"){
			$lisDepartment  = $departmentService->getDepartments();
			 
			if($lisDepartment != null){
				$deps = array();
				$depsList = Array();
		
				foreach($lisDepartment as $dep){
					$deps['id_department'] = $dep->getDepartmentId();
					$deps['d_name'] = $dep->getName();
					$deps['d_description'] = $dep->getDescription();
					$depsList[] = $deps;
				}
				echo Zend_Json::encode(array("response" => "ok", "data" => $depsList));
			}else{
				echo Zend_Json::encode(array("response" => "fail", "data" => "No existen Departamentos"));
			}
		}else if(isset($showForm) && $showForm == "f"){
	    		$depForm = new Application_Form_DepartmentForm();
	    		echo $this->view->form = $depForm;	
	    }
		exit();
	}
	
	public function addAction(){
	
		//Validamos que la respuesta sea Post
		if($this->getRequest()->isPost()){
			
			//Guardamos los valores que vienen por Post en una variable
			$formData = $this->getRequest()->getPost();
			
			//Validamos que el parametro id_department no este vacio
			if($formData['id_department'] != null){
				
				//Instanciamos nuestro service department
				$service = new Application_Service_Impl_DepartmentServiceImpl();
				//Llamamos al metodo editDepartment y le pasamos los valores que llegan por post
				$depEdit = $service->editDepartment($formData);
					
				if($depEdit == true){
					echo Zend_Json::encode(array("response" => "ok", "data" => "Departamento editado correctamente"));
				}else{
					echo Zend_Json::encode(array("response" => "fail", "data" => "Error desconosido, consulta al administrador"));
				}
			}else{
				$departmentService = new Application_Service_Impl_DepartmentServiceImpl();
				$depaAdd = $departmentService->addDepartment($formData);
	
				if($depaAdd != null ){
					echo Zend_Json::encode(array("response" => "ok", "data" => "Departamento agregado correctamente"));
				}else{
					echo Zend_Json::encode(array("response" => "fail", "data" => "Error desconosido, consulta al administrador"));
				}
			}
		}
		exit();
	}
	
	public function updateAction(){
		$idDepartment = $this->_getParam("j");
		$service = new Application_Service_Impl_DepartmentServiceImpl();
		
		if(isset($idDepartment)){
			$dep = $service->getDepartmentById($idDepartment);
			 
			if($dep != null){
				$depArr = array();
				$depArr['d_name'] = $dep['d_name'];
				$depArr['d_description'] = $dep['d_description'];
				echo Zend_Json::encode(array("response" => "ok", "data" => $depArr));
			}else{
				echo Zend_Json::encode(array("response" => "fail", "data" => "no existe el puesto."));
			}
		}
		exit();
	}
	
	public function deleteAction(){
		$service = new Application_Service_Impl_DepartmentServiceImpl();
		$deleteParam = $this->_getParam("erase");
		$service->deleteDepartment($deleteParam);
		exit;
	}
	
	/*public function addAction(){
		$addParam = $this->getParam("insert");
		$service = new Application_Service_Impl_DepartmentServiceImpl();
		$service->addDepartment($addParam);
	}*/
}