<?php
namespace System\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\Feature;
//Adaptador de la db
use Zend\Db\Adapter\Adapter;
/*Usamos el componente SQL que nos permite realizar consultas utilizando m�todos.*/
use Zend\Db\Sql\Sql;
/*Nos da algunas herramientas para trabajar con el resulset de las consultas, puede ser prescindible*/
use Zend\Db\ResultSet\ResultSet;
use Zend\Validator\Explode;

class RoleModel extends TableGateway
{
	private $dbAdapter;
	
	public function __construct()
	{
		$this->dbAdapter = \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter();
		$this->table = "iof_role";
		$this->featureSet = new Feature\FeatureSet();
		$this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
		$this->initialize();
	}
	
	//Metodo que trae todos los roles
	public function fetchAll()
	{
		$select = $this->select();
		$data  = $select->toArray();
		return $data;
	}
	
	//Metodo que agrega un rol
	public function addRole($data)
	{
		$role = $this->insert($data);
		return $role;
	}
	
	//Metodo que editar un rol 
	public function editRole($data)
	{	//echo "<pre>"; print_r($data); exit;
		if(isset($data['rid'])){
			$updateRole = $this->update($data, array("rid" => $data['rid']));
		}
		return $data;
	}
	
	//Eliminamos un role y restablecemos el valor autoincrement
	public function deleteRole($id_role)
	{
		$deleteRole = $this->delete(array("rid" => $id_role));
		if($deleteRole){
			$this->resetValueAutoIncrement();
		}
		return true;
	}
	
