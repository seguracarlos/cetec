<?php
namespace In\Services;

use In\Model\UsersShippingModel;

class UsersShippingServices
{
	private $usersShippingModel;
	
	/**
	 * @return \In\Model\UsersShippingModel
	 */
	public function getModel()
	{
		return $this->usersShippingModel = new UsersShippingModel();
	}
	
	/**
	 * 
	 * @return array
	 */
	public function fetchAll()
	{
		$rows = $this->getModel()->fetchAll();
		return $rows;
	}
	
	/**
	 * 
	 * @param unknown $id
	 */
	public function getRowById($id)
	{
		
	}
	
	/**
	 * 
	 * @param unknown $data
	 */
	public function addRow($data)
	{
		
	}
	
	/**
	 * 
	 * @param unknown $data
	 */
	public function editRow($data)
	{
		
	}
	
	/**
	 * 
	 * @param unknown $id
	 */
	public function deleteRow($id)
	{
		
	}

}