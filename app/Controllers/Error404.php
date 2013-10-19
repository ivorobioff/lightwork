<?php
namespace LightWork\Controllers;

use LightWork\Libs\Controllers\Page;

class Error404 extends Page
{
	protected $_require_auth = false;

	public function show()
	{
		echo 'error404';
	}
}