<?php
namespace System\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\Feature;
//Adaptador de la db
use Zend\Db\Adapter\Adapter;
/* Usamos el componente SQL que nos permite realizar consultas utilizando m�todos. */
use Zend\Db\Sql\Sql;

class RolePermissionModel extends TableGateway
{
	private $dbAdapter;
	
	public function __construct()
   	{
   		$this->dbAdapter = \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter();
    	$this->table = 'iof_role_permission';
       	$this->featureSet = new Feature\FeatureSet();
     	$this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
    	$this->initialize();
   	}
   	
   	//Metodo que agrega y actualiza permisos de roles
	public function addOrUpdatePermissionsToRole($identifier,$data)
	{	
		//print_r($identifier);
		//echo "<pre>"; print_r($data); exit;
		//Validamos que accion hara insert/update
		if($identifier == "ad"){
			foreach ($data as $permissions) {
				$addPermissions = $this->insert($permissions);
			}
		}else if($identifier == "ed"){
			//echo "<pre>"; print_r($data); exit;
			foreach ($data as $updatePermissions) {
				$addPermissions = $this->update($updatePermissions, array("id" => $updatePermissions['id'] ));
			}
		}
		echo "<pre>"; print_r($addPermissions); exit;
		return $addPermissions;
	}
	
	//Metodo que elimina los permisos de un rol
	public function deletePermissionsByIdRoleAndResource($id_role,$id_resource)
	{
		$sql = sprintf("DELETE iof_role_permission 
						FROM iof_role_permission 
						INNER JOIN iof_permission ON iof_permission.id = iof_role_permission.permission_id 
						WHERE iof_role_permission.role_id = %s AND iof_permission.resource_id = %s",$id_role, $id_resource);
		
		$select = $this->dbAdapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
		return $select;
	}

}