	//Metodo que trae un rol por id_role
	public function getRoleById($id_role)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select
			//->columns(array('email','password','nombre', 'apellido'))
			->from($this->table)
			->where(array('rid' => $id_role));
		
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		
		$role = array(
				"id_role"     => $result[0]['rid'],
				"rol_name"    => $result[0]['role_name'],
				"role_status" => $result[0]['status']
		);
		return $role;
	}
	
	//Obtenemos los recursos asignados a un rol
	public function getAllResourcesAssignedToRole($id_role)
	{
		$sql = "SELECT *
				FROM iof_resource
				WHERE id
				IN (
					SELECT resource_id
					FROM iof_permission 
					JOIN iof_role_permission ON iof_permission.id = iof_role_permission.permission_id
					WHERE iof_role_permission.role_id = $id_role
				)
				ORDER BY app ASC";
		
		$select = $this->dbAdapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
		$result = $select->toArray();
		return $result;
	}
	
	//obtenemos los recursos no asignados a un rol
	public function getAllResourcesNoAssignedToRole($id_role)
	{
		$sql = "SELECT *
				FROM iof_resource
				WHERE id NOT
				IN (
					SELECT resource_id
					FROM iof_permission 
					JOIN iof_role_permission ON iof_permission.id = iof_role_permission.permission_id
					WHERE iof_role_permission.role_id = $id_role
				)
				ORDER BY app ASC";
	
		$select = $this->dbAdapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
		$result = $select->toArray();
		return $result;
		//echo "<pre>"; print_r($result); exit;
	}

	
	//Metodo para obtener ultimo id
	public function getLastId()
	{
		$sql    = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->columns(array(new \Zend\Db\Sql\Expression('MAX(rid) as role_id')));
		$select->from($this->table);
		
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
	}
	
	//Metodo para resetear el valor autoincrement
	public function resetValueAutoIncrement()
	{
		$sql    = "ALTER TABLE iof_role AUTO_INCREMENT = 1";
		$select = $this->dbAdapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
		return $select;
	}
	
	public function getResourcesForMenu($roleid)
	{	
		$sql = "SELECT *
				FROM iof_resource
				WHERE id
				IN (
					SELECT resource_id
					FROM iof_permission JOIN iof_role_permission ON iof_permission.id = iof_role_permission.permission_id
					WHERE iof_role_permission.role_id = $roleid
				)";
		
		$select = $this->dbAdapter->query($sql, Adapter::QUERY_MODE_EXECUTE);

		$result = $select->toArray();
		//echo "<pre>";
		//print_r($result);
		//exit();
		$sistementries=array();
		$ingresosentries=array();
		$egresosentries=array();
		$enlasaentries=array();
		$horusentries=array();
		 
		
		$apps = array("permissions" => array(),
				"user" => array());
	
		foreach ($result as $res) {
			$entry = new \Auth\Utility\Menu();
			$entry->setName($res['name']);
			$entry->setNameMenu($res['nameMenu']);
			$entry->setPath($res['path']);
			$entry->setPather('bank');
			$entry->setApp($res['app']);
			$entry->setSub($res['sub']);
	
	
			if($res['app']=='Sistema'){
				$sistementries[]=$entry;
			}elseif($res['app']=='Ingresos'){
				$ingresosentries[]=$entry;
			}elseif($res['app']=='Egresos'){
				$egresosentries[]=$entry;
			}elseif($res['app']=='Enlasa'){
				$enlasaentries[]=$entry;
			}elseif($res['app']=='Horus'){
				$horusentries[]=$entry;
			}
		}
		
		 
		$apps["sistema"]=$sistementries;
		$apps["ingresos"]=$ingresosentries;
		$apps["egresos"]=$egresosentries;
		$apps["enlasa"]=$enlasaentries;
		$apps["horus"]=$horusentries;
		return $apps;
		 
	}
	
	public function getResourcesMenusite($id_role)
	{
		$sql = new Sql($this->getAdapter());
	
		$select = $sql->select()
		->from(array(
				't1' => 'iof_role'
		))
		->columns(array(
				'role_name'
		))
		->where('role_id ='.$id_role)
		->join(array(
				't2' => 'iof_role_permission'
		), 't1.rid = t2.role_id', array(), 'left')
		->join(array(
				't3' => 'iof_permission'
		), 't3.id = t2.permission_id', array(
				'id', 'permission_name', 'name_esp', 'app', 'name_menu', 'sub_action', 'is_displayed_action', 'displayed_order_action', 'agroup', 'agroupName', 'menutempAction', 'pathResource'
		), 'left')
		->where('is_displayed_action = 1')
		->join(array(
				't4' => 'iof_resource'
		), 't4.id = t3.resource_id', array(
				'resource_name', 'resource', 'name', 'path'
		), 'left')
		->where('t3.permission_name is not null and t4.resource_name is not null and t2.status != 0')
		->order('t3.displayed_order_action');
	
		$statement = $sql->prepareStatementForSqlObject($select);
		$result = $this->resultSetPrototype->initialize($statement->execute())
		->toArray();
		$apps = array();
		foreach($result as $res){
			$path = $res['pathResource'];
			$pather = explode('/',$path);
			$entry = new \Auth\Utility\Menu();
			$entry->setName($res['name']);
			$entry->setNameMenu($res['name_menu']);
			$entry->setPath($path);
			$entry->setPather($pather[1]);
			$entry->setApp($res['app']);
			$entry->setSub($res['sub_action']);
			$entry->setPathresource($res['resource_name'].'-'.$res['permission_name']);
			$entry->setDisplayorder($res['displayed_order_action']);
			$entry->setAgroup($res['agroup']);
			if(!isset($apps[$res['app']])){
				$apps[$res['app']] = array();
			}
			array_push($apps[$res['app']], $entry);
		}
		return $apps;
	}
	public function getResourcesActionsViews($id_role)
	{
		$sql = new Sql($this->getAdapter());
	
		$select = $sql->select()
		->from(array(
				't1' => 'iof_role'
		))
		->columns(array(
				'role_name'
		))
		->where('role_id ='.$id_role)
		->join(array(
				't2' => 'iof_role_permission'
		), 't1.rid = t2.role_id', array(), 'left')
		->join(array(
				't3' => 'iof_permission'
		), 't3.id = t2.permission_id', array(
				'id', 'permission_name', 'name_esp', 'app', 'name_menu', 'sub_action', 'is_displayed_action', 'displayed_order_action', 'agroup', 'agroupName', 'menutempAction', 'pathResource'
		), 'left')
		->join(array(
				't4' => 'iof_resource'
		), 't4.id = t3.resource_id', array(
				'resource_name', 'resource', 'name', 'path'
		), 'left')
		->where('t3.permission_name is not null and t4.resource_name is not null and t2.status != 0')
		->order('t1.rid');
	
		$statement = $sql->prepareStatementForSqlObject($select);
		$result = $this->resultSetPrototype->initialize($statement->execute())
		->toArray();
		//echo "<pre>"; print_r($result); exit;
		$sistementries=array();
		$ingresosentries=array();
		$egresosentries=array();
		$enlasaentries=array();
		$horusentries=array();
			
	
		$apps = array();
	
	
		foreach($result as $res){
			$path = $res['pathResource'];
			$pather = explode('/',$path);
			$entry = new \Auth\Utility\Menu();
			$entry->setName($res['name']);
			$entry->setNameMenu($res['name_menu']);
			$entry->setPath($path);
			$entry->setPather($pather[1]);
			$entry->setApp($res['app']);
			$entry->setSub($res['sub_action']);
			$entry->setPathresource($res['resource_name'].'-'.$res['permission_name']);
			$entry->setDisplayorder($res['displayed_order_action']);
			$entry->setAgroup($res['agroup']);
			if(!isset($apps[$res['app']])){
				$apps[$res['app']] = array();
			}
			array_push($apps[$res['app']], $entry);
		}
		return $apps;
	}
	
	public function getAllPermisions($id_role)
	{
		$sql = new Sql($this->getAdapter());
	
		$select = $sql->select()
		->from(array(
				't1' => 'iof_role'
		))
		->columns(array(
				'role_name'
		))
		->where('role_id ='.$id_role)
		->join(array(
				't2' => 'iof_role_permission'
		), 't1.rid = t2.role_id', array(), 'left')
		->join(array(
				't3' => 'iof_permission'
		), 't3.id = t2.permission_id', array(
				'id', 'permission_name', 'name_esp', 'app', 'name_menu', 'sub_action', 'is_displayed_action', 'displayed_order_action', 'agroup', 'agroupName', 'menutempAction', 'pathResource'
		), 'left')
		->join(array(
				't4' => 'iof_resource'
		), 't4.id = t3.resource_id', array(
				'resource_name', 'resource', 'name', 'path'
		), 'left')
		->where('t3.permission_name is not null and t4.resource_name is not null and t2.status != 0')
		->order('t1.rid');
	
		$statement = $sql->prepareStatementForSqlObject($select);
		$result = $this->resultSetPrototype->initialize($statement->execute())
		->toArray();
		$apps = array();
		foreach($result as $res){
			$path = $res['pathResource'];
			$pather = explode('/',$path);
			$entry = new \Auth\Utility\Menu();
			$entry->setName($res['name']);
			$entry->setNameMenu($res['name_menu']);
			$entry->setPath($path);
			$entry->setPather($pather[1]);
			$entry->setApp($res['app']);
			$entry->setSub($res['sub_action']);
			$entry->setPathresource($res['resource_name'].'-'.$res['permission_name']);
			$entry->setDisplayorder($res['displayed_order_action']);
			$entry->setAgroup($res['agroup']);
			if(!isset($apps[$res['app']])){
				$apps[$res['app']] = array();
			}
			array_push($apps[$res['app']], $entry);
		}
		return $apps;
	}
	
	/*
	 * METODO QUE RECUPERA LOS PERMISOS DE UN USUARIO POR EL ROL
	 */
	public function obtenerPermisosPorRole($id_role)
	{
		$sql = new Sql($this->getAdapter());
		
		$select = $sql->select()
		->from(array(
				't1' => 'iof_role'
		))
		->columns(array(
				'role_name'
		))
		->where('role_id ='.$id_role)
		->join(array(
				't2' => 'iof_role_permission'
		), 't1.rid = t2.role_id', array(), 'left')
		->join(array(
				't3' => 'iof_permission'
		), 't3.id = t2.permission_id', array(
				'id', 'permission_name', 'name_esp', 'app', 'name_menu', 'sub_action', 'is_displayed_action', 'displayed_order_action', 'agroup', 'agroupName', 'menutempAction', 'pathResource'
		), 'left')
		->join(array(
				't4' => 'iof_resource'
		), 't4.id = t3.resource_id', array(
				'resource_name', 'resource', 'name', 'path'
		), 'left')
		->where('t3.permission_name is not null and t4.resource_name is not null and t2.status != 0')
		->order('t1.rid');
		
		$statement = $sql->prepareStatementForSqlObject($select);
		$result    = $this->resultSetPrototype->initialize($statement->execute())->toArray();
		
		return $result;
	}
}