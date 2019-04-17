<?php
namespace System\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;

class ResourcesModel extends TableGateway
{
	private $dbAdapter;
	
	public function __construct()
	{
		$this->dbAdapter  = \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter();
		$this->table      = "iof_resource";
		$this->featureSet = new Feature\FeatureSet();
		$this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
		$this->initialize();
	}
	
	/*public function getAllResource()
	{
		$select    = $this->select();
		$resources = $select->toArray();
		return $resources;
	}*/
	
	public function getAllResource()
	{
		$sql    = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select
		->columns(array('id', 'resource_name', 'app', 'agroupName', 'menutemp', 'name', 'nameMenu'))
		->from(array('i_r'   => $this->table))
		->join(array('i_p' => 'iof_permission'), 'i_r.id = i_p.resource_id', array('id_permission'=>'id', 'name_esp', 'name_menu'), 'LEFT');
		
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
	}
}