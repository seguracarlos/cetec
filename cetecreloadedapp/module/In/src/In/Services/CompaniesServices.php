<?php
namespace In\Services;

use In\Model\CompaniesModel;
use Iofractal\Model\AddressModel;
use System\Services\UsersService;

class CompaniesServices
{
	private $companiesModel;
	private $addressModel;
	private $usersServices;
	
	// Instanciamos el modelo de companias
	public function getCompaniesModel()
	{
		return $this->companiesModel = new CompaniesModel();
	}
	
	// Instanciamos el modelo de direcciones
	public function getAddressModel()
	{
		return $this->addressModel = new AddressModel();
	}
	
	// Instanciamos el servicio de usuarios
	public function getUsersServices()
	{
		return $this->usersServices = new UsersService();
	}
	
	// Obtemos las companias (Clientes, prospectos o proveedores)
	public function fetchAll($type)
	{
		$companies = $this->getCompaniesModel()->fetchAll($type);
		$quiz      = array();
		
		foreach($companies as $row) {
			if (!isset($quiz[$row['id_company']])) {
				$quiz[$row['id_company']] = array(
						'id_company'    => $row['id_company'],
						'name_company'  => $row['name_company'],
						'brand'         => $row['brand'],
						'rfc'           => $row['rfc'],
						'website'       => $row['website'],
						'business'      => $row['business'],
						'interestingin' => $row['interestingin'],
						'record_date'   => $row['record_date'],
						'name'          => $row['name'],
						'surname'       => $row['surname'],
						'lastname'      => $row['lastname'],
						'email'         => $row['email'],
						'phone'         => $row['phone'],
						'name_job'      => $row['name_job'],
						'id_job_user'   => $row['id_job_user'],
				);
			}
		}
		return $quiz;
	}
	
	// Obtemos una compania por id (Clientes, prospectos o proveedores)
	public function getCompanyById($id)
	{
		$company = $this->getCompaniesModel()->getCompanyById($id);
		return $company;
	}
	
	// Agregamos una compania (Clientes, prospectos o proveedores)
	public function addCompany($data)
	{	
		// Guardamos un cliente (0)
		if($data['type_company'] == 1){
			// Creamos el arreglo con la informacion de la compania
			$company     = $this->createCompanyInfo($data);
			//echo "<pre>"; print_r($company);exit;
			// Guardamos la compania
			$saveCompany = $this->getCompaniesModel()->addCompany($company);
			//echo "<pre>"; print_r($saveCompany);exit;
			// Creamos la direccion de la compania
			$address     = $this->createAddressCompany($data, $saveCompany);
			//echo "<pre>"; print_r($address);
			// Guardamos la direccion
			$saveAddress = $this->getAddressModel()->addAddress($address);
			// Creamos el contacto
			$contact     = $this->createUserContact($data, $saveCompany);
			//echo "<pre>"; print_r($contact); exit;
			// Guardamos el contacto
			$saveContact = $this->getUsersServices()->addUser($contact);
		}
		// Guardamos un proveedor (1)
		else if($data['type_company'] == 2){
			// Creamos el arreglo con la informacion de la compania
			$company     = $this->createCompanyInfo($data);
			// Guardamos la compania
			$saveCompany = $this->getCompaniesModel()->addCompany($company);
			// Creamos la direccion de la compania
			$address     = $this->createAddressCompany($data, $saveCompany);
			// Guardamos la direccion
			$saveAddress = $this->getAddressModel()->addAddress($address);
			// Creamos el contacto
			$contact     = $this->createUserContact($data, $saveCompany);
			// Guardamos el contacto
			$saveContact = $this->getUsersServices()->addUser($contact);
		}
		// Guardamos un prospecto (2)
		else if($data['type_company'] == 3){
			// Creamos el arreglo con la informacion de la compania
			$company     = $this->createCompanyInfo($data);
			// Guardamos la compania
			$saveCompany = $this->getCompaniesModel()->addCompany($company);
			// Creamos la direccion de la compania
			$address     = $this->createAddressCompany($data, $saveCompany);
			// Guardamos la direccion
			$saveAddress = $this->getAddressModel()->addAddress($address);
			// Creamos el contacto
			$contact     = $this->createUserContact($data, $saveCompany);
			// Guardamos el contacto
			$saveContact = $this->getUsersServices()->addUser($contact);
		}
		
		return $saveCompany;
	}
	
