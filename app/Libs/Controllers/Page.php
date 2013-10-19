<?php
namespace LightWork\Libs\Controllers;

use LightWork\Libs\Controllers;
use LightWork\Libs\Views;
use LightWork\Models\Budgets;

abstract class Page extends Controllers
{
	/**
	 * @var \LightWork\Libs\Views
	 */
	protected $_layout;
	protected $_title = 'MoneyLogger 1.0';

	public function __construct()
	{
		parent::__construct();

		if (!$this->_checkAuth())
		{
			redirect('/Auth/');
		}

		$this->_layout = Views::create('layout.phtml');
	}

	protected function render(Views $view)
	{
		$budget = new Budgets();

		$this->_layout
			->assign('view', $view)
			->assign('title', $this->_title)
			->assign('budget_summary', $budget->getSummary())
			->assign('year', date('Y'))
			->render();
	}
}
