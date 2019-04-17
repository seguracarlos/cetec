<?php
namespace Classes\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;

class StudentNotesModel extends TableGateway
{
	private $dbAdapter;
	
	public function __construct()
   	{
   		$this->dbAdapter = \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter();
    	$this->table = 'student_notes';
       	$this->featureSet = new Feature\FeatureSet();
     	$this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
    	$this->initialize();
   	}
	
   	/*Recuperamos todos los gastos*/
	public function fetchAll()
	{
		$select = $this->select();
		$data  = $select->toArray();	
		return $data;
	}
	
	//Recuperamos un gasto por id
	public function getNotesByTopicAndUser($id_topic,$id_user)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select
			->from(array('n' => $this->table))
			->where(array('n.id_subtopic' => $id_topic,'n.id_student'=> $id_user));
	
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
	}
	
	public function counNotesByTopic($id_topic,$id_user)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select
		->columns(array('id_note' => new \Zend\Db\Sql\Expression('COUNT(*)')))
		->from(array('n' => $this->table))
		->where(array('n.id_subtopic' => $id_topic,'n.id_student'=> $id_user));
		
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result[0]['id_note'];
	}
	
	public function addNote($data)
	{
		$connection = null;
		
		try {
			$connection = $this->dbAdapter->getDriver()->getConnection();
			$connection->beginTransaction();
			$note = $this->insert($data);
			$data['id_note'] = $this->getLastInsertValue();
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
	
	/*Editar un gasto*/
	public function updateNote($data)
	{
		try {
			$connection = $this->dbAdapter->getDriver()->getConnection();
			$connection->beginTransaction();
			$note = $this->update($data,array('id_note' => $data['id_note']));
			$data['id_note'] = $this->getLastInsertValue();
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
	
	//Eliminar un usuario
	public function deleteNote($id_Note)
	{
		$deleteNote = $this->delete(array('id_note' => $id_Note));
		return $deleteNote;
	}
	
}