<?php
namespace System\Services;

use System\Model\UsersModel;
use System\Model\UsersDetailsModel;
use System\Model\UsersAddressesModel;
//Componente para cifrar contrasenas
use Zend\Crypt\Password\Bcrypt;
//add this to your class
use Zend\Crypt\BlockCipher;
use System\Model\UsersRolesModel;
use Zend\Code\Scanner\Util;
use Zend\Validator\Barcode\Issn;
use Auth\Utility\UserPassword;
use Horus\Services\PaymentServices;
use Exams\Model\ExamscoreDaoImpl;
use Exams\Services\ExtraAttempsService;

class UsersService
{
	private $userModel;
	private $usersRolesModel;
	private $usersDetailsModel;
	private $usersAddressesModel;
	
	protected function getUserModel()
	{
		return $this->userModel = new UsersModel();
	}
	
	protected function getUsersRolesModel()
	{
		return $this->usersRolesModel = new UsersRolesModel();
	}
	
	/*
	 * Modelo de detalle de usuarios
	 */
	protected function getUsersDetailsModel()
	{
		return $this->usersDetailsModel = new UsersDetailsModel();
	}
	
	/*
	 * Modelo de direcciones de usuario
	 */
	protected function getUsersAddressesModel()
	{
		return $this->usersAddressesModel = new UsersAddressesModel();
	}
	
	public function fetchAll($type_user)
	{
		$rows  = $this->getUserModel()->fetchAll($type_user);
		$quiz  = array();
		$inscription = 0;
		foreach($rows as $row) {
			if (!isset($quiz[$row['user_id']])) {
				$paymentService = new PaymentServices();
				$scoresService = new ExamscoreDaoImpl();
				$attempsService = new ExtraAttempsService();
				$idExam = 0;
				
				if($row['trim'] == 1){
					$idExam = 269;
				}elseif($row['trim'] ==2){
					$idExam = 340;
				}elseif($row['trim'] == 3){
					$idExam = 341;
				}elseif($row['trim'] == 4){
					$idExam = 342;
				}elseif($row['trim'] == 5){
					$idExam = 343;
				}elseif($row['trim'] == 6){
					$idExam = 344;
				}
				
				$normalscoresByUser = $scoresService->getNormalScoreByUser($row['user_id'],$idExam);
				$extraScoresByUser = $scoresService->getExtraScoreByUser($row['user_id'],$idExam);
				$allScoresByUser    = $scoresService->getAllScoresCurrentTrimByUser($row['user_id'],$idExam);
				$allTrimScores = $scoresService->getAllScoresByUser($row['user_id']);
				$extraAttempsByUser = $attempsService->getExtraAttempsByUser($row['user_id'],$idExam);
				$activeExtraAttemps = $attempsService->getExtraAttempsActiveByUser($row['user_id'],$idExam);
				$paymentUser = $paymentService->getPaymentInfoByUser($row['user_id']);
				$month = "N/A";
				$highestScore = "";
				
				
				foreach ($allScoresByUser as $value) {
					if ($value['totalscore'] >= $highestScore)
						$highestScore = $value['totalscore'];
				}
				

				$quiz[$row['user_id']] = array(
						'user_id'         => $row['user_id'],
						'name'            => $row['name'],
						'surname'         => $row['surname'],
						'lastname'        => $row['lastname'],
						'email'           => $row['email'],
						'phone'           => $row['phone'],
						'curp'            => $row['curp'],
						'fechaNac'        => $row['datebirth'],
						'user_name'       => $row['user_name'],
						'id_job'          => $row['id_job'],
						'id_department'   => $row['id_department'],
						'cost'            => $row['cost'],
						'period'          => $row['period'],
						'mannerofpayment' => $row['mannerofpayment'],
						'date_admission'  => $row['date_admission'],
						'role_id'         => $row['role_id'],
						'role_name'       => $row['role_name'],
						'name_job'        => $row['name_job'],
						'd_name'          => $row['d_name'],
						'canlogin'        => $row['canlogin'],
						'trim'            => $row['trim'],
						'numSep'          => $row['numSep'],
						'documents'       => $row['documents'],
						'payments'        => $paymentUser,
						'score'           => $normalscoresByUser,
						'lastScore'       => $allScoresByUser,
						'extraAttemps'    => $extraAttempsByUser,
						'activeExtraAttemps' => $activeExtraAttemps,
						'highestScore' => $highestScore,
						'globalScores' => $allTrimScores
 				);
			}
		}
		
		foreach ($quiz as $key => $value)
		{
			if ($value['lastname'] === "")
			{
				unset($quiz[$key]);
				$quiz[] = $value;
			}
		}
		
		// rebuild array index
		$quiz = array_values($quiz);
		return $quiz;
	}
	
