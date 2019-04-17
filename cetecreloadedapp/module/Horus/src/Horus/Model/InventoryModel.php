<?php
namespace Horus\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;

class InventoryModel extends TableGateway
{
	private $dbAdapter;
	
	public function __construct()
   	{
   		$this->dbAdapter = \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter();
    	$this->table = 'inventories';
       	$this->featureSet = new Feature\FeatureSet();
     	$this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
    	$this->initialize();
   	}
	
   	/*Recuperamos todos los usuarios disponibles*/
	public function fetchAll()
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select
			->columns(array('id_inventories', 'types_id_types', 'id_acl_users', 'id_department', 'article', 'amount', 'brand', 'serialnumber', 'material', 'model', 'description', 'id_product', 'chilled_dry'))
			->from(array('i'   => $this->table))
			->join(array('u' => 'iof_users'), 'i.id_acl_users = u.user_id', array('name', 'surname', 'lastname'), 'Left')
			->join(array('d' => 'department'), 'i.id_department = d.id_department', array('d_name'), 'LEFT')
			->join(array('t' => 'types'), 'i.types_id_types = t.id_types', array('name_type' => 'description'), 'LEFT')
			//->join(array('d'   => 'department'), 'u.id_department = d.id_department', array('d_name'), 'LEFT')
		;
	
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
	}
	
	/* 
	 * Obtenemos todos los registros por id 
	 */
	public function fetchAllById($id)
	{
		$idRow  = (int) $id;
		$sql    = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select
		->columns(array('id_inventories', 'types_id_types', 'id_acl_users', 'id_department', 'article', 'amount', 'brand', 'serialnumber', 'material', 'model', 'description', 'id_product', 'capacity'))
		->from(array('i'   => $this->table))
		->where(array("types_id_types" => (int) $idRow));
		
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
	}
	
	//Recuperamos un usuario por id
	public function getInventoryById($id_inventory)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select
			->columns(array('id_inventories', 'types_id_types', 'id_acl_users', 'id_department', 'article', 'amount', 'brand', 'serialnumber', 'material', 'model', 'description', 'id_employee', 'state', 'id_product', 'capacity', 'photofile', 'chilled_dry'))
			->from(array('i'   => $this->table))
			->join(array('u'   => 'iof_users'), 'i.id_acl_users = u.user_id', array('name', 'surname', 'lastname'), 'Left')
			->join(array('d'   => 'department'), 'i.id_department = d.id_department', array('d_name'), 'LEFT')
			->join(array('t'   => 'types'), 'i.types_id_types = t.id_types', array('name_type' => 'description'), 'LEFT')
			->join(array('i_p' => 'inventories_photos'), 'i.id_inventories = i_p.id_inventory', array('name_photo'), 'LEFT')
			->where(array('i.id_inventories' => $id_inventory));
	
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
	}
	
	public function addInventory($data)
	{
		/*echo "<pre>"; print_r($data); exit;*/
		$addInventory           = $this->insert($data);
		$data['id_inventories'] = $this->getLastInsertValue();
		return $data;
	}
	
	public function editInventory($data)
	{
		$edit = $this->update($data,array("id_inventories" => $data['id_inventories']));
		return $edit;
	}
	
	
	public function deleteInventory($id_inventory)
	{
		$delete_inventory = $this->delete(array('id_inventories' => $id_inventory));
		return $delete_inventory;
	}
	
	//Metodo para cambiar la contraseña de un usuario
	public function changePassword($data)
	{
		$updatePass = $this->update($data, array("user_id" => $data['user_id']));
		return array("msg" => "1");
	}
	
	//Validar si una contraseña es correcta
	public function validatePassword($data)
	{
		$id   = $data['user_id'];
		$pass = $data['password'];

		/*$sql = sprintf("SELECT COUNT(PASSWORD ) AS count
					    FROM iof_users
						WHERE user_id = %s
						AND PASSWORD = %s",$id, $pass);*/

		$sql = "SELECT COUNT(PASSWORD ) AS count
				FROM iof_users
				WHERE user_id = $id
				AND PASSWORD = '$pass'";
		
		$select = $this->dbAdapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
		
		$result  = $select->toArray();
		return $result;
	}
	
	/*
	 * UNIDADES EN VIAJE
	 */
	public function getAllTruckActive()
	{
		/*$sql = "SELECT id_inventories, article, brand, model, description, id_product
				FROM inventories
				WHERE id_inventories 
				IN (
					SELECT id_truck
					FROM shippings
				)";
		
		$select = $this->dbAdapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
		$result = $select->toArray();
		return $result;*/
		
		$sql    = new Sql($this->adapter);
		$select = $sql->select()->from(array('i' => $this->table));
		
		$subSelect = $sql->select()
			->from(array("s" => "shippings"))
			->columns(array('id_truck'));
		
		$select
			->columns(array(
					'id_inventories', 'article', 'brand', 'model', 'id_product'
			))
			->join(array('shi' => 'shippings'), 'i.id_inventories = shi.id_truck', array('id_shipping', 'start_date'), 'LEFT')
			->where(array("shi.end_date"=>"0000-00-00"))
			->where->in("id_inventories", $subSelect);
			//->where->isNull("shi.end_date");
			
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
	}
	
	/*
	 * OBTENER UNIDADES SIN VIAJES ACTUALES
	 */
	public function getAllTruckAvailable($type)
	{
		$sql    = new Sql($this->adapter);
		$select = $sql->select()->from(array('i' => $this->table));
		
		$subSelect = $sql->select()
			->from(array("s" => "shippings"))
			->columns(array('id_truck'))
			->where(new \Zend\Db\Sql\Predicate\Expression('DATE(end_date) = ?', '000-00-00'))
			//->where->notEqualTo(array('end_date', '0000-00-00'), array('end_date', '0000-00-00'))
			//->where->in("end_date", '0000-00-00')
			//->where(array("end_date" => '0000-00-00'))
			//->where->isNull("end_date")
		;
		/*$selectString2 = $sql->getSqlStringForSqlObject($subSelect);
		$execute2      = $this->dbAdapter->query($selectString2, Adapter::QUERY_MODE_EXECUTE);
		$result2       = $execute2->toArray();
		echo "<pre>"; print_r($result2); exit;*/
		$select
			->columns(array(
					'id_inventories', 'article', 'brand', 'model', 'id_product', 'types_id_types', 'capacity'
			))
			//->join(array('shi' => 'shippings'), 'i.id_inventories = shi.id_truck', array('id_shipping', 'start_date'), 'LEFT')
			->where(array("i.types_id_types" => (int) $type))
			->where->notIn("i.id_inventories", $subSelect)
			//->where->isNotNull("shi.end_date")
			;
			
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
	}
	
	/*
	 * OBTENER LOS CAMIONES DISPONIBLES Y ASIGNADO A UN VIAJE
	 */
	public function getTrucksAvailableAndAssigned($type, $id)
	{
		$sql    = new Sql($this->adapter);
		$select = $sql->select();
		$select
			->columns(array(
					'id_inventories', 'article', 'brand', 'model', 'id_product', 'types_id_types', 'capacity'
			))
			->from(array('i' => $this->table))
			->join(array('shi' => 'shippings'), 'i.id_inventories = shi.id_truck', array(), 'LEFT')
			->where(array("i.types_id_types" => (int) $type))
			->where(array("shi.id_shipping" => (int) $id));
			
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
	}

}