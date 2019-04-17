<?php
namespace Auth\Utility;

use Zend\Permissions\Acl\Acl as ZendAcl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Acl extends ZendAcl implements ServiceLocatorAwareInterface
{
    const DEFAULT_ROLE = 'guest';
    protected $_roleTableObject;
    protected $serviceLocator;
    protected $roles;
    protected $permissions;
    protected $resources;
    protected $rolePermission;
    protected $commonPermission;

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    public function initAcl()
    {
        $this->roles          = $this->_getAllRoles();
        $this->resources      = $this->_getAllResources();
        $this->rolePermission = $this->_getRolePermissions();
        // No estamos poniendo estos recursos y el permiso en la tabla son comun a todos los usuarios
        $this->commonPermission = array(
            'Auth\Controller\Index' => array(
            	'logout',
                'index'
            ),
        	'Horus\Controller\Welcome' => array(
        		'index'
        	),
        	'System\Controller\Permissions' => array(
        		'getpermissionbyidresource'
        	),
        	'Iofractal\Controller\Addresses' => array(
        		'getalldistrict',
        		'getallneighborhood'
        	),
        	'System\Controller\Users' => array(
        		'getAllRoles',
        	),
            'System\Controller\Profile' => array(
                'index',
            	'edit', //resource db
            	'changepassword', //resource db
            ),
        	'In\Controller\Journey' => array(
        		'autocompleteclients'
        	),
        	//********************** CHANGE TO DB ***************************
        	'Exams\Controller\Exam' => array( 
        		'index',
        		'add',
                'edit',
        		'delete',
        		'userexam',
        		'replyexam',
        		'getscore',
        		'getscoresbyuser',
        		'addextraattemp',
        		'getextraattemplist',
        		'checkanswers',
        		'viewresult'
        	),
        	'Exams\Controller\Examquestions' => array( 
        		'index',
        		'add',
        		'deletequestion',
        		'getquestion',
        		'editquestion',
        		'save',
        		'edit'
        	),
        	'Exams\Controller\Examopcions' => array(
        		'index',
        		'add',
        		'addopcion',
        		'getopciones',
        		'editopcion',
        		'deleteopcion'
        	),
        	'Out\Controller\Expenses' => array(
        		'customsponsorship',
        		'addsponsor',
        		'editsponsor',
        		'deletesponsor'
        	),
        		
        	'Classes\Controller\Studentnotes' => array(
        		'index',
        		'viewnotes',
        		'add',
        		'update',
        		'delete',
        	),
        	
        	'Out\Controller\Studytime' => array(
        		'index',
        		'updatetime',
        	),
        	'Classes\Controller\Class' => array(
        		'contents',
        		'getcontent',
        		'addcontent',
        		'updatecontent',
        		'deletecontent',
        		'addtopic',
        		'updatetopic',
        		'deletetopic',
        		'index',
        		'myclass',
        		'displayorder',
        		'getvimeothumb'	        			
        				
        	),	
        	'Horus\Controller\Employee' => array(
        		'confirmshipping',
        		'payments',
        		'documents'
        	),
        );
        $this->_addRoles()
             ->_addResources()
             ->_addRoleResources();
    }

    public function isAccessAllowed($role, $resource, $permission)
    {
        if (! $this->hasResource($resource)) {
            return false;
        }
        if ($this->isAllowed($role, $resource, $permission)) {
            return true;
        }
        return false;
    }

    protected function _addRoles()
    {
        $this->addRole(new Role(self::DEFAULT_ROLE));

        if (! empty($this->roles)) {
            foreach ($this->roles as $role) {
                $roleName = $role['role_name'];
                if (! $this->hasRole($roleName)) {
                    $this->addRole(new Role($roleName), self::DEFAULT_ROLE);
                }
            }
        }
        return $this;
    }

    protected function _addResources()
    {
        if (! empty($this->resources)) {
            foreach ($this->resources as $resource) {
                if (! $this->hasResource($resource['resource_name'])) {
                    $this->addResource(new Resource($resource['resource_name']));
                }
            }
        }

        // add common resources
        if (! empty($this->commonPermission)) {
            foreach ($this->commonPermission as $resource => $permissions) {
                if (! $this->hasResource($resource)) {
                    $this->addResource(new Resource($resource));
                }
            }
        }

        return $this;
    }

    protected function _addRoleResources()
    {
        // allow common resource/permission to guest user
        if (! empty($this->commonPermission)) {
            foreach ($this->commonPermission as $resource => $permissions) {
                foreach ($permissions as $permission) {
                    $this->allow(self::DEFAULT_ROLE, $resource, $permission);
                }
            }
        }

        if (! empty($this->rolePermission)) {
            foreach ($this->rolePermission as $rolePermissions) {
                $this->allow($rolePermissions['role_name'], $rolePermissions['resource_name'], $rolePermissions['permission_name']);
            }
        }

        return $this;
    }

    protected function _getAllRoles()
    {
        $roleTable = $this->getServiceLocator()->get("RoleTable");
        return $roleTable->getUserRoles();
    }

    protected function _getAllResources()
    {
        $resourceTable = $this->getServiceLocator()->get("ResourceTable");
        return $resourceTable->getAllResources();
    }

    public function _getRolePermissions()
    {
        $rolePermissionTable = $this->getServiceLocator()->get("RolePermissionTable");
        return $rolePermissionTable->getRolePermissions();
    }

    private function debugAcl($role, $resource, $permission)
    {
        echo 'Role:-' . $role . '==>' . $resource . '\\' . $permission . '<br/>';
    }
}
