<?php


namespace In\Income;

use Zend_Controller_Action;
use Application_Form_Books;



class SalesManagerController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
        echo "dad";
    }
    
    public function getAction()
    {
    	echo "get";	
    	
    	$this->view->form = new Application_Form_Books();
    	
    }


}

