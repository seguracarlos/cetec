<?php
namespace System\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;

class UsersRolesModel extends TableGateway
{
	private $dbAdapter;
	
	public function __construct()
	{
		$this->dbAdapter = \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter();
		$this->table = 'iof_user_role';
		$this->featureSet = new Feature\FeatureSet();
		$this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
		$this->initialize();
	}
	
	//Metodo que agrega un usuario relacionado con un rol
	public function addRoleToUser($data)
	{
		$user_role = $this->insert($data);
		
		return $user_role;
	}
	
	//Metodo que edita un usuario relacionado con un rol
	public function editRoleToUser($data,$user_id)
	{
		$user_role = $this->update($data, array("user_id" => $user_id));
		return $user_role;
	}
}