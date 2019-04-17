<?php

namespace Iofractal\Controller;
use Iofractal\Controller\BaseController;
use Zend\View\Model\ViewModel;

use Iofractal\Services\DistrictServices;
use Iofractal\Services\NeighborhoodServices;

class AddressesController extends BaseController
{
	
	// Municipios
    public function getalldistrictAction()
    {
    	$districtServices = new DistrictServices();
    	$request          = $this->getRequest();
        $response         = $this->getResponse();

        if($request->isPost()){
            $inf = $request->getPost();
            //print_r($inf);exit;
            $district = $districtServices->fetchAll($inf['id_state']);
            	
            if($district){
            	$response->setContent(\Zend\Json\Json::encode(array('response' => "ok", "data" => $district)));
            }else{
            	$response->setContent(\Zend\Json\Json::encode(array('response' => "fail", "data" => "Error desconosido, consulta al administrador *.*")));
            }
        }

        return $response;
    }
    
    // Colonias
    public function getallneighborhoodAction()
    {
    	$neighborhoodServices = new NeighborhoodServices();
    	$request              = $this->getRequest();
    	$response             = $this->getResponse();
    
    	if($request->isPost()){
    		$inf = $request->getPost();
    		//print_r($inf);exit;
    		$neighborhood = $neighborhoodServices->fetchAll($inf['id_district']);
    		 
    		if($neighborhood){
    			$response->setContent(\Zend\Json\Json::encode(array('response' => "ok", "data" => $neighborhood)));
    		}else{
    			$response->setContent(\Zend\Json\Json::encode(array('response' => "fail", "data" => "Error desconosido, consulta al administrador *.*")));
    		}
    	}
    
    	return $response;
    }
 
}
