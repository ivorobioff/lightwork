<?php
namespace LightWork\Libs\DataBase\Adapters;

use LightWork\Libs\Singleton;
use LightWork\Libs\Application;
use LightWork\Libs\DataBase\Adapters\Mysqli;
use LightWork\Libs\Config;

class Factory extends Singleton
{
	/**
	 * @return \LightWork\Libs\DataBase\Adapters\Factory
	 */
	static public function getInstance()
	{
		return parent::getInstance();
	}

	/**
	 * @param unknown $db_name
	 * @return \LightWork\Libs\DataBase\Adapters\Mysqli
	 */
	public function createMysqliAdapter($db_name)
	{
		$config = Config::getCustom('db');
		$config = $config[$db_name];
		return new Mysqli(new \mysqli($config['host'], $config['username'], $config['password'], $config['dbname']));
	}
}