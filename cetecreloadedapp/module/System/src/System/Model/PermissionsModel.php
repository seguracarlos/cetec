<?php
namespace System\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\Feature;
//Adaptador de la db
use Zend\Db\Adapter\Adapter;
/* Usamos el componente SQL que nos permite realizar consultas utilizando m�todos. */
use Zend\Db\Sql\Sql;

class PermissionsModel extends TableGateway
{
	private $dbAdapter;
	
	public function __construct()
   	{
   		$this->dbAdapter = \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter();
    	$this->table = 'iof_permission';
       	$this->featureSet = new Feature\FeatureSet();
     	$this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
    	$this->initialize();
   	}
	
	public function fetchAll()
	{
		$select = $this->select();
		$data  = $select->toArray();	
		return $data;
	}
	
	public function getPermissionsByIdResource($id_resource)
	{
		$sql    = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select
			->columns(array('id','permission_name','name_esp', 'agroupName'))
			->from(array('iof_per' => $this->table))
			->join(array('iof_res' => 'iof_resource'), 'iof_per.resource_id = iof_res.id',
							array('app'),'INNER')
			->where(array('resource_id' => $id_resource));
		
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		//echo "<pre>"; print_r($result); exit;
		return $result;
	}
	
	public function getPermissionsByIdRole($id_role, $id_resource)
	{
		$sql = "select irp.id as id_role_perm, irp.role_id, irp.permission_id, irp.status, ip.id, ip.permission_name, ip.name_esp, ip.agroupName
				from iof_role_permission as irp
				join iof_permission as ip on irp.permission_id = ip.id
				join iof_resource as ir on ip.resource_id = ir.id
				where irp.role_id = $id_role and ip.resource_id = $id_resource" ;
		
		$select = $this->dbAdapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
		$result = $select->toArray();
		return $result;
		//echo "<pre>"; print_r($result); exit;
	}
	
	public function addPermissionsToRole($data)
	{
		//$addPermissions = $this->insert($data);
		$sql = "insert into iof_role_permission values $data";
		
		$select = $this->dbAdapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
		$result = $select->toArray();
		return $addPermissions;
	}

}