<?php
namespace Company\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Feed\Reader\Collection;

class PreferencesModel extends TableGateway
{
	private $dbAdapter;

	public function __construct()
	{
		$this->dbAdapter = \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter();
		$this->table = 'preferences';
		$this->featureSet = new Feature\FeatureSet();
		$this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
		$this->initialize();
	}


	//Obtiene el logo de la empresa
	public function getLogo()
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select
		->from(array('p' => $this->table))
		->where(array('p.name'=> 'FOTO'));
		$selectString = $sql->getSqlStringForSqlObject($select);
		$execute      = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		$result       = $execute->toArray();
		return $result;	
	}
	//Atualizar logo de la empresa
	public function updateImgLogo($img){
		$data = array('value' => $img);
		$logo = $this->update($data, array("name" => 'FOTO'));
		return $logo;
	}
}