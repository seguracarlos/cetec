<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Iofractal\Controller;
use Iofractal\Controller\BaseController;
use Zend\View\Model\ViewModel;

use Auth\Form\LoginForm;
use Auth\Form\Filter\LoginFilter;
use Auth\Utility\UserPassword;
use Zend\Session\Container;

//class IndexController extends AbstractActionController
class IndexController extends BaseController
{
    public function indexAction()
    {
   		$layout = $this->layout();
    	$layout->setTemplate('layout/login');
    	
    	$viewModel = new ViewModel();
    	$loginForm = new LoginForm('loginForm');
    	$loginForm->setInputFilter(new LoginFilter());
    	$loginForm->setAttribute('action', $this->getRequest()->getBaseUrl()."/login");
    	
    	$viewModel->setVariable('loginForm', $loginForm);
    	return $viewModel;
    }
 
}
