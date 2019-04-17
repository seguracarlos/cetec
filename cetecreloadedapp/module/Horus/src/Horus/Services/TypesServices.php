<?php
namespace Horus\Services;

use Horus\Model\TypesModel;

class TypesServices
{
	private $typesModel;
	
	public function getTypesModel()
	{
		return $this->typesModel = new TypesModel();
	}
	
	public function fetchAll()
	{
		$types = $this->getTypesModel()->fetchAll();
		return $types;
	}
}