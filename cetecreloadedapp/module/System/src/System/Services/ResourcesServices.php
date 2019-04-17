<?php
namespace System\Services;

use System\Model\ResourcesModel;

class ResourcesServices
{
	private $resourceModel;
	
	//Obtenemos la instancia de nuestro modelo
	public function getResourceModel()
	{
		return $this->resourceModel = new ResourcesModel();
	}
	
	//Obtenemos todos los recursos disponibles
	public function getAllResources()
	{
		$resources = $this->getResourceModel()->getAllResource();
		$array = array();
		
			foreach($resources as $row) {
			if (!isset($array[$row['id']])) {
				$array[$row['id']] = array(
					"id"  => $row['id'],
					"resource_name" => $row['resource_name'],
					"app"           => $row['app'],
					"agroupName"    => $row['agroupName'],
					"menutemp"      => $row['menutemp'],
					"name"          => $row['name'],
					"nameMenu"      => $row['nameMenu'],
					"id_permission" => $row['id_permission'],
					"name_esp"      => $row['name_esp'],
					"name_menu"     => $row['name_menu'],
				);
			}
		}
			
		return $array;
	}
}