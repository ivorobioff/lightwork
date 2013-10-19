<?php
namespace LightWork\Libs;

class Config
{
	static public function getCustom($var, $default = null)
	{
		return self::_get('custom.php', $var, $default);
	}
	
	static public function getTestClass($suite)
	{
		return self::_get('test_suites.php', $suite);
	}
	
	static public function isProduction()
	{
		return static::getCustom('is_production');
	}
	
	static private function _get($file, $var, $default = null)
	{
		$config = include APP_DIR.'/config/'.$file;
		
		return always_set($config, $var, $default);
	}
}