<?php
namespace Expenses\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;

class ExpensesModel extends TableGateway
{
	private $dbAdapter;
	
	public function __construct()
   	{
   		$this->dbAdapter = \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter();
    	$this->table = 'expenses';
       	$this->featureSet = new Feature\FeatureSet();
     	$this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
    	$this->initialize();
   	}
	
   	/*Recuperamos todos los gastos*/
	public function fetchAll()
	{
		$select = $this->select();
		$data  = $select->toArray();	
		return $data;
	}
	
	//Recuperamos un gasto por id
	public function getExpensesById($id_expense)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select
			//->columns(array('user_id','name','surname', 'lastname', 'email', 'phone', 'password'))
			->from(array('e' => $this->table))
			//->join(array('u_r' => 'iof_user_role'), 'u.user_id = u_r.user_id', array('role'=>'role_id'), 'INNER')
			->where(array('e.idExpenses' => $id_expense));
	
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
	}
	
	public function addExpenses($data)
	{
		/*echo "<pre>"; print_r($data); exit;*/
		$addInventory = $this->insert($data);
		return $addInventory;
	}
	
	/*Editar un gasto*/
	public function editExpenses($data, $id_expense)
	{
		$id       = (int) $id_expense;
		$expenses = $this->update($data, array("idExpenses" => $id));

		return $data;
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