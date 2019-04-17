<?php
namespace Registrationusers\Service;

use Registrationusers\Model\RegisterModel;
use System\Model\UsersRolesModel;
use System\Model\UsersDetailsModel;
use System\Model\UsersAddressesModel;
use Auth\Utility\UserPassword;

class RegisterService
{
	private $registerModel;
	private $usersRolesModel;
	private $usersDetailsModel;
	private $usersAddressesModel;
	
	/**
	 * @return \Registrationusers\Model\RegisterModel
	 */
	public function getModel()
	{
		return $this->registerModel = new RegisterModel();
	}
	
	protected function getUsersRolesModel()
	{
		return $this->usersRolesModel = new UsersRolesModel();
	}
	
	protected function getUsersDetailsModel()
	{
		return $this->usersDetailsModel = new UsersDetailsModel();
	}
	
	protected function getUsersAddressesModel()
	{
		return $this->usersAddressesModel = new UsersAddressesModel();
	}
	
	/*
	 * OBTENER TODOS LOS DATOS
	 */
	public function fetchAll()
	{
		$rows = $this->getModel()->fetchAll();
		
		return $rows;
	}
	
	public function getRowById($id)
	{
		$row = $this->getModel()->getRowById($id);
		return $row;
	}
	
	public function getRowByMail($mail)
	{
		$row = $this->getModel()->getRowByMail($mail);
		return $row;
	}
	
	/*
	 * AGREGAR NUEVO REGISTRO
	 */
	public function addRow($data)
	{
		$book 	  = $this->createRowInfo($data);
		$saveRow = $this->getModel()->addRow($book);
		
		// Creamos arreglo con el rol y id del usuario
		$userRoleObject   =$this->createObjectRoleUser($saveRow);
		// Guardamos el ususario y su rol
		$addUserRole      = $this->getUsersRolesModel()->addRoleToUser($userRoleObject );
		
		// Creamos el arreglo con el detalle del usuario
		$userDetailObject = $this->createObjectUserDetails($saveRow);
		//echo "<pre>"; print_r($userDetailObject);
		// Guardamos el detalle de usuario
		$user_detail      = $this->getUsersDetailsModel()->addUserDetails($userDetailObject);
		
		// Creamos objeto con los datos de direccion del usuario
		$direccion = $this->createAddressesUser($data, $saveRow);
		//echo "<pre>"; print_r($direccion); exit;
		$saveAddress = $this->getUsersAddressesModel()->addUserAddresses($direccion);

		return $saveRow;
	}
	
	public function editRow($data)
	{
		//$book 	  = $this->createRowInfo($data);
		$saveBook = $this->getModel()->editRow($data);
	
		return $saveBook;
	}
	
	
	private function createRowInfo($data)
	{
		$createPassword = new UserPassword();
		$info = array(
				'id_company'     => 1,
				'user_id'        => (isset($data['user_id']) ? $data['user_id'] : ''),
				'user_type'      => 1,
				'name'           => isset($data['nombre']) ? trim($data['nombre']) : "",
				'surname'        => isset($data['apellidom']) ? trim($data['apellidom']) : "",
				'lastname'       => isset($data['apellidop']) ? trim($data['apellidop']) : "",
				'email'          => trim($data['email']),
				'canlogin'       => 0,
				'sexo'           => isset($data['sexo']) ? trim($data['sexo']) : "",
				'curp'           => isset($data['curp']) ? trim($data['curp']) : "",
				'datebirth'      => isset($data['fecha']) ? trim($data['fecha']) : "",
				//isset($formData['password']) ? 'password' => md5(trim($formData['password'])) : false
				'password'       => isset($data['password']) ? $createPassword->securePassword(trim($data['password'])) : false

		);
		
		return $info;
	}
	
	/*
	 * Crear arreglo con el rol y id del usuario
	 */
	private function createObjectRoleUser($id)
	{
		$data = array(
				'user_id' => $id,
				'role_id' => 4,
		);
	
		return $data;
	}
	
	/*
	 * Crear arreglo con el detalle del usuario
	 */
	private function createObjectUserDetails($id)
	{
		$data = array(
				"acl_users_id"    => $id,
				"contract_type"   => "2",
				"date_admission"  => \Util_Tools::dateDBFormat("/", date('Y-m-d')),
				"cost"            => 0,
				"mannerofpayment" => 0,
				//"local_phone"     => "",
				"cellphone"       => "",
				"period"          => 0,
				"birthday"        => "0000-00-00"
		);
	
		return $data;
	}
	
	/*
	 * Arreglo con la direccion del ussuario
	 */
	public function createAddressesUser($data, $id_user)
	{
		$address = array(
				"user_id"      => $id_user,
				"street"       => (isset($data['street'])) ? trim($data['street']) : "",
				"postalcode"   => (isset($data['postalcode'])) ? $data['postalcode'] : "",
				"number"       => (isset($data['number'])) ? $data['number'] : "",
				"interior"     => (isset($data['interior'])) ? $data['interior'] : "",
				"state_id"     => (isset($data['state_id'])) ? $data['state_id'] : "",
				"district_id"  => (isset($data['district'])) ? $data['district'] : "",
				"neighborhood" => (isset($data['postalcode'])) ? $data['neighborhood'] : "",
		);
	
		return $address;
	}
	
	public function deleteRow($id)
	{
		$idRow     = (int) $id;
		$deleteRow = $this->getModel()->deleteRow($idRow);
		return $deleteRow;
	}
	
}