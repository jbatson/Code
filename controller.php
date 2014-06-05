<?php
/*
 * Cinnamon Website Management System
 * File: controller.php
 * 
 * +TERMS+
 */
 
require_once(BASEPATH.'/application/cinnamon/core/template/template.php'); 
 
class Cinnamon_Controller
{
	protected $_model;
	protected $_action;
	protected $_controller;
	protected $_parameters;
	protected $_template;
	
	public function __construct($controller, $action, $model, $parameters)
	{
		if(!empty($model))
		{
			$this->_model = $model;
		}
		else
		{
			$this->_model = $controller.'Model';	
		}
		
		$this->_action = $action;
		$this->_controller = $controller;
		$this->_parameters = $parameters;
		
		$this->_template = new Cinnamon_Template($this->_controller, $this->_action, $this->_model, $this->_parameters);
	}
	
	public function set($name, $value)
	{
		$this->_template->set($name, $value);
	}
	
	public function __destruct()
	{
		$this->_template->render();
	}
}