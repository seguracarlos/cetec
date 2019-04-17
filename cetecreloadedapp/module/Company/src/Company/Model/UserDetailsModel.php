<?php
namespace Company\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;

class UserDetailsModel extends TableGateway
{
	private $dbAdapter;
	
	public function __construct()
   	{
   		$this->dbAdapter = \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter();
    	$this->table = 'user_details';
       	$this->featureSet = new Feature\FeatureSet();
     	$this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
    	$this->initialize();
   	}
   	
   	public function updateDetailsUser($data){
   		$connection = $this->dbAdapter->getDriver()->getConnection();
   		$connection->beginTransaction();
   		 
   		try {
   			$acl_users_id = $data['acl_users_id'];
   			if($acl_users_id != null || $acl_users_id != 0){
   				$userFavorite = $this->update($data,array("acl_users_id" => $acl_users_id));
   				$sql = new Sql($this->dbAdapter);
   				$select = $sql->select();
   				$select
   				->from(array('u' => $this->table))
   				->where(array('u.acl_users_id' => $acl_users_id));
   				 
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
}