	/*
	 * Obtener usuario por id
	 */
	public function getUserById($id_user)
	{
		$row = $this->getUserModel()->getUserById((int) $id_user);
		$invert = explode("-",$row[0]['datebirth']);		
		$fecha_invert = $invert[2]."-".$invert[1]."-".$invert[0];
		
		$invert2 = explode("-",$row[0]['date_certificate']);
		$fecha_invert2 = $invert2[2]."-".$invert2[1]."-".$invert2[0];
		$row[0]['datebirth'] = $fecha_invert;
		$row[0]['date_certificate'] = $fecha_invert2;
		return $row;
	}
	
	public function addUser($formData)
	{	
		//echo "<pre>"; print_r($formData);  exit;
		// Crear arreglo con los datos del usuario
		$userObject       = $this->createObjectUsers($formData);
		//echo "<pre>"; print_r($userObject); exit;
		// Guardamos el ususario y regresamos id
		$user             = $this->getUserModel()->addUser($userObject);
		
		// Creamos arreglo con el rol y id del usuario
		$userRoleObject   =$this->createObjectRoleUser($formData, $user);
		//echo "<pre>"; print_r($userRoleObject);
		// Guardamos el ususario y su rol
		$addUserRole      = $this->getUsersRolesModel()->addRoleToUser($userRoleObject );
		
		// Creamos el arreglo con el detalle del usuario
		$userDetailObject = $this->createObjectUserDetails($formData, $user);
		//echo "<pre>"; print_r($userDetailObject);
		// Guardamos el detalle de usuario
		$user_detail      = $this->getUsersDetailsModel()->addUserDetails($userDetailObject);
		
		// Creamos objeto con los datos de direccion del usuario
		$direccion = $this->createAddressesUser($formData,$user);
		//echo "<pre>"; print_r($direccion); exit;
		$saveAddress = $this->getUsersAddressesModel()->addUserAddresses($direccion);
		 
		return $user;
	}
	
	//Editar un usuario
	public function editUser($formData)
	{	
		//echo "<pre>"; print_r($formData); exit;
		// Crear arreglo con los datos del usuario
		//$userObject        = $this->createObjectUsers($formData);
		//echo "<pre>"; print_r($userObject); exit;
		// Guardamos el ususario y regresamos id
		$editUser          = $this->getUserModel()->editUser($formData);
		
		// Creamos arreglo con el rol y id del usuario
// 		$userRoleObject    =$this->createObjectRoleUser($formData, $formData['user_id']);
// 		//echo "<pre>"; print_r($userRoleObject); exit;
// 		// Guardamos el ususario y su rol
// 		$editUserRole      = $this->getUsersRolesModel()->editRoleToUser($userRoleObject, $formData['user_id']);
		
// 		// Creamos el arreglo con el detalle del usuario
// 		$userDetailObject = $this->createObjectUserDetails($formData, $formData['user_id']);
// 		// Guardamos el detalle de usuario
// 		$editUserDetail   = $this->getUsersDetailsModel()->editUserDetails($userDetailObject);
		
		return $editUser;
	}
	
