<?php
namespace Auth\Model;

use Zend\Db\Sql\Sql;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;

class PreferencesTable extends AbstractTableGateway
{

    public $table = 'preferences';
    
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet(ResultSet::TYPE_ARRAY);
        $this->initialize();
    }
    
    /**
     * Obtiene el logo de la empresa
     */
    public function getLogo()
    {
        try {
			$sql = new Sql($this->getAdapter());
			$select = $sql->select();
			$select
				->from(array('p' => $this->table))
				->where(array('p.name'=> 'FOTO'));
			
			$statement = $sql->prepareStatementForSqlObject($select);
            $result = $this->resultSetPrototype->initialize($statement->execute())->toArray();
            
			return $result;
        } catch (\Exception $e) {
            throw new \Exception($e->getPrevious()->getMessage());
        }
    }    
}
