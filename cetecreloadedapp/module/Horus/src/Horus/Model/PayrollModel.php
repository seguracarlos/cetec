<?php
namespace Horus\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;

class PayrollModel extends TableGateway
{
	private $dbAdapter;
	
	public function __construct()
   	{
   		$this->dbAdapter = \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter();
    	$this->table = 'pay_payroll';
       	$this->featureSet = new Feature\FeatureSet();
     	$this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
    	$this->initialize();
   	}
   	
   	/*
   	 * SALARIO DE VIAJES FORANEOS
   	 */
   	public function obtenerSalarioViajesForaneos($id_user, $desde, $hasta)
   	{
   		$sql    = new Sql($this->dbAdapter);
   		$select = $sql->select();
   		$select
   			->columns(array('salario_viajes_foraneos' => new \Zend\Db\Sql\Expression('SUM(amount)')))
   			->from(array('u_s'   => 'users_shipping'))
   			->where(array("u_s.id_user" => (int) $id_user))
   			->join(array('s' => 'shippings'), 'u_s.id_shipping = s.id_shipping', array(),'LEFT')
			->where(array("s.type_destination"=>2))
   			->where->between('u_s.date_shipping', $desde, $hasta);
   		
   		$selectString = $sql->getSqlStringForSqlObject($select);
   		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
   		$result       = $execute->toArray();
   		return $result;
   	}
   	
   	/*
   	 * SALARIO DE VIAJES LOCALES
   	 */
   	public function obtenerSalarioViajesLocales($id_user, $desde, $hasta)
   	{
   		$sql    = new Sql($this->dbAdapter);
   		$select = $sql->select();
   		$select
   			->columns(array('salario_viajes_locales' => new \Zend\Db\Sql\Expression('SUM(amount)')))
   			->from(array('u_s'   => 'users_shipping'))
   			->where(array("u_s.id_user" => (int) $id_user))
   			->join(array('s' => 'shippings'), 'u_s.id_shipping = s.id_shipping', array(),'LEFT')
			->where(array("s.type_destination"=>1))
   			->where->between('u_s.date_shipping', $desde, $hasta);
   		
   		$selectString = $sql->getSqlStringForSqlObject($select);
   		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
   		$result       = $execute->toArray();
   		return $result;
   	}
   	
   	/*
   	 * Calcular salario por empleado
   	 */
   	public function getSalaryByUser($idUser, $desde, $hasta)
   	{
   		return ;
   	}
   	
   	/*
   	 * Calcular salario por empleado (ENLASA-VIAJES)
   	 */
   	public function getSalaryByUserShippings($idUser, $desde, $hasta)
   	{	//print_r($desde);print_r($hasta);exit;
		$sql    = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select
			->columns(array('totalpagar' => new \Zend\Db\Sql\Expression('SUM(amount)')))
			->from(array('u_s'   => 'users_shipping'))
			/*->join(array('s' => 'shippings'), 'u_s.id_shipping = s.id_shipping', array(),'LEFT')
			->where(array("s.type_destination"=>2))*/
			->where(array("u_s.id_user" => (int) $idUser))
			->where->between('u_s.date_shipping', $desde, $hasta);
		
		$selectString = $sql->getSqlStringForSqlObject($select);
		//print_r($selectString); exit;
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
   	}
   	
   	/*
   	 * Calcular total de dias de asistencia (ENLASA-ASISTENCIA)
   	 */
   	public function assistanceDaysByUser($idUser, $desde, $hasta)
   	{
   		$sql    = new Sql($this->dbAdapter);
   		$select = $sql->select();
   		$select
   			->columns(array('dias_asistencia' => new \Zend\Db\Sql\Expression('COUNT(a.id_user)')))
   			->from(array('a' => 'assistance'))
   			->where(array("a.id_user" => (int) $idUser))
   			->where(array("a.status_assistance" => (int) 1))
   			->where->between('a.date_assistance', $desde, $hasta);
   		
   		$selectString = $sql->getSqlStringForSqlObject($select);
   		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
   		$result       = $execute->toArray();
   		return $result;
   	}
   	
   	/*
   	 * CALCULAR LA ASISTENCIA SEMANAL POR USUARIO
   	 */
   	public function getAssistantUserWeekly($idUser, $desde, $hasta)
   	{
   		$sql    = new Sql($this->dbAdapter);
   		$select = $sql->select();
   		$select
   		//->columns(array()))
   		->from(array('a' => 'assistance'))
   		->join(array('u' => 'iof_users'), 'a.id_user = u.user_id', array('id_job'),'LEFT')
   		->join(array('u_d' => 'user_details'), 'u.user_id = u_d.acl_users_id', array('cost', 'period', 'mannerofpayment', 'date_admission'),'LEFT')
   		->join(array('j' => 'job_users'), 'u.id_job = j.id', array('name_job'), 'LEFT')
   		->where(array("a.id_user" => (int) $idUser))
   		->where(array('a.status_assistance'  => (int) 1))
   		->where->between('a.date_assistance', $desde, $hasta);
   		 
   		$selectString = $sql->getSqlStringForSqlObject($select);
   		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
   		$result       = $execute->toArray();
   		//$result['type_destination'] = 1;
   		return $result;
   	}
   	
