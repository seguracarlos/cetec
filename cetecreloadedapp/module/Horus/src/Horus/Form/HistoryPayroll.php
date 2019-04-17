<?php
namespace Horus\Form;

use Zend\Form\Form;
use System\Services\UsersService;

class HistoryPayroll extends Form
{
	public function __construct($name = null)
	{
		parent::__construct($name);
		
		$this->setAttributes(array(
				'action'=>"",
				'method' => 'post'
		));
		
		$this->add(array(
				'name' => 'employee',
				'type' => 'Select',
				'options' => array (
						'label' => 'Empleado:',
						'empty_option' => '--selecciona--',
						'value_options' => $this->getAllEmployees()
				),
				'attributes' => array(
						'id'    =>'employee',
						'class' => 'form-control input-lg'
				)
		));
		
	}
	
	/*
	 * Funcion que obtiene todos los empleados disponibles
	 */
	public function getAllEmployees()
	{
		$users_services = new UsersService();
		$employess      = $users_services->fetchAll(1);
		$result         = array();
	
		foreach ($employess as $emp){
			$result[$emp['user_id']] = $emp['name']." ".$emp['surname']." ".$emp['lastname'];
		}
	
		return $result;
	}
}