<?php
namespace Classes\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;

class LoginHistoryModel extends TableGateway
{
	private $dbAdapter;
	
	public function __construct()
   	{
   		$this->dbAdapter = \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter();
    	$this->table = 'login_history';
       	$this->featureSet = new Feature\FeatureSet();
     	$this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
    	$this->initialize();
   	}
	
   	
	
	public function getLoginHistoryByUser($id_user)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select
			->from(array('l' => $this->table))
			->where(array('l.id_user' => $id_user));
	
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		if($result!=null){
			return $result;
		}
		return null;
	}
	
	public function addHistory($data){
 		try {
 			$connection = $this->dbAdapter->getDriver()->getConnection();
 			$connection->beginTransaction();
			$time = $this->insert($data);
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
	
}