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

class RegisterModel extends TableGateway
{
	private $dbAdapter;
	
	public function __construct()
	{
		$this->dbAdapter  = \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter();
		$this->table      = 'iof_users';
		$this->featureSet = new Feature\FeatureSet();
		$this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
		$this->initialize();
	}
	
	public function fetchAll()
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select
		//->columns(array('id_company', 'name_company', 'brand', 'rfc', 'website', 'business', 'interestingin', 'record_date'))
		->from(array('t'   => $this->table))
		->join(array('t_t' => 'tipo_tutorial'), 't.tipo_id = t_t.id_tipo', array('name_tipo'), 'LEFT');
		//->where(array('display' => 1));
	
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
	}
	
	
	public function getRowById($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select
		//->columns(array('id_company', 'name_company', 'brand', 'rfc', 'website', 'business', 'interestingin'))
		->from(array('t'   => $this->table))
		//->join(array('u_s' => 'users_shipping'), 's.id_shipping = u_s.id_shipping', array('id_user','type_user', 'amount'), 'LEFT')
		->where(array('t.user_id' => $id));
	
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
	}
	
	public function getRowByMail($mail)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select
		->from(array('t'   => $this->table))
		->where(array('t.email' => $mail));
		
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
	}
	
	public function addRow($data)
	{
		$connection = null;
		
		try {
			$connection = $this->dbAdapter->getDriver()->getConnection();
			$connection->beginTransaction();
		
			// Ejecutar una o mas consultas aqui
			$email   = $data['email'];
			$consult = $this->dbAdapter->query("SELECT count(email) as count FROM iof_users WHERE email='$email'",Adapter::QUERY_MODE_EXECUTE);
			$result  = $consult->toArray();
			//echo "<pre>"; print_r($result); exit;
			if($result[0]["count"] == 0){
				$row     = $this->insert($data);
				$saveRow = $this->getLastInsertValue();
				$connection->commit();
			}else{
				$saveRow = false;
			}
			
			return $saveRow;
			
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
	
	public function editRow($data)
	{
		$connection = null;
	
		try {
			$connection = $this->dbAdapter->getDriver()->getConnection();
			$connection->beginTransaction();
	
			// Ejecutar una o mas consultas aqui
			$row = $this->update($data, array('user_id' => $data['user_id']));
			$saveRow = $data['user_id'];
			$connection->commit();
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
	
	public function deleteRow($id)
	{
		$deleteRow = $this->delete(array('idtutorial' => (int) $id));
		return $deleteRow;
	}
}