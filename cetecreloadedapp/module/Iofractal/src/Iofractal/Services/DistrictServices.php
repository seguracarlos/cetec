<?php
namespace Iofractal\Services;

use Iofractal\Model\DistrictModel;

class DistrictServices
{
	private $districtModel;
	
	// Instanciamos el modelo de delegaciones y municipios
	public function getDistrictModel()
	{
		return $this->districtModel = new DistrictModel();
	}
	
	// Obtemos los municipios o delegaciones
	public function fetchAll($id)
	{
		$district = $this->getDistrictModel()->fetchAll($id);
		return $district;
	}
	
}