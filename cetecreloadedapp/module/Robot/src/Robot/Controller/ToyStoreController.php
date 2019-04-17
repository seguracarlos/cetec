<?php


namespace Robot;

use BaseController;



include APPLICATION_PATH . "/controllers/BaseController.php";

class ToyStoreController extends BaseController
{

    public function init(){
    	BaseController::init();
    }
    
    public function indexAction()
    {
    	
    	echo "hoa";
        // action body
    }
    
    public function addAction(){}


}

