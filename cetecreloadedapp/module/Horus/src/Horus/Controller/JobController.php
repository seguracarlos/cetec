<?php


namespace Horus;

use BaseController;
use JobUserServiceImpl;
use Zend_Json;
use Application_Form_JobForm;


include_once APPLICATION_PATH . '/controllers/BaseController.php';
include_once APPLICATION_PATH . "/services/impl/JobUserServiceImpl.php";

class JobController extends BaseController{

	
	public function init(){
       BaseController::init();
    }
    

    public function indexAction(){
        
    	$showForm = $this->_getParam("sF");
    	$showList = $this->_getParam("sL");
    	$idJob = $this->_getParam("j");
    	
    	$serviceJob = new JobUserServiceImpl();
    	
    	if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();

            if($formData['id'] != null){
            	 $jobEdit = $serviceJob->editJob($formData);
            	
            	 if($jobEdit == "true"){
            	 	echo Zend_Json::encode(array("response" => "ok", "data" => "Puesto editado correctamente"));
            	 }else{
            	 	echo Zend_Json::encode(array("response" => "fail", "data" => "Error desconosido, consulta al administrador"));
            	 }
            }else{
	            $jobAdd = $serviceJob->addJob($formData);
	            if($jobAdd != null){
	            	echo Zend_Json::encode(array("response" => "ok", "data" => "Puesto agregado correctamente"));
	            }else{
	            	echo Zend_Json::encode(array("response" => "fail", "data" => "Error desconosido, consulta al administrador"));
	            }
            }
           
    	}else{
	    	if(isset($idJob)){
	    		$job = $serviceJob->getJobById($idJob);
	    		
	    		if($job != null){
	    			$jobArr = array();
		    		$jobArr['name_job'] = $job[0]->getName_job();
		    		$jobArr['description'] = $job[0]->getDescription();
		    		echo Zend_Json::encode(array("response" => "ok", "data" => $jobArr));
	    		}else{
	    			echo Zend_Json::encode(array("response" => "fail", "data" => "no existe el puesto."));
	    		}
	    		
	    	}else if(isset($showForm) && $showForm == "f"){
	    		
	    		$jobForm = new Application_Form_JobForm();
	    		echo $this->view->form = $jobForm;
	    		
	    	}else if(isset($showList) && $showList == "l"){
	    		$listJob = $serviceJob->getAllState();
	    		
	    		if($listJob != null){
		    		$jobs = array();
		    		$jobsList = Array();
		    		
		    		foreach($listJob as $job){
		    			$jobs['id'] = $job->getId();
		    			$jobs['name_job'] = $job->getName_job();
		    			$jobs['description'] = $job->getDescription();
		    			$jobsList[] = $jobs;
		    		}
	    			echo Zend_Json::encode(array("response" => "ok", "data" => $jobsList));
	    			
	    		}else{
	    			echo Zend_Json::encode(array("response" => "fail", "data" => "No existen Puestos"));
	    		} 		
	    		
	    	}
    	}
    	
    	exit;
    }
    
    public function addAction(){
    	
    }
    
    public function deleteAction(){
    	$serviceJob = new JobUserServiceImpl();
    	
    	$deleteParam = $this->_getParam("erase");
	    if ($this->getRequest()->isPost()) {
	        $serviceJob->deleteJob($deleteParam);
	    }
   	    exit;
    }
}

