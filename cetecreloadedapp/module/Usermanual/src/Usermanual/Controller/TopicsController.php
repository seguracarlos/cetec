<?php
namespace Usermanual\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Usermanual\Forms\ParamForm;
use Usermanual\Forms\UsermanualForm;
use Usermanual\Forms\TopicsusermanualForm;
use Usermanual\Forms\TopicsusermanualEditForm;

use Usermanual\Services\Impl\TopicsServiceImpl;
use Usermanual\Services\Impl\ContentsServiceImpl;
use Usermanual\Constants\Artefacttype;

class TopicsController extends AbstractActionController{
	
	private $type;
	
	public function __construct(){
// 		BaseController::init();
		$this->type = ArtefactType::USERMANUAL;
// 		$this->type = ArtefactType::MEMORY;
	}
	
	
	public function indexAction(){
		$user = 15;
		
		$paramform = new ParamForm();
		$topicsform = new TopicsusermanualForm();
		if($this->getRequest()->isPost()){
			
// 			$user = $this->getCurrentUserId();
// 			$baseUrl = $this->_request->getBaseUrl();
			
			$id = $_POST['id_topic_content'];
    		$topics= new TopicsServiceImpl();
    		
    		$validate = $topics->ValidateParent($this->type,$id,$user);

			if(!$validate){
				return $this->redirect()->toRoute('usermanual', array(
						'controller'	=> 'IndexController',
						'action'		=> 'index'
				));
			}
			

			$data = array(
					'tree'		=> json_encode($topics->getAllTreeByType($this->type,$user)),
					'parent'	=> $validate,
					'formparam'	=> $paramform,
					'topicsform'=> $topicsform,
					'parent'	=> $validate,
					'topics'	=> $topics->getTopics($this->type,$id,$user),
			);
			return new ViewModel($data);
			
		}else{
			return $this->redirect()->toRoute('usermanual', array(
					'controller'	=> 'IndexController',
					'action'		=> 'index'));
		}
	}
	
	public function addAction(){
		$user = 15;
		
		$form = new TopicsusermanualForm();
		$paramform = new ParamForm();
		$parents= new TopicsServiceImpl();
		
		if($this->getRequest()->isPost()){
			
// 			$user = $this->getCurrentUserId();
// 			$baseUrl = $this->_request->getBaseUrl();

			if($_POST['id_topic_add'] != 0){
				
				$id = $_POST['id_topic_add'];
				$validate = $parents->ValidateParent($this->type,$id,$user);
				
// 				$form->Cancelar->setAttrib('onclick','linkContentTopic('.$validate[0]["id_topic"].')');
				
				if(!$validate){
					return $this->redirect()->toRoute('usermanual', array(
							'controller'	=> 'IndexController',
							'action'		=> 'index'));
				}
				$data = array(
						'tree'			=> json_encode($parents->getAllTreeByType($this->type,$user)),
						'topic_parent'	=> $validate,
						'formparam'		=> $paramform,
						'form'			=> $form,
						'id_parent'	=> $id,
				);
				return new ViewModel($data);
				?>
				<script>
					$('#id_parent').val(<?php echo $id; ?>);
				</script>
				<?php
			}else{
				
				$formData=$this->getRequest()->getPost();
				
				$usermanualSave=$parents->addTopic($formData,$this->type,$user);
				$id = $usermanualSave['parent'];
				
				$validate = $parents->ValidateParent($this->type,$id,$user);
				
				if(!$validate){
					return $this->redirect()->toRoute('usermanual', array(
							'controller'	=> 'IndexController',
							'action'		=> 'index'));
				}
				$data = array(
						'tree'			=> json_encode($parents->getAllTreeByType($this->type,$user)),
						'topic_parent'	=> $validate,
						'formparam'		=> $paramform,
						'form'			=> $form,
						'id_parent'	=> $id,
				);
				return new ViewModel($data);
				?>
				<script>
				<?php 
					$paramform->setAction($this->basePath('/usermanual/index')); ?>
					$('#id_topicSend').val(<?php echo $id; ?>);
					document.paramform.submit();
				</script>
				<?php	
			}
		}else{
			return $this->redirect()->toRoute('usermanual', array(
					'controller'	=> 'IndexController',
					'action'		=> 'index'));
		}
	}
	
	
	public function updateAction(){
// 		$user = $this->getCurrentUserId();
		
		$user = 15;
		
		if($this->getRequest()->isPost()){
// 			$baseUrl = $this->_request->getBaseUrl();

			$parents= new TopicsServiceImpl();
			
			$form = new TopicsusermanualForm();
			$paramform = new ParamForm();
			
			
			if($_POST['id_topic_add'] != 0){

				$id = $_POST['id_topic_add'];
				$usermanualEdit = $parents->getTopicBy($id,$this->type,$user);
				
// 				$form->Cancelar->setAttrib('onclick','linkContentTopic('.$usermanualEdit[0]["parent"].')');
				
				if(!$usermanualEdit){
					return $this->redirect()->toRoute('usermanual', array(
							'controller'	=> 'IndexController',
							'action'		=> 'index',
							'info'			=> $usermanualEdit
							
					));
					
				}else{
					$form->setData($usermanualEdit[0]);
					$paramform->setData($usermanualEdit[0]);
				}

				$data = array(
						'tree'			=> json_encode($parents->getAllTreeByType($this->type,$user)),
						'formparam'		=> $paramform,
						'form'			=> $form,
						'info'			=> $usermanualEdit,
				);
				return new ViewModel($data);
			}else{
				
// 				$paramform->setAction($this->basePath('/usermanual/index'));
				
				$topic = $this->getRequest()->getPost();
				
				$usermanualEdit = $parents->editTopic($topic,$user);
// 				$id = $usermanualEdit['id_topic'];
// 				$topicParent = $parents->getTopicBy($id, $this->type,$user);
// 				$id_parent = $topicParent[0]['parent'];
				
				return $this->redirect()->toRoute('usermanual', array(
						'controller'	=> 'IndexController',
						'action'		=> 'index',
							
				));

			}
		}else{
    			return $this->redirect()->toRoute('usermanual', array(
    					'controller'	=> 'IndexController',
    					'action'		=> 'index'
    			));
		}
	}
	
	public function deleteAction(){
		$user = 15;
		
		if($this->getRequest()->isPost()){
// 			$user = $this->getCurrentUserId();
			$parents= new TopicsServiceImpl();
			
// 			$baseUrl = $this->_request->getBaseUrl();
			$deleteParam = $_POST['id_topic'];

			$validate = $parents->ValidateParent($this->type,$deleteParam,$user);
			$id_parent = $validate[0]['parent'];
			
			$parents->deleteParent($deleteParam);
			return $this->redirect()->toRoute('usermanual', array(
					'controller'	=> 'IndexController',
					'action'		=> 'index'));
// 			$paramform = new ParamForm();
// 			$paramform->setAttribute($baseUrl.'/usermanual/index');
// 			$this->view->formparam = $paramform;
			
			?>
				<script>
					$('#id_topicSend').val(<?php echo $id_parent; ?>);
					document.paramform.submit();
				</script>
			<?php
		}else{
			return $this->redirect()->toRoute('usermanual', array(
					'controller'	=> 'IndexController',
					'action'		=> 'index'));
		}
	}
	
} 
