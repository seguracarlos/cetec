<?php
namespace Out\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;

class ExpensesTripModel extends TableGateway
{
	private $dbAdapter;
	
	public function __construct()
   	{
   		$this->dbAdapter = \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter();
    	$this->table = 'expensestrip';
       	$this->featureSet = new Feature\FeatureSet();
     	$this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
    	$this->initialize();
   	}
   	public function fetchAllExpensesTrip($id_viaje){
   		$sql = new Sql($this->dbAdapter);
   		$select = $sql->select();
   		$select
   		->from(array('s' => $this->table))
   		->where(array('id_trip_expensestrip ='.$id_viaje));
   		$selectString = $sql->getSqlStringForSqlObject($select);
   		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
   		$result       = $execute->toArray();
   		return $result;
   	}
   	public function addExpense($data){
   		$expense = $this->insert($data);
   		$expenseData['id_expensestrip'] = $this->getLastInsertValue();
   		$sql = new Sql($this->dbAdapter);
   		$select = $sql->select();
   		$select
   		->from(array('e'   => $this->table))
   		->where(array('e.id_expensestrip' => $expenseData['id_expensestrip']));
   		$selectString = $sql->getSqlStringForSqlObject($select);
   		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
   		$result       = $execute->toArray();
   		return $result;
   	}
   	public function deleteExpense($id_expense,$id_viaje){
   		$delete_expense = $this->delete(array('id_expensestrip' => $id_expense,'id_trip_expensestrip' => $id_viaje));
   		return $delete_expense;
   	}
   	public function getJourney($id_viaje){
   		$sql = new Sql($this->dbAdapter);
   		$select = $sql->select();
   		$select
   		->from(array('s' => 'shippings'))
   		->columns(array('id_shipping','start_date','end_date','starting_mileage','end_mileage','status_saveExpenses','end_gasoline_end_journey','client_folio','status_full_data','return_viatic_rest','internal_folio','type_destination'))
   		->where(array('s.id_shipping ='.$id_viaje))
   		->where(array('s.status = 1'))
   		->join(array('c' => 'company'),'c.id_company = s.company_ID',array('name_company'),'left')
   		->join(array('p' => 'preferences'),'s.type_gasoline = p.id',array('type_gasoline_name' => 'name', 'type_gasoline_value' => 'value'),'left')
   		->join(array('d' => 'destinations'),'d.id_destination = s.id_destination',array('name_destination'),'left')
   		->join(array('i' => 'inventories'),'i.id_inventories = s.id_truck',array('id_product'),'left');
   		$selectString = $sql->getSqlStringForSqlObject($select);
   		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
   		$result       = $execute->toArray();
   		return $result;
   	}
   	public function getSinister($id_viaje){
   		$sql = new Sql($this->dbAdapter);
   		$select = $sql->select();
   		$select
   		->from(array('s' => 'sinister'))
   		->where(array('s.id_viaje ='.$id_viaje));
   		$selectString = $sql->getSqlStringForSqlObject($select);
   		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
   		$result       = $execute->toArray();
   		return $result;
   	}
   	public function getTypesGas(){
   		$sql = new Sql($this->dbAdapter);
   		$select = $sql->select();
   		$select
   		->from(array('p'   => 'preferences'))
   		->where(array('name = "GASOLINE_DISEL"'));
   		$selectString = $sql->getSqlStringForSqlObject($select);
   		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
   		$result       = $execute->toArray();
   		 
   		$select = $sql->select();
   		$select
   		->from(array('p'   => 'preferences'))
   		->where(array('name = "GASOLINE_MAGNA"'));
   		$selectString = $sql->getSqlStringForSqlObject($select);
   		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
   		$result2       = $execute->toArray();
   		 
   		$resultFinish = array_merge($result, $result2);
   		return $resultFinish;
   	}
   	public function getUsersJourney($id_viaje){
   		$sql = new Sql($this->dbAdapter);
   		$select = $sql->select();
   		$select
   		->from(array('u_s' => 'users_shipping'))
   		->columns(array('id','type_user'))
   		->where(array('u_s.id_shipping ='.$id_viaje))
   		->join(array('u' => 'iof_users'),'u_s.id_user = u.user_id',array('name','surname','lastname'),'left');
   		$selectString = $sql->getSqlStringForSqlObject($select);
   		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
   		$result       = $execute->toArray();
   		return $result;
   	}
   	public function getGasolineCoust()
   	{
   		$sql = new Sql($this->dbAdapter);
   		$select = $sql->select();
   		$select
   		->from(array('p' => 'preferences'))
   		->columns(array('name','value'))
   		->where(array('name = "GASOLINE_DISEL"'));
   		$selectString = $sql->getSqlStringForSqlObject($select);
   		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
   		$result       = $execute->toArray();
   		return $result;
   	}
}