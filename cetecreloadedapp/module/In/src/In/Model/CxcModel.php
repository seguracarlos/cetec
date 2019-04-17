<?php
namespace In\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;

class CxcModel extends TableGateway
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
	
   	/* 
   	 * Todos los registros disponibles 
   	 */
	public function fetchAll()
	{
		$projects  = array ();
		$index     = 0;
		$idproyect = 0;
		$contador  = 1;
		
		$sql       = new Sql($this->dbAdapter);
		$select    = $sql->select();
		$select
			->columns(array('id_datePayment', 'datePayment', 'projectId', 'amount', 'statusCxc', 'total'))
			->from(array('cxc' => $this->table))
			->join(array('pro' => 'projects'), 'cxc.projectId = pro.ID', array('ID', 'project_name', 'numberofpayments'), 'LEFT')
			->join(array('com' => 'company'), 'pro.company_ID = com.id_company', array('name_company'), 'LEFT');
	
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		
		if(Count($result)){
			foreach($result as $key => $row){
				if($key == $index){
					if($row['ID'] != $idproyect){
						$contador = 1;
					}else{
						$contador++;
					}
					$index++;
				}
				
				$idproyect = $row['ID'];

				$projects[]  = array(
						'id_datePayment'   => $row['id_datePayment'],
						'id_project'       => $row['projectId'],
						'project_name'     => $row['project_name'],
						'name_company'     => $row['name_company'],
						'datePayment'      => $row['datePayment'],
						'amount'           => $row['amount'],
						'statusCxc'        => $row['statusCxc'],
						'total'            => $row['total'],
						'number'           => $contador,
						'numberofpayments' => $row['numberofpayments'],
				);
			}
		}
		
		return $projects;
	}
	
	/* 
   	 * Obtener un registro por id
   	 */
	public function getRowById($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select
		->columns(array('id_service', 'name_service'=>'name', 'clave', 'dateService', 'description', 'cost', 'percentageGain'))
		->from(array('s' => $this->table))
		->where(array('s.id_service' => $id));
		;
	
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
			$row     = $this->update($data, array("id_service" => $data['id_service']));
			$editRow = $data['id_service'];
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
		$deleteRow = $this->delete(array('id_service' => $idRow));
		
		return $deleteRow;
	}

}