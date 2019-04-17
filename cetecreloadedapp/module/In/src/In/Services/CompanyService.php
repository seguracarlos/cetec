<?php
namespace Company\services;

use Company\Model\CompanyModel;
//Componente para cifrar contrase�as
use Zend\Crypt\Password\Bcrypt;

class CompanyService
{
	private $companyModel;
	
	public function getCompanyModel()
	{
		return $this->companyModel = new CompanyModel();
	}
	
	//Recupera la compañia por usuario con sessión inicializada
	public function getCompanyByUser($id_company)
	{
		$company = $this->getCompanyModel()->getCompanyByUser($id_company);
		return $company;
	}
	//Actualizar el logo de la empresa
	public function updateImgLogo($img,$company){
		$logo = $this->getCompanyModel()->updateImgLogo($img,$company);
		return $logo;
	}
	//Consultar logo de la empresa para el ajax
	public function getLogo($company){
		$logo = $this->getCompanyModel()->getLogo($company);
		return $logo;
	}
	//Actualiza información de la empresa
	public function  updateCompany($formData,$id_company){
		$data = array(
			'id_company' => $id_company,
			'id_colony' => $formData['colonys'],
			'name_company' => $formData['name_company'],
			'brand' => $formData['brand'],
			'rfc' => $formData['rfc'],
			'website' => $formData['website'],
			'phone' => $formData['phone'],
			'extension' => $formData['extension'],
			'map' => $formData['map'],
			'name_bank' => $formData['name_bank'],
			'number_acount' => $formData['number_acount'],
			'interbank_clabe' => $formData['interbank_clabe'],
			'sucursal_name' => $formData['sucursal_name'],
			'business' => $formData['business'],
			'street' => $formData['street'],
			'number_ext' => $formData['number_ext'],
			'number_int' => $formData['number_int']
		);
		$company = $this->getCompanyModel()->updateCompany($data);
		return $company;
	}
}