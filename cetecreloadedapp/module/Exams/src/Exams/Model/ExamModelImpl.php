<?php 
namespace Exams\Model;
// include_once APPLICATION_PATH . '/entities/TopicsEntity.php';
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Db\Adapter\Adapter;

use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;

class ExamModelImpl extends TableGateway{
	
	private $dbAdapter;
	
	public function __construct()
	{
		$this->dbAdapter	= \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter();
		$this->table		= 'topics';
		$this->featureSet	= new Feature\FeatureSet();
		$this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
		$this->initialize();
	}
	
	//Se hace la consulta del contenido del Jstree para cada tipo de artefacto
	public function getAllTreeByType($type,$user){
		$sql = new Sql($this->dbAdapter);
		
		$select = 'SELECT id_topic as id, parent, topic_name  as text, type, 
					id_user FROM topics WHERE artefact_type ='.
				$type.' AND id_user = '.$user.' OR id_user IS NULL AND artefact_type ='.$type;
		$execute 		= $this->dbAdapter->query($select, Adapter::QUERY_MODE_EXECUTE);
		$result			= $execute->toArray();
		
		return $result;
	}
	
	public function getExamById($idTopic){
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select
		->from('topics')
		->where(array('id_topic' => $idTopic));
		
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
	}
	
	public function getAllTrim($parent){
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select
		->from('topics')
		->where(array('parent' => $parent));
		
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
	}
	
	public function getAllParents($type,$user){
		$sql = new Sql($this->dbAdapter);
		
		$select=$sql->select()
					->from(	 array('p'			=>		$this->table))
					->where( array(
								'p.parent'	=>		$type,
						  		'p.id_user'	=>		$user));
		
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
	}
	
	public function getExamByTrim($parent){
		$sql = new Sql($this->dbAdapter);
		
		$select=$sql->select()
		->from(	 array('p'			=>		$this->table))
		->where( array(
				'p.project_name'	=>		$parent,
				'p.artefact_type'	=>		5));
		
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
	}
	
	public function getAllExams(){
		$sql = new Sql($this->dbAdapter);
	
		$select=$sql->select()
		->from(	 array('p'			=>		$this->table))
		->where( array(
				'p.artefact_type'	=>		5));
	
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
	}
	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//									de aqui para arriba esta terminado										//
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	//Agrega un examen
	public function addExam($data){
		
		$connection = null;
		
		try {
			$connection = $this->dbAdapter->getDriver()->getConnection();
			$connection->beginTransaction();
			$exam = $this->insert($data);
			$data['id_topic'] = $this->getLastInsertValue();
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
	
	//Se consulta un parent para editarlo
	public function getParentBy($id, $type,$user){
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from('topics')
		->where(array('id_topic' => $id,
				'artefact_type'   => $type,
				'id_user'         => $user
		));
		
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
	}
	//Se modifica un parent
	public function editParent($data){
		$connection = null;
		
		try {
			$connection = $this->dbAdapter->getDriver()->getConnection();
			$connection->beginTransaction();
			$exam = $this->update($data, array("id_topic" => $data['id_topic']));
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
	
	//Se elimina un parent
	public function deleteParent($deleteParam){
		$connection = null;
		try{
			$connection = $this->dbAdapter->getDriver()->getConnection();
			$connection->beginTransaction();
			$data = $this->delete(array("id_topic" => $deleteParam));
			$connection->commit();
			return $data;
		}catch(\Exception $e){
			if($connection instanceof \Zend\Db\Adapter\Driver\ConnectionInterface){
				$connection->rollback();
				return null;
			}
		}
	}
}
?>