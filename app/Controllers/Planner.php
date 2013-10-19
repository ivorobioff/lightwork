<?php
namespace LightWork\Controllers;

use LightWork\Libs\Controllers\Page;
use LightWork\Libs\Views;
use LightWork\Models\Categories;
use LightWork\Models\Groups;

class Planner extends Page
{
	public function index()
	{
		$group_model = new Groups();
		$categories_model = new Categories();

		$view = Views::create('/planner/index.phtml')
			->assign('groups', $group_model->getAll())
			->assign('categories', $categories_model->getAll());

		$this->render($view);
	}
}
