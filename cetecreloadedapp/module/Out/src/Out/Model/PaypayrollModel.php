<?php
namespace Out\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;

class PaypayrollModel extends TableGateway
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
   	public function addPayroll($dataPay_payroll){
   		$payroll = $this->insert($dataPay_payroll);
   		$dataPay_payroll['id_paypayroll'] = $this->getLastInsertValue();
   		$sql = new Sql($this->dbAdapter);
   		$select = $sql->select();
   		$select
   		->from(array('p'   => $this->table))
   		->where(array('p.id_paypayroll' => $dataPay_payroll['id_paypayroll']));
   		$selectString = $sql->getSqlStringForSqlObject($select);
   		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
   		$result       = $execute->toArray();
   		return $result;
   	}
}