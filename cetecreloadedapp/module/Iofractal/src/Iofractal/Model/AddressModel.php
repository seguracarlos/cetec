<?php
namespace Iofractal\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;

class AddressModel extends TableGateway
{
	private $dbAdapter;
	
	public function __construct()
   	{
   		$this->dbAdapter  = \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter();
    	$this->table      = 'addresses';
       	$this->featureSet = new Feature\FeatureSet();
     	$this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
    	$this->initialize();
   	}
	
   	/* Todas las direccones*/
	public function fetchAll()
	{
		$sql    = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select
			->from(array('s' => $this->table))
			->order('s.state ASC');
	
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
	}
	
	// Agregar direccion
	public function addAddress($data)
	{
		$connection = null;
	
		try {
			$connection = $this->dbAdapter->getDriver()->getConnection();
			$connection->beginTransaction();
	
			/* Ejecutar una o m�s consultas aqu� */
			$address     = $this->insert($data);
			$saveAddress = $this->getLastInsertValue();
			$connection->commit();
		}
		catch (\Exception $e) {
			if ($connection instanceof \Zend\Db\Adapter\Driver\ConnectionInterface) {
				$connection->rollback();
			}
			/* Tratamiento de errores */
			$saveAddress = $e->getCode();
		}
	
		return $saveAddress;
	}
	
	// Modificar direcciones
	public function editAddress($data)
	{
	$connection = null;
	
	try {
		$connection = $this->dbAdapter->getDriver()->getConnection();
		$connection->beginTransaction();
	
		/* Ejecutar una o m�s consultas aqu� */
		$address     = $this->update($data, array("company_id" => $data['company_id']));
		$editAddress = $data['company_id'];
		$connection->commit();
	}
	catch (\Exception $e) {
		if ($connection instanceof \Zend\Db\Adapter\Driver\ConnectionInterface) {
			$connection->rollback();
		}
		/* Tratamiento de errores */
		$editAddress = $e->getCode();
	}
	//echo "<pre>"; print_r($editCompany); exit;
	return $editAddress;
	}
}