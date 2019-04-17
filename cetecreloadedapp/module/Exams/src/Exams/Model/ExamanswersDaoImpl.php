<?php
namespace Exams\Model;
// include_once APPLICATION_PATH . '/entities/TopicsEntity.php';
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Db\Adapter\Adapter;

use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;

class ExamanswersDaoImpl  extends TableGateway{
	protected $_name="examanswers";
	protected $_primary="id_resp";
	
	private $dbAdapter;
	
	public function __construct()
	{
		$this->dbAdapter	= \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter();
		$this->table		= 'examanswers';
		$this->featureSet	= new Feature\FeatureSet();
		$this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
		$this->initialize();
	}
	
	public function getAnswersVersionById($idVersion){

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from('examanswers')
		->join('examscore', 'examanswers.id_version = examscore.id_version')
		->where(array('examanswers.id_version='.$idVersion));
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
	}
	
	public function addUserAnswer($data){
		$connection = null;
		
		try {
			$connection = $this->dbAdapter->getDriver()->getConnection();
			$connection->beginTransaction();
			$exam = $this->insert($data);
			$data['id_resp'] = $this->getLastInsertValue();
			$connection->commit();
			return $data;
		}
		catch (\Exception $e) {
			if ($connection instanceof \Zend\Db\Adapter\Driver\ConnectionInterface) {
				$connection->rollback();
				return null;
			}
		}
		
	}
	
	public function getVersionInfoById($idVersion){
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from('examscore')
		->where(array('id_version='.$idVersion));
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result[0];
	}
}
?>