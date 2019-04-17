<?php
namespace Horus\Services;

use Horus\Model\InventoryStateModel;

class InventoryStateServices
{
	private $inventoryStateModel;
	
	public function getInventoryStateModel()
	{
		return $this->inventoryStateModel = new InventoryStateModel();
	}
	
	public function fetchAll()
	{
		$users = $this->getInventoryStateModel()->fetchAll();
		return $users;
	}
}