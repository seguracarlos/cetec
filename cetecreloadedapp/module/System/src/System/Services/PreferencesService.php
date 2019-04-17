<?php
namespace System\Services;
use System\Model\PreferencesModel;
use System\Entities\PreferencesEntity;

class PreferencesService
{
	
	private $preferencesModel;
	
	public function getPreferencesModel()
	{
		return $this->preferencesModel = new PreferencesModel();
	}
	
	public function getAll() {
		$preferences = $this->getPreferencesModel()->getAll();			
		return $preferences;
	}

	public function getPreferencesById($id)
	{
		$preferences = $this->getPreferencesModel()->getPreferencesById($id);
		return $preferences;
	}
	
	public function updatePreferences($preference){
		$preferences = $this->getPreferencesModel()->updatePreferences($preference);
		return $preferences;
	}
	
	public function createEntityPreferencesEntity($row) {
		$entity = new PreferencesEntity() ;
		$entity->setId($row ['id']);
		$entity->setName($row ['name']);
		$entity->setValue($row['value']);
		return $entity;
	}

}

