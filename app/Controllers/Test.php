<?php
namespace LightWork\Controllers;

use LightWork\Libs\Exceptions\Error404;
use LightWork\Libs\Test\ResultPrinter;
use LightWork\Libs\Config;
use LightWork\Libs\Controllers;
/**
 * Контроллер для запуска тестовой среды
 * @author Igor Vorobioff<i_am_vib@yahoo.com>
 */
class Test
{
	public function run($params)
	{				
		if (!$class_name = Config::getTestClass($params[0]))
		{
			show_404();
		}
		
		$suite = new \PHPUnit_Framework_TestSuite();
		$suite->setName($class_name);
		
		$suite->addTestSuite($class_name);
		
		$listener = new ResultPrinter();
		
		$test_result = new \PHPUnit_Framework_TestResult();
		 
		$test_result->addListener($listener);
		
		echo '<pre>';
		$suite->run($test_result);
		echo '</pre>';
		
		if ($listener->hasErrors())
		{
			$listener->printErrors();
		}
		
		return '';
	}
}