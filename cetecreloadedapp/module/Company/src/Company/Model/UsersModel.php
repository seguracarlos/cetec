<?php
namespace Company\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;

class UsersModel extends TableGateway
{
	private $dbAdapter;
	
	public function __construct()
   	{
   		$this->dbAdapter = \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter();
    	$this->table = 'iof_users';
       	$this->featureSet = new Feature\FeatureSet();
     	$this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
    	$this->initialize();
   	}
   	
   	public function getUserFavorite($company)
   	{
   		$sql = new Sql($this->dbAdapter);
   		$select = $sql->select();
   		$select
   		->from(array('u' => $this->table))
   		->where(array('u.id_company'=> $company))
   		->where(array('u.user_principal' => '1'))
   		->join(array('d' => 'user_details'),'d.acl_users_id = u.user_id',array('*'),'left')
   		->join(array('j' => 'job_users'),'j.id = u.id_job',array('*'),'left')
   		->join(array('p' => 'department'),'p.id_department = u.id_department',array('*'),'left')
   		->join(array('i' => 'iof_user_role'),'i.user_id = u.user_id',array('*'),'left')
   		->join(array('r' => 'iof_role'),'r.rid = i.role_id',array('*'),'left');
   		$selectString = $sql->getSqlStringForSqlObject($select);
   		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
   		$result       = $execute->toArray();
   		return $result;
   	}
   	
   	public function getJobs(){
   		$sql = new Sql($this->dbAdapter);
   		$select = $sql->select();
   		$select
   		->from(array('j' => 'job_users'));
   		$selectString = $sql->getSqlStringForSqlObject($select);
   		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
   		$result       = $execute->toArray();
   		return $result;
   	}
   	
   	public function editUserFavorite($data){
   		$connection = $this->dbAdapter->getDriver()->getConnection();
   		$connection->beginTransaction();
   		
   		try {
   			$user_id = $data['user_id'];
   			if($user_id != null || $user_id != 0){
   				$userFavorite = $this->update($data,array("user_id" => $user_id));
   				$sql = new Sql($this->dbAdapter);
   				$select = $sql->select();
   				$select
   				->from(array('u' => $this->table))
   				->where(array('u.user_id' => $user_id));
   		
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