   	/*
   	 * Obtener detalle de nomina por usuario
   	 */
   	public function detailShippingsPayrollByIdUser($id, $desde, $hasta)
   	{
		$sql    = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select
			->columns(array(
					'id_shipping', 'type_destination', 'direct_route'
			))
			->from(array('s'   => 'shippings'))
			->join(array('u_s' => 'users_shipping'), 's.id_shipping = u_s.id_shipping', array('id_user', 'type_user', 'amount', 'date_shipping'),'LEFT')
			->join(array('d'   => 'destinations'), 's.id_destination = d.id_destination', array('name_destination', 'description_destination'), 'LEFT')
			->where(array("u_s.id_user" => (int) $id))
			->where->between('u_s.date_shipping', $desde, $hasta);
		
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
   	}
   	
   	/*
   	 * Pagar Nomina
   	 */
   	public function addPayRoll($data)
   	{
   		//echo "modelo nomina";
   		foreach ($data as $row){
   			$saveRows = $this->insert($row);
   		}
   		//print_r($data);
   		//exit;
   		return $saveRows;
   	}
   	
   	/*
   	 * Agregar prestamos
   	 */
   	public function addLoans($data)
   	{
   		//print_r($data);exit;
   		$sql    = new Sql($this->dbAdapter);
   		$insert = $sql->insert("pay_payroll");
   		
   		foreach ($data as $row){
   			$insert->values($row);
   			$loan   = $sql->prepareStatementForSqlObject($insert)->execute()->getGeneratedValue();
   		}
   		
   		
   		//$insertString = $sql->getSqlStringForSqlObject($insert);
   		//print_r($insertString);exit;
   		//$loan = $this->dbAdapter->query($insertString, Adapter::QUERY_MODE_EXECUTE);
   		//$insertString->execute()->getAffectedRows();
   		
   		//$lastId = $this->adapter->getDriver()->getLastGeneratedValue();
   		//print_r($loan);exit;
   		return $loan;
   	}
   	
   	/*
   	 * FUNCION PARA APLICAR LOS BONOS O LOS DESCUENTOS
   	 */
   	public function aplicateAmountByEmployee($data)
   	{
   		foreach ($data as $row){
   			
   			$amounts = $this->update($row, array("id_paypayroll" => (int) $row['id_paypayroll']));
   			//$this->update($data, array("id_destination" => (int) $data['id_destination']));
   		}
   		return $amounts;
   	}
   	
   	/*
   	 * LISTADO DE LA NOMINA PARA GENERAR UN EXCEL
   	 */
   	public function generateExcelPayroll($NTyp, $date)
   	{
   		$sql = new Sql($this->dbAdapter);
   		$mainSelect = $sql->select()->from(array('u' => 'iof_users'));
   		
   		$selectPost = $sql->select()
   		->from(array('pp' => 'pay_payroll'))
   		->columns(array('pay' => new \Zend\Db\Sql\Expression('COUNT(pp.id_user)')))
   		->where('pp.id_user = u.user_id')
   		->where(array('pp.date' => $date))
   		->where(array('pp.type' => "NOMINA"));
   		
   		$selectPost2 = $sql->select()
   		->from(array('p_p' => 'pay_payroll'))
   		->columns(array('amount'))
   		->where('p_p.id_user = u.user_id')
   		->where(array('p_p.date' => $date))
   		->where(array('p_p.type' => "NOMINA"));
   		
   		$selectPost3 = $sql->select()
   		->from(array('p_p' => 'pay_payroll'))
   		->columns(array('date'))
   		->where('p_p.id_user = u.user_id')
   		->where(array('p_p.date' => $date))
   		->where(array('p_p.type' => "NOMINA"));
   		
   		$mainSelect->columns(
   				array(
   						'user_id','name','surname', 'lastname',
   						'pay' => new \Zend\Db\Sql\Expression('?',array($selectPost)),
   						'amount' => new \Zend\Db\Sql\Expression('?',array($selectPost2)),
   						'date' => new \Zend\Db\Sql\Expression('?',array($selectPost3)),
   				))
   				->join(array('u_d' => 'user_details'), 'u.user_id = u_d.acl_users_id', array('date_admission', 'countBanc'),'LEFT')
   				->join(array('j' => 'job_users'), 'u.id_job = j.id', array('name_job'), 'LEFT')
   				->where(array("u_d.period" => (int) $NTyp))
   				->where(array("u_d.date_admission <= '$date'"))
   				->where->in("j.name_job", array("Operador", "Ayudante"));
   		
   		$selectString = $sql->getSqlStringForSqlObject($mainSelect);
   		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
   		$result       = $execute->toArray();
   		return $result;
   	}
   	
