<?php
namespace Horus\Services;

use Horus\Model\DepartmentsModel;

class DepartmentsServices
{
	private $departmentsModel;
	
	public function getDepartmentsModel()
	{
		return $this->departmentsModel = new DepartmentsModel();
	}
	
	public function fetchAll()
	{
		$departments = $this->getDepartmentsModel()->fetchAll();
		return $departments;
	}
}