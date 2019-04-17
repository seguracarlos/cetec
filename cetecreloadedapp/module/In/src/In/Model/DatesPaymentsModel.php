<?php
namespace In\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;

class DatesPaymentsModel extends TableGateway
{
	private $dbAdapter;
	
	public function __construct()
   	{
   		$this->dbAdapter  = \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter();
    	$this->table      = 'dates_of_payments';
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
			//->columns(array('id_company', 'name_company', 'brand', 'rfc', 'website', 'business', 'interestingin', 'record_date'))
			->from(array('p'   => $this->table))
			->join(array('c' => 'company'), 'p.company_ID = c.id_company', array('name_company'), 'LEFT')
			/*->join(array('j_u' => 'job_users'), 'i_u.id_job = j_u.id', array('name_job','id_job_user'=>'id'), 'LEFT')
			->join(array('t' => 'types'), 'i.types_id_types = t.id_types', array('name_type' => 'description'), 'LEFT')*/
			->where(array('p.type' => $type));
		;
	
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
	}
	
	// Proyecto por id
	public function getProjectById($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select
		//->columns(array('id_company', 'name_company', 'brand', 'rfc', 'website', 'business', 'interestingin'))
		->from(array('p'   => $this->table))
		//->join(array('a'   => 'addresses'), 'c.id_company = a.company_id', array('street', 'postalcode', 'number', 'interior', 'neighborhood', 'state_id', 'district', 'phone', 'ext', 'url_map'), 'LEFT')
		//->join(array('i_u' => 'iof_users'), 'c.id_company = i_u.id_company', array('user_id'=>'user_id', 'name_contact'=>'name', 'surname_contact'=>'surname', 'lastname_contact'=>'lastname', 'phone_contact'=>'phone', 'mail_contact'=>'email', 'canlogin'=>'canlogin', 'user_name'=>'user_name', 'password'=>'password', 'id_job'=>'id_job'), 'LEFT')
		//->join(array('s'   => 'states_of_mexico'), 'a.state_id = s.id', array('name_state' => 'state'), 'LEFT')
		//->join(array('d'   => 'district'), 'a.district = d.id', array('name_district' => 'name'), 'LEFT')
		//->join(array('n'   => 'neighborhood'), 'a.neighborhood = n.id', array('name_neighborhood' => 'colony'), 'LEFT')
		//->where(array('c.id_company' => $id))
		->where(array('p.ID' => $id));
		;
	
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
	}
	
	/*
	 * Agregar un proyecto
	 */
	public function addPaymentsProject($data)
	{	//echo "<pre>"; print_r($data); exit;
		$connection = null;
		
		try {
			$connection = $this->dbAdapter->getDriver()->getConnection();
			$connection->beginTransaction();
		
			/* Ejecutar una o más consultas aquí */
			foreach ($data as $d){
				$payments     = $this->insert($d);
			}
			
			//$savePayments = $this->getLastInsertValue();
			$savePayments = $data;
			$connection->commit();
		}
		catch (\Exception $e) {
			if ($connection instanceof \Zend\Db\Adapter\Driver\ConnectionInterface) {
				$connection->rollback();
			}
			/* Tratamiento de errores */
			$savePayments = $e->getCode();
		}
		
		return $savePayments;
	}
	
	/*
	 * Modificar una compania
	 */
	public function editProject($data)
	{	//echo "<pre>"; print_r($data); exit;
		$connection = null;
	
		try {
			$connection = $this->dbAdapter->getDriver()->getConnection();
			$connection->beginTransaction();
	
			/* Ejecutar una o más consultas aquí */
			$project     = $this->update($data, array("ID" => $data['ID']));
			$editProject = $data['ID'];
			$connection->commit();
		}
		catch (\Exception $e) {
			if ($connection instanceof \Zend\Db\Adapter\Driver\ConnectionInterface) {
				$connection->rollback();
			}
			/* Tratamiento de errores */
			$editProject = $e->getCode();
		}
		//echo "<pre>"; print_r($editCompany); exit;
		return $editProject;
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

}