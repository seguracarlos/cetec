<?php
namespace Ioteca\Controller;

use Iofractal\Controller\BaseController;
use Application_Service_Impl_CoursesServiceImpl;
use Application_Form_Courses;
use Application_Entity_CoursesEntity;
use DaoCourses;

include_once APPLICATION_PATH . '/services/CoursesServices.php';
include_once APPLICATION_PATH . '/controllers/BaseController.php';
include_once APPLICATION_PATH . '/services/ACLCheckerService.php';

class CoursesController extends BaseController
{
    /*function init()
    {
    	BaseController::init();
	}*/
	
    public function indexAction()
    {
    	echo "Controller Courses - index"; exit;
    	$serviceCourses = new Application_Service_Impl_CoursesServiceImpl();
    	$listado = $serviceCourses->listCourses();
    	
    	if($listado != null){
    		$this->view->courses = $listado;
    	}else{
    		$this->view->courses = 0;
    	}
     }
   
     public function addAction(){
     	$form = new Application_Form_Courses();
     	$serviceCourses = new Application_Service_Impl_CoursesServiceImpl();
     
     	if($this->getRequest()->isPost()){
     		$formData = $this->getRequest()->getPost();
            $formData['acl_users_id'] = $this->view->userid;
     		 
     		if($form->isValid($formData)){
     			 
     			$idCourse = $serviceCourses->saveCourses($formData);
     			if($idCourse != null){
     				$this->_redirect("/Ioteca/courses/index");
     			}
     		}
     	}
     
     	$this->view->form = $form;
     }
     
	
	
     public function updateAction(){
     	$form = new Application_Form_Courses();
     	$coursesService = new Application_Service_Impl_CoursesServiceImpl();
     	$courses = new Application_Entity_CoursesEntity();
     	
     	$idcourses = $this->_getParam('c');
     	if($this->getRequest()->isPost()){
     		$formData = $this->_getAllParams();
     		$formData['idcurses'] = $idcourses;
     
     		if($form->isValid($formData)){
     			 
     			$coursesEdit = $coursesService->updateCourses($formData);
     			if($coursesEdit == true){
     				$this->_redirect("/Ioteca/courses/index");
     			}else{
     				$this->_redirect("/Ioteca/courses/update?c=".$idcourses);
     			}
     		}
     	}else{
     		
     		$course = $coursesService->getCoursesById($idcourses);
     		$form->populate($course[0]);
     		$this->view->form = $form;
     	}
     }

   public function viewCursesAction(){
       
	 	$cu = new DaoCourses();
		// Asignamos a la vista el resultado de consultar por todos los registros
	    $curses = $curses->getCurses();
	    $this->view->courses = $curses;
	
   }
  
    
    public function deleteAction(){
    	$deleteParam = $this->_getParam("erase");
    	if ($this->getRequest()->isPost()) {
    		$serviceCourses = new Application_Service_Impl_CoursesServiceImpl();
    		$serviceCourses->deleteCourses($deleteParam);
    		
    	}
    	exit();
    }
    
}

