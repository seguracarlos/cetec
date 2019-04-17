<?php

namespace Out\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\Feature;

class SponsorshipModel extends TableGateway
{	
	private $dbAdapter;
	
	public function __construct()
	{
		$this->dbAdapter = \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter();
		$this->table = 'sponsorship';
		$this->featureSet = new Feature\FeatureSet();
		$this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
		$this->initialize();
	}
	
	public function getSponsors($id_presentation){
		$select =$this->dbAdapter->query("select * from sponsorship
				where id_presentation=17",Adapter::QUERY_MODE_EXECUTE);
		$data   = $select->toArray();
		return $data;
	}
	
	public function addSponsor($data){
		
		try {
			$connection = $this->dbAdapter->getDriver()->getConnection();
			$connection->beginTransaction();
			$sponsor = $this->insert($data);
			$data['id_sponsor'] = $this->getLastInsertValue();
			$connection->commit();
			return $data;
		}
		catch (\Exception $e) {
			if ($connection instanceof \Zend\Db\Adapter\Driver\ConnectionInterface) {
				$connection->rollback();
				return null;
			}
		}
	}
	
	public function updateSponsor($data){
		
		$connection = null;
		try {
			$connection = $this->dbAdapter->getDriver()->getConnection();
			$connection->beginTransaction();
			$sponsor = $this->update($data, array("id_sponsor" => $data['id_sponsor']));
			$connection->commit();
			return $data;
		}
		catch (\Exception $e) {
			if ($connection instanceof \Zend\Db\Adapter\Driver\ConnectionInterface) {
				$connection->rollback();
				return null;
			}
		}
		
	}
	
	public function deleteSponsor($data){
		$connection = null;
		
		try{
			$connection = $this->dbAdapter->getDriver()->getConnection();
			$connection->beginTransaction();
			$sponsor = $this->delete(array("id_sponsor" => $data['id_sponsor']));
			$connection->commit();
			return $data;
		}catch(\Exception $e){
			if($connection instanceof \Zend\Db\Adapter\Driver\ConnectionInterface){
				$connection->rollback();
				return null;
			}
		}
	}
}