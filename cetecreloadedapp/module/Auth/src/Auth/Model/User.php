<?php
namespace Auth\Model;

use Zend\Db\Sql\Sql;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;

class User extends AbstractTableGateway
{

    public $table = 'iof_users';
    
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet(ResultSet::TYPE_ARRAY);
        $this->initialize();
    }
    
    public function getUsers($where = array(), $columns = array())
    {
        try {
            $sql = new Sql($this->getAdapter());
            $select = $sql->select()->from(array(
                'user' => $this->table
            ));
            
            if (count($where) > 0) {
                $select->where($where);
            }
            
            if (count($columns) > 0) {
                $select->columns($columns);
            }
            
            $select->join(array('userRole' => 'iof_user_role'), 
            					'userRole.user_id = user.user_id', 
            			  array('role_id'), 'LEFT');
            $select->join(array('role' => 'iof_role'), 'userRole.role_id = role.rid', 
            			  array('role_name'), 'LEFT');
            
            $select->join(array('j_u' => 'job_users'), 'user.id_job = j_u.id',
            		array('name_job'), 'LEFT');
            
            $statement = $sql->prepareStatementForSqlObject($select);
            $users = $this->resultSetPrototype->initialize($statement->execute())->toArray();
            
            return $users;
        } catch (\Exception $e) {
            throw new \Exception($e->getPrevious()->getMessage());
        }
    }    
    
    /*
     * BLOQUEAR CUENTA DE USUARIO
     */
    public function blockUserAccount($email)
    {
    
    	$data = array(
    			'canlogin' => 0
    	);
    
    	$user = $this->update($data,array("email" => $email));
    
    	return $user;
    }
    
}
