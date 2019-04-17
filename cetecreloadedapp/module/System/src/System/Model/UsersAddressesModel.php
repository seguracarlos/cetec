<?php
namespace System\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;

class UsersAddressesModel extends TableGateway
{
	private $dbAdapter;
	
	public function __construct()
   	{
   		$this->dbAdapter = \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter();
    	$this->table = 'users_addresses';
       	$this->featureSet = new Feature\FeatureSet();
     	$this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
    	$this->initialize();
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

	/*
	 * Agregar dirreccion a usuarios
	 */
	public function addUserAddresses($data)
	{
		$insertDetails = $this->insert($data); 
		return $insertDetails;
	}
	
	/*
	 * Modificar Direccion de un usuario
	 */
	public function editUserAddresses($data)
	{
		$connection = $this->dbAdapter->getDriver()->getConnection();
		$connection->beginTransaction();
		
		try {
			$id_user = (int) $data['user_id'];
			if($id_user != null || $id_user != 0){
				$user    = $this->update($data,array("user_id" => $id_user));
				$result  = $user;
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
}