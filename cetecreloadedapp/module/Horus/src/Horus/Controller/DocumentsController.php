<?php


namespace Horus;

use BaseController;
use Application_Service_Impl_DocumentsServiceImpl;
use Application_Form_DocumentsForm;
use Application_Entity_DocumentsEntity;
use Application_Entity_FieldsEntity;
use SeparatorString;
use Zend_Json;
use Application_Entity_DocumentsFieldsContentEntity;
use Zend_Loader_Autoloader;
use Zend_Pdf;
use Zend_Pdf_Page;
use Zend_Pdf_Font;
use Zend_Pdf_Style;
use Zend_Pdf_Color_RGB;
use Zend_Pdf_Exception;
use Exception;


include_once APPLICATION_PATH . '/controllers/BaseController.php';

class DocumentsController extends BaseController{
	
	
	public function indexAction(){
		$serviceDoc = new Application_Service_Impl_DocumentsServiceImpl();
		$documents = $serviceDoc->getAllDocuments();
		$creatAExp = $this->_getParam('CreAnExp');
		if(isset($creatAExp) == "true"){
			$this->view->documentsCreAnExp = $documents;
		}else{
			$this->view->documents = $documents;
		}
	}
	
	public function createAction(){
		
		$formulario = new Application_Form_DocumentsForm();
		$serviceDoc = new Application_Service_Impl_DocumentsServiceImpl();
		$docEntity = new Application_Entity_DocumentsEntity();
		$fieldEntity = new Application_Entity_FieldsEntity();
		
		if ($this->getRequest()->isPost()) {
		    $formData = $this->getRequest()->getPost();
		    
		    $docEntity->setName($formData['name']);
		    $docEntity->setDescription($formData['description']);
		    
		    $documentSave = $serviceDoc->addDocuments($docEntity);
		    
		    if($documentSave != null){
		    	foreach($formData['realField'] as $fields){
			    	$field = explode(SeparatorString::SEPARATOR, $fields);
			    	$fieldEntity->setLabel($field[0]);
			    	$fieldEntity->setId_Document($documentSave);
			    	$fieldEntity->setType($field[1]);
			    	$fieldEntity->setPosition($field[2]);
			    	
			    	$serviceDoc->addField($fieldEntity);
		        }
		        $msj = "ok";
		        
		    }else{
		    	$msj = "FailSave";
		    }
		    
		    echo Zend_Json::encode(array('response' => $msj));
		    exit;
		}else{
			$this->view->formulario = $formulario;
		}
		
	}
	
