<?php
namespace In\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;

class DestinationsModel extends TableGateway
{
	private $dbAdapter;
	
	public function __construct()
   	{
   		$this->dbAdapter  = \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter();
    	$this->table      = 'destinations';
       	$this->featureSet = new Feature\FeatureSet();
     	$this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
    	$this->initialize();
   	}
	
   	/* 
   	 * Todos los registros disponibles 
   	 */
	public function fetchAll()
	{
		$sql    = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select
			//->columns(array('id_company', 'name_company', 'brand', 'rfc', 'website', 'business', 'interestingin', 'record_date'))
			->from(array('d' => $this->table))
			->order("name_destination ASC");
			//->join(array('c' => 'company'), 'p.company_ID = c.id_company', array('name_company'), 'LEFT')
			//->where(array('p.type' => $type));
	
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
	}
	
	/* 
   	 * Obtener un registro por id
   	 */
	public function getRowById($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select
		->columns(array('id_destination', 'name_destination', 'type_destination', 'direct_route', 'description_destination', 'operator_salary', 'assistant_salary', 'dry_van', 'tonel_van', 'truck', 'refrigerated_truck'))
		->from(array('d' => $this->table))
		->where(array('d.id_destination' => $id));
	
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
	}
	
	/* 
   	 * Agregar un registro nuevo
   	 */
	public function addRow($data)
	{	
		$connection = null;
		
		try {
			$connection = $this->dbAdapter->getDriver()->getConnection();
			$connection->beginTransaction();
		
			/* Ejecutar una o más consultas aquí */
			$row     = $this->insert($data);
			$saveRow = $this->getLastInsertValue();
			$connection->commit();
		}
		catch (\Exception $e) {
			if ($connection instanceof \Zend\Db\Adapter\Driver\ConnectionInterface) {
				$connection->rollback();
			}
			/* Tratamiento de errores */
			$saveRow = $e->getCode();
		}
		
		return $saveRow;
	}
	
	/*
	 * Modificar un registro existente
	 */
	public function editRow($data)
	{	
		$connection = null;
	
		try {
			$connection = $this->dbAdapter->getDriver()->getConnection();
			$connection->beginTransaction();
	
			/* Ejecutar una o más consultas aquí */
			$row     = $this->update($data, array("id_destination" => (int) $data['id_destination']));
			$editRow = $data['id_destination'];
			$connection->commit();
		}
		catch (\Exception $e) {
			if ($connection instanceof \Zend\Db\Adapter\Driver\ConnectionInterface) {
				$connection->rollback();
			}
			/* Tratamiento de errores */
			$editRow = $e->getCode();
		}
		
		return $editRow;
	}
	
	/*
	 * Eliminar un registro existente
	 */
	public function deleteRow($id)
	{
		$idRow     = (int) $id;
		$deleteRow = $this->delete(array('id_destination' => (int) $idRow));
		
		return $deleteRow;
	}

}