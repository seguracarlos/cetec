<?php

namespace Exams\Model;
// include_once APPLICATION_PATH . '/entities/TopicsEntity.php';
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Db\Adapter\Adapter;

use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;


class ExamscoreDaoImpl  extends TableGateway{
	protected $_name="examscore";
	protected $_primary="id_version";
	
	private $dbAdapter;
	
	public function __construct()
	{
		$this->dbAdapter	= \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter();
		$this->table		= 'examscore';
		$this->featureSet	= new Feature\FeatureSet();
		$this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
		$this->initialize();
	}
	
	public function addNewScore($id_user,$id_exam,$type){
		$data = array(
				"id_user" => $id_user,
				"id_exam" => $id_exam,
				"exam_type"    => $type
		);
		
		
		$connection = null;
		
		try {
			$connection = $this->dbAdapter->getDriver()->getConnection();
			$connection->beginTransaction();
			$exam = $this->insert($data);
			$data['id_score'] = $this->getLastInsertValue();
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
	
	public function updateScore($id_user,$id_version,$user_score){
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from('examscore')
		->where(array('id_version' => $id_version))
		->where(array('id_user' => $id_user));
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		$newvalue = $result[0]['score']+$user_score;
		$where = array("id_version" => $id_version,"id_user" =>$id_user, "score" => $newvalue);
		$updateScore = $this->update($where, array("id_version" => $where['id_version']));
		return $result;
	}
	
	public function getScore($id_user,$id_version){
		
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from('examscore')
		->where(array('id_version' => $id_version,'id_user' => $id_user));
		
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
	}
	
	
	public function getAllScoresByUser($id_user){
		$query=$this->dbAdapter->query("SELECT * FROM examscore where id_user=$id_user",Adapter::QUERY_MODE_EXECUTE);
		$result=$query->toArray();
		return $result;
	}
	
	public function getAllScoresCurrentTrimByUser($id_user,$idExam){	
		$query=$this->dbAdapter->query("SELECT * FROM examscore where id_user=$id_user and id_exam = $idExam",Adapter::QUERY_MODE_EXECUTE);
		$result=$query->toArray();
		return $result;
	}
	
	public function getNormalScoreByUser($id_user,$idExam){
		$query=$this->dbAdapter->query("SELECT * FROM examscore where id_user=$id_user and id_exam = $idExam and exam_type=1",Adapter::QUERY_MODE_EXECUTE);
		$result=$query->toArray();
		return $result;
	}
	
	public function getExtraScoreByUser($id_user,$idExam){
		$query=$this->dbAdapter->query("SELECT * FROM examscore where id_user=$id_user and id_exam = $idExam and exam_type=2",Adapter::QUERY_MODE_EXECUTE);
		$result=$query->toArray();
		return $result;
	}
	
	public function getApprovedScore($id_user,$idexam){
		$query=$this->dbAdapter->query("SELECT * FROM examscore where id_user=$id_user and id_exam=$idexam and totalscore > 6",Adapter::QUERY_MODE_EXECUTE);
		$result=$query->toArray();
		return $result;
	}
		
	public function addFinalScore($id_user,$id_version,$finalScore){
		$data=array(
				'totalscore'=>$finalScore,
				"id_version" => $id_version,
				"id_user"    => $id_user
		);
		
		$connection = null;
		try {
			$connection = $this->dbAdapter->getDriver()->getConnection();
			$connection->beginTransaction();
			$question = $this->update($data, array("id_user" => $data['id_user'],"id_version" => $data['id_version']));
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
}
?>