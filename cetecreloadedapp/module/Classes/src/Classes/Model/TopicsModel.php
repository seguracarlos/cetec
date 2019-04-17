<?php
namespace Classes\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Db\Adapter\Adapter;

use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;

use Usermanual\Entities\TopicsEntity;


class TopicsModel extends TableGateway {
	
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
	
	public function getAllTopics($type,$user){
		$sql = new Sql($this->dbAdapter);
		
		$select = 'SELECT topics.id_topic, parent, topic_name, 
					type, id_subtopic, content FROM topics RIGHT JOIN contents 
					on topics.id_topic = contents.id_topic WHERE artefact_type ='.
					$type.' AND id_user = '.$user.' OR id_user IS NULL AND artefact_type ='.$type;
		
		$execute 		= $this->dbAdapter->query($select, Adapter::QUERY_MODE_EXECUTE);
		$result			= $execute->toArray();
		return $result;
	}
	
	public function getTopicById($id, $id2, $id3){
		$sql = new Sql($this->adapter);
		
// 		$select = 'SELECT topics.id_topic, content FROM topics INNER JOIN contents 
// 					on topics.id_topic = contents.id_topic WHERE topics.id_topic BETWEEN '.
// 					$id .' AND '. $id2;
		$select = 'SELECT topics.id_topic, content FROM topics INNER JOIN contents
					on topics.id_topic = contents.id_topic WHERE topics.id_topic LIKE '.
							$id .' OR topics.id_topic LIKE '. $id2 .' OR topics.id_topic LIKE '. $id3;
		
		$execute 		= $this->dbAdapter->query($select, Adapter::QUERY_MODE_EXECUTE);
		$result			= $execute->toArray();
		
// 		print_r($result);
// 		exit();
		return $result;
	}
	
	//consulta de el tree para exportar
	public function getExportTree($id){
		$sql = new Sql($this->dbAdapter);
		
		$select=$sql->select()
			->columns(array("id_topic ", "parent", "topic_name ", "type"))
			->from(	array("p" 		=> $this->table))
			->where(array('p.parent'=>$id));
		
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
	}
	//Se hace la consulta de los artefactos dependiendo el tipo de artefacto que sean
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

	public function getAllUserParents($type){
		$sql = new Sql($this->dbAdapter);
		
		$select=$sql->select()
			->from(array("p"	=>		$this->table))
			->where("p.parent=?",$type);
		
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
	}
	
	//Se agrega un parent
	public function addParent(TopicsEntity $topicEntity){
		$db = new Sql($this->dbAdapter);
		
		$data=array('id_user'=>$topicEntity->getIdUser(),
		'topic_name'=>$topicEntity->getTopicName(),
		'parent'=>$topicEntity->getParent(),
		'type'=>$topicEntity->getType(),
		'project_name'=>$topicEntity->getProjectName(),
		'description'=>$topicEntity->getDescription(),
		'artefact_type'=>$topicEntity->getArtefact_type());
			
		$this->insert($data);
		return $data;
	}
	
	//Se consulta un parent para editarlo
	public function getCourses($parent){
		$db = new Sql($this->dbAdapter);
		$select = $db->select()
			->from(array("p"				=>	$this->table))
			->where(array("p.parent"		=>	$parent,
						  "p.type"	=>	"folder"));
		
		$selectString = $db->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
	}
	
	//Se modifica un parent
	public function editParent(TopicsEntity $topicEntity){
		$db = new Sql($this->dbAdapter);
		$data=array(
				'topic_name'	=>$topicEntity->getTopicName(),
				'project_name'	=>$topicEntity->getProjectName(),
				'description'	=>$topicEntity->getDescription());
		
		$id_topic = array(
				'id_topic'		=>$topicEntity->getIdTopic());
		
		$this->update($data,$id_topic);
		return null;
	}
	
	//Se elimina un parent
	public function deleteParent($deleteParam){
		$db = new Sql($this->dbAdapter);
		return $this->delete($deleteParam);
	}
	
	
	//Se valida que el padre de los temas exista
	public function ValidateParent($type,$id,$user){
		$sql= new Sql($this->dbAdapter);
		
		$select=$sql->select()
		->from(array("p"=> $this->table))
			->where(array(
					"p.artefact_type"	=> $type,
					"p.id_topic"		=> $id,
					"p.id_user"			=> $user
			));
		
		$selectString	= $sql->getSqlStringForSqlObject($select);
		$execute		= $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result			= $execute->toArray();
		
		return $result;
	}
	
