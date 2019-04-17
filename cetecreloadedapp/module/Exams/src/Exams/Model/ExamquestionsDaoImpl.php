<?php 

namespace Exams\Model;
// include_once APPLICATION_PATH . '/entities/TopicsEntity.php';
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;


class ExamquestionsDaoImpl extends TableGateway
{
	protected $_name = "questions";
	protected $_primary = "id_question";
	
	private $dbAdapter;
	
	public function __construct()
	{
		$this->dbAdapter	= \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter();
		$this->table		= 'questions';
		$this->featureSet	= new Feature\FeatureSet();
		$this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
		$this->initialize();
	}
	
	public function getQuestionById($id_exam)
	{
	
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from('questions')
		->where(array('id_topic' => $id_exam));
		
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
	}
	
	public function addQuestion($data)
	{	
		$connection = null;
		
		try {
			$connection = $this->dbAdapter->getDriver()->getConnection();
			$connection->beginTransaction();
			$question = $this->insert($data);
			$data['id_question'] = $this->getLastInsertValue();
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
	
	
	public function getQuesById($id_question)
	{	
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from('questions')
		->where(array('id_question' => $id_question));
		
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
		
	}
	
	public function getQuestionByIdQuestion($id_question)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from('questions')
		->where(array('id_question' => $id_question));
		
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result[0];
	}
	
	public function getQuestionAndOptions($id_question){
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from($this->table)
				->join('opciones', 'questions.id_question = opciones.id_question')
				->where(array('questions.id_question='.$id_question));
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
	}
	
public function editQuestion($data)
	{
		$connection = null;
		try {
			$connection = $this->dbAdapter->getDriver()->getConnection();
			$connection->beginTransaction();
			$question = $this->update($data, array("id_question" => $data['id_question']));
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
	
	public function deleteQuestion($data){
	
		$connection = null;
		try{
			$connection = $this->dbAdapter->getDriver()->getConnection();
			$connection->beginTransaction();
			$question = $this->delete(array("id_question" => $data['id_question']));
			$connection->commit();
			return $data;
		}catch(\Exception $e){
			if($connection instanceof \Zend\Db\Adapter\Driver\ConnectionInterface){
				$connection->rollback();
				return null;
			}
		}
	}
	
	public function checkAnswer($id_question,$id_resp,$questionType){
		$score = 0;
		if($questionType == 2){
		
			$sql = new Sql($this->dbAdapter);
			$select = $sql->select();
			$select->from('questions')
			->columns(array('value'))
			->where(array('id_question' => $id_question,'id_resp' => $id_resp));
			$selectString = $sql->getSqlStringForSqlObject($select);
			$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
			$result       = $execute->toArray();
			if(!$result){
				$score=0;
			}else{
				$score=$result[0]['value'];
			}
	
	
		}
		if($questionType == 3||$questionType == 4){
			
			$sql = new Sql($this->dbAdapter);
			$select = $sql->select();
			$select->from('questions')
			->where(array('id_question' => $id_question));
			$selectString = $sql->getSqlStringForSqlObject($select);
			$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
			$result       = $execute->toArray();	
			$correct = $result[0]['id_resp'];
				
			if($id_resp == $correct){
				$score = $result[0]['value'];
			}
			
		}
		return $score;
	}
	
//recuperar opciones con respuiestas correctas para calificar el examen
	public function getTotalPoints($id_exam){
// 		$db = Zend_Db_Table::getDefaultAdapter();
// 		$select=$db->select()
// 		->from($this->_name,new Zend_Db_Expr('SUM(value)'))
// 		->where("id_topic=".$id_exam);
// 		$row = $db->fetchAll($select);
// 		return $row[0]['SUM(value)'];		
		$consulta=$this->dbAdapter->query("SELECT SUM(value) as total  FROM questions where id_topic=$id_exam",Adapter::QUERY_MODE_EXECUTE);
		$datos=$consulta->toArray();
		return $datos;
	}	
		
}
?>