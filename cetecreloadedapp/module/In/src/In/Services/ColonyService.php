<?php
namespace Company\services;

use Company\Model\ColonyModel;
//Componente para cifrar contrase�as
use Zend\Crypt\Password\Bcrypt;

class ColonyService
{
	private $colonyModel;

	public function getColonyModel()
	{
		return $this->colonyModel = new ColonyModel();
	}

	public function getDirection($colony)
	{
		$direction = $this->getColonyModel()->getDirection($colony);
		return $direction;
	}
	
	public function getStates(){
		$states = $this->getColonyModel()->getStates();
		return $states;
	}
	
	public function getDistricts($state){
		$districts = $this->getColonyModel()->getDistricts($state);
		return $districts;
	}
	
	public function getColonys($district){
		$colonys = $this->getColonyModel()->getColonys($district);
		return $colonys;
	}
	public function getPostalCode($colony){
		$postalcode = $this->getColonyModel()->getPostalCode($colony);
		return $postalcode;
	}

}