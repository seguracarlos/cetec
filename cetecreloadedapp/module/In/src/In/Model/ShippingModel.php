<?php
namespace In\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;

class ShippingModel extends TableGateway
{
	private $dbAdapter;
	
	public function __construct()
   	{
   		$this->dbAdapter  = \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter();
    	$this->table      = 'shippings';
       	$this->featureSet = new Feature\FeatureSet();
     	$this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
    	$this->initialize();
   	}
	
   	/*
   	 * Todas las ordenes de envio
   	 */
	public function fetchAll()
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select
			//->columns(array('id_company', 'name_company', 'brand', 'rfc', 'website', 'business', 'interestingin', 'record_date'))
			->from(array('s'   => $this->table))
			->join(array('c' => 'company'), 's.company_ID = c.id_company', array('name_company'), 'LEFT');
	
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
	}
	
	/*
	 * Obtener registro por id
	 */
	public function getRowById($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select
		//->columns(array('id_company', 'name_company', 'brand', 'rfc', 'website', 'business', 'interestingin'))
		->from(array('s'   => $this->table))
		->join(array('u_s' => 'users_shipping'), 's.id_shipping = u_s.id_shipping', array('id_user','type_user', 'amount'), 'LEFT')
		->where(array('s.id_shipping' => $id));
	
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
	}
	
	/*
	 * Obtener detalle de registro por id
	 */
	public function getDetailRowById($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select
		//->columns(array('id_company', 'name_company', 'brand', 'rfc', 'website', 'business', 'interestingin'))
		->from(array('s'   => $this->table))
		->join(array('u_s' => 'users_shipping'), 's.id_shipping = u_s.id_shipping', array('id_user','type_user', 'amount'), 'LEFT')
		->join(array('i_u' => 'iof_users'),'u_s.id_user = i_u.user_id',array('name', 'surname', 'lastname'), 'LEFT')
		->join(array('c'   => 'company'), 's.company_ID = c.id_company', array('name_company'), 'LEFT')
		->join(array('d'   => 'destinations'), 's.id_destination = d.id_destination', array('name_destination', 'description_destination'), 'LEFT')
		->where(array('s.id_shipping' => $id));
	
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
	}
	
	/*
	 * Agregar
	 */
	public function addRow($data)
	{	//echo "<pre>"; print_r($data); exit;
		$connection = null;
		
		try {
			$connection = $this->dbAdapter->getDriver()->getConnection();
			$connection->beginTransaction();
		
			// Ejecutar una o mas consultas aqui 
			$row     = $this->insert($data);
			$saveRow = $this->getLastInsertValue();
			//print_r($saveRow); exit;
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
	
	/*
	 * Modificar un registro
	 */
	public function editRow($data)
	{	//echo "<pre>"; print_r($data); exit;
		 
		$connection = null;
	
		try {
			$connection = $this->dbAdapter->getDriver()->getConnection();
			$connection->beginTransaction();
			$data['status'] = 0;
			// Ejecutar una o mis consultas aqui
			$row     = $this->update($data, array("id_shipping" => $data['id_shipping']));
			$editRow = $data['id_shipping'];
			$connection->commit();
		}
		catch (\Exception $e) {
			if ($connection instanceof \Zend\Db\Adapter\Driver\ConnectionInterface) {
				$connection->rollback();
			}
			/* Tratamiento de errores */
			$editRow = $e->getCode();
		}
		//echo "<pre>"; print_r($editCompany); exit;
		return $editRow;
	}
	
	/*
	 * Eliminar
	 */
	public function deleteRow($id)
	{
		$deleteRow = $this->delete(array('id_shipping' => (int) $id));
		return $deleteRow;
	}
	
	/*
	 * Obtenemos proyectos por id de compania
	 */
	public function getAllProjectsByIdCompany($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select
		//->columns(array('id_company', 'name_company', 'brand', 'rfc', 'website', 'business', 'interestingin', 'record_date'))
		->from(array('p'   => $this->table))
		//->join(array('c' => 'company'), 'p.company_ID = c.id_company', array('name_company'), 'LEFT')
		/*->join(array('j_u' => 'job_users'), 'i_u.id_job = j_u.id', array('name_job','id_job_user'=>'id'), 'LEFT')
		 ->join(array('t' => 'types'), 'i.types_id_types = t.id_types', array('name_type' => 'description'), 'LEFT')*/
		->where(array('p.company_ID' => $id));
	
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
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

		$sql = "SELECT COUNT(PASSWORD ) AS count
				FROM iof_users
				WHERE user_id = $id
				AND PASSWORD = '$pass'";
		
		$select = $this->dbAdapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
		
		$result  = $select->toArray();
		return $result;
	}
	
	public function fetchAllProjectsDate($type)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select
		->from(array('p'   => $this->table))
		->join(array('c' => 'company'), 'p.company_ID = c.id_company',array('*'),'LEFT')
		->join(array('u_p' => 'users_projects'), 'p.ID = u_p.projects_ID',array('*'),'LEFT')
		->join(array('i_u' => 'iof_users'), 'i_u.user_id = u_p.acl_users_id', array('name_iof_user' => 'name','surname'),'LEFT')
		->join(array('i' => 'inventories'), 'i_u.user_id = i.id_acl_users',array('*'),'LEFT')
		->where(array('p.type' => $type));
		
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
	}
	
	/*
	 * Confirmar una orden de envio
	 */
	public function confirmShipping($data)
	{
		$row = $this->update($data, array("id_shipping" => $data['id_shipping']));
		return $row;
	}
	
	/*
	 * Todos los viajes activos
	 */
	public function getAllShippingsActive()
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select
			->columns(array('id_shipping', 'start_date'))
			->from(array('s' => $this->table))
			->join(array('c' => 'company'), 's.company_ID = c.id_company', array('name_company'), 'LEFT')
			->order("start_date DESC")
			->where(array("s.end_date"=>"0000-00-00"));
			//->where->isNull("s.end_date");
	
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
	}
	
	/*
	 * OBTENER EL SUIGUIENTE NUMERO DE FOLIO INTERNO
	 */
	public function getNextFolioNumber()
	{
		$sql 	= new Sql($this->dbAdapter);
		$select = $sql->select();
		$select
			->columns(array('maxId' => new \Zend\Db\Sql\Expression('MAX(s.internal_folio)')))
			->from(array('s' => $this->table));
		
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute	  = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result		  = $execute->toArray();
		return $result;
	}
	
	/*
	 * OBTENER LISTA DE LOS CLIENTES DE LOS CLIENTES
	 */
	public function autoCompleteClientsEnd($data)
	{
		//print_r($data); exit;
		$sql 	= new Sql($this->dbAdapter);
		$select = $sql->select();
		$select
			//->columns(array('maxId' => new \Zend\Db\Sql\Expression('MAX(s.internal_folio)')))
			->from(array('e_c' => 'end_customers'))
			->where->like('Nombre', $data['q'].'%');
		
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute	  = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result		  = $execute->toArray();
		return $result;
	}
	
}