<?php
namespace LightWork\Models;

use LightWork\Libs\Utils;
use LightWork\Models\Archive\Interfaces\Resetable;
use LightWork\Models\Archive\Interfaces\Savable;
use LightWork\Db\Budgets as BudgetsTable;
use LightWork\Db\Categories as CategoriesTable;

class Budgets implements Resetable, Savable
{
	private $_table;

	public function __construct()
	{
		$this->_table = new BudgetsTable();
	}

	public function addDefault($user_id)
	{
		$data = array(
			'user_id' => $user_id,
			'income' => 0,
			'real_expenses' => 0
		);

		$this->_table->insert($data);
	}

	public function get()
	{
		return $this->_table
			->where('user_id', user_id())
			->fetchOne();
	}

	public function getSummary()
	{
		$budget = $this->get();

		$total_planned = CategoriesTable::create()
			->select('SUM(amount) AS total')
			->where('user_id', user_id())
			->createResultFormat()
			->getValue('total', 0);

		return array(
			'budget' => Utils::toMomey(($budget['income'] - $budget['real_expenses'])),
			'expenses' => Utils::toMomey(($total_planned)),
			'remainder' => Utils::toMomey(($budget['income'] - $total_planned))
		);
	}

	public function deposit($amount)
	{
		$this->_table
			->where('user_id', user_id())
			->update('income = income +', $amount);
	}

	public function withdrawal($amount)
	{
		$this->_table
		->where('user_id', user_id())
		->update('income = income -', $amount);
	}

	public function hasEnoughMoney($amount)
	{
		return $this->_table
			->where('user_id', user_id())
			->where('income >=', $amount)
			->check();
	}

	public function addRealExpenses($amount)
	{
		$this->_table
			->where('user_id', user_id())
			->update('real_expenses = real_expenses + ', $amount);
	}

	public function subtractRealExpenses($amount)
	{
		$this->_table
			->where('user_id', user_id())
			->update('real_expenses = real_expenses - ', $amount);
	}

	public function getArchiveAlias()
	{
		return 'budget';
	}

	public function buildArchiveData()
	{
		return $this->getSummary();
	}

	public function reset()
	{
		$summary = $this->getSummary();
		$this->_table
			->where('user_id', user_id())
			->update(array(
				'real_expenses' => 0,
				'income' => $summary['remainder']
			));
	}
}