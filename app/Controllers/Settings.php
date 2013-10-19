<?php
namespace LightWork\Controllers;

use LightWork\Libs\Controllers\Page;
use LightWork\Libs\Views;

class Settings extends Page
{
	public function index()
	{
		$view = Views::create('/settings/index.phtml');
		$this->render($view);
	}
}
