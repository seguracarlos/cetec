<?php
namespace In\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;

class CompaniesModel extends TableGateway
{
	private $dbAdapter;
	
	public function __construct()
   	{
   		$this->dbAdapter  = \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter();
    	$this->table      = 'company';
       	$this->featureSet = new Feature\FeatureSet();
     	$this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
    	$this->initialize();
   	}
	
   	/* Todas las companias por tipo*/
	public function fetchAll($type)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select
			->columns(array('id_company', 'name_company', 'brand', 'rfc', 'website', 'business', 'interestingin', 'record_date'))
			->from(array('c'   => $this->table))
			->join(array('i_u' => 'iof_users'), 'c.id_company = i_u.id_company', array('name', 'surname', 'lastname', 'email', 'phone'), 'Left')
			->join(array('j_u' => 'job_users'), 'i_u.id_job = j_u.id', array('name_job','id_job_user'=>'id'), 'LEFT')
			/*->join(array('t' => 'types'), 'i.types_id_types = t.id_types', array('name_type' => 'description'), 'LEFT')*/
			->where(array('c.cust_type' => $type))
			//->where(array('c.type_client' => 1))
			->order("name_company ASC");

		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
	}
	
	// Comapnia por id
	public function getCompanyById2($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select
			->columns(array('id_company', 'name_company', 'brand', 'rfc', 'website', 'business', 'interestingin'))
			->from(array('c'   => $this->table))
			->join(array('a'   => 'addresses'), 'c.id_company = a.company_id', array('street', 'postalcode', 'number', 'interior', 'neighborhood', 'state_id', 'district', 'phone', 'ext', 'url_map'), 'LEFT')
			->join(array('i_u' => 'iof_users'), 'c.id_company = i_u.id_company', array('user_id'=>'user_id', 'name_contact'=>'name', 'surname_contact'=>'surname', 'lastname_contact'=>'lastname', 'phone_contact'=>'phone', 'mail_contact'=>'email', 'canlogin'=>'canlogin', 'user_name'=>'user_name', 'password'=>'password', 'id_job'=>'id_job'), 'LEFT')
			//->join(array('j'   => 'job_users'), 'i_u.id_job = j.id', array('name_job' => 'description'), 'LEFT')
			->where(array('c.id_company' => $id))
			->where(array('i_u.user_principal' => 1));
		;
	
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
	}
	
	// Comapnia por id
	public function getCompanyById($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select
			->columns(array('id_company', 'name_company', 'brand', 'rfc', 'website', 'business', 'interestingin', 'type_client'))
			->from(array('c'   => $this->table))
			->join(array('a'   => 'addresses'), 'c.id_company = a.company_id', array('street', 'postalcode', 'number', 'interior', 'neighborhood', 'state_id', 'district', 'phone', 'ext', 'url_map'), 'LEFT')
			->join(array('i_u' => 'iof_users'), 'c.id_company = i_u.id_company', array('user_id'=>'user_id', 'name_contact'=>'name', 'surname_contact'=>'surname', 'lastname_contact'=>'lastname', 'phone_contact'=>'phone', 'mail_contact'=>'email', 'canlogin'=>'canlogin', 'user_name'=>'user_name', 'password'=>'password', 'id_job'=>'id_job'), 'LEFT')
			->join(array('u_r' => 'iof_user_role'), 'i_u.user_id = u_r.user_id', array('role'=>'role_id'), 'LEFT')
			->join(array('s'   => 'states_of_mexico'), 'a.state_id = s.id', array('name_state' => 'state'), 'LEFT')
			->join(array('d'   => 'district'), 'a.district = d.id', array('name_district' => 'name'), 'LEFT')
			->join(array('n'   => 'neighborhood'), 'a.neighborhood = n.id', array('name_neighborhood' => 'colony'), 'LEFT')
			->where(array('c.id_company' => $id));
		//->where(array('i_u.user_principal' => 1));
	
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
	}
	
	/*
	 * Agregar una compania
	 */
	public function addCompany($data)
	{
		$connection = null;
		
		try {
			$connection = $this->dbAdapter->getDriver()->getConnection();
			$connection->beginTransaction();
		
			// Ejecutar una o más consultas aquí
			$company = $this->insert($data);
			$saveCompany = $this->getLastInsertValue();
			$connection->commit();
		}
		catch (\Exception $e) {
			if ($connection instanceof \Zend\Db\Adapter\Driver\ConnectionInterface) {
				$connection->rollback();
			}
			// Tratamiento de errores
			$saveCompany = $e->getCode();
		}
		
		return $saveCompany;
	}
	
	/*
	 * Modificar una compania
	 */
	public function editCompany($data)
	{	//echo "<pre>"; print_r($data); exit;
		$connection = null;
	
		try {
			$connection = $this->dbAdapter->getDriver()->getConnection();
			$connection->beginTransaction();
	
			/* Ejecutar una o más consultas aquí */
			$company     = $this->update($data, array("id_company" => $data['id_company']));
			$editCompany = $data['id_company'];
			$connection->commit();
		}
		catch (\Exception $e) {
			if ($connection instanceof \Zend\Db\Adapter\Driver\ConnectionInterface) {
				$connection->rollback();
			}
			/* Tratamiento de errores */
			$editCompany = $e->getCode();
		}
		//echo "<pre>"; print_r($editCompany); exit;
		return $editCompany;
	}
	
	/*
	 * Eliminar compania
	 */
	public function deleteCompany($id)
	{
		print_r($id);exit;
		$delete = $this->delete(array('id_company' => (int) $id));
		return $delete;
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
	 * CLIENTES MAS FRECUENTES
	 */
	public function getAllClientsActive($type)
	{
		$sql    = new Sql($this->adapter);
		$select = $sql->select()->from(array('c' => $this->table));
	
		$subSelect = $sql->select()
			->from(array("s" => "shippings"))
			->columns(array('totalShipp' => new \Zend\Db\Sql\Expression('COUNT(s.company_ID)')))
			->where('s.company_ID = c.id_company');
	
		$select
			->columns(array(
					'id_company', 'name_company', 'brand', 'rfc', 'website', 'business', 'interestingin',
					'totalShipp' => new \Zend\Db\Sql\Expression('?',array($subSelect))
			))
			->order("totalShipp DESC")
			->where(array('c.cust_type' => $type))
			->limit(20);
	
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
	
	}

}