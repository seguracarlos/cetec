<?php
namespace Horus\Services;

use Horus\Model\InventoryArticlesModel;

class InventoryArticlesServices
{
	private $inventoryArticlesModel;
	
	public function getInventoryArticlesModel()
	{
		return $this->inventoryArticlesModel = new InventoryArticlesModel();
	}
	
	public function fetchAll()
	{
		$articles = $this->getInventoryArticlesModel()->fetchAll();
		return $articles;
	}
}