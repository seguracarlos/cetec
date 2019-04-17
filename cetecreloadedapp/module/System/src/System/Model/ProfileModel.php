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

class ProfileModel extends TableGateway
{
	private $dbAdapter;
	
	public function __construct()
	{
		$this->dbAdapter = \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter();
		$this->table = "profileinfo";
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
	

	public function addProfileInfo($data)
	{
		$profileInfo = $this->insert($data);
		return $data;
	}
	
	//Metodo que editar un rol 
	public function updateProfileInfo($data)
	{	//echo "<pre>"; print_r($data); exit;
		//if(isset($data['rid'])){
		$updateProfileInfo = $this->update($data, array("id_user" => $data['id_user']));
		//}
		return $data;
	}
	

	
	//Metodo que trae un rol por id_role
	public function getProfileByUser($id_user)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select
			//->columns(array('email','password','nombre', 'apellido'))
			->from($this->table)
			->where(array('id_user' => $id_user));
		
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		
		
		return $result;
	}
	
}