<?php
namespace System\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;

class UsersDetailsModel extends TableGateway
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
	
   	/*Recuperamos todos los usuarios disponibles*/
	public function fetchAll($type_user)
	{
		//print_r($type_user); exit;
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select
			->columns(array('user_id', 'name', 'surname', 'lastname', 'email', 'phone', 'user_name', 'id_job', 'id_department'))
			->from(array('u'   => $this->table))
			->join(array('u_r' => 'iof_user_role'), 'u.user_id = u_r.user_id', array('role_id'), 'INNER')
			->join(array('i_r' => 'iof_role'), 'u_r.role_id = i_r.rid', array('role_name'), 'LEFT')
			->join(array('j_u' => 'job_users'), 'u.id_job = j_u.id', array('name_job'), 'LEFT')
			->join(array('d'   => 'department'), 'u.id_department = d.id_department', array('d_name'), 'LEFT')
		;
		
		if(isset($type_user) && $type_user == 1){
			$select->where(array('u.user_type' => $type_user));
		}
	
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
			//->columns(array('user_id','name','surname', 'lastname', 'email', 'phone'))
			->from(array('u' => $this->table))
			->join(array('u_r' => 'iof_user_role'), 'u.user_id = u_r.user_id', array('role'=>'role_id'), 'INNER')
			->where(array('u.user_id' => $id_user));
	
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
	}
	
	//Usuarios por usuario (Nomina)
	public function getPayRollByUser()
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select
			//->columns(array('user_id','name','surname', 'lastname', 'email', 'phone', 'password'))
			->from(array('u' => $this->table))
			//->join(array('u_r' => 'iof_user_role'), 'u.user_id = u_r.user_id', array('role'=>'role_id'), 'INNER')
			//->where(array('u.user_id' => $id_user));
			;
	
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
	}
	
	public function addUserDetails($data)
	{
		$insertDetails = $this->insert($data); 
		return $insertDetails;
	}
	
	/*Editar un usuario*/
	public function editUserDetails($data)
	{
		/*$connection = $this->dbAdapter->getDriver()->getConnection();
		$connection->beginTransaction();
		
		try {*/
			$id_user = (int) $data['acl_users_id'];
			//if($id_user != null || $id_user != 0){
				$user    = $this->update($data,array("acl_users_id" => $id_user));
				$result  = $user;
		/*		$connection->commit();
			}
		} catch (\Exception $e) {
			$result = "fail";
			$connection->rollback();
		}*/
		return $result;
	}
	
	//Eliminar un usuario
	public function deleteUser($user_id)
	{
		$delete_user = $this->delete(array('user_id' => $user_id));
		return $delete_user;
	}
	
	//Metodo para cambiar la contrase�a de un usuario
	public function changePassword($data)
	{
		$updatePass = $this->update($data, array("user_id" => $data['user_id']));
		return array("msg" => "1");
	}
	
	//Validar si una contrase�a es correcta
	public function validatePassword($data)
	{
		$id   = $data['user_id'];
		$pass = $data['password'];

		/*$sql = sprintf("SELECT COUNT(PASSWORD ) AS count
					    FROM iof_users
						WHERE user_id = %s
						AND PASSWORD = %s",$id, $pass);*/

		$sql = "SELECT COUNT(hash ) AS count
				FROM iof_users
				WHERE user_id = $id
				AND hash = '$pass'";
		
		$select = $this->dbAdapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
		
		$result  = $select->toArray();
		return $result;
	}

}