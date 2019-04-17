<?php

namespace Auth\Utility;

class Menu
{
	protected $_path;
	protected $_pather;
	protected $_name;
	protected $_nameMenu;
	protected $_app;
	protected $_sub;
	protected $_pathresource;
	protected $_displayorder;
	protected $_agroup;
	
	public function __construct(array $options = null)
	{
		if (is_array($options)) {
			$this->setOptions($options);
		}
	}

	public function __set($name, $value)
	{
		$method = 'set' . $name;
		if (('mapper' == $name) || !method_exists($this, $method)) {
			throw new Exception('Invalid guestbook property');
		}
		$this->$method($value);
	}

	public function __get($name)
	{
		$method = 'get' . $name;
		if (('mapper' == $name) || !method_exists($this, $method)) {
			throw new Exception('Invalid guestbook property');
		}
		return $this->$method();
	}

	public function setOptions(array $options)
	{
		$methods = get_class_methods($this);
		foreach ($options as $key => $value) {
			$method = 'set' . ucfirst($key);
			if (in_array($method, $methods)) {
				$this->$method($value);
			}
		}
		return $this;
	}

	public function setPath($text)
	{
		$this->_path = (string) $text;
		return $this;
	}

	public function getPath()
	{
		return $this->_path;
	}
	
	public function setPather($text)
	{
		$this->_pather = (string) $text;
		return $this;
	}

	public function getPather()
	{
		return $this->_pather;
	}

	public function setName($text)
	{
		$this->_name = (string) $text;
		return $this;
	}

	public function getName()
	{
		return $this->_name;
	}
	public function setNameMenu($text)
	{
		$this->_nameMenu = (string) $text;
		return $this;
	}

	public function getNameMenu()
	{
		return $this->_nameMenu;
	}

	public function setApp($text)
	{
		$this->_app = (string) $text;
		return $this;
	}

	public function getApp()
	{
		return $this->_app;
	}

	public function setSub($sub)
	{
		$this->_sub = (string) $sub;
		return $this;
	}

	public function getSub()
	{
		return $this->_sub;
	}
	
	public function setPathresource($pathresource)
	{
		$this->_pathresource = (string) $pathresource;
		return $this;
	}
	
	public function getPathresource()
	{
		return $this->_pathresource;
	}
	public function setDisplayorder($displayorder)
	{
		$this->_displayorder = (string) $displayorder;
		return $this;
	}
	
	public function getDisplayorder()
	{
		return $this->_displayorder;
	}
	
	public function setAgroup($agroup)
	{
		$this->_agroup = (string) $agroup;
		return $this;
	}
	
	public function getAgroup()
	{
		return $this->_agroup;
	}
	
}