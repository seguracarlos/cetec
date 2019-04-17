<?php
namespace System\Model;


use System\Entities\PreferencesEntity;
use System\Services\PreferencesService;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\Feature;
//Adaptador de la db
use Zend\Db\Adapter\Adapter;
/*Usamos el componente SQL que nos permite realizar consultas utilizando m�todos.*/
use Zend\Db\Sql\Sql;
/*Nos da algunas herramientas para trabajar con el resulset de las consultas, puede ser prescindible*/
use Zend\Db\ResultSet\ResultSet;

class PreferencesModel extends TableGateway
{
	
	private $dbAdapter;
	
	public function __construct()
	{
		$this->dbAdapter = \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter();
		$this->table = "preferences";
		$this->featureSet = new Feature\FeatureSet();
		$this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
		$this->initialize();
	}

	public function getAll(){
		
		$servicePreferences = new PreferencesService();
		
		$select = $this->select();
		$rows  = $select->toArray();
		return $rows;
		
		$preferences = array();
		
		if (count($rows)){
			foreach($rows as $row){
				$preferencesObj = $servicePreferences->createEntityPreferencesEntity($row);
				array_push($preferences, $preferencesObj);
			}
		}
		return $preferences;
		
	}
	
	public function updatePreferences(PreferencesEntity $preference)
	{
		$data = array(
			"id"=>($preference->getId()),
			"value"=>$preference->getValue()
		);
		$preferences = $this->update($data,array("id" => $preference->getId()));
		return $preferences;
	}
	
	public function getPreferencesById($id) 
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select
		->columns(array('id','name','value'))
		->from(array('p' => $this->table))
		->where(array('p.id' => $id));
		
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		$entities = $this->createPreferencesEntity($result);
		return $entities;

	}

	private function createPreferencesEntity($rows)
	{

		if (count ( $rows )) {
			$preferences = array ();
			foreach ( $rows as $row ) {
				$preference = $this->createEntityPreferencesEntity( $row );
				array_push ( $preferences, $preference );
			}
			return $preferences;
		}
		return null;
	}


	private function createEntityPreferencesEntity($row) {

		$entity = new PreferencesEntity() ;
		$entity->setId($row ['id']);
		$entity->setName($row ['name']);
		$entity->setValue($row['value']);
		return $entity;
	}

}