	//Recupera temas y subtemas
	
	public function getTrim($type,$id,$user){
		$sql = new Sql($this->dbAdapter);
		$select=$sql->select()
		->from(array("t"=> $this->table))
		->where(array(
				"t.artefact_type"	=> $type,
				"t.parent"			=> $id,
				"t.id_user"			=> $user));
		
		$selectString	= $sql->getSqlStringForSqlObject($select);
		$execute		= $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result			= $execute->toArray();
		
		return $result;
	}
	public function getTopics($type,$trim,$user){
		$sql = new Sql($this->dbAdapter);
		$select=$sql->select()
			->from(array("t"=> $this->table))
			->where(array(
							"t.artefact_type"	=> $type,
							"t.parent"			=> $trim,
							"t.id_user"			=> $user));
		
		$selectString	= $sql->getSqlStringForSqlObject($select);
		$execute		= $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result			= $execute->toArray();
		
		return $result;
	}
	
	
	public function getTopicsParents($type,$id,$user){
		$sql = new Sql($this->dbAdapter);
	
		$select=$sql->select()
		->from($this->table)
		->where(array(
				'artefact_type'	=> $type,
				'type'			=> 'folder',
				'id_user'		=> $user,
				'parent'		=> $id,
		));
	
		$selectString	= $sql->getSqlStringForSqlObject($select);
		$execute		= $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result			= $execute->toArray();

		return $result;
	}
	
	//Agregar un tema o subtema
	public function addTopic($data){
		$db = new Sql($this->dbAdapter);

		try{
			$this->insert($data);
			$data['id_topic'] = $this->getLastInsertValue();
			// 			$db->commit();
			// 			$db->closeConnection();
			return $data;
		}catch (Exception $e){
			$db->rollback();
			// 			$db->closeConnection();
			return null;
		}
	}
	
	//Agregar un ecenario de prueba con prerequisitos.
	public function addTopicTest(TopicsEntity $topicEntity){
		$db = new Sql($this->dbAdapter);
		$data=array('id_user'=>$topicEntity->getIdUser(),
		'topic_name'=>$topicEntity->getTopicName(),
		'parent'=>$topicEntity->getParent(),
		'type'=>$topicEntity->getType(),
		'artefact_type'=>$topicEntity->getArtefact_type(),
		'prerequisites'=>$topicEntity->getPrerequisites());
		$db->beginTransaction();
		try{
			$this->insert($data);
			$data['id_topic']=$db->lastInsertId();
			$db->commit();
			$db->closeConnection();
			return $data;
		}catch (Exception $e){
			$db->rollback();
			$db->closeConnection();
			return null;
		}
	}
	
	//Actualiza un tema o subtema
	public function editTopic($data){
		$db = new Sql($this->dbAdapter);
// 		$data=array('id_user'=>$topicEntity->getIdUser(),
// 		'id_topic'=>$topicEntity->getIdTopic(),
// 		'topic_name'=>$topicEntity->getTopicName());
// 		$db->beginTransaction();
		try{
			$this->update($data,"id_topic = ".$data['id_topic']);
			return $data;
		}catch (Exception $e){
			$db->rollback();
			return null;
		}
	}
	
	public function deleteTopic($idTopic)
	{
		$db = new Sql($this->dbAdapter);
		try{
			$this->delete("id_topic = ".$idTopic);
			return $idTopic;
		}catch (Exception $e){
			$db->rollback();
			return null;
		}
	}
	
	//Actualizar un caso de prueba
	public function editTopicTest(TopicsEntity $topicEntity){
		$db = new Sql($this->dbAdapter);
		$data=array('id_user'=>$topicEntity->getIdUser(),
		'id_topic'=>$topicEntity->getIdTopic(),
		'topic_name'=>$topicEntity->getTopicName(),
		'prerequisites'=>$topicEntity->getPrerequisites());
		$db->beginTransaction();
		try{
			$this->update($data,"id_topic = ".$data['id_topic'],"id_user = ".$data['id_user']);
			$db->commit();
			$db->closeConnection();
			return $data;
		}catch (Exception $e){
			$db->rollback();
			$db->closeConnection();
			return null;
		}
	}

	public function getAllUserTreeByType($type){
		$db = new Sql($this->dbAdapter);
		$select=$db->select()
		->colums(array("id_topic ", "parent", "topic_name ", "type","id_user"))
		->from(array("p" => $this->table))
		->where("p.artefact_type=?",$type);
		$row = $db->fetchAll($select);
		return $row;
	}

}