   	/*
   	 * OBTENER LISTADO DE NOMINA A PAGAR (POR VIAJES-ENLASA)
   	 */
   	public function getPayRollByUserToDateEnlasa($NTyp, $date)
   	{
   		$sql = new Sql($this->dbAdapter);
   		$mainSelect = $sql->select()->from(array('u' => 'iof_users'));
   	
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
   				->where(array("u_d.date_admission <= '$date'"))
   				->where->in("j.name_job", array("Operador", "Ayudante"));
   	
   		$selectString = $sql->getSqlStringForSqlObject($mainSelect);
   		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
   		$result       = $execute->toArray();
   		return $result;
   	}
   	
   	/*
   	 * OBTENER LISTADO DE LA NOMINA PAGADA
   	 */
   	public function getPaidPayrollList($date)
   	{
   		
   	}
	
	//Validar si una contraseña es correcta
	/*public function validatePassword($data)
	{
		$id   = $data['user_id'];
		$pass = $data['password'];

		$sql = sprintf("SELECT COUNT(PASSWORD ) AS count
					    FROM iof_users
						WHERE user_id = %s
						AND PASSWORD = %s",$id, $pass);

		$sql = "SELECT COUNT(PASSWORD ) AS count
				FROM iof_users
				WHERE user_id = $id
				AND PASSWORD = '$pass'";
		
		$select = $this->dbAdapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
		
		$result  = $select->toArray();
		return $result;
	}*/
   	
   	/*
   	 * TOTAL DE VIAJES LOCALES
   	 */
   	public function totalViajesLocales($idUser, $desde, $hasta)
   	{
   		$sql    = new Sql($this->dbAdapter);
   		$select = $sql->select();
   		$select
   			//->columns(array('total_viajes_locales' => new \Zend\Db\Sql\Expression('COUNT(u_s.date_shipping)')))
   			->columns(array('id_shipping'))
   			->from(array('u_s' => 'users_shipping'))
   			->where(array("u_s.id_user" => (int) $idUser))
   			//->where(array("a.status_assistance" => (int) 1))
   			->join(array('s' => 'shippings'), 'u_s.id_shipping = s.id_shipping', array(),'LEFT')
   			->where(array("s.type_destination" => 1))
   			->where->between('s.start_date', $desde, $hasta);
   		 
   		$selectString = $sql->getSqlStringForSqlObject($select);
   		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
   		$result       = $execute->toArray();
   		return $result;
   	}
   	
   	/*
   	 * TOTAL DE VIAJES FORANEOS
   	 */
   	public function totalViajesForaneos($idUser, $desde, $hasta)
   	{
   		$sql    = new Sql($this->dbAdapter);
   		$select = $sql->select();
   		$select
	   		//->columns(array('total_viajes_foraneos' => new \Zend\Db\Sql\Expression('COUNT(u_s.id_user)')))
   			->columns(array('id_shipping'))
	   		->from(array('u_s' => 'users_shipping'))
	   		->where(array("u_s.id_user" => (int) $idUser))
	   		//->where(array("a.status_assistance" => (int) 1))
	   		->join(array('s' => 'shippings'), 'u_s.id_shipping = s.id_shipping', array(),'LEFT')
	   		->where(array("s.type_destination" => 2))
	   		->where->between('s.start_date', $desde, $hasta);
   		 
   		$selectString = $sql->getSqlStringForSqlObject($select);
   		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
   		$result       = $execute->toArray();
   		return $result;
   	}
   	
   	/*
   	 * TOTAL BONOS Y DESCUENTOS
   	 */
   	//public function obtenerBonosDescuentos($id_user, $desde, $hasta)
   	//{
		/*$sql = new Sql($this->dbAdapter);
   		$mainSelect = $sql->select()->from(array('u' => 'iof_users'));
   	
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
		$select = $sql->select()->from(array('p_p' => $this->table));
		
		$selectBonos = $sql->select()
			->from($this->table)
			->columns(array(
					'bonos' => new \Zend\Db\Sql\Expression('SUM(amount)')
			))
			->where(array("p_p.id_user" => (int) $id_user))
			->where(array("p_p.type" => "BONO"))
	   		->where->between('p_p.date', $desde, $hasta);
		
   		$select
   			->columns(array(
   					'payf' => new \Zend\Db\Sql\Expression('?',array($selectBonos))
   			));
   	
   		$selectString = $sql->getSqlStringForSqlObject($select);
   		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
   		$result       = $execute->toArray();
   		return $result;*/
   	//}
   	
