<?php

namespace Ioteca\Controller;

use Iofractal\Controller\BaseController;
use TutorialServiceImpl;
use Application_Form_Tutorial;
use Application_Entity_TutorialEntity;

class TutorialController extends BaseController
{
    /*function init()
    { 
    	BaseController::init();
	}*/
	
    public function indexAction()
    {
    	echo "Controller Tutorial - index"; exit;
		// Instancia de la clase servicios
		$service = new TutorialServiceImpl();   
		$todos= $service->showAllTutorial();
		
		$idParam = $this->_getParam("tutorial");
		if(isset($idParam)){
			$this->view->tutorial = $service->getTutorialById($idParam);
			echo "<div class='center'><a id='back' href='javascript:history.back();'>Regresar</a></div>";
		}else{
			
			$this->view->tutorial= $service->showAllTutorial();	
		}
	
     }
	
	//agrega
	public function addAction()
	{
		echo "Controller Tutorial - add"; exit;
        $form = new Application_Form_Tutorial();
        $tutorialService = new TutorialServiceImpl();

        //recibe post de formulario
        if($this->getRequest()->isPost()){
            $formData = $this->getRequest()->getPost();
        	if($form->isValid($this->_getAllParams())){
        	
                $formData['iduser'] = $this -> getCurrentUserId();

    			$tutorial = $tutorialService->createTutorial($formData);
                if($tutorial != null){
                    $this->_redirect("/Ioteca/tutorial/index");
                }else{
                    $this->_redirect("/Ioteca/tutorial/index");
                }
        	}
        }
         $this->view->form = $form;
    }

	public function editAction()
    {
    	echo "Controller Tutorial - edit"; exit;
    		$form = new Application_Form_Tutorial();
    		$id = $this->_getParam('idtutorial');
    		$user_id = $this -> getCurrentUserId();
    		
    		if($this->getRequest()->isPost()){
    			if($form->isValid($this->_getAllParams())){
    				
    				$tutorialService = new TutorialServiceImpl();
    				$tutorial = new Application_Entity_TutorialEntity();
    				$tutorial->setIdtutorial($this->_getParam("idtutorial"));
    				$tutorial->setAuthor($this->_getParam('author'));
    				$tutorial->setName($this->_getParam('name'));
    				$tutorial->setStatus($this->_getParam('status'));
    				$tutorial->setUrl($this->_getParam('url'));
    				$tutorial->setDescription($this->_getParam('description'));
    				$tutorial->setTipo_id($this->_getParam('id_tipo'));

    				$tutorialService->updateTutorial($tutorial);
    				
    				$param = $this->_getParam("");
    				if(isset($param)){
    					$this->_redirect("");
    				}else{
    					$this->_redirect("/Ioteca/tutorial");
    				}
    			}
    		}else{
    				$service = new TutorialServiceImpl();		
    						
    				$form->populate($service->getTutorialById($id));
    				
    			}
    			
    			$this->view->form=$form;
    }
    	
    public function deleteAction()
    {
    	echo "Controller Tutorial - delete"; exit;
    	$deleteParam = $this->_getParam("erase");
    	$service =  new TutorialServiceImpl();
    	$service->deleteTutorial($deleteParam);
    }
    
     
   }
    