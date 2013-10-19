<?php
namespace LightWork\Controllers;

use LightWork\Libs\Controllers\Page;
use LightWork\Libs\Views;
use LightWork\Models\LogsBuilder;

class Logs extends Page
{
	public function index()
	{
		$builder = new LogsBuilder($_GET);

		$view = Views::create('/logs/index.phtml')
			->assign('logs', $builder->createLogsIterator())
			->assign('paginator', $builder->getPaginator())
			->assign('filter', $builder->getFilterParams());

		$this->render($view);
	}
}
