<?php


namespace Out;

use BaseController;
use Application_Form_CxpForm;
use Application_Service_Impl_CxpServiceImpl;
use Zend_Json;
use Application_Util_Tools;



include_once APPLICATION_PATH . '/controllers/BaseController.php';
include_once APPLICATION_PATH . "/Constants/Constants.php";

class CxpController extends BaseController
{
    function init()
    {
    	 BaseController::init();
    }

    /*
	 * Metodo index, muestra los registros de las cxp
	 */
    public function indexAction()
    {
        $form = new Application_Form_CxpForm();
    	$service = new Application_Service_Impl_CxpServiceImpl();
    	$pays = $this->_getParam('ordtableId');
    	
    	if (isset($pays)){
    			
    		$payments = $service->getPaymentMakeByOrder($pays,'true');
    		if (($payments == "")||($payments == 0)){
    			echo Zend_Json::encode(array('status' => 'null'));
    			exit;
    		}else {
    			echo Zend_Json::encode(array('status' => 'ok','data' => $payments));
    			exit;
    		}
    	
    	}else{
    		
    		$allOrder = $service->getAllOrderPayments();
    		if($allOrder == null){
    			$this->view->cxp = 0;
    		}else{
    			$this->view->cxp = $allOrder;
    		}
    		
    		$this->view->form = $form;
    		
    	} 
    }
	/*
	 * Metodo para gusrdar los registros de las cxp
	 */
	 public function addpaymentAction() {   
	 			
		$service = new Application_Service_Impl_CxpServiceImpl();
		
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			$idOrder = $this->_getParam("iO");

			$service->saveMadePayment($idOrder, Application_Util_Tools::notCurrencyOutPoint($formData['amount']), Application_Util_Tools::dateDBFormat("/", $formData['payment_date']), $formData['account'], $formData['reference']);
			$ifUpdate = $service->updatePayStatus($formData['id_payment'], "1");
			
			if($ifUpdate){
				echo Zend_Json::encode(array("response" => "true"));
			}else{
				echo Zend_Json::encode(array("response" => "false"));
			}
			
		}
		exit;
    }
    
    public function getpaymentsAction(){
    	$service = new Application_Service_Impl_CxpServiceImpl();
    	$sD = $this->_getParam("sD");
    	
    	if(isset($sD)){
    		if($sD == "Pm"){
    			$allCxpPay = $service->getTotalCxpPay();
    			if($allCxpPay != null){
    				if($allCxpPay[0]['total'] != null){
    					echo Zend_Json::encode(array("response" => "ok", "data" => $allCxpPay[0]['total']));
    				}else{
    					echo Zend_Json::encode(array("response" => "fail"));
    				}				
    			}else{
    				echo Zend_Json::encode(array("response" => "fail"));
    			}
    		}else if($sD == "PnM"){
    			$allCxpNoPay = $service->getTotalNoCxpPay();
    			if($allCxpNoPay != null){
    				if($allCxpNoPay[0]['total'] != null){
    					echo Zend_Json::encode(array("response" => "ok", "data" => $allCxpNoPay[0]['total']));
    				}else{
    					echo Zend_Json::encode(array("response" => "fail"));
    				}
    			}else{
    				echo Zend_Json::encode(array("response" => "fail"));
    			}
    		}
    			
    	}
    	
    	exit;
    }
	
    /*
	 * Metodo para Actualizar los registros de las cxp
	 */
	 public function updateviewAction()
    {   
    }
	 public function deleteviewAction()
	 {
     }//end of delete action
    
	public function addAction(){}
   
}

/*
 * 
 * 		    $formData = $this->getRequest()->getPost();
			
		    if ($form->isValid($formData)) {
		    	
		    if($paymentAmount == $formData['amount'] || $formData['amount'] < $paymentAmount){//si el pago es menor o igual al pago
		    		
		    		$savePayment = $service->saveAndUpdateMadePayment($accion = null, $paymentAmount, $formData['amount'], Application_Util_Tools::dateDBFormat("/", $formData['payment_date']), $idPayemntOrder, $idOrder, $formData['account'], $formData['reference']);
					
		    		print_r($savePayment);
					exit;
		    		
		    	}elseif($paymentAmount < $formData['amount']){//si no, si es mayor el pago de usuario al pago de la cxc
		    		
		    		$payments = $service->getDatePaymentByOrder($idOrder);//todos los pagos del projecto seleccionado
			    	
		    		$rowAmount = array();
			    	$rowDelete = array();
			    	$rowUpdate = array();
			    	
			    		if(count($payments)){//si hay pagos --- cuentalos
			    			$numberOfPayments = count($payments);//numero de pagos
			    			
			    			foreach ($payments as $key => $payment){//itera los apgos
			    				if($payment['amount'] != 0.00){//de cada pago obten los que sean difertes a 0.00
		    						$rowAmount[] = $payment;
			    				}
			    			}		
			    		}
		    		
			    			$amountRemain = $formData['amount'];//el total del pago que inserto el usuario
			    			$remain = 0;//inicializa remain
			    			
				    		foreach ($rowAmount as $pay){//itera las filas que no sean 0.00
				    			if($amountRemain != 0){//si el monto del suario es 0
					    			if($pay['amount'] <= $amountRemain){//y que el pago de la fila sea menor o igual al pago del cliente
					    				$remain = $amountRemain - $pay['amount'];//al pago del cliente restale le pago de la fila
					    				$amount_update = 0.00;//pago para eliminar las fials
					    				$amountRemain = $remain;//el monto restante el igual a la resta el pago dde la fila con el del usuario
					    				$service->updateMadePayment($pay['id_paymentorder'], $amount_update);//actualiza la fila con el pago que le toca
					    			}
				    			}
				    			if($amountRemain != 0){//si el monto restante no quedo en 0 
					    			if($pay['amount'] > $remain){//y el pago de la fila es mayor a al pago restante 
					    				$amountRemain = $pay['amount'] - $remain;//al pago de la fila restale el pago restante
					    				$service->updateMadePayment($pay['id_paymentorder'] +1 , $amountRemain);////actualiza el pago por el iddel apgo con el monto restante
					    				$amountRemain = $remain - $remain;//al monto restante restalo por el mismo y termina con el pago del cliente
					    			}
				    			}	
				    		}
				    	
				   echo $service->saveMadePayment($idOrder, $formData['amount'], Application_Util_Tools::dateDBFormat("/", $formData['payment_date']), $formData['account'], $formData['reference']);//guarda el pago que hizo el cleinte    		
		    	
		    	}
			}
 * */