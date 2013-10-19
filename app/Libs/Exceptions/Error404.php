<?php
namespace LightWork\Libs\Exceptions;

use LightWork\Controllers\Error404 as Error404Controller;
use LightWork\Libs\Exceptions;

class Error404 extends Exceptions
{
	/**
	 * @var \LightWork\Controllers\Error404
	 */
	private $_controller;

	public function __construct(Error404Controller $controller)
	{
		parent::__construct('error_404');
		$this->_controller = $controller;
	}

	public function show()
	{
		return $this->_controller->show();
	}
};