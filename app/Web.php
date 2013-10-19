<?php
namespace LightWork;

use LightWork\Libs\Exceptions\Error404;
use LightWork\Libs\Autoloader;
use LightWork\Libs\Router;

class Web
{
	public function start()
	{
		session_start();
		$this->_registerAutoloader();
		require_once APP_DIR.'/Libs/shortcuts.php';

		$router = new Router();
		try
		{
			$router->parse();

			$controller_class = $router->getControllerClass();
			$controller = new $controller_class();
			$controller->{$router->getActionMethod()}($router->getParams());
		}
		catch (Error404 $ex)
		{
			return $ex->show();
		}
	}

	private function _registerAutoloader()
	{
		require_once APP_DIR.'/Libs/Autoloader.php';
		
		spl_autoload_register(function($class){
			$loader = new Autoloader();
			$loader->load($class);
		});
	}
}