<?php
namespace System\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Predicate;
class UsersModel extends TableGateway
{
	private $dbAdapter;
	
	public function __construct()
   	{
   		$this->dbAdapter = \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter();
    	$this->table = 'iof_users';
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
			->columns(array('user_id', 'name', 'surname', 'lastname', 'email', 'phone', 'user_name', 'id_job', 'id_department', 'canlogin','numSep','trim','documents','curp','datebirth'))
			->from(array('u'   => $this->table))
			->join(array('u_d' => 'user_details'), 'u.user_id = u_d.acl_users_id', array('cost', 'period', 'mannerofpayment', 'date_admission'), 'LEFT')
			->join(array('u_r' => 'iof_user_role'), 'u.user_id = u_r.user_id', array('role_id'), 'LEFT')
			->join(array('i_r' => 'iof_role'), 'u_r.role_id = i_r.rid', array('role_name'), 'LEFT')
			->join(array('j_u' => 'job_users'), 'u.id_job = j_u.id', array('name_job'), 'LEFT')
			->join(array('d'   => 'department'), 'u.id_department = d.id_department', array('d_name'), 'LEFT')
			->order('lastname');
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
			->join(array('u_r' => 'iof_user_role'), 'u.user_id = u_r.user_id', array('role'=>'role_id'), 'LEFT')
			->join(array('u_d' => 'user_details'), 'u.user_id = u_d.acl_users_id', array('cost', 'period', 'mannerofpayment', 'date_admission', 'local_phone', 'cellphone', 'contract_type', 'birthday' ,'name_bank'=>'nameBanc','number_acount'=>'countBanc', 'interbank_clabe'=>'clabe', 'sucursal_name'=>'branch', 'photofile_ife', 'photofile_license', 'photofile_certification'), 'LEFT')
			->join(array('u_a' => 'users_addresses'), 'u.user_id = u_a.user_id',array('street', 'postalcode', 'number', 'interior', 'state_id', 'district_id', 'neighborhood'),'LEFT')	

			->join(array('s'   => 'states_of_mexico'), 'u_a.state_id = s.id', array('name_state' => 'state'), 'LEFT')
			->join(array('d'   => 'district'), 'u_a.district_id = d.id', array('name_district' => 'name'), 'LEFT')
			->join(array('n'   => 'neighborhood'), 'u_a.neighborhood = n.id', array('name_neighborhood' => 'colony'), 'LEFT')
			->where(array('u.user_id' => $id_user));
	
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
	}
	
	public function addUser($data)
	{
		
		$email   = $data['email'];
		$consult = $this->dbAdapter->query("SELECT count(email) as count FROM iof_users WHERE email='$email'",Adapter::QUERY_MODE_EXECUTE);
		$result  = $consult->toArray();
		
		//if($result[0]["count"] == 0){
			$user            = $this->insert($data);
			//echo "<pre>"; print_r($user); exit;
			//$data['user_id'] = $this->getLastInsertValue();
			$idUser          = $this->getLastInsertValue();
			//$user_role       = array('user_id' => $data['user_id']); 
			$result          = $idUser;
		/*}else{
			$result = false;
		}*/
		return $result;
	}
	
