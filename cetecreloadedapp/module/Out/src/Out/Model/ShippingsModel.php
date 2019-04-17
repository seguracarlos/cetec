<?php
namespace Out\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;

class ShippingsModel extends TableGateway
{
	private $dbAdapter;
	
	public function __construct()
   	{
   		$this->dbAdapter = \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter();
    	$this->table = 'shippings';
       	$this->featureSet = new Feature\FeatureSet();
     	$this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
    	$this->initialize();
   	}
   	
   	public function fetchAllShippings()
   	{
   		$sql = new Sql($this->dbAdapter);
   		$select = $sql->select();
   		$select
   		->from(array('s'   => $this->table))
   		->where(array('s.status = 1'))
   		->where(array('s.type_destination = 2'))
   		->join(array('c' => 'company'), 's.company_ID = c.id_company',array('name_company','id_company'),'LEFT')
   		->join(array('d' => 'destinations'), 's.id_destination = d.id_destination',array('name_destination','type_destination','description_destination'),'LEFT')
   		->join(array('i' => 'inventories'), 's.id_truck = i.id_inventories',array('id_product','description'),'LEFT')
   		->join(array('sin' => 'sinister'), 'sin.id_viaje = s.id_shipping',array('id_sinister'),'LEFT');
   		$selectString = $sql->getSqlStringForSqlObject($select);
   		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
   		$result       = $execute->toArray();
   		return $result;
   	}
   	public function fetchAllShippingsList()
   	{
   		$sql = new Sql($this->dbAdapter);
   		$select = $sql->select();
   		$select
   		->from(array('s'   => $this->table))
   		->where(array('s.status = 1'))
   		->join(array('c' => 'company'), 's.company_ID = c.id_company',array('name_company','id_company'),'LEFT')
   		->join(array('d' => 'destinations'), 's.id_destination = d.id_destination',array('name_destination','type_destination','description_destination'),'LEFT')
   		->join(array('i' => 'inventories'), 's.id_truck = i.id_inventories',array('id_product','description'),'LEFT')
   		->join(array('sin' => 'sinister'), 'sin.id_viaje = s.id_shipping',array('id_sinister'),'LEFT');
   		$selectString = $sql->getSqlStringForSqlObject($select);
   		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
   		$result       = $execute->toArray();
   		return $result;
   	}
   	public function getUsersShipping($id_shipping)
   	{
   		$sql = new Sql($this->dbAdapter);
   		$select = $sql->select();
   		$select
   		->from(array('u_s'   => 'users_shipping'))
   		->where(array('u_s.id_shipping ='.$id_shipping))
   		->join(array('i_u' => 'iof_users'), 'i_u.user_id = u_s.id_user', array('name_iof_user' => 'name','surname'),'LEFT');
   		$selectString = $sql->getSqlStringForSqlObject($select);
   		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
   		$result       = $execute->toArray();
   		return $result;
   	}
   	public function getShippingById($id)
   	{
   		$sql = new Sql($this->dbAdapter);
   		$select = $sql->select();
   		$select
   		->from(array('s' => $this->table))
   		->join(array('c' => 'company'), 's.company_ID = c.id_company', array('name_company'), 'LEFT')
   		->join(array('d' => 'destinations'), 's.id_destination = d.id_destination',array('name_destination','type_destination','description_destination'),'LEFT')
   		->where(array('s.id_shipping' => $id));
   		$selectString = $sql->getSqlStringForSqlObject($select);
   		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
   		$result       = $execute->toArray();
   		return $result;
   	}
   	public function consultEndKm($id_viaje){
   		$sql = new Sql($this->dbAdapter);
   		$select = $sql->select();
   		$select
   		->from(array('s' => $this->table))
   		->columns(array('end_mileage'))
   		->where(array('id_shipping = '.$id_viaje));
   		$selectString = $sql->getSqlStringForSqlObject($select);
   		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
   		$result       = $execute->toArray();
   		return $result;
   	}
   	public function addEndKm($data){
   		if(isset($data['id_shipping'])){
   			$end_km = $this->update($data,array('id_shipping' => $data['id_shipping']));
   		}
   		$sql = new Sql($this->dbAdapter);
   		$select = $sql->select();
   		$select
   		->from(array('s' => $this->table))
   		->columns(array('end_mileage'))
   		->where(array('id_shipping = '.$data['id_shipping']));
   		$selectString = $sql->getSqlStringForSqlObject($select);
   		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
   		$result       = $execute->toArray();
   		return $result;
   	}
   	public function consultStartingGasoline($id_viaje){
   		$sql = new Sql($this->dbAdapter);
   		$select = $sql->select();
   		$select
   		->from(array('s' => $this->table))
   		->columns(array('start_gasoline'))
   		->where(array('id_shipping = '.$id_viaje));
   		$selectString = $sql->getSqlStringForSqlObject($select);
   		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
   		$result       = $execute->toArray();
   		return $result;
   	}
   	public function  addStartingGasoline($data){
   		if(isset($data['id_shipping'])){
   			$start_gasoline = $this->update($data,array('id_shipping' => $data['id_shipping']));
   		}
   		$sql = new Sql($this->dbAdapter);
   		$select = $sql->select();
   		$select
   		->from(array('s' => $this->table))
   		->columns(array('start_gasoline'))
   		->where(array('id_shipping = '.$data['id_shipping']));
   		$selectString = $sql->getSqlStringForSqlObject($select);
   		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
   		$result       = $execute->toArray();
   		return $result;
   	}
   	public function consultEndingGasoline($id_viaje){
   		$sql = new Sql($this->dbAdapter);
   		$select = $sql->select();
   		$select
   		->from(array('s' => $this->table))
   		->columns(array('end_gasoline'))
   		->where(array('id_shipping = '.$id_viaje));
   		$selectString = $sql->getSqlStringForSqlObject($select);
   		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
   		$result       = $execute->toArray();
   		return $result;
   	}
   	public function  addEndingGasoline($data){
   		if(isset($data['id_shipping'])){
   			$start_gasoline = $this->update($data,array('id_shipping' => $data['id_shipping']));
   		}
   		$sql = new Sql($this->dbAdapter);
   		$select = $sql->select();
   		$select
   		->from(array('s' => $this->table))
   		->columns(array('end_gasoline'))
   		->where(array('id_shipping = '.$data['id_shipping']));
   		$selectString = $sql->getSqlStringForSqlObject($select);
   		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
   		$result       = $execute->toArray();
   		return $result;
   	}
   	public function consultEndingGasolineEndJourney($id_viaje){
   		$sql = new Sql($this->dbAdapter);
   		$select = $sql->select();
   		$select
   		->from(array('s' => $this->table))
   		->columns(array('end_gasoline_end_journey'))
   		->where(array('id_shipping = '.$id_viaje));
   		$selectString = $sql->getSqlStringForSqlObject($select);
   		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
   		$result       = $execute->toArray();
   		return $result;
   	}
   	public function  addEndingGasolineEndJourney($data){
   		if(isset($data['id_shipping'])){
   			$end_gasoline_end_journey = $this->update($data,array('id_shipping' => $data['id_shipping']));
   		}
   		$sql = new Sql($this->dbAdapter);
   		$select = $sql->select();
   		$select
   		->from(array('s' => $this->table))
   		->columns(array('end_gasoline_end_journey'))
   		->where(array('id_shipping = '.$data['id_shipping']));
   		$selectString = $sql->getSqlStringForSqlObject($select);
   		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
   		$result       = $execute->toArray();
   		return $result;
   	}
   	public function addSatatusExpense($dataShipping){
   		if(isset($dataShipping['id_shipping'])){
   			$statusExpense = $this->update($dataShipping,array('id_shipping' => $dataShipping['id_shipping']));
   		}
   		$sql = new Sql($this->dbAdapter);
   		$select = $sql->select();
   		$select
   		->from(array('s' => $this->table))
   		->columns(array('status_saveExpenses','client_folio', 'internal_folio'))
   		->where(array('id_shipping = '.$dataShipping['id_shipping']));
   		$selectString = $sql->getSqlStringForSqlObject($select);
   		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
   		$result       = $execute->toArray();
   		return $result;
   	}
   	public function addTypeGasoline($data){

   		if(isset($data['id_shipping'])){
   			$start_gasoline = $this->update($data,array('id_shipping' => $data['id_shipping']));
   		}
   		$sql = new Sql($this->dbAdapter);
   		$select = $sql->select();
   		$select
   		->from(array('s' => $this->table))
   		->columns(array('type_gasoline'))
   		->join(array('p' => 'preferences'), 's.type_gasoline = p.id', array('name'))
   		->where(array('id_shipping = '.$data['id_shipping']));
   		$selectString = $sql->getSqlStringForSqlObject($select);
   		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
   		$result       = $execute->toArray();
   		return $result;
   	}
   	public function getDatasById($id){
   		$sql = new Sql($this->dbAdapter);
   		$select = $sql->select();
   		$select
   		->from(array('s' => $this->table))
   		->columns(array('id_shipping','starting_mileage','end_mileage','end_gasoline_end_journey','client_folio','return_viatic_rest'))
   		->join(array('p' => 'preferences'), 's.type_gasoline = p.id', array('name','value'),'LEFT')
   		->join(array('d' => 'destinations'), 's.id_destination = d.id_destination', array('type_destination'),'LEFT')
   		->where(array('id_shipping = '.$id));
   		$selectString = $sql->getSqlStringForSqlObject($select);
   		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
   		$result       = $execute->toArray();
   		return $result;
   	}
   	public function updateShippingDates($data){
   		if(isset($data['id_shipping'])){
   			$start_gasoline = $this->update($data,array('id_shipping' => $data['id_shipping']));
   		}
   		$sql = new Sql($this->dbAdapter);
   		$select = $sql->select();
   		$select
   		->from(array('s' => $this->table))
   		->columns(array('id_shipping'))
   		->where(array('id_shipping = '.$data['id_shipping']));
   		$selectString = $sql->getSqlStringForSqlObject($select);
   		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
   		$result       = $execute->toArray();
   		return $result;
   	}
}