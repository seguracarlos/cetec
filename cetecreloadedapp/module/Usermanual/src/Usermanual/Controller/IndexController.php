<?php
namespace Usermanual\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Usermanual\Forms\ParamForm;
use Usermanual\Forms\UsermanualForm;


use Usermanual\Services\Impl\TopicsServiceImpl;
use Usermanual\Services\Impl\ContentsServiceImpl;
use Usermanual\Constants\Artefacttype;
use Zend\Filter\Compress\Zip;

// require_once APPLICATION_PATH .'/libs/dompdf/dompdf_config.inc.php';


class IndexController extends AbstractActionController
{
	
	/*
	 * variable privada $type hardcodeada debido a que no recoge el valor de la constante predefinida
	 */
	private $type;
	private $user = 15;
	
	public function __construct()
	{
		/*
		 * variable privada 
		 * @$user 
		 * hardcodeada debido a el inicio de sesión
		 */
		$this->type = Artefacttype::USERMANUAL;
// 		$this->type = ArtefactType::MEMORY;
	}
	
    public function indexAction()
    {
		$paramform	= new ParamForm();
        $parents	= new TopicsServiceImpl();
		
		$treeNode = $parents->getAllTreeByType($this->type,$this->user);
		    for($i = 0; $i < count($treeNode); $i++){
		
					if($treeNode[$i]['text'] != null){
		
						if($treeNode[$i]['parent'] == "#"){
							$treeNode[$i]['text'] = "<a style='color:#000000'
							onclick='parentLink()'
							id='parentNode'
							href='#'>".$treeNode[$i]['text']."</a>";
						}
		
						if($treeNode[$i]['type'] == "file"){
							$treeNode[$i]['text'] = "<a style='color:#000000'
							onclick='linkContent(".$treeNode[$i]['id'].")'
							id='".$treeNode[$i]['id']."' class='file'
							href='#'>".$treeNode[$i]['text']."</a>";
						}
		
						if($treeNode[$i]['type'] == "folder"){
							$treeNode[$i]['text'] = "<a style='color:#000000'
							id='".$treeNode[$i]['id']."' class='folder'
							onclick='linkContentTopic(".$treeNode[$i]['id'].")'
							href='#'>".$treeNode[$i]['text']."</a>";
						}
					}
				}
		$data = array(
				'tree'			=> json_encode($treeNode),
				'usermanual'	=> $parents->getAllParents($this->type,$this->user),
				'forms'			=> $paramform,
		);
		return new ViewModel($data);
    }
    
    public function addAction(){
    	
    	$form		= new UsermanualForm();
    	$paramform	= new ParamForm();
    	$parents	= new TopicsServiceImpl();

    	if($this->getRequest()->isPost()){
    		
    		$formData=$this->getRequest()->getPost();
    		$parentSave=$parents->addParent($formData,$this->type,$this->user);
    		
    		if($parentSave){
    			
    			return $this->redirect()->toRoute('usermanual', array(
    						'controller'	=> 'IndexController',
    						'action'		=> 'index'));
    		}
    		
    	}
    	$data = array(
    			'tree'		=>	json_encode($parents->getAllTreeByType($this->type,$this->user)),
    			'addform'	=>	$form,
    			'form'		=>	$paramform
    	);
    	return new ViewModel($data);
    }
    
    
    public function updateAction(){
    	
    	$form=new UsermanualForm();
    	$paramform =new ParamForm();
    	$parents= new TopicsServiceImpl();
    	
    	if ($this->getRequest()->isGet()){
    		$id = $this->params()->fromRoute('m');
    		$usermanualEdit = $parents->getParentBy($id, $this->type,$this->user);
    		
    		$paramform->setData($usermanualEdit[0]);
    		$form->setData($usermanualEdit[0]);

    		$data = array(
    				'tree'			=>	json_encode($parents->getAllTreeByType($this->type,$this->user)),
    				'formparam'		=>	$paramform,
    				'form'			=>	$form,
    				'id'			=>	$id,
    				'topic_name'	=>	$usermanualEdit[0]['topic_name'],
    				'project_name'	=>	$usermanualEdit[0]['project_name'],
    				'description'	=>	$usermanualEdit[0]['description'],
    		);
    		
    		return new ViewModel($data);
    		
    	/*
    	 * En caso de POST
    	 */	
    	}elseif($this->getRequest()->isPost()){
//     		$user = $this->getCurrentUserId();
//     		$baseUrl = $this->_request->getBaseUrl();
    		$id = $_POST['id_topic'];
    		$formData=$this->getRequest()->getPost();
    		
    		$parentSave=$parents->editParent($formData,$this->type,$this->user);

    		}else{
    			$topic = $this->getRequest()->getPost();
    			$id = $topic['id_topic'];
    			$usermanualEdits = $parents->editParent($topic,$this->user);
    		}
    		if(!$usermanualEdit){
    			return $this->redirect()->toRoute('usermanual', array(
    					'controller'	=> 'IndexController',
    					'action'		=> 'index'));
    		}else{
	    		return $this->redirect()->toRoute('usermanual', array(
	    						'controller'	=> 'IndexController',
	    						'action'		=> 'index'));
    		}
    	
    	
    }
    
    public function deleteAction(){
    	$parents= new TopicsServiceImpl();
    	$deleteParam = $this->params()->fromRoute('m');
    	$parents->deleteParent($deleteParam);
    	$data = array(
    			'tree'	=>	json_encode($parents->getAllTreeByType($this->type,$this->user))
    	);
    	
    	return $this->redirect()->toRoute('usermanual', array(
    			'controller'	=> 'IndexController',
    			'action'		=> 'index'));
    	return new ViewModel($data);
    }
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function consultAction(){
    	$parents= new TopicsServiceImpl();
    	$this->view->tree=json_encode($parents->getAllTreeByType($this->type,$this->user));
    	$this->view->usermanual=$parents->getAllParents($this->type,$this->user);
    }
    
    public function consultusermanualAction(){
    	$user = $this->getCurrentUserId();
    	$param = $this->_getParam('t');
    	$id = (int)$param;
    	if(!$id){
    		$id = 0;
    	}
    	$topics= new TopicsServiceImpl();
    	$content= new ContentsServiceImpl();
    	$this->view->tree=json_encode($topics->getAllTreeByType($this->type,$this->user));
    	$validate = $topics->ValidateParent($this->type,$id,$this->user);
    	if(!$validate){
    		$this->_redirect('Gutenberg/docs_usermanual/consult');
    	}
    	$this->view->content=$content->getContentById($id);
    	$this->view->parent=$validate;
    	$this->view->topics=$topics->getTopics($this->type,$id,$this->user);
    }
    
    
}