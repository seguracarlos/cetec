<?php
namespace Company\services;

use Company\Model\PreferencesModel;

class PreferencesService
{
	private $preferencesModel;
	
	public function getPreferencesModel()
	{
		return $this->preferencesModel = new PreferencesModel();
	}
	
	//Recupera el logo de la empresa
	public function getLogo()
	{
		$logo = $this->getPreferencesModel()->getLogo();
		return $logo;
	}
	//Actualizar el logo de la empresa
	public function updateImgLogo($img){
		$logo = $this->getPreferencesModel()->updateImgLogo($img);
		return $logo;
	}
}