<?php

namespace Ioteca\Controller;

use Iofractal\Controller\BaseController;
use BooksServiceImpl;
use Application_Form_Books;

class BooksController extends BaseController
{
    /*function init()
    {
    	BaseController::init();
	}*/
    
	public function indexAction()
    {
    	echo "Controller Books - index"; exit;
    
    	$service = new BooksServiceImpl();   
		$todos= $service->showAllBooks();
		
		$idParam = $this->_getParam("books");
		if(isset($idParam)){
			$this->view->books = $service->getBooksById($idParam);
			echo "<div class='center'><a id='back' href='javascript:history.back();'>Regresar</a></div>";
		}else{
			
			if(isset($_GET['noExist'])){
				echo "<script type='text/javascript'>alert('El libro seleecionado ha sido borrado recientemente');</script>";
			}
			
			$this->view->books= $service->showAllBooks();	
		}
     }
	
     //agrega
	public function addAction(){
		
        $form = new Application_Form_Books();
        $booksService = new BooksServiceImpl();

        //recibe post del formulario
        if($this->getRequest()->isPost()){
            $formData = $this->getRequest()->getPost();
        	if($form->isValid($this->_getAllParams())){

                $formData['iduser'] = $this->getCurrentUserId();

        		$book = $booksService->createBooks($formData);
                if($book != null){
                    $this->_redirect("/Ioteca/books/index");
                }else{
                    $this->_redirect("/Ioteca/books/index");
                }
        	}
        }
        
        $this->view->form = $form;
    }

	public function updateAction()
    {
    	
	$form = new Application_Form_Books();
    		$id = $this->_getParam('idbooks');		
    		
    		if($this->getRequest()->isPost()){
    			
    			if($form->isValid($this->_getAllParams())){
    				
    				$booksService = new BooksServiceImpl();
    				
    				$booksService->updateBooks($this->_getAllParams());
    				
    					$this->_redirect("/Ioteca/books");
    				}
    			}else{
    				$service = new BooksServiceImpl();		
    				$populate = $service->getBooksById($id);		
    				if($populate == 0){
    					
    					$this->_redirect('/Ioteca/books/?noExist=true');
    				}else{
    					foreach ($populate as $object ){
    						 $form->getElement('name')->setValue($object->getName());
    						 $form->getElement('author')->setValue($object->getAuthor());
    						 $form->getElement('editorial')->setValue($object->getEditorial());
    						 $form->getElement('status')->setValue($object->getStatus());
    						 $form->getElement('url')->setValue($object->getUrl());
    						 $form->getElement('id_type')->setValue($object->getTipo_books());
    						 $form->getElement('pages')->setValue($object->getPages());
    						 $form->getElement('description')->setValue($object->getDescription());
    						 $form->getElement('numIsbn')->setValue($object->getNumIsbn());
    						 $form->getElement('fisico')->setValue($object->getFisico());
    						 $form->getElement('electronico')->setValue($object->getElectronico());   						 
    					}
    				}
    					
    			}
    			$this->view->form=$form;
    }
		

	public function deleteAction()
	{
    	 
    	$deleteParam = $this->_getParam("erase");
    	$service =  new BooksServiceImpl();
    	
    	echo $service->deleteBooks($deleteParam);
    	
    	exit;
    	
    }
}
