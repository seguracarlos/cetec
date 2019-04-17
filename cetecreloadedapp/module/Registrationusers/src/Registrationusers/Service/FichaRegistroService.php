<?php
namespace Registrationusers\Service;

use Registrationusers\Model\FichaRegistroModel;

class FichaRegistroService
{
	private $fichaRegistro;

	public function getToken($token)
	{
		$register = $this->fichaRegistro = new FichaRegistroModel();
		$user = $register->getRowById($token);
		return $user;
	}
	
	public function getTokenByUser($id_user)
	{
		$register = $this->fichaRegistro = new FichaRegistroModel();
		$user = $register->getTokenByUser($id_user);
		return $user;
		
	}
	
	public function addToken($user_id){
		$registro = $this->fichaRegistro = new FichaRegistroModel();
		$aleatorio1 = rand() * (1 - 1000 + 1) + 1;
		$aleatorio2 = rand() * (1 - 1000 + 1) + 2;
		$aleatorio = $aleatorio1 * $aleatorio2/2;
		$token=md5($aleatorio);
		$data = array(
				'id_user' => $user_id,
				'token'   => $token,
				'status'  => '0',
				//'mail'    => $data['email']
		);
		$newToken = $registro->addRow($data);
		return $newToken;
	}
	
	public function updateToken($user_id){
		$registro = $this->fichaRegistro = new FichaRegistroModel();
		$aleatorio1 = rand() * (1 - 1000 + 1) + 1;
		$aleatorio2 = rand() * (1 - 1000 + 1) + 2;
		$aleatorio = $aleatorio1 * $aleatorio2/2;
		$token=md5($aleatorio);
		$data = array(
				'id_user' => $user_id,
				'token'   => $token,
				'status'  => '0',
				//'mail'    => $data['email']
		);
		$newToken = $registro->updateRow($data);
		return $newToken;
	}
	
	public function deleteToken($id_user){
		$token = $this->fichaRegistro = new FichaRegistroModel();
		$data = array(
				"id_user" => $id_user,
		);
		$tokenUpdate = $token->deleteRow($data);
		return $tokenUpdate;
	}
	
}