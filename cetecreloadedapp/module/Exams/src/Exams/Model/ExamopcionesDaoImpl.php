<?php 

namespace Exams\Model;
// include_once APPLICATION_PATH . '/entities/TopicsEntity.php';
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Db\Adapter\Adapter;

use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;


class ExamopcionesDaoImpl extends TableGateway
{
	protected $_name = "opciones";
	protected $_primary = "id_opcion";
	
	private $dbAdapter;
	
	public function __construct()
	{
		$this->dbAdapter	= \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter();
		$this->table		= 'opciones';
		$this->featureSet	= new Feature\FeatureSet();
		$this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
		$this->initialize();
	}
	
	public function addOpcion($data)
	{
	
		$connection = null;
		
		try {
			$connection = $this->dbAdapter->getDriver()->getConnection();
			$connection->beginTransaction();
			$question = $this->insert($data);
			$data['id_opcion'] = $this->getLastInsertValue();
			$connection->commit();
			return $data;
		}
		catch (\Exception $e) {
			if ($connection instanceof \Zend\Db\Adapter\Driver\ConnectionInterface) {
				$connection->rollback();
				return null;
			}
		}
		//echo "<pre>"; print_r($data); exit;
	}
	
	public function getOpcionOfQuestion($id_question)
	{
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select
		->from(array('o' => $this->table))
		->join(array('q' => 'questions'),'o.id_question = q.id_question',array('id_question','question_name','type_question'),'left')
		->where(array('o.id_question = '.$id_question));
		
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		
		return $result;
				
			
				
	}
	
	public function getOpcionById($id_opcion)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
	
		$select = $db
		->select()
		->from(array("o" =>  $this->_name))
		->joinLeft(array('q' => 'questions'),
							'o.id_question= q.id_question',
							array('q.id_question','q.question_name','q.type_question'))
		->where("id_opcion = " . $id_opcion);
	
		$row = $db->fetchAll($select);
	
		return $row;
	}
	
	public function editOpcion($formData)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
	
		$data = $formData;
	
		$db->beginTransaction();
	
		try {
			$db->update($this->_name, $data, "id_opcion = " . $data['id_opcion']);
	
			$db->commit();
			return $data;
		}catch (Exception $e)
		{
			$db->rollBack();
			return false;
		}
		//$this->update($data, "poll_id = " . $data['poll_id']);
		//echo "<pre>"; print_r($data); exit;
	
	}
	
	public function editOpcionEx($data)
	{
		$connection = null;
		
	  try {
			$connection = $this->dbAdapter->getDriver()->getConnection();
			$connection->beginTransaction();
			$question = $this->update($data, array("id_opcion" => $data['id_opcion']));
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

	public function deleteOpcion($id_opcion){
		$connection = null;
		try{
			$connection = $this->dbAdapter->getDriver()->getConnection();
			$connection->beginTransaction();
			$opcion = $this->delete(array("id_opcion" => $id_opcion));
			$connection->commit();
			return $id_opcion;
		}catch(\Exception $e){
			if($connection instanceof \Zend\Db\Adapter\Driver\ConnectionInterface){
				$connection->rollback();
				return null;
			}
		}
	
	}
	
	
	
	
}
?>