	public function editAction(){
		$serviceDoc = new Application_Service_Impl_DocumentsServiceImpl();
		$formulario = new Application_Form_DocumentsForm();
		$docEntity = new Application_Entity_DocumentsEntity();
		$fieldEntity = new Application_Entity_FieldsEntity();
		$entityDocFieldContetn = new Application_Entity_DocumentsFieldsContentEntity();
		 
		$idDocument = $this->_getParam('id');
		$idDoc = $this->_getParam('idDoc');
		$editField = $this->_getParam('contentField');
		
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			
			if(isset($editField) == "true"){
				$delFieldsOld = $serviceDoc->deleteFieldsOldContentById($formData['idVersion']);
				
			    if($delFieldsOld == "true"){
                    $entityDocFieldContetn->setId_content_field($formData['idVersion']);
                    $entityDocFieldContetn->setDate($formData['date']);		 
		    		$entityDocFieldContetn->setId_document($idDoc);
			 		$entityDocFieldContetn->setContent($formData['docRealContent']);
			 		
			 		$serviceDoc->updateFieldContents($entityDocFieldContetn);
			        $msj = "ok";
			    }else{
			    	$msj = "FailSave";
			    }
			    echo Zend_Json::encode(array('response' => $msj));
			    exit;
			}else{
				$docEntity->setId_Document($formData['id_document']);
			    $docEntity->setName($formData['name']);
			    $docEntity->setDescription($formData['description']);
			    
			    $documentUpdate = $serviceDoc->updateDocuments($docEntity);
	
			    if($documentUpdate == "true"){
			    	$delFieldsOld = $serviceDoc->deleteFieldsOldByDocument($formData['id_document']);
			    	
			    	if($delFieldsOld == "true"){
			    		foreach($formData['realField'] as $fields){
					    	$field = explode(SeparatorString::SEPARATOR, $fields);
					    	$fieldEntity->setLabel($field[0]);
					    	$fieldEntity->setId_Document($formData['id_document']);
					    	$fieldEntity->setType($field[1]);
					    	$fieldEntity->setPosition($field[2]);
					    	
					    	$serviceDoc->addField($fieldEntity);
			            }
			             $msj = "ok";
			    	}else{
			    		$msj = "FailSave";
			    	}
	
			    }else{
			    	$msj = "FailSave";
			    }
			    
			    echo Zend_Json::encode(array('response' => $msj));
			    exit;
			}
			
		}else{
			
			if(isset($idDoc) != "" || isset($idDoc) != null){
				$fields = $serviceDoc->getFieldsDocumentById($idDoc);
				echo Zend_Json::encode(array('fields' => $fields));
				exit;
			}

			$this->view->formulario = $formulario;
			$document = $serviceDoc->getDocumentById($idDocument);
			
			$formulario->populate($document);
		}
	}
	
	public function deleteAction(){
	    $deleteParam = $this->_getParam("erase");
        if($deleteParam == ""){
            $this->_redirect('/documents/index');
        }else{
        	if ($this->getRequest()->isPost()) {
         		$serviceDoc = new Application_Service_Impl_DocumentsServiceImpl();
         		$serviceDoc->deleteDocument($deleteParam);
        	}
        }
	}
	
	public function deleteversionAction(){
	    $deleteParam = $this->_getParam("erase");
	    $d = $this->_getParam('d');
	    
        if($deleteParam == ""){
            $this->_redirect('/documents/versions?idDoc='.$d);
        }else{
        	if ($this->getRequest()->isPost()) {
         		$serviceDoc = new Application_Service_Impl_DocumentsServiceImpl();
         		$serviceDoc->deleteVersionDocument($deleteParam);
        	}
        }
	}
	
	public function viewAction(){
		 $idDocument = $this->_getParam("idDoc");
		 $serviceDoc = new Application_Service_Impl_DocumentsServiceImpl();
		 $document = $serviceDoc->getDocumentById($idDocument);
		 $fields = $serviceDoc->getFieldsDocumentById($idDocument);
		 $this->view->document = $document;
		 $this->view->field = $fields;
	}
	
	public function creaexporAction(){
		 $idDocument = $this->_getParam("idDoc");
		 $serviceDoc = new Application_Service_Impl_DocumentsServiceImpl();
		 $entityDocFieldContetn = new Application_Entity_DocumentsFieldsContentEntity();
		 
		 if ($this->getRequest()->isPost()) {
		 	$post = $this->getRequest()->getPost();
		 	$docId = $this->_getParam("docId");
	 	
		 	if(count($post)){
		      //$fieldsComplete = explode(SeparatorString::SEPARATOR, $post['docRealContent']);
			 //	for ($i = 0; $i < count($fieldsComplete)-1; $i++){
			 		/*$fieldCont = explode(SeparatorString::SEPARATOR2, $fieldsComplete[$i]);
			 		$entityDocFieldContetn->setId_document($docId);
			 		$entityDocFieldContetn->setContent($fieldCont[0]);
			 		$entityDocFieldContetn->setPosition($fieldCont[1]);
			 		$entityDocFieldContetn->setFieldId($fieldCont[2]);*/
			 		$entityDocFieldContetn->setId_document($docId);
			 		$entityDocFieldContetn->setContent($post['docRealContent']);
			 		$entityDocFieldContetn->setDate(date('Y-m-d'));
			 		
			 		$serviceDoc->addFieldContents($entityDocFieldContetn);
			 //	}
			 	echo Zend_Json::encode("true");
		 	}else{
		 		echo Zend_Json::encode("false");
		 	}
		 	exit;
		 }else{
		 	 $document = $serviceDoc->getDocumentById($idDocument);
			 $fields = $serviceDoc->getFieldsDocumentById($idDocument);
			 $this->view->document = $document;
			 $this->view->field = $fields;
		 }
	}
	
	public function versionsAction(){
		$idDocument = $this->_getParam("idDoc");
		$edit = $this->_getParam('edit');
		$ajax = $this->_getParam('ajax');
		$idVersion = $this->_getParam('idVer');
		
		$serviceDoc = new Application_Service_Impl_DocumentsServiceImpl();
		
		if(isset($idDocument)){
			if(isset($edit)){
				 $document = $serviceDoc->getDocumentById($idDocument);
				 $fields = $serviceDoc->getFieldsDocumentById($idDocument);
				 $this->view->document = $document;
				 $this->view->field = $fields;
			}elseif(isset($ajax)){
				$documentVersions = $serviceDoc->getContentVersion($idVersion);

				echo Zend_Json::encode($documentVersions);
				exit;
			}else{
				$documentVersions = $serviceDoc->getVersionsDocuments($idDocument);
				$this->view->version = $documentVersions;	
			}
		}else{
			$this->_redirect("/documents/index?CreAnExp=true");
		}

	}
	
	public function exportAction(){
         $idDocument = $this->_getParam("idDoc");
         $idVersion = $this->_getParam('idVer');
		 $exp = $this->_getParam('exp');
		 
         $serviceDoc = new Application_Service_Impl_DocumentsServiceImpl();
		 
		 if(isset($exp) == "true"){
				// include auto-loader class
				require_once 'Zend/Loader/Autoloader.php';
				$this->_helper->layout->disableLayout();
				// register auto-loader
				$loader = Zend_Loader_Autoloader::getInstance();
				
				try {
				  // create PDF
				  $pdf = new Zend_Pdf();
				  
				  // create A4 page
				  //$pdf->pages[] = ($page = $pdf->newPage('A4')); 
				  $page = new Zend_Pdf_Page(Zend_Pdf_Page::SIZE_A4);
				  $pageHeight = $page->getHeight();
				  $pageWidth = $page->getWidth();
				  $widthMiddle = $pageWidth - 170;
				  // define font resource
				  $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
				  //letras de color negro
	              $letraNegra = new Zend_Pdf_Style();
	              $letraNegra->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
	              $letraNegra->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), 13);
            
				  $document = $serviceDoc->getDocumentById($idDocument);
				  // set font for page
				  // write text to page
				  $page->setFont($font, 24)
				       ->drawText($document['name'], $widthMiddle / 2, $pageHeight-70); 
				  
				$documentVersions = $serviceDoc->getContentVersion($idVersion);
			    $startActionAnt = 680;
			    $page->setStyle($letraNegra);
					 foreach($documentVersions as $version){
				 		$field = explode(SeparatorString::SEPARATOR, $version['content']);	
				 		for($i = 0; $i < count($field)-1; $i++){
				 			$fieldCont = explode(SeparatorString::SEPARATOR2, $field[$i]);
				 			
				 			if($fieldCont[1] == 1){//center
					 			$cadeAnt = nl2br($fieldCont[0]);
					            $cadeAnt = strip_tags($cadeAnt);
					            if(strlen($fieldCont[0]) > 85){
					            	$cadeAnt = wordwrap($cadeAnt , 85, '\n', true);
					            	$lineasAnt = explode('\n', $cadeAnt);
					            }else{
					            	$cadeAnt = wordwrap($cadeAnt , 100, '\n', true);
					            	$lineasAnt = explode('\n', $cadeAnt);
					            }
					            
					            foreach ($lineasAnt as $lineAnt) {
					             if ($startActionAnt < 80){
					             array_push($pdf->pages, $page);
			                     $page = new Zend_Pdf_Page(Zend_Pdf_Page::SIZE_A4);
					              //letras de color negro
					              $letraNegra = new Zend_Pdf_Style();
					              $letraNegra->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
					              $letraNegra->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), 13);
					              $page->setStyle($letraNegra);
			                      $startActionAnt = 800;
			                 }
					                $lineAnt = ltrim($lineAnt);
					                $page->drawText($lineAnt, 30, $startActionAnt-10);
					                $startActionAnt = $startActionAnt-20;
					            }
					            $startActionAnt = $startActionAnt - 50;
				 			}elseif ($fieldCont[1] == 2){
					 			$cadeAnt = nl2br($fieldCont[0]);
					            $cadeAnt = strip_tags($cadeAnt);
					            if(strlen($fieldCont[0]) > 30){
					            	$cadeAnt = wordwrap($cadeAnt , 30, '\n', true);
					            	$lineasAnt = explode('\n', $cadeAnt);
					            }else{
					            	$cadeAnt = wordwrap($cadeAnt , 70, '\n', true);
					            	$lineasAnt = explode('\n', $cadeAnt);
					            }
	
					            foreach ($lineasAnt as $lineAnt) {
					             if ($startActionAnt < 80){
					             array_push($pdf->pages, $page);
			                     $page = new Zend_Pdf_Page(Zend_Pdf_Page::SIZE_A4);
					              //letras de color negro
					              $letraNegra = new Zend_Pdf_Style();
					              $letraNegra->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
					              $letraNegra->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), 13);
					              $page->setStyle($letraNegra);
			                      $startActionAnt = 800;
			                 }
					                $lineAnt = ltrim($lineAnt);
					                $page->drawText($lineAnt, 30, $startActionAnt);
					                $startActionAnt = $startActionAnt-20;
					            }
					            $startActionAnt = $startActionAnt - 30;
				 			}elseif($fieldCont[1] == 3){
				 			    $cadeAnt = nl2br($fieldCont[0]);
					            $cadeAnt = strip_tags($cadeAnt);
				 			    if(strlen($fieldCont[0]) > 30){
					            	$cadeAnt = wordwrap($cadeAnt , 30, '\n', true);
					            	$lineasAnt = explode('\n', $cadeAnt);
					            }else{
					            	$cadeAnt = wordwrap($cadeAnt , 70, '\n', true);
					            	$lineasAnt = explode('\n', $cadeAnt);
					            }
	
					            foreach ($lineasAnt as $lineAnt) {
					             if ($startActionAnt < 80){
					             array_push($pdf->pages, $page);
			                     $page = new Zend_Pdf_Page(Zend_Pdf_Page::SIZE_A4);
					              //letras de color negro
					              $letraNegra = new Zend_Pdf_Style();
					              $letraNegra->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
					              $letraNegra->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), 13);
					              $page->setStyle($letraNegra);
			                      $startActionAnt = 800;
			                 }
					                $lineAnt = ltrim($lineAnt);
					                $page->drawText($lineAnt, 400, $startActionAnt);
					                $startActionAnt = $startActionAnt-20;					                 
					            }
					            $startActionAnt = $startActionAnt - 30;
				 			}	
				 			
				 		 }
					  }     

				  array_push($pdf->pages, $page);
				  $pdfData =  $pdf->render(); 
        		  //$pdf->save();
        		  $this->view->nameDoc = $document['name']; 
        	      $this->view->pdfData= $pdfData; 
				 
				} catch (Zend_Pdf_Exception $e) {
				  die ('PDF error: ' . $e->getMessage());  
				} catch (Exception $e) {
				  die ('Application error: ' . $e->getMessage());    
				}
		 }else{
		 	 $documentVersions = $serviceDoc->getContentVersion($idVersion);
			 $docShow = array();
			 $docShowCont = array();
			 $cont = array();
			 
			 foreach($documentVersions as $version){
			 		$docShow['idDocument'] = $version['id_document'];
				 		$field = explode(SeparatorString::SEPARATOR, $version['content']);
				 		
				 		for($i = 0; $i < count($field)-1; $i++){
				 			$fieldCont = explode(SeparatorString::SEPARATOR2, $field[$i]);
				 			
					    	$cont['content'] = $fieldCont[0];
					    	$cont['position'] = $fieldCont[1];
					    	$cont['idField'] = $fieldCont[2];
					    	$docShowCont[] = $cont;
				 		}
				 		
				 	$docShow['contents'] = $docShowCont;
			 }
	
			 $document = $serviceDoc->getDocumentById($docShow['idDocument']);
			 $this->view->document = $document;
			 $this->view->contFields = $docShow;
		 }

	}
	
	public function addAction(){}
}