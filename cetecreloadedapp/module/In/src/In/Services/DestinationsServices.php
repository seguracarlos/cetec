<?php
namespace In\Services;

use In\Model\DestinationsModel;

class DestinationsServices
{
	private $service;
	
	/**
	 * Instanciamos el modelo
	 */
	public function getModel()
	{
		return $this->service = new DestinationsModel();
	}
	
	/**
	 * Obtenemos todos los registros disponibles 
	 */
	public function fetchAll()
	{
		$rows = $this->getModel()->fetchAll();
		return $rows;
	}
	
	/**
	 * Obtenemos un registro por id
	 */
	public function getRowById($id)
	{
		$row = $this->getModel()->getRowById($id);
		return $row;
	}
	
	/**
	 * Agregar un registro
	 */
	public function addRow($data)
	{
		$data = array(
		    'id_destination'          => $data['id_destination'],
		    'type_destination'        => trim($data['type_destination']),
		    'name_destination'        => trim($data['name_destination']),
		    'description_destination' => trim($data['description_destination']),
		    'direct_route'       	  => trim($data['direct_route']),
			'operator_salary'         => trim(\Util_Tools::notCurrencyOutPoint($data['operator_salary'])),
			'assistant_salary'        => trim(\Util_Tools::notCurrencyOutPoint($data['assistant_salary'])),
			'dry_van'      			  => trim(\Util_Tools::notCurrencyOutPoint($data['dry_van'])),
			'tonel_van'      		  => trim(\Util_Tools::notCurrencyOutPoint($data['tonel_van'])),
			'truck'     		 	  => trim(\Util_Tools::notCurrencyOutPoint($data['truck'])),
			'refrigerated_truck'      => trim(\Util_Tools::notCurrencyOutPoint($data['refrigerated_truck'])),
		);
		//echo "<pre>"; print_r($data); exit;
		// Guardamos
		$saveRow = $this->getModel()->addRow($data);
		
		return $saveRow;
	}
	
	/**
	 * Modificar un registro
	 */
	public function editRow($data)
	{	
		$data = array(
		    'id_destination'          => $data['id_destination'],
		    'type_destination'        => trim($data['type_destination']),
		    'name_destination'        => trim($data['name_destination']),
		    'description_destination' => trim($data['description_destination']),
		    'direct_route'       	  => trim($data['direct_route']),
			'operator_salary'         => trim(\Util_Tools::notCurrencyOutPoint($data['operator_salary'])),
			'assistant_salary'        => trim(\Util_Tools::notCurrencyOutPoint($data['assistant_salary'])),
			'dry_van'      			  => trim(\Util_Tools::notCurrencyOutPoint($data['dry_van'])),
			'tonel_van'      		  => trim(\Util_Tools::notCurrencyOutPoint($data['tonel_van'])),
			'truck'     		 	  => trim(\Util_Tools::notCurrencyOutPoint($data['truck'])),
			'refrigerated_truck'      => trim(\Util_Tools::notCurrencyOutPoint($data['refrigerated_truck'])),
		);
		
		// Modificamos
		$editRow = $this->getModel()->editRow($data);
		
		return $editRow;
	}
	
	/**
	 * Eliminar un registro
	 */
	public function deleteRow($id)
	{
		$id        = (int) $id;
		$deleteRow = $this->getModel()->deleteRow($id);
		return $deleteRow; 
	}
	
}