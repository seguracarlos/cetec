<?php

namespace System\Entities;

class PreferencesEntity {
	private $id;
	private $name;
	private $value;
	public function getId()
	{
		return $this->id;
	}
	
	public function setId($id)
	{
		$this->id = $id;
	}
	public function getName()
	{
		return $this->name;
	}
	
	public function setName($Name)
	{
		$this->name = $Name;
	}
	public function getValue()
	{
		return $this->value;
	}
	
	public function setValue($value)
	{
		$this->value = $value;
	}	
}