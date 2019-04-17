<?php 
namespace Usermanual\Model\Impl;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Db\Adapter\Adapter;


use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;
// include_once APPLICATION_PATH .'/daos/ContentsDao.php';---¬
use Usermanual\Model\ContentsModel;
// include_once APPLICATION_PATH . '/entities/ContentsEntity.php';---¬
use Usermanual\Entities\ContentsEntity;

class ContentsModelImpl extends TableGateway implements ContentsModel{

	private $dbAdapter;

	//Consulta un contenido a editar	
	public function __construct()
	{
		$this->dbAdapter	= \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter();
		$this->table		= 'contents';
		$this->featureSet	= new Feature\FeatureSet();
		$this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
		$this->initialize();
	}
	
	public function getContentById($id){
			$sql = new Sql($this->dbAdapter);
			
			$select = $sql->select()
				->from(array("c" => $this->table))
				->where(array("c.id_topic" => $id) );
			
			$selectString	= $sql->getSqlStringForSqlObject($select);
			$execute		= $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
			$result			= $execute->toArray();
// 			print_r($result);
// 			exit();
			return $result;
	}
	
	public function getIdContents(){
		$sql = new Sql($this->dbAdapter);
		
		$select = $sql->select()
				->columns(array('id_topic'))
				->from($this->table);
		
		$selectString	= $sql->getSqlStringForSqlObject($select);
		$execute		= $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result			= $execute->toArray();
// 		print_r($result);
// 		exit();
		return $result;
	}
	
	//Agrega un nuevo contenido
	public function addContent(ContentsEntity $contentEntity){
		
		$db = new Sql($this->dbAdapter);
		
		$data=array('content'=>$contentEntity->getContent(),
		'id_topic'=>$contentEntity->getIdTopic());

		try{
			$this->insert($data);
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
	public function editContent(ContentsEntity $contentEntity){
		
		$db = new Sql($this->dbAdapter);
		$data=array('id_content'=>$contentEntity->getIdContent(),
		'content'=>$contentEntity->getContent(),
		'id_topic'=>$contentEntity->getIdTopic());
// 		$db->beginTransaction();	
		
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
		

}
?>