	/*Editar un usuario*/
	public function editUser($data)
	{
		$connection = $this->dbAdapter->getDriver()->getConnection();
		$connection->beginTransaction();
		try {
			//if($id_user != null || $id_user != 0){
	           $user    = $this->update($data,array("user_id" => $data['user_id']));
				$result  = "done";
				$connection->commit();
			//}
		} catch (\Exception $e) {
			//print_r($e->getMessage());exit;
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
	
	/*
	 * Metodo que obtiene todos los contactos de una compania
	 */
	public function getAllContactsByIdCompany($id)
	{
		$sql    = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select
			->columns(array('user_id', 'name', 'surname', 'lastname', 'email', 'phone', 'user_name', 'id_job', 'id_department','user_principal'))
			->from(array('u'   => $this->table))
			->join(array('u_d' => 'user_details'), 'u.user_id = u_d.acl_users_id', array('cost', 'period', 'mannerofpayment', 'date_admission'), 'LEFT')
			->join(array('u_r' => 'iof_user_role'), 'u.user_id = u_r.user_id', array('role_id'), 'LEFT')
			->join(array('i_r' => 'iof_role'), 'u_r.role_id = i_r.rid', array('role_name'), 'LEFT')
			->join(array('j_u' => 'job_users'), 'u.id_job = j_u.id', array('name_job'), 'LEFT')
			->join(array('d'   => 'department'), 'u.id_department = d.id_department', array('d_name'), 'LEFT')
			->where(array("u.id_company"=> (int) $id));

		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
	}
	
	/*
	 * LISTA DE LOS EMPLEADOS EN NOMINA
	 */
	public function getPayRollByUser()
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select
			->columns(array('user_id','name','surname', 'lastname'))
			->from(array('u'   => $this->table))
			->join(array('u_d' => 'user_details'), 'u.user_id = u_d.acl_users_id', array('cost', 'period', 'date_admission'), 'LEFT')
			->join(array('j_u' => 'job_users'), 'u.id_job = j_u.id', array('name_job'), 'LEFT')
			->where(array('u.user_type' => 1))
			->where->in("j_u.name_job", array("Operador", "Ayudante"));
	
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
	}
	
	/*
	 * Todos los usuarios
	 */
	public function getUsersAndDetails()
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select
		->columns(array('user_id','name','surname', 'lastname'))
		->from(array('u'   => $this->table))
		->join(array('u_d' => 'user_details'), 'u.user_id = u_d.acl_users_id', array('cost', 'period', 'mannerofpayment', 'date_admission'), 'LEFT');
	
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
	}
	
	/*
	 * Obtenemos todos los usuarios a pagar la nomina por fecha
	 */
	public function getPayRollByUserToDate($NTyp, $date)
	{
		$sql = new Sql($this->dbAdapter);
		$mainSelect = $sql->select()->from(array('u' => $this->table));
		
		$selectPost = $sql->select()
			->from(array('pp' => 'pay_payroll'))
			->columns(array('negativeVote' => new \Zend\Db\Sql\Expression('COUNT(pp.id_user)')))
			->where('pp.id_user = u.user_id')
			->where(array('pp.date' => $date))
			->where(array('pp.type' => "NOMINA"));
		
		$mainSelect->columns(
				array(
					'user_id','name','surname', 'lastname',
					'pay' => new \Zend\Db\Sql\Expression('?',array($selectPost))
				))
			->join(array('u_d' => 'user_details'), 'u.user_id = u_d.acl_users_id', array('cost', 'period', 'mannerofpayment', 'date_admission'),'LEFT')
			->join(array('j' => 'job_users'), 'u.id_job = j.id', array('name_job'), 'LEFT')	
			->where(array("u_d.period" => (int) $NTyp))
			->where(array("u_d.date_admission <= '$date'"));
		
		$selectString = $sql->getSqlStringForSqlObject($mainSelect);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
	}
	
	/*
	 * Historial de nomina por empleado
	 */
	public function getPayrollsByUserId($idEmployee)
	{
		$sql    = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select
			->columns(array('user_id','name','surname', 'lastname'))
			->from(array('u'   => $this->table))
			->join(array('u_d' => 'user_details'), 'u.user_id = u_d.acl_users_id', array('cost', 'period', 'date_admission'), 'LEFT')
			->join(array('p_p' => 'pay_payroll'), 'u.user_id = p_p.id_user', array('id_paypayroll', 'amount', 'date', 'description', 'type', 'keys'), 'LEFT')
			->where(array("p_p.id_user" => (int) $idEmployee));
		
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
	}
	
	/*
	 * USUARIOS CON MAYOR NUMERO DE VIAJES
	 */
	public function getAllUsersActive($type)
	{
		$sql    = new Sql($this->adapter);
		$select = $sql->select()->from(array('u' => $this->table));
		
		$subSelect = $sql->select()
			->from(array("u_s" => "users_shipping"))
			->columns(array('totalShipp' => new \Zend\Db\Sql\Expression('COUNT(u_s.id_user)')))
			->where('u_s.id_user = u.user_id');
		
		$select
			->columns(array(
					'user_id', 'name', 'surname', 'lastname',
					'totalShipp' => new \Zend\Db\Sql\Expression('?',array($subSelect))
			))
			->join(array('j' => 'job_users'), 'u.id_job = j.id', array('name_job'), 'LEFT')
			->order("totalShipp DESC")
			->limit(20);
		
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
		
	}
	
