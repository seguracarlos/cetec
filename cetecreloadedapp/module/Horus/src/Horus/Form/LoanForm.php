<?php 
namespace Horus\Form;

use Zend\Form\Form;
use System\Services\UsersService;

class LoanForm extends Form
{
	public function __construct($name = null)
	{
		parent::__construct($name);
		
		$this->setAttributes(array(
				"action"  => "",
				"methiod" => "post",
				"id"      => "formLoans"
		));
		
		// ID
		$this->add(array(
				"name" => "id",
				"type" => "Hidden",
		));
		
		// IMPORTE
		$this->add(array(
				"name" => "amount",
				"type" => "Text",
				"attributes" => array(
						"id"          => "amount",
						"class"       => "form-control",
						"placeholder" => "Importe"
				)
		));
		
		// EMPLEADO
		$this->add(array(
				'name' => 'employee',
				'type' => 'Select',
				'options' => array (
						'label' => 'Empleado:',
						'empty_option'  => '--selecciona--',
						'value_options' => $this->getAllEmployees(),
				),
				'attributes' => array(
						'id'    => 'employee',
						'class' => 'form-control',
				)
		));
		
		// DESCRIPCION
		$this->add(array(
				"name" => "description",
				"type" => "Textarea",
				"attributes" => array(
						"id"    => "description",
						"class" => "form-control"
				)
		));
		
	}
	
	// Obtener empleados
	private function getAllEmployees()
	{
		$userServices = new UsersService();
		$rows         = $userServices->fetchAll(1);
		$result       = array();
		
		foreach ($rows as $row){
			//$result[] = ['attributes'=> ['data-est'=>$s_m['id']], 'value' => $s_m['id'], 'label' => $s_m['state'] ];
			$result[$row['user_id']] = $row['name']." ".$row['surname']." ".$row['lastname'];
		}
		
		return $result;
	}
}