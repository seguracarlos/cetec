<?php
namespace Iofractal\Services;

use Iofractal\Model\AddressModel;

class AddressServices
{
	private $addressModel;
	
	// Instanciamos el modelo de direcciones
	public function getAddressModel()
	{
		return $this->addressModel = new AddressModel();
	}
	
	// Obtemos las direcciones
	public function fetchAll()
	{
		$states = $this->getAddressModel()->fetchAll();
		return $states;
	}
	
	// Agregamos direccion
	public function addAddress($data)
	{
		$address = $this->getAddressModel()->fetchAll();
		return $address;
	}
	
}