	// Modificamos una compania (Clientes, prospectos o proveedores)
	public function editCompany($data)
	{	
		// Guardamos un cliente (0)
		if($data['type_company'] == 1){
			// Creamos el arreglo con la informacion de la compania
			$company     = $this->createCompanyInfo($data);
			// Modificamos la compania
			$editCompany = $this->getCompaniesModel()->editCompany($company);
			// Creamos la direccion de la compania
			$address     = $this->createAddressCompany($data, $company['id_company']);
			// Modificamos la direccion
			$editAddress = $this->getAddressModel()->editAddress($address);
			// Creamos el contacto
			$contact     = $this->createUserContact($data, $company['id_company']);
			// Guardamos el contacto
			$editContact = $this->getUsersServices()->editUser($contact);
		}
		// Guardamos un proveedor (1)
		else if($data['type_company'] == 2){
			// Creamos el arreglo con la informacion de la compania
			$company     = $this->createCompanyInfo($data); 
			// Modificamos la compania
			$editCompany = $this->getCompaniesModel()->editCompany($company);
			// Creamos la direccion de la compania
			$address     = $this->createAddressCompany($data, $company['id_company']);
			// Modificamos la direccion
			$editAddress = $this->getAddressModel()->editAddress($address);
			// Creamos el contacto
			$contact     = $this->createUserContact($data, $company['id_company']);
			// Guardamos el contacto
			$editContact = $this->getUsersServices()->editUser($contact);
		}
		// Guardamos un prospecto (2)
		else if($data['type_company'] == 3){
			// Creamos el arreglo con la informacion de la compania
			$company     = $this->createCompanyInfo($data);
			// Modificamos la compania
			$editCompany = $this->getCompaniesModel()->editCompany($company);
			// Creamos la direccion de la compania
			$address     = $this->createAddressCompany($data, $company['id_company']);
			// Modificamos la direccion
			$editAddress = $this->getAddressModel()->editAddress($address);
			// Creamos el contacto
			$contact     = $this->createUserContact($data, $company['id_company']);
			// Guardamos el contacto
			$editContact = $this->getUsersServices()->editUser($contact);
		}
	
		return $editCompany;
	}
	
	/*
	 * Eliminar compania
	 */
	public function deleteCompany($id)
	{
		$idCompany = (int) $id;
		$delete    = $this->getCompaniesModel()->deleteCompany($idCompany);
		return $delete; 
	}
	
	// Genera array de compania
	public function createCompanyInfo($data)
	{
		// Guardamos un cliente (0)
		if($data['type_company'] == 1){
			$interestingin = $data['interestingin'];
			$company       = 1;
			$nameBank      = "";
			$interbak      = "";
			$number        = "";
			$sucursal      = "";
		}
		// Guardamos un proveedor (1)
		else if($data['type_company'] == 2){
			$interestingin = "";
			$company       = 2;
			$nameBank      = $data['name_bank'];
			$interbak      = $data['interbank_clabe'];
			$number        = $data['number_acount'];
			$sucursal      = $data['sucursal_name'];
		}
		// Guardamos un prospecto (2)
		else if($data['type_company'] == 3){
			$interestingin = $data['interestingin'];
			$company       = 3;
			$nameBank      = "";
			$interbak      = "";
			$number        = "";
			$sucursal      = "";
		}
		
		// Arreglo con los datos de las companias
		$company = array(
			'company_isactive'  => 1,
			'id_update_actions' => 1,
			'id_company'        => $data['id_company'],
			'name_company'      => $data['name_company'],
			'rfc'               => $data['rfc'],
			'brand'             => $data['brand'],
			'business'          => $data['business'],
			'website'           => $data['website'],
			'interestingin'     => $interestingin,
			'isprospect'        => 0,
			'ishost'            => 0,
			'cust_type'         => $company,
			'name_bank'         => $nameBank,
			'number_acount'     => $number,
			'interbank_clabe'   => $interbak,
			'sucursal_name'     => $sucursal,
			'type_client'       => (isset($data['type_client'])) ? $data['type_client'] : 0, 
		);
		
		return $company;
	}
	
	// Arreglo con los datos de las direcciones
	private function createAddressCompany($formData,  $id_company)
	{
		$adrress = array(
			//"id_address"   => $formData['id_address'],
			"company_id"   => $id_company,
			"street"       => $formData['street'],
			"number"       => $formData['number'],
			"interior"     => $formData['interior'],
			"state_id"     => $formData['state_id'],
			"district"     => $formData['district'],
			"postalcode"   => $formData['postalcode'],
			"neighborhood" => $formData['neighborhood'],
			"phone"        => $formData['phone'],
			"ext"          => $formData['ext'],
			"url_map"      => $formData['url_map']
		);
	
		return $adrress;
	}
	
	// Arreglo con los datos del contacto
	private function createUserContact($formData, $id_company)
	{
		if($formData['type_company'] == 1){
			$typeCompany = 1;
			$job         = $formData['id_job'];
			$cellphone = "";
		}else if($formData['type_company'] == 2){
			$typeCompany = 2;
			$job         = $formData['id_job'];
			$cellphone = "";
		}else if($formData['type_company'] == 3){
			$typeCompany = 3;
			
			if($job = $formData['id_job'] == 0){
				$job = 1;
			}else{
				$job = $formData['id_job'];
			}
			$cellphone = $formData['cellphone_contact'];
		}
		
		$userContact = array(
			'user_id'        => $formData['user_id'],
			'id_company'     => $id_company,
			'canlogin'       => $formData['canlogin'],
			'type'           => $typeCompany,
			'user_principal' => '1',
			'company_ID'     => $id_company,
			'name'           => $formData['name_contact'],
			'surname'        => $formData['surname_contact'],
			'lastname'       => $formData['lastname_contact'],
			'email'          => $formData['mail_contact'],
			'id_job'         => $job,
			'phone'          => $formData['phone_contact'],
			'cellphone' 	 => $cellphone,
			'user_name'      => $formData['user_name'],
			//'password'       => $formData['password'],
			'role'        => $formData['role'],
			'user_type'      => 3,
		);
		
		(isset($formData['password'])) ? $userContact['password'] = md5(trim($formData['password'])) : false;

		return $userContact;
	}
	
	/*
	 * CLIENTES MAS FRECUENTES
	 */
	public function getAllClientsActive($type)
	{
		$rows = $this->getCompaniesModel()->getAllClientsActive($type);
		return $rows;
	}

}