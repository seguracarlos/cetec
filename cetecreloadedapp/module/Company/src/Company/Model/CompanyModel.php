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
			->where(array('c.id_company' => $id_company))
			->join(array('a' => 'addresses'),'a.company_id = c.id_company',array('*'),'left')
			->join(array('s' => 'states_of_mexico'),'s.id = a.state_id',array('state'),'left')
			->join(array('d' => 'district'),'d.id = a.district',array('name'),'left')
			->join(array('n' => 'neighborhood'),'n.id = a.neighborhood',array('colony'),'left');
	
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