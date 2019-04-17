<?php


namespace Horus;

use BaseController;
use Application_Form_Clock;
use ClockService;
use Privilege;
use Clock;



include_once APPLICATION_PATH . '/controllers/BaseController.php';
include_once APPLICATION_PATH . '/forms/Clock.php';
include_once APPLICATION_PATH . '/services/ClockService.php';
include_once APPLICATION_PATH . '/util/Tools.php';

class ClockController extends BaseController {

    function init() {
        BaseController::init();
    }

    public function indexAction() {
    	
    	$form = new Application_Form_Clock();
    	$serviceClock = new ClockService();
    	$msg = "";
    	
    	$chickIn = $this->_getParam('checkin');
    	$checkOut = $this->_getParam('checkout');
    	$idUSerLog = $this->getCurrentUserId();
		$acl1 = $this->getACLAuth();
    	
		if ($this->getCurrentUserIdRole() == Privilege::CLIENTE) {
    		$usersArr = $serviceClock->getAllUsersClocksByUserId($this->getCurrentUserName(), $this->getCurrentUserId());
    	}else{
    		$usersArr = $serviceClock->getClocksByHistoryCombi($acl1->_user, $acl1->_getUserRoleId);
    	}
		
		if ($this->getRequest()->isPost()) {
		    
		    if(isset($chickIn) == Clock::ENTRADA){//click en el boton de entrada
		    	//echo "entra";
		    	$type = 1;//1 es entrada
		    	$isInto = $serviceClock->isUserRegistered($idUSerLog, $type);
		    	if($isInto == "false"){//igual a false, no ha entrado
		    		$response = $serviceClock->register($type, $idUSerLog);
		    		$user = $this->view->user;
		    		if($response == "true"){//guardo correctamente
		    			$msg = $user. ", tu entrada fue registrada, Bienvenido :)";
		    		$this->_redirect('/Horus/clock/index');
		    		}else{//no guardo, error
		    			$msg = $user . " Hubo un error al marcar la entrada, intentalo otra vez :( ";
		    		}
		    	}else{//ya marco hora de entrada el dia de hoy
		    		$msg = "Ya marcaste hora de entrada.";
		    	}
		    }else if(isset($checkOut) == Clock::SALIDA){//clien en el bototn de salida
		    	//echo "salida";
		    	$type = 2;//2 es salida
		    	$isInto = $serviceClock->isUserRegistered($idUSerLog, $type);
		    	if($isInto == "false"){//si no ha marcado hora de salida hoy
		    		$response = $serviceClock->register($type, $idUSerLog);
		    		if($response == "true"){//guardo correctamente
		    			$msg = "Salida registrada, Hasta luego :)";
		    		}else{//no guardo, error
		    			$msg = "Hubo un error al marcar la salida, intentalo otra vez :( ";
		    		}
		    	}else{//si ya marco hora de salida
		    		$msg = "Ya marcaste hora de salida.";
		    	}
		    }
		    
		    print "<script>alert('$msg');</script>";
		}
		
		$this->view->form = $form;
		$this->view->usersAr=$usersArr;
		
		if ($this->getCurrentUserIdRole() == Privilege::CLIENTE) {
			$this->view->dataCols=$usersArr;
		}else{
			$this->view->dataCols=$usersArr[1];
		}

		$this->view->yoId=$usersArr[2];
    }
    
    /*
     * Get user check in- check out history
     * User ID is get from session
     * */
    public function historyAction(){
    	$service = new ClockService();
    	//$this->view->user;
    	$historyClock = $service->getHistoryUser();
    	$this->view->historyClock = $historyClock;
    }
    
    private function createChartArray($usersArray){
    	
    	$dataColumn;
    	
//    	foreach ($usersArray as $value) {
//    		$dataColumn="data.addColumn('number', '" . $value['user_name'] . "');";
//    	}
    	
    	for ($i = 0; $i < count($usersArray); $i++) {
    		$dataColumn[$i]="data.addColumn('number', '" . $usersArray[$i]['label'] . "');";
    	//	echo "akki vlaafsf   " . $usersArray[$i]['label'];
    	}
    	
    	return $dataColumn;
    	
    	//data.addColumn('number', '$value['user_name']');
    	
    }
    
    public function addAction(){}
    
}

