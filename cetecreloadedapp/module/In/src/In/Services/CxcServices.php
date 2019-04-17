<?php
namespace In\Services;

use In\Model\CxcModel;

class CxcServices
{
	private $cxcModel;
	
	/*
	 * Instanciamos el modelo de cxc
	 */
	public function getCxcModel()
	{
		return $this->cxcModel = new CxcModel();
	}
	
	/*
	 * Obtenemos todos los registros disponibles 
	 */
	public function fetchAll()
	{
		$select = $this->getCxcModel()->fetchAll();
		return $select;
	}
	
	/*
	 * Obtenemos un registro por id
	 */
	public function getRowById($id)
	{
		$row = $this->getServicesModel()->getRowById($id);
		
		return $row;
	}
	
	/*
	 * Agregar un registro
	 */
	public function addRow($data)
	{
		$data = array(
		    'id_service'  => $data['id_service'],
		    'clave'       => trim($data['clave']),
		    'name'        => trim($data['name_service']),
		    //'dateService' => $this->dateDBFormat($data['dateService']),
		    'description' => trim($data['description']),
		    'cost'        => $this->notCurrencyOutPoint($data['cost']),
		);
		
		// Guardamos
		$saveRow = $this->getServicesModel()->addRow($data);
		
		return $saveRow;
	}
	
	/*
	 * Modificar un registro
	 */
	public function editRow($data)
	{	
		$data = array(
		    'id_service'  => $data['id_service'],
		    'clave'       => trim($data['clave']),
		    'name'        => trim($data['name_service']),
		    //'dateService' => $this->dateDBFormat($data['dateService']),
		    'description' => trim($data['description']),
		    'cost'        => $this->notCurrencyOutPoint($data['cost']),
		);
		
		// Modificamos
		$editRow = $this->getServicesModel()->editRow($data);
		
		return $editRow;
	}
	
	/*
	 * Eliminar un registro
	 */
	public function deleteRow($id)
	{
		$id        = (int) $id;
		$deleteRow = $this->getServicesModel()->deleteRow($id);
		return $deleteRow; 
	}
	
	public static function notCurrencyOutPoint($number)
	{
		$sig[] = ',';
		$sig[] = '$';
		return str_replace($sig,'',$number);
	}
	
	public static function dateDBFormat($separator, $dateSource) 
	{
		$explode = explode($separator, $dateSource);
		$anio    = $explode[2] . '-';
		$mes     = $explode[1] . '-';
		$dia     = $explode[0];
	
		$fullDBDate = $anio . $mes . $dia;
		return $fullDBDate;
	}
	
	public static function dateFormat($separator, $dateSource)
	{
		$explode = explode($separator, $dateSource);
		$dia     = $explode[2] . '/';
		$mes     = $explode[1] . '/';
		$anio    = $explode[0];
	
		$fullDBDate = $dia . $mes . $anio;
		return $fullDBDate;
	}
}