   	/*
   	 * OBTENER LISTA DE BONOS O DESCUENTOS SIN APLICAR
   	 */
   	public function getAllBonusByUser($data)
   	{
   		$sql    = new Sql($this->dbAdapter);
   		$select = $sql->select();
   		$select
   		->columns(array(
   				'id_paypayroll', 'amount', 'date', 'description', 'type', 'id_user', 'status'
   		))
   		->from(array("p_p"=>$this->table))
   		->where(array("p_p.id_user" => (int) $data['id_employee']))
   		->where(array("p_p.type" => $data['type']))
   		->where(array("p_p.status" => 0))
   		//->where->between('p_p.date', $desde, $hasta)
   		;
   		
   		$selectString = $sql->getSqlStringForSqlObject($select);
   		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
   		$result       = $execute->toArray();
   		return $result;
   	}
   	
   	/*
   	 * OBTENER LISTA DE BONOS O DESCUENTOS YA APLICADOS
   	 */
   	public function getAllBonusesAndDiscountsWithoutApplyingByUser($id_user, $date, $type)
   	{
   		$sql    = new Sql($this->dbAdapter);
   		$select = $sql->select();
   		$select
   			//->columns(array())
	   		->from(array("p_p"=>$this->table))
	   		->where(array("p_p.id_user" => (int) $id_user))
	   		->where(array("p_p.type" => $type))
	   		->where(array("p_p.status" => 1))
	   		->where(array("p_p.date" => $date));
   		
   		$selectString = $sql->getSqlStringForSqlObject($select);
   		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
   		$result       = $execute->toArray();
   		return $result;
   	}
   	
   	/*
   	 * OBTENER TOTAL BONOS APLICADOS
   	 */
   	public function obtenerBonos($id_user, $date, $type)
   	{
   		$sql    = new Sql($this->dbAdapter);
   		$select = $sql->select();
   		$select
   		->columns(array(
   				'bonos' => new \Zend\Db\Sql\Expression('SUM(amount)')
   		))
   		->from(array("p_p"=>$this->table))
   		->where(array("p_p.id_user" => (int) $id_user))
   		->where(array("p_p.type" => "BONO"))
   		->where(array("p_p.status" => 1))
   		->where(array("p_p.date" => $date));
   		//->where->between('p_p.date', $desde, $hasta)
   	
   		$selectString = $sql->getSqlStringForSqlObject($select);
   		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
   		$result       = $execute->toArray();
   		return $result;
   	}
   	
   	/*
   	 * OBTENER TOTAL DESCUENTOS APLICADOS
   	 */
   	public function obtenerDescuentos($id_user, $date, $type)
   	{
   		$sql    = new Sql($this->dbAdapter);
   		$select = $sql->select();
	   	$select
	   		->columns(array(
	   				'descuentos' => new \Zend\Db\Sql\Expression('SUM(amount)')
	   		))
	   		->from(array("p_p"=>$this->table))
	   		->where(array("p_p.id_user" => (int) $id_user))
	   		->where(array("p_p.type" => "DESCUENTO"))
	   		->where(array("p_p.status" => 1))
	   		->where(array("p_p.date" => $date));
	   		//->where->between('p_p.date', $desde, $hasta)
	   		
	   	$selectString = $sql->getSqlStringForSqlObject($select);
   		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
   		$result       = $execute->toArray();
   		return $result;
   	}
   	
   	/*
   	 * ELIMINAR BONOS O DESCUENTOS POR USUARIOS
   	 */
   	public function deleteAmountByUser($id)
   	{
   		$id_amount = (int) $id;
   		$deleteRow = $this->delete(array("id_paypayroll" => $id_amount));
   		return $deleteRow;
   	}
   	
   	/*
   	 * OBTENER EL SALARIO CUANDO YA FUE PAGADA LA NOMINA
   	 */
   	public function getSalaryPaidPayrollByUser($id_user, $date)
   	{
   		$sql    = new Sql($this->dbAdapter);
   		$select = $sql->select();
	   	$select
	   		->columns(array('amount'))
	   		->from(array("p_p"=>$this->table))
	   		->where(array("p_p.id_user" => (int) $id_user))
	   		->where(array("p_p.type" => "NOMINA"))
	   		//->where(array("p_p.status" => 1))
	   		->where(array("p_p.date" => $date));
	   		//->where->between('p_p.date', $desde, $hasta)
	   		
	   	$selectString = $sql->getSqlStringForSqlObject($select);
   		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
   		$result       = $execute->toArray();
   		return $result;
   	}
   	
   	/*
   	 * OBTENER LOS VIAJES POR DIA DE UN USUARIO
   	 */
   	

}