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
	//Actualiza información de la empresa
	public function  updateCompany($formData,$id_company){
		$data = array(
			'id_company' => $id_company,
			'name_company' => $formData['name_company'],
			'brand' => $formData['brand'],
			'rfc' => $formData['rfc'],
			'website' => $formData['website'],
			'name_bank' => $formData['name_bank'],
			'number_acount' => $formData['number_acount'],
			'interbank_clabe' => $formData['interbank_clabe'],
			'sucursal_name' => $formData['sucursal_name'],
			'business' => $formData['business'],
		);
		$company = $this->getCompanyModel()->updateCompany($data);
		return $company;
	}
}