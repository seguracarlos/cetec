<?php
namespace Company\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Feed\Reader\Collection;

class AddressesModel extends TableGateway
{
	private $dbAdapter;

	public function __construct()
	{
		$this->dbAdapter = \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter();
		$this->table = 'addresses';
		$this->featureSet = new Feature\FeatureSet();
		$this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
		$this->initialize();
	}
	
	public function updateAddressCompany($data){
		$connection = $this->dbAdapter->getDriver()->getConnection();
		$connection->beginTransaction();
		
		try {
			$company_id = $data['company_id'];
			if($company_id != null || $company_id != 0){
				$addressCompany = $this->update($data,array("company_id" => $company_id));
				$sql = new Sql($this->dbAdapter);
				$select = $sql->select();
				$select
				->from(array('a' => $this->table))
				->where(array('a.company_id' => $company_id));
		
				$selectString = $sql->getSqlStringForSqlObject($select);
				$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
				$result       = $execute->toArray();
				$connection->commit();
			}
		} catch (\Exception $e) {
			$result = "fail";
			$connection->rollback();
		}
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
		->from(array('c' => 'neighborhood'))
		->where(array('c.district_id'=> $district));
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
	}
	public  function getPostalCode($colony){
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select
		->from(array('c' => 'neighborhood'))
		->where(array('c.id'=> $colony));
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
	}
}