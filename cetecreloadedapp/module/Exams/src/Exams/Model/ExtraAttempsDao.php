<?php

namespace Exams\Model;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Db\Adapter\Adapter;

use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;


class ExtraAttempsDao  extends TableGateway{
	protected $_name="extraAttemps";
	protected $_primary="id_extra_attemp";
	
	private $dbAdapter;
	
	public function __construct()
	{
		$this->dbAdapter	= \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter();
		$this->table		= 'extraAttemps';
		$this->featureSet	= new Feature\FeatureSet();
		$this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
		$this->initialize();
	}
	
	public function addExtraAttemp($data){
		$connection = null;
		
		try {
			$connection = $this->dbAdapter->getDriver()->getConnection();
 			$connection->beginTransaction();
			$attemp = $this->insert($data);
			$data['id_extra_attemp'] = $this->getLastInsertValue();
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
	
	public function updateAttemp($data){
		$connection = null;
		
		try {
			$connection = $this->dbAdapter->getDriver()->getConnection();
			$connection->beginTransaction();
			$exam = $this->update($data, array("id_user" => $data['id_user'], "id_exam" => $data['id_exam']));
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
	
	public function getScore($id_user,$id_version){
		
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from('examscore')
		->where(array('id_version' => $id_version,'id_user' => $id_user));
		
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
	}
	
	public function getExtraAttempsByUser($id_user){
		$query=$this->dbAdapter->query("SELECT * FROM extraAttemps where id_user=$id_user",Adapter::QUERY_MODE_EXECUTE);
		$result=$query->toArray();
		return $result;
	}
	
	public function getExtraAttempsActiveByUser($id_user,$idExam){
		$query=$this->dbAdapter->query("SELECT * FROM extraAttemps where id_user=$id_user and id_exam =  $idExam and used=0",Adapter::QUERY_MODE_EXECUTE);
		$result=$query->toArray();
		return $result;
	}
		
}
?>