	/*
	 * Editar detalle de usuario 
	 */
	public function editDetailUser($data)
	{
		//echo "<pre>"; print_r($data); exit;;
		// Arreglo de detalle de ususario
		$detail = array(
				"acl_users_id"    => $data['user_id'],
				"countBanc"       => $data['number_acount'],
				"clabe"           => $data['interbank_clabe'],
				"branch"          => $data['sucursal_name'],
				"nameBanc"        => $data['name_bank'],
				//"cost"            => $data['user_id'],
				//"period"          => $data['user_id'],
				//"mannerofpayment" => $data['user_id'],
				"birthday"        => (isset($data['birthday'])) ? \Util_Tools::dateDBFormat("/", $data['birthday']) : "0000-00-00",
				"local_phone"     => $data['local_phone'],
				"cellphone"       => $data['cellphone'],
				//"date_admission"  => $data['user_id'],
				"contract_type"   => $data['contract_type'],
				
				"number_ss" => (isset($formData['number_ss'])) ? $formData['number_ss'] : "",
				
				//Campos para guardar fotos de documentos
				"photofile_ife" 			=> (isset($data['photofile_ife'])) ? $data['photofile_ife'] : "" ,
				"photofile_license" 		=> (isset($data['photofile_license'])) ? $data['photofile_license'] : "" ,
				"photofile_certification"	=> (isset($data['photofile_certification'])) ? $data['photofile_certification'] : "" ,
		);
		//echo "<pre>"; print_r($detail); exit;
		// Arreglo de direccion
		$address           = $this->createAddressesUser($data);
		// Guardamos la direccion del ususario
		$editAddressesUser = $this->getUsersAddressesModel()->editUserAddresses($address); 
		// Guardamos el detalle de usuario
		$editUserDetail   = $this->getUsersDetailsModel()->editUserDetails($detail);
		// Resultado
		$result           = array("detail" => $editUserDetail, "addresses" => $editAddressesUser);
		return $result;
	}
	
