<?php
namespace Advertising\Controller;

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
		$data = array(
				
		);
		return new ViewModel($data);

    }
    
    public function addAction(){
    	
    }
    
    public function editAction(){
    	
    }
    
    public function deleteAction(){
    	
    }
}