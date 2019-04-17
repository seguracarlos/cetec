<?php
namespace Horus\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;

class InventoryPhotoModel extends TableGateway
{
	private $dbAdapter;
	
	public function __construct()
   	{
   		$this->dbAdapter = \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter();
    	$this->table = 'inventories_photos';
       	$this->featureSet = new Feature\FeatureSet();
     	$this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
    	$this->initialize();
   	}
	
   	/*Recuperamos todos los tipos disponibles*/
	public function fetchAll()
	{
		$select = $this->select();
		$data  = $select->toArray();	
		return $data;
	}
	
	public function addPhoto($data)
	{
		$insert = $this->insert($data);
		return $insert;
	}
	
	public function editPhoto($data,$id)
	{
		$edit = $this->update($data,array("id_inventory" => $id));
		return $edit;
	}
	
	public function deletePhoto($id)
	{
		$delete_photo = $this->delete(array('id_inventory' => $id));
		return $delete_photo;
	}
}