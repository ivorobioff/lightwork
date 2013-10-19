<?php
namespace LightWork\Libs\DataBase;

use LightWork\Libs\DataBase\Adapters\Factory;
use LightWork\Libs\DataBase\QueryBuilder\Builder;

abstract class DefaultDb extends Builder
{
	protected $_db_name = 'default';

	/**
	 * (non-PHPdoc)
	 * @see Builder::_getAdapter()
	 */
	protected function _getAdapter()
	{
		return Factory::getInstance()->createMysqliAdapter($this->_db_name);
	}
}