<?php
namespace LightWork\Controllers;

use LightWork\Libs\Controllers\Processor;
use LightWork\Libs\Logger;
use LightWork\Models\Budgets;

class BudgetProcessor extends Processor
{
	public function deposit()
	{
		$logger = new Logger();

		$amount = floatval($_POST['amount']);

		if ($amount < 0.01)
		{
			return $this->ajaxError(array(_t('/budget/validator/small_amount')));
		}

		$model = new Budgets();

		$logger->fixBefore();

		$model->deposit($amount);

		$logger
			->fixAfter()
			->setAction(Logger::AC_BUDGET_DEPOSIT)
			->setAmount($amount)
			->save();

		$this->ajaxSuccess($model->getSummary());
	}

	public function withdrawal()
	{
		$logger = new Logger();

		if (!isset($_POST['amount']))
		{
			return $this->ajaxError(array(_t('/budget/validator/small_amount')));
		}

		$amount = floatval($_POST['amount']);

		if ($amount < 0.01)
		{
			return $this->ajaxError(array(_t('/budget/validator/small_amount')));
		}

		$model = new Budgets();

		if (!$model->hasEnoughMoney($amount))
		{
			return $this->ajaxError(array(_t('/budget/validator/not_enough_money')));
		}

		$logger->fixBefore();

		$model->withdrawal($amount);

		$logger
			->fixAfter()
			->setAction(Logger::AC_BUDGET_WITHDRAWAL)
			->setAmount($amount)
			->save();

		$this->ajaxSuccess($model->getSummary());
	}
}