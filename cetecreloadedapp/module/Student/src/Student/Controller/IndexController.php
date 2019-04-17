<?php
namespace Student\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Student\Constants\Artefacttype;

use Usermanual\Forms\ParamForm;
use Usermanual\Forms\UsermanualForm;


use Usermanual\Services\Impl\TopicsServiceImpl;
use Usermanual\Services\Impl\ContentsServiceImpl;

class IndexController extends AbstractActionController
{
	/*
	 * variable privada 
	 * @$user 
	 * hardcodeada debido a el inicio de sesión
	 */
	private $user = 15;
	
	private $type;
	
	public function __construct(){
//     	$user = $this->getCurrentUserId();
		$this->type = ArtefactType::USERMANUAL;
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
    
    public function contentsAction(){
    	$paramform = new ParamForm();
    	if($this->getRequest()->isPost()){
    			
    		// 			$user = $this->getCurrentUserId();
    		// 			$baseUrl = $this->_request->getBaseUrl();
    			
    		$id = $_POST['id_topic_content'];
    		$topics= new TopicsServiceImpl();
    	
    		$validate = $topics->ValidateParent($this->type,$id,$this->user);
    	
    		if(!$validate){
    			return $this->redirect()->toRoute('student', array(
    					'controller'	=> 'IndexController',
    					'action'		=> 'index'
    			));
    		}
    			
    	
    		$data = array(
    				'tree'		=> json_encode($topics->getAllTreeByType($this->type,$this->user)),
    				'parent'	=> $validate,
    				'formparam'	=> $paramform,
    				'parent'	=> $validate,
    				'topics'	=> $topics->getTopicsParents($this->type,$id,$this->user),
    		);
    		return new ViewModel($data);
    			
    	}else{
    		return $this->redirect()->toRoute('student', array(
    				'controller'	=> 'IndexController',
    				'action'		=> 'index'));
    	}
    }
}