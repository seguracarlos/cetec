<?php
namespace Company\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;

class CompanyModel extends TableGateway
{
	private $dbAdapter;
	
	public function __construct()
   	{
   		$this->dbAdapter = \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter();
    	$this->table = 'company';
       	$this->featureSet = new Feature\FeatureSet();
     	$this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
    	$this->initialize();
   	}
	
   	
	
	//Recupera la compañia por usuario con sessión inicializada
	public function getCompanyByUser($id_company)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select
			->from(array('c' => $this->table))
			->where(array('c.id_company' => $id_company));
	
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
	}
	//Atualizar logo de la empresa
	public function updateImgLogo($img,$company){
		$data = array('logo' => $img);
		$logo = $this->update($data, array("id_company" => $company));
		return $logo;
	}
	//Consultar logo de la empresa para el ajax
	public function getLogo($company){
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select
		->columns(array('logo'))
		->from(array('c' => $this->table))
		->where(array('c.id_company' => $company));
		
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
	}
	public function updateCompany($data){
		$connection = $this->dbAdapter->getDriver()->getConnection();
		$connection->beginTransaction();
		
		try {
			$id_company = $data['id_company'];
			if($id_company != null || $id_company != 0){
				$company = $this->update($data,array("id_company" => $id_company));
				$sql = new Sql($this->dbAdapter);
				$select = $sql->select();
				$select
				->from(array('c' => $this->table))
				->where(array('c.id_company' => $id_company));
				
				$selectString = $sql->getSqlStringForSqlObject($select);
				$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
				$result       = $execute->toArray();
				$connection->commit();
			}
		} catch (\Exception $e) {
			$result = "fail";
			$connection->rollback();
		}
		return $result;
	}
}