<?php
namespace Usermanual\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Usermanual\Services\Impl\TopicsServiceImpl;

use Usermanual\Services\Impl\ContentsServiceImpl;
use Usermanual\Constants\Artefacttype;

use Usermanual\Forms\ParamForm;
use Usermanual\Forms\ContentsusermanualForm;

class ContentsController extends AbstractActionController{
	
	private $type;
	
	public function __construct(){
// 		BaseController::init();
		$this->type = ArtefactType::USERMANUAL;
// 		$this->type = ArtefactType::MEMORY;
	}
	
	public function contentsAction(){
		
		$user = 15;
// 		$user = $this->getCurrentUserId();

		$topics		= new TopicsServiceImpl();
		$contents	= new ContentsServiceImpl();
			
		$paramform		= new ParamForm();
		$ContentsForm	= new ContentsusermanualForm();
		

		if($this->getRequest()->isPost()){
			
// 			$baseUrl = $this->_request->getBaseUrl();
			$id = $_POST['id_topic_content'];
			
			if($_POST['id_topic_content'] != 0){
				
				$id = $_POST['id_topic_content'];
				$validate = $topics->ValidateParent($this->type,$id,$user);

				$contentEdit = $contents->getContentById($id);
				
				if ($contentEdit) {
					$ContentsForm->setData($contentEdit [0]);
					$paramform->setData($contentEdit [0]);

					$data = array(
							'tree'			=> json_encode($topics->getAllTreeByType($this->type,$user)),
							'Formparam'		=> $paramform,
							'ContentsForm'	=> $ContentsForm,
							'ContentEdit'	=> $contentEdit [0] ['content'],
							'cont'			=> $validate,
							'id'			=> '',
					);
					return new ViewModel($data);
					
				}
				else{
					$data = array(
							'tree'			=> json_encode($topics->getAllTreeByType($this->type,$user)),
							'Formparam'		=> $paramform,
							'ContentsForm'	=> $ContentsForm,
							'cont'			=> $validate,
							'id'			=> $id,
					);
					return new ViewModel($data);
				}
				
			}else{
 				
				$formData = $this->getRequest ()->getPost ();
				
				if ($formData ['id_content'] == 0) {
					
					$contentSave = $contents->addContent($formData);
					return $this->redirect()->toRoute('usermanual', array(
							'controller'		=> 'IndexController',
							'action'			=> 'index'));
				} 
				else {
					
					$contentEdit = $contents->editContent($formData);
					
					return $this->redirect()->toRoute('usermanual', array(
					'controller'		=> 'IndexController',
					'action'			=> 'index'));
					
				}
				
				$id = $formData['id_topic'];
				$topicParent = $topics->getTopicBy($id,$this->type,$user);
				$id_parent = $topicParent[0]['parent'];
 				
				
			}
		}else{
			return $this->redirect()->toRoute('usermanual', array(
					'controller'		=> 'IndexController',
					'action'			=> 'index'));
		}
		
	}
		
	public function addAction(){}	
	
}