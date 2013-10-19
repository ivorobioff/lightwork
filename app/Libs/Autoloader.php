<?php
namespace LightWork\Libs;

class Autoloader
{
	public function load($class)
	{
		$s = strrpos($class, '\\') === false ? '_' : '\\';
				
		$class_array = explode($s, $class);
		
		if ($class_array[0] == 'LightWork')
		{
			$this->_loadLightWork($class_array);
		}
		elseif ($class_array[0] == 'PHP')
		{
			$this->_loadPHP($class_array);
		}
		elseif ($class_array[0] == 'PHPUnit')
		{
			$this->_loadPHPUnit($class_array);	
		}
	}
	
	private function _loadLightWork(array $class_array)
	{
		unset($class_array[0]);
					
		$class_path = APP_DIR.'/'.implode('/', $class_array).'.php';
		
		if (!file_exists($class_path))
		{
			$class_path = rtrim($class_path, '.php');
			$class_path .= '/index.php';
		}
		
		require_once $class_path;
	}
	
	private function _loadPHPUnit(array $class_array)
	{
		$vendor = APP_DIR.'/vendor';
		require_once $vendor.'/'.implode('/', $class_array).'.php';
	}
		
	private function _loadPHP(array $class_array)
	{
		$vendor = APP_DIR.'/vendor';
		require_once $vendor.'/'.implode('/', $class_array).'.php';
	}
}