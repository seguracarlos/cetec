<?php
namespace Accountmanager\Services;

use Accountmanager\Model\AccountmanagerModel;

class AccountmanagerServices
{
	private $cashModel;
	
	public function getCashModel()
	{
		return $this->cashModel = new CashModel();
	}
	
	public function fetchAll()
	{
		$bank = $this->getBankModel()->fetchAll();
		return $bank;
	}
	
	public function getUserById($id_user)
	{
		$user = $this->getInventoryModel()->getUserById($id_user);
		return $user;
	}
	
	public function addInventory($formData)
	{	
		//echo "<pre>"; print_r($formData); exit;
		$addInventory = $this->getInventoryModel()->addInventory($formData);
		return $addInventory;
	}
	
	//Editar un usuario
	public function editUser($formData)
	{

		$user_edit = $this->getInventoryModel()->editUser($data);
		return $user_edit;
	}
	
	//Eliminar un usuario
	public function deleteUser($id_user)
	{
		$delete_user = $this->getInventoryModel()->deleteUser($id_user);
		return $delete_user;
	}
}