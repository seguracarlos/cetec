<?php 
namespace Classes\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Db\Adapter\Adapter;


use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;

class ContentsModel extends TableGateway{

	private $dbAdapter;
	
	public function __construct()
	{
		$this->dbAdapter	= \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter();
		$this->table		= 'contents';
		$this->featureSet	= new Feature\FeatureSet();
		$this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
		$this->initialize();
	}
	
// 	public function getContentById($id){
// 			$sql = new Sql($this->dbAdapter);
			
// 			$select = $sql->select()
// 				->from(array("c" => $this->table))
// 				->where(array("c.id_topic" => $id) );
			
// 			$selectString	= $sql->getSqlStringForSqlObject($select);
// 			$execute		= $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
// 			$result			= $execute->toArray();
// // 			print_r($result);
// // 			exit();
// 			return $result;
// 	}
	
	public function getContentById($id){
		$sql = new Sql($this->dbAdapter);
			
		$select = $sql->select()
		->from(array("c" => $this->table))
		->where(array("c.id_content" => $id) );
			
		$selectString	= $sql->getSqlStringForSqlObject($select);
		$execute		= $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result			= $execute->toArray();
		// 			print_r($result);
		// 			exit();
		return $result;
	}
	
	public function getContentByIdTopic($idParentContent){
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from('contents')
		->where(array('id_subtopic' => $idParentContent))
		->order('display_order');
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
	}
	
	public function getOnlySlidesTopic($idParentContent){
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->columns(array('id_content','id_topic','type','display_order'))
		->from('contents')
		->where(array('id_subtopic' => $idParentContent))
		->order('display_order');
	
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;
	}
	
	public function getIdParent($parent){
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select
		->columns(array('id_topic'))
		->from(array('c'   => $this->table))
		->where(array('c.id_subtopic = '.$parent))
		->join(array('t' => 'topics'), 't.id_topic = c.id_topic',array('topic_name'));
		
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result[0];
	}
	
	public function getTopicId($idSubtopic){
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->columns(array('parent'))
		->from('topics')
		->where(array('id_topic' => $idSubtopic));

		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result[0]['parent'];
	}
	//Agrega un nuevo contenido
	public function addContent($data){
		
		$db = new Sql($this->dbAdapter);
		

		try{
			$this->insert($data);
			$data['id_content'] = $this->getLastInsertValue();
// 			$db->commit();
// 			$db->closeConnection();
			return $data;
		}catch (Exception $e){
			$db->rollback();
// 			$db->closeConnection();
			return null;
		}
	}
	//edita un contenido
	public function editContent($data){
		
		$db = new Sql($this->dbAdapter);
	
		try{
			$this->update($data,"id_content = ".$data['id_content']);
// 			$db->commit();
// 			$db->closeConnection();
			return true;
		}catch (Exception $e){
			$db->rollback();
// 			$db->closeConnection();
			return null;
		}
	}

	public function deleteContent($formData){
	
		$db = new Sql($this->dbAdapter);
	
		try{
			$this->delete(array('id_content' => $formData['idContent']));
			// 			$db->commit();
			// 			$db->closeConnection();
			return true;
		}catch (Exception $e){
			$db->rollback();
			// 			$db->closeConnection();
			return null;
		}
	}

}
?>