	/*
	 * OBTENER EMPLEADOS DISPONIBLES PARA VIAJES
	 */
	public function getEmployeesAvailable()
	{
		$sql    = new Sql($this->adapter);
		$select = $sql->select()->from(array('u' => $this->table));
		
		$subSelect = $sql->select()
			->from(array("u_s" => "users_shipping"))
			->columns(array('id_user'))
			->join(array('s' => 'shippings'), 'u_s.id_shipping = s.id_shipping', array(), 'LEFT')
			->where(new \Zend\Db\Sql\Predicate\Expression('DATE(s.end_date) = ?', '000-00-00'))
		;
		
		$select
			->columns(array(
					'user_id', 'name', 'surname', 'lastname', 'id_job'
			))
			//->join(array('u_s2' => 'users_shipping'), 'u.user_id = u_s2.id_user', array('role_id'), 'LEFT')
			//->join(array('u_r' => 'iof_user_role'), 'u.user_id = u_r.user_id', array('role_id'), 'LEFT')
			//->join(array('i_r' => 'iof_role'), 'u_r.role_id = i_r.rid', array('role_name'), 'LEFT')
			->join(array('j_u' => 'job_users'), 'u.id_job = j_u.id', array('name_job'), 'LEFT')
			//->where(array('table2.column2' => 2, 'table2.column3' => 3), Predicate\PredicateSet::OP_OR);\Zend\Db\Sql\Predicate\Predicate
			//->where(array("j_u.name_job"=>"Operador", "j_u.name_job"=>"Ayudante"), \Zend\Db\Sql\Predicate\Predicate::OP_OR)
			//->where->between("j_u.name_job", "Operador", "Ayudante")
			//->where->in("u.id_job", array(5, 7))
		->join(array('asis' => 'assistance'), 'u.user_id = asis.id_user', array("date_assistance", "status_assistance"), 'LEFT')
		->where(array("asis.date_assistance" => date("Y-m-d")))
		->where(array("asis.status_assistance" => 1))
			->order("u.surname ASC")
			->where->in("j_u.name_job", array("Operador", "Ayudante"))
			->where->notIn("u.user_id", $subSelect)
			//->where->isNull("shi.end_date");
			;
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
	}
	
	/*
	 * OBTENER LOS EMPLEADOS DISPONIBLES Y ASIGNADOS A UN VIAJE
	 */
	public function getEmployeesAvailableAndAssigned($type, $id)
	{
		$sql    = new Sql($this->adapter);
		//$select = $sql->select()->from(array('u' => $this->table));
	
		/*$subSelect = $sql->select()
		->from(array("u_s" => "users_shipping"))
		->columns(array('id_user'))
		->join(array('s' => 'shippings'), 'u_s.id_shipping = s.id_shipping', array(), 'LEFT')
		->where(new \Zend\Db\Sql\Predicate\Expression('DATE(s.end_date) = ?', '000-00-00'))
		;*/
	
		$select =	$sql->select();
		$select	
			->columns(array(
					'user_id', 'name', 'surname', 'lastname', 'id_job'
			))
			->from(array('u' => $this->table))
			->join(array('j_u' => 'job_users'), 'u.id_job = j_u.id', array('name_job'), 'LEFT')
			
			
			->join(array('u_s' => 'users_shipping'), 'u_s.id_user = u.user_id', array(), 'LEFT')
			
			
			->order("u.surname ASC")
			->where(array("u.user_type" => (int) $type))
			->where(array("u_s.id_shipping" => (int) $id))
			->where->in("j_u.name_job", array("Operador", "Ayudante"))
			//->where->notIn("u.user_id", $subSelect);
			;
		
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
	}
	
	/*
	 * Activar una cuenta o desactivar
	 */
	public function confirmShipping($data)
	{
		//print_r($data);exit;
		$row = $this->update($data, array("user_id" => $data['user_id']));
		return $row;
	}

}