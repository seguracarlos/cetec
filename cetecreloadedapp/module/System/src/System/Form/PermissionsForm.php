<?php
namespace System\Form;

use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Form\Factory;
use System\Services\ResourcesServices;
use System\Services\RoleService;

class PermissionsForm extends Form
{
	public function __construct($name = null, $status = null, $id_role = null)
	{
		parent::__construct($name);
		
		$this->setAttributes(array(
			'action'=>"",
			'method' => 'post'
		));
		
		$this->add(array(
			'name' => 'id_role',
			'attributes' => array(
			'type'  => 'hidden',
			)
		));
		
		$this->add(array(
			'name'    => 'rol_name',
			'options' => array(
				'label' => 'Nombre del rol:'
			),
			'attributes' => array(
				'type'     => 'text',
				'class'    => 'form-control input-lg',
				//'required' => 'required',
				'placeholder' => 'Nombre del rol', 
			)
		));
		
		if($status != null){
			$attributes = $status;
		}else{
			$attributes = "Active";
		}
		
		$this->add(array(
			'type' => 'Radio',
			'name' => 'status',
			'options' => array(
				//'label' => 'Status:',
				//'label_attributes' => array('class'=>'checkbox-inline'),
				'value_options' => array(
						array('label' => 'Activo', 'value' => 'Active'),
						array('label' => 'Inactivo', 'value' => 'Inactive'),
				),
			),
			'attributes' => array(
				'value' => $attributes
			)
		));
		
		//Validamos los recursos disponibles
		$role_id = $id_role;
		if($role_id != null){
			$optionsPermissions = $this->getAllResourcesNoAssignedToRole($id_role);
			$optionsPermissionsAssigned = $this->getAllResourcesAssignedToRole($id_role);
		}else if($role_id == null){
			$optionsPermissions =  $this->getAllResources();
			$optionsPermissionsAssigned = array();
		}
		
		$this->add(array(
			'name' => 'permissions',
			'type' => 'Select',
			'options' => array (
				'label' => 'Permisos Disponibles:',
				//'empty_option' => '--selecciona--',
				'value_options' => $optionsPermissions
			),
			'attributes' => array(
				'id'       =>'permissions',
				//'multiple' => 'multiple',
				'class' => 'form-control',
				'size'  => '10'
			)
		));
		
		$this->add(array(
			'name' => 'destinationPermissions',
			'type' => 'Select',
			'options' => array (
				'label' => 'Permisos Asignados:',
				//'empty_option' => '--selecciona--',
				'value_options' => $optionsPermissionsAssigned
			),
			'attributes' => array(
				'id'       =>'destinationPermissions',
				//'multiple' => 'multiple',
				'class' => 'form-control',
				'size'  => '10'
			)
		));
		
		$this->add(array(
			'name' => 'saveRole',
			'attributes' => array(
				'id'    => 'saveRole',
				'type'  => 'submit',
				'value' => 'Guardar',
				'class' => 'btn btn-primary btn-lg btn-block'
			)
		));
		
	}
	
 	/*
 	 * FUNCION QUE OBTIENE TODOS LOS RECURSOS DISPONIBLES
 	 */
	public function getAllResources()
	{
		$resourceService = new ResourcesServices();
		$resources       = $resourceService->getAllResources();
		$result          = array();
		
		foreach ($resources as $res){
			$result[$res['id']] = $res['app'] . " - " . $res['name_menu'];
		}

		return $result;
	}
	
	/*
	 * FUNCION QUE OBTIENE LOS RECURSOS ASIGNADOS A UN ROL
	 */
	public function getAllResourcesAssignedToRole($id_role)
	{
			$roleServices = new RoleService();
			$resources    = $roleServices->getAllResourcesAssignedToRole($id_role);
			$result       = array();
			//echo "<pre>"; print_r($resources); exit;
			foreach ($resources as $res){
				$result[$res['id']] = $res['app'] . " - " . $res['name'];
			}
			
		return $result;
	}
	
	/*
	 * FUNCION QUE OBTIENE LOS RECURSOS NO ASIGNADOS A UN ROL
	 */
	public function getAllResourcesNoAssignedToRole($id_role)
	{
			$roleServices = new RoleService();
			$resources    = $roleServices->getAllResourcesNoAssignedToRole($id_role);
			$result       = array();
			//echo "<pre>"; print_r($resources); exit;
			foreach ($resources as $res){
				$result[$res['id']] = $res['app'] . " - " . $res['name'];
			}
			//echo "<pre>"; print_r($result); exit;
		return $result;
	} 
}