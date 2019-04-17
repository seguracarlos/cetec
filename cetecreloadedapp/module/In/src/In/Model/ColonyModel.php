<?php
namespace Company\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Feed\Reader\Collection;

class ColonyModel extends TableGateway
{
	private $dbAdapter;

	public function __construct()
	{
		$this->dbAdapter = \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter();
		$this->table = 'colony';
		$this->featureSet = new Feature\FeatureSet();
		$this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
		$this->initialize();
	}



	public function getDirection($colony)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select
		->from(array('c' => $this->table))
		->where(array('c.id_colony'=> $colony))
		->join(array('d' => 'district'),
				'c.id_district = d.id_district')
		->join(array('e' => 'states_of_mexico'),
				'd.state_id = e.id_state');
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;	
	}
	
	public function getStates(){
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select
		->from(array('e' => 'states_of_mexico'));
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
	}
	
	public function getDistricts($state){
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select
		->from(array('d' => 'district'))
		->where(array('d.state_id'=> $state));
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
	}
	
	public function getColonys($district){
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select
		->from(array('c' => $this->table))
		->where(array('c.id_district'=> $district));
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
	}
	public  function getPostalCode($colony){
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select
		->from(array('c' => $this->table))
		->where(array('c.id_colony'=> $colony));
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
	}
}