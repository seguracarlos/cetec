<?php
namespace Iofractal\Services;

use Iofractal\Model\NeighborhoodModel;

class NeighborhoodServices
{
	private $neighborhoodModel;
	
	// Instanciamos el modelo de colonias
	public function getNeighborhoodModel()
	{
		return $this->neighborhoodModel = new NeighborhoodModel();
	}
	
	// Obtemos las colonias
	public function fetchAll($id)
	{
		$neighborhood = $this->getNeighborhoodModel()->fetchAll($id);
		return $neighborhood;
	}
	
}