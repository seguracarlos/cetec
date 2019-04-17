<?php
namespace Horus\Services;

use Horus\Model\InventoryModel;
use Horus\Model\InventoryPhotoModel;

class InventoryService
{
	private $inventoryModel;
	
	public function getInventoryModel()
	{
		return $this->inventoryModel = new InventoryModel();
	}
	
	public function fetchAll()
	{
		$users = $this->getInventoryModel()->fetchAll();
		return $users;
	}
	
	public function fetchAllById($id)
	{
		$rows = $this->getInventoryModel()->fetchAllById($id);
		return $rows;
	}
	
	//Recuperamos un usuario por id
	public function getInventoryById($id_inventory)
	{
		$inventory = $this->getInventoryModel()->getInventoryById($id_inventory);
		return $inventory;
	}
	
	
	/*
	 * AGREGAR INVENTARIO
	 */
	public function addInventory($formData)
	{
		$service_photo_inventory = new InventoryPhotoModel();
		
		$data = array(
			"serialnumber"   => $formData['serialnumber'],
			"amount"         => $formData['amount'],
			"brand"          => $formData['brand'],
			"model"          => $formData['model'],
			"state"          => $formData['state'],
			"description"    => $formData['description'],
			"id_product"     => $formData['id_product'],
			"capacity"       => $formData['capacity'],
			"types_id_types" => $formData['types_id_types'],
			"article"        => $formData['article'],
			"id_department"  => $formData['id_department'],
			"id_acl_users"   => $formData['user_id'],
			"id_employee"    => $formData['id_employee'],
			"chilled_dry"    => (isset($formData['chilled_dry']) ? $formData['chilled_dry'] : ""),
		);
		
		$addInventory = $this->getInventoryModel()->addInventory($data);
		
		// Guardar imagen del inventario
		if($addInventory['id_inventories']){
			$name = "";
			
			if($formData['photofile'] != ""){
				$image_inventory = $this->savePhoto($formData['photofile']);
				$name = $image_inventory['name'];
			}	
			//echo "<pre>"; print_r($name);exit;
			$dataPhoto =array(
					"id_inventory" => $addInventory['id_inventories'],
					"name_photo"   => $name
			);
					
			$insert_photo = $service_photo_inventory->addPhoto($dataPhoto);
		}
		
		return $addInventory;
	}
	
	/*
	 * EDITAR INVENTARIO
	 */
	public function editInventory($formData,$photo)
	{
		$service_photo_inventory = new InventoryPhotoModel();
		
		$data = array(
				"id_inventories" => $formData['id_inventories'],
				"serialnumber"   => $formData['serialnumber'],
				"amount"         => $formData['amount'],
				"brand"          => $formData['brand'],
				"model"          => $formData['model'],
				"state"          => $formData['state'],
				"description"    => $formData['description'],
				"id_product"     => $formData['id_product'],
				"capacity"       => $formData['capacity'],
				"types_id_types" => $formData['types_id_types'],
				"article"        => $formData['article'],
				"id_department"  => $formData['id_department'],
				"id_employee"    => $formData['id_employee'],
				"chilled_dry"    => (isset($formData['chilled_dry']) ? $formData['chilled_dry'] : ""),
		);
		
		$editInventory = $this->getInventoryModel()->editInventory($data);
		
		if($formData['photofile'] != ""){
			//echo "<pre>"; print_r($formData); exit;
			$delete_photo    = $this->deletePhoto($photo);
			$image_inventory = $this->savePhoto($formData['photofile']);
			
			$dataPhoto =array(
					"name_photo"   => $image_inventory['name']
			);
			//echo "<pre>"; print_r($dataPhoto); exit;
			$insert_photo = $service_photo_inventory->editPhoto($dataPhoto, $formData['id_inventories']);
		}
		
		return $editInventory;
	}
	
	/*
	 * ELIMINAR INVENTARIO
	 */
	public function deleteInventory($id_inventory)
	{
		$photo = $this->getInventoryById($id_inventory);
		
		if($photo[0]['name_photo'] != ""){
			$this->deletePhoto($photo[0]['name_photo']);
		}
		
		$delete_inventory = $this->getInventoryModel()->deleteInventory($id_inventory);
		return $delete_inventory;
	}
	
	/*
	 * GUARDAR IMAGEN DE INVENTARIO
	 */
	public function savePhoto($file)
	{
		$msg       = array();
		$directory = realpath('./public/img/inventories');
		define('UPLOAD_DIR', $directory);
		$img       = explode(',', $file);
		$data      = base64_decode($img[1]);
		$namePhoto = md5(uniqid(rand (),true)) . '.png';
		$file      = UPLOAD_DIR . "/" . $namePhoto ;
		$success   = file_put_contents($file, $data);
		
		if ($success){
			$msg = array("status" => "ok", "msg" => "successfully saved image", "name" => $namePhoto);
		}else{
			$msg = array("status" => "fail", "msg" => "not saved image");
		}
		
		return $msg;
	}
	
	/*
	 * ELIMINAR FOTO DE INVENTARIO
	 */
	public function deletePhoto($file)
	{
		$directory = realpath('./public/img/inventories');
		$delete    = unlink($directory."/".$file);
		return true;
	}
	
	/*
	 * UNIDADES EN VIAJE
	 */
	public function getAllTruckActive()
	{
		$rows = $this->getInventoryModel()->getAllTruckActive();
		return $rows;
	}
	
	/*
	 * OBTENER UNIDADES SIN VIAJES ACTUALES
	 */
	public function getAllTruckAvailable($type)
	{
		$rows = $this->getInventoryModel()->getAllTruckAvailable($type);
		return $rows;
	}
	
	/*
	 * OBTENER LOS CAMIONES DISPONIBLES Y ASIGNADO A UN VIAJE
	 */
	public function getTrucksAvailableAndAssigned($type, $id)
	{
		$truckAssigned   = $this->getInventoryModel()->getTrucksAvailableAndAssigned($type, $id);
		//echo "Asignados";
		//echo "<pre>"; print_r($truckAssigned);
		$trucksAvailable = $this->getInventoryModel()->getAllTruckAvailable($type);
		//echo "Disponibles";
		//echo "<pre>"; print_r($trucksAvailable);
	
		//echo "Resultado";
		$result = array_merge($truckAssigned, $trucksAvailable);
		//echo "<pre>"; print_r($result); exit;
		return $result;
	}
	
	/*
	 * OBTENER LOS CAMIONES DISPONIBLES
	 */
	public function getTrucksAvailable($type)
	{
		
		$trucksAvailable = $this->getInventoryModel()->getAllTruckAvailable($type);
		//echo "<pre>"; print_r($result); exit;
		return $trucksAvailable;
	}
}