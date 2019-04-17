<?php
namespace Registrationusers\Model;

/*
 * Usamos el componente tablegateway que nos permite hacer consultas
 * utilizando una capa de abstracción, aremos las consultas sobre
 * una tabla que indicamos en el constructor
 */
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;

class FichaRegistroModel extends TableGateway
{
	private $dbAdapter;
	
	public function __construct()
	{
		$this->dbAdapter  = \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter();
		$this->table      = 'tokens';
		$this->featureSet = new Feature\FeatureSet();
		$this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
		$this->initialize();
	}
	
	
	public function getRowById($token)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select
		//->columns(array('id_company', 'name_company', 'brand', 'rfc', 'website', 'business', 'interestingin'))
		->from(array('t'   => $this->table))
		//->join(array('u_s' => 'users_shipping'), 's.id_shipping = u_s.id_shipping', array('id_user','type_user', 'amount'), 'LEFT')
		->where(array('t.token' => $token));
	
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
	}
	
	public function getTokenByUser($id_user)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select
		//->columns(array('id_company', 'name_company', 'brand', 'rfc', 'website', 'business', 'interestingin'))
		->from(array('t'   => $this->table))
		//->join(array('u_s' => 'users_shipping'), 's.id_shipping = u_s.id_shipping', array('id_user','type_user', 'amount'), 'LEFT')
		->where(array('t.id_user' => $id_user));
		
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
		
	}
	
	public function addRow($data)
	{
		//echo "<pre>"; print_r($data); exit;
		$connection = null;
		
		try {
			$connection = $this->dbAdapter->getDriver()->getConnection();
			$connection->beginTransaction();
		
				$row     = $this->insert($data);
				$data['id_token'] = $this->getLastInsertValue();
				$connection->commit();
			
			
			return $data;
			
		}
		catch (\Exception $e) {
			if ($connection instanceof \Zend\Db\Adapter\Driver\ConnectionInterface) {
				$connection->rollback();
			}
			// Tratamiento de errores
			$saveRow = $e->getCode();
		}
		
		return $saveRow;
	}
	
	public function updateRow($data)
	{
		$connection = null;
	
		try {
			$connection = $this->dbAdapter->getDriver()->getConnection();
			$connection->beginTransaction();
	
			// Ejecutar una o mas consultas aqui
			$row = $this->update($data, array('id_user' => $data['id_user']));
			$updateRow = $data['id_user'];
			$connection->commit();
		}
		catch (\Exception $e) {
			if ($connection instanceof \Zend\Db\Adapter\Driver\ConnectionInterface) {
				$connection->rollback();
			}
			// Tratamiento de errores
			$updateRow = $e->getCode();
		}
	
		return $data;
	}
	
	public function deleteRow($id)
	{
		$deleteRow = $this->delete(array('id_user' => $id));
		return $deleteRow;
	}
}