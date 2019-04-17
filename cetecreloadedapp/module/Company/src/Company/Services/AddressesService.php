<?php
namespace Company\services;

use Company\Model\AddressesModel;
//Componente para cifrar contrase�as
use Zend\Crypt\Password\Bcrypt;

class AddressesService
{
	private $addressesModel;

	public function getAddressesModel()
	{
		return $this->addressesModel = new AddressesModel();
	}
	
	public function updateAddressCompany($formData,$id_company){
		$postalcode = $this->getAddressesModel()->getPostalCode($formData['colonys']);
		$data = array(
				'company_id' => $id_company,
				'street' => $formData['street'],
				'postalcode' => $postalcode[0]['postal_code'],
				'number' => $formData['number_ext'],
				'interior' => $formData['number_int'],
				'neighborhood' => $formData['colonys'],
				'state_id' => $formData['states'],
				'district' => $formData['districts'],
				'phone' => $formData['phone'],
				'ext' => $formData['extension'],
				'url_map' => $formData['map']
		);
		$addressCompany = $this->getAddressesModel()->updateAddressCompany($data);
		return $addressCompany;
	}
	public function getStates(){
		$states = $this->getAddressesModel()->getStates();
		return $states;
	}
	
	public function getDistricts($state){
		$districts = $this->getAddressesModel()->getDistricts($state);
		return $districts;
	}
	
	public function getColonys($district){
		$colonys = $this->getAddressesModel()->getColonys($district);
		return $colonys;
	}
	public function getPostalCode($colony){
		$postalcode = $this->getAddressesModel()->getPostalCode($colony);
		return $postalcode;
	}

}