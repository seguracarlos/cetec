<?php
namespace Horus\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;

class CashModel extends TableGateway
{
	private $dbAdapter;
	
	public function __construct()
   	{
   		$this->dbAdapter = \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter();
    	$this->table = 'cash';
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
		//->columns(array('user_id','name','surname', 'lastname', 'email', 'phone', 'password'))
		->from(array('c' => $this->table))
		->join(array('c_c_b' => 'cash_current_balance'), 'c.id_cash = c_c_b.id_cash', array('time' => 'time', "amount", "amount"), 'LEFT')
		//->where(array('u.user_id' => $id_user));
		->order(array('time DESC'))
		;
		 
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
	}
	
	//Recuperamos un usuario por id
	public function getUserById($id_user)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select
			->columns(array('user_id','name','surname', 'lastname', 'email', 'phone', 'password'))
			->from(array('u' => $this->table))
			->join(array('u_r' => 'iof_user_role'), 'u.user_id = u_r.user_id', array('role'=>'role_id'), 'INNER')
			->where(array('u.user_id' => $id_user));
	
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
	}
	
	public function addInventory($data)
	{
		/*echo "<pre>"; print_r($data); exit;*/
		$addInventory = $this->insert($data);
		return $addInventory;
	}
	
	/*Editar un usuario*/
	public function editUser($data)
	{
		$connection = $this->dbAdapter->getDriver()->getConnection();
		$connection->beginTransaction();
		
		try {
			$id_user = (int) $data['user_id'];
			if($id_user != null || $id_user != 0){
				$user    = $this->update($data,array("user_id" => $id_user));
				$result  = "done";
				$connection->commit();
			}
		} catch (\Exception $e) {
			$result = "fail";
			$connection->rollback();
		}
		return $result;
	}
	
	//Eliminar un usuario
	public function deleteUser($user_id)
	{
		$delete_user = $this->delete(array('user_id' => $user_id));
		return $delete_user;
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

}