	/*
	 * Arreglo con la direccion del ussuario
	 */
	public function createAddressesUser($data, $id_user)
	{
		$address = array(
				"user_id"      => (!empty($data['user_id'])) ? $data['user_id'] : $id_user,
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
	
	//Eliminar un usuario
	public function deleteUser($id_user)
	{
		$delete_user = $this->getUserModel()->deleteUser($id_user);
		return $delete_user;
	}
	
	//Metodo para cambiar la contraseï¿½a de un usuario
	public function changePassword($formData, $id)
	{
		//if($formData['password1'] == $formData['password2'])
		//{
			$passSecure = new UserPassword();
			$newPass = $passSecure->securePassword($formData['newpass']);
			$data = array(
					"user_id"  => $id,
					//"hash" => $formData['password1']
					"password" => $newPass
			);
			//print_r($data);exit;
			$changesPassword = $this->getUserModel()->changePassword($data);
		//}	
		
		return $changesPassword;
	}
	
	//Validar si una contraseï¿½a es correcta
	public function validatePassword($formData)
	{
		$data = array(
			"user_id" => $formData['id'],
			"hash"    => $formData['val']		
		);
		
		$validatePass = $this->getUserModel()->validatePassword($data);
		return $validatePass;
	}
	
	// 	Metodo para encriptar las contraseñas
	private function Bcrypt($password)
	{
		/* Ciframos la contraseña para la maxima seguridad le aplicamos un salt y 
	 	* hacemos el hash del hash 5 veces (por defecto vienen mas de 10 pero es mas lento)
	 	*/
		
		$bcrypt = new Bcrypt(array(
				'salt' => 'aleatorio_salt_pruebas_victor',
				'cost' => 5
		));
		
		$securePass = $bcrypt->create($password);
		$password   = $securePass;
		
		return $password;
	}
	
	// Metodo para cifrar cadenas
	public function encrypt_pass($password)
	{
		// Configuración de la clase de encriptación a usar AES
		$cipher = BlockCipher::factory('mcrypt', array('algorithm' => 'aes'));
		
		// Establecer la clave de cifrado / sal
		$cipher->setKey('esta es la llave para encriptar');
		
		// Texto a encriptar
		$text = $password;
		
		// Encriptar los datos
		$encrypted = $cipher->encrypt($text);
		
		return $encrypted;
	}
	
	// Metodo para descifrar cadenas
	public function decrypt_pass($password)
	{
		// Configuración de la clase de encriptación a usar AES
		$cipher = BlockCipher::factory('mcrypt', array('algorithm' => 'aes'));
		
		// Establecer la clave de cifrado / sal
		$cipher->setKey('esta es la llave para encriptar');
		
		// Texto a desencriptar
		$text = $password;
		
		// Desencriptar los datos
		$decrypted = $cipher->decrypt($text);
		
		return $decrypted;
	}
	
	/*
	 * Crear arreglo con los datos del usuario 
	 */
	private function createObjectUsers($formData)
	{
		$passSecure = new UserPassword();
		
		$formData['user_type']=1;
		if($formData['user_type'] == 1){ //Empleado
			$id_company    = 1;
			$rfc           = "";
			$photofilename = "";
			$photofile     = "";
			$phone         = "";
			$deparment     = 1;
		}elseif ($formData['user_type'] == 2){ // Usuario
			$id_company    = 1;
			$rfc           = "";
			$photofilename = $formData['photofilename'];
			$photofile     = $formData['photofile'];
			$phone         = "";
			$deparment     = $formData['id_department'];
		}elseif ($formData['user_type'] == 3){ // Contacto
			$id_company    = $formData['id_company'];
			$rfc           = "";
			$photofilename = "";
			$photofile     = "";
			$phone         = "";
			$deparment     = 1;
		}
		
		//$en = $this->encrypt_pass(trim($formData['password']));
		//$de = $this->decrypt_pass($en);
		
		$invert = explode("-",$formData['fecha']);
		$fecha_invert = $invert[2]."-".$invert[1]."-".$invert[0];
		
		$data = array(
				'id_company'     => $id_company,
				'user_id'        => $formData['user_id'],
				'user_type'      => $formData['user_type'],
				'user_principal' => null,
				'rfc'            => $rfc,
				'name'           => trim($formData['name']),
				'surname'        => trim($formData['surname']),
				'lastname'       => trim($formData['lastname']),
				'email'          => trim($formData['email']),
				'id_job'         => null,
				'id_department'  => $deparment,
				'phone'          => $phone,
				'photofilename'  => $photofilename,
				'photofile'      => $photofile,
				'canlogin'       => "0",
				'user_name'      => null,
				'sexo'           => $formData['sexo'],
				'datebirth'      => $fecha_invert,
				//isset($formData['password']) ? 'password' => md5(trim($formData['password'])) : false
				//'password'       => isset($formData['password']) ? md5(trim($formData['password'])) : false
				'curp' 		  => (isset($formData['curp'])) ? $formData['curp'] : "",
				'discount_ss' => (isset($formData['discount_ss'])) ? $formData['discount_ss'] : "",
		);
		//echo "<pre>"; print_r($data); exit;
		//Agregamos la contraseña al arreglo si es que viene por post
		(isset($formData['password'])) ? $data['password'] = $passSecure->securePassword($formData['password']) : false;
		return $data;
	}
	
	/*
	 * Crear arreglo con el rol y id del usuario
	 */
	private function createObjectRoleUser($formData, $id)
	{
		$formData['user_type']=1;
		if ($formData['user_type'] == 1){ // Empleado
			//$role = $formData['role'];
			$role = 4;
		}elseif ($formData['user_type'] == 2){ // Usuario
			$role = $formData['role'];
		}elseif ($formData['user_type'] == 3){ // Contacto
			$role = $formData['role'];
		}
		
		$data = array(
				'user_id' => $id,
				'role_id' => $role
		);
	
		return $data;
	}
	
	/*
	 * Crear arreglo con el detalle del usuario
	 */
	private function createObjectUserDetails($formData, $id)
	{
		if ($formData['user_type'] == 1){ // Empleado
			$date_admission  = \Util_Tools::dateDBFormat("/", $formData['date_admission']);
			$cost            = $formData['cost'];
			$mannerofpayment = $formData['mannerofpayment'];
			$period          = $formData['period'];
			$cellphonecontact = "";
			$date_admission  = \Util_Tools::dateDBFormat("/", $formData['date_admission']);
		}elseif ($formData['user_type'] == 2){ // Usuario
			$date_admission  = "";
			$cost            = "";
			$mannerofpayment = "";
			$period          = "";
			$cellphonecontact = "";
			$date_admission  = "";
		}elseif ($formData['user_type'] == 3){ // Contacto
			$date_admission   = "";
			$cost             = "";
			$mannerofpayment  = "";
			$period           = "";
			$cellphonecontact = (isset($formData['cellphone'])) ? $formData['cellphone'] : "";
			$date_admission   = "";
		}
		
		$data = array(
				"acl_users_id"    => $id,
				"contract_type"   => "2",
				"date_admission"  => $date_admission,
				"cost"            => $cost,
				"mannerofpayment" => $mannerofpayment,
				//"local_phone"     => "",
				"cellphone"       => $cellphonecontact,
				"period"          => $period,
				"birthday"        => "0000-00-00"
		);
		
		return $data;
	}
	
	/*
	 * Metodo que obtiene todos los contactos de una compania
	 */
	public function getAllContactsByIdCompany($id)
	{
		$rows = $this->getUserModel()->getAllContactsByIdCompany((int) $id);
		return $rows;
	}
	
	/*
	 * Obtenemos usuarios de la nomina
	 */
	public function getPayRollByUser()
	{
		$rows = $this->getUserModel()->getPayRollByUser();
		return $rows;
	}
	
	/*
	 * Obtenemos todos los detalles de usuarios
	 */
	public function getUsersAndDetails()
	{
		$rows = $this->getUserModel()->getUsersAndDetails();
		return $rows;
	}
	
	/*
	 * Obtenemos todos los usuarios a pagar la nomina por fecha
	 */
	public function getPayRollByUserToDate($type, $date)
	{
		$NTyp = 0;
		if($type == "S"){
			$NTyp = 1;
		}elseif($type == "Q"){
			$NTyp = 2;
		}elseif($type == "M"){
			$NTyp = 3;
		}
		
		$rows = $this->getUserModel()->getPayRollByUserToDate($NTyp, $date);
		return $rows;
	}
	
	/*
	 * Historial de nomina por empleado
	 */
	public function getPayrollsByUserId($idEmployee)
	{
		$rows = $this->getUserModel()->getPayrollsByUserId($idEmployee);
		return $rows;
	}
	
	/*
	 * USUARIOS CON MAYOR NUMERO DE VIAJES
	 */
	public function getAllUsersActive($type)
	{
		$rows = $this->getUserModel()->getAllUsersActive($type);
		return $rows;
	}
	
	/*
	 * OBTENER EMPLEADOS DISPONIBLES PARA VIAJES
	 */
	public function getEmployeesAvailable()
	{
		$rows = $this->getUserModel()->getEmployeesAvailable();
		return $rows;
	}
	
	/*
	 * OBTENER LOS EMPLEADOS DISPONIBLES Y ASIGNADOS A UN VIAJE
	 */
	public function getEmployeesAvailableAndAssigned($type, $id)
	{
		$usersAssigned  = $this->getUserModel()->getEmployeesAvailableAndAssigned($type, $id);
		//echo "Asignados";
		//echo "<pre>"; print_r($usersAssigned);
		$usersAvailable = $this->getUserModel()->getEmployeesAvailable($type);
		//echo "Disponibles";
		//echo "<pre>"; print_r($usersAvailable);
		
		//echo "Resultado";
		$result = array_merge($usersAssigned, $usersAvailable);
		//echo "<pre>"; print_r($result); exit;
		return $result;
	}
	
	/*
	 * Activar una cuenta o desactivar
	 */
	public function confirmShipping($data)
	{
		//print_r($data);exit;
		$id       = (int) $data['id'];
		$shipping = array(
				"user_id" 	=> $id,
				"canlogin" 	=> $data['status']
		);
	
		$row = $this->getUserModel()->confirmShipping($shipping);
		return $row;
	}
	

}