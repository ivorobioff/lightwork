<?php
namespace LightWork\Libs;

use LightWork\Libs\Exceptions\Error404;
use LightWork\Libs\Config;

class Router
{
	private $_controller_class;
	private $_action_method;
	private $_params;

	public function parse()
	{
		$url_path = $_SERVER['REQUEST_URI'];

		$url_parts = explode('?', $url_path);

		$url_path = always_set($url_parts, 0, '');
		$url_path = trim(trim($url_path), '/');

		$url_query = always_set($url_parts, 1, '');

		$url_query_array = array();
		parse_str($url_query, $url_query_array);

		$url_array = array();

		if ($url_path)
		{
			$url_array = explode('/', $url_path);
		}
		
		if (!isset($url_array[0]))
		{
			$default_path = Config::getCustom('default_path');
			$controller_name = $default_path['controller'];
			$this->_action_method = always_set($url_array, 1, always_set($default_path, 'action', 'index'));
		}
		else
		{
			$controller_name = $url_array[0];
			$this->_action_method = always_set($url_array, 1, 'index');
		}


		array_shift($url_array);
		array_shift($url_array);

		$this->_params = $url_array;

		$_GET = array_merge($_GET, $url_query_array);
		$_GET['controller'] = $controller_name;
		$_GET['action'] = $this->_action_method;

		if (!file_exists(APP_DIR.'/Controllers/'.$controller_name.'.php'))
		{
			show_404();
		}

		$this->_controller_class = '\LightWork\Controllers\\'.$controller_name;

		if (!class_exists($this->_controller_class))
		{
			show_404();
		}

		$reflection = new \ReflectionClass($this->_controller_class);

		if (!$reflection->isInstantiable())
		{
			show_404();
		}

		if (!$reflection->hasMethod($this->_action_method))
		{
			show_404();
		}

		if (!$reflection->getMethod($this->_action_method)->isPublic())
		{
			show_404();
		}
	}

	public function getControllerClass()
	{
		return $this->_controller_class;
	}

	public function getActionMethod()
	{
		return $this->_action_method;
	}

	public function getParams()
	{
		return $this->_params;
	}
}