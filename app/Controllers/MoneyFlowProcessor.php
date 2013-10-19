<?php
namespace LightWork\Controllers;

use LightWork\Libs\Controllers\Processor;
use LightWork\Libs\Logger;
use LightWork\Models\Budgets;
use LightWork\Models\Categories;

class MoneyFlowProcessor extends Processor
{
	public function withdrawal()
	{
		$logger = new Logger();

		if ($fail = $this->_hasFail())
		{
			return $this->ajaxError($fail);
		}

		$amount = floatval($_POST['amount']);
		$id = $_POST['id'];

		$model = new Categories();
		$budget = new Budgets();

		if ($request_amount = always_set($_POST, 'request_amount'))
		{
			$logger_request = new Logger();

			$logger_request->fixBefore($id);

			$model->requestAmount($id, $request_amount);

			$category = $model->getById($id);

			$logger->fixAfter($id)
				->setAmount($request_amount)
				->setAction(Logger::AC_REQUEST_AMOUNT)
				->setTitle($category['title'])
				->save();
		}
		else
		{
			$category = $model->getById($id);
		}

		if ($category['current_amount'] < $amount)
		{
			$post_back = $_POST;
			$post_back['request_amount'] =  $amount - $category['current_amount'];
			return $this->ajaxError(array('post_back' => $post_back));
		}

		$logger->fixBefore($id);

		$model->withdrawal($id, $amount);
		$budget->addRealExpenses($amount);

		$logger->fixAfter($id)
			->setAmount($amount)
			->setAction(Logger::AC_CATEGORY_WITHDRAWAL)
			->setTitle($category['title'])
			->setComment(always_set($_POST, 'comment', ''))
			->save();

		return $this->ajaxSuccess(array('model' => $model->getById($id), 'budget' => $budget->getSummary()));
	}

	public function refund()
	{
		$logger = new Logger();

		if ($fail = $this->_hasFail())
		{
			return $this->ajaxError($fail);
		}

		$amount = floatval($_POST['amount']);
		$id = $_POST['id'];

		$model = new Categories();
		$budget = new Budgets();

		$category = $model->getById($id);

		if ($category['current_amount'] + $amount > $category['amount'])
		{
			return $this->ajaxError(array('amount' => _t('/money_flow/validator/refund_too_big')));
		}

		$logger->fixBefore($id);

		$model->refund($id, $amount);
		$budget->subtractRealExpenses($amount);

		$logger
			->fixAfter($id)
			->setAction(Logger::AC_CATEGORY_REFUND)
			->setAmount($amount)
			->setTitle($category['title'])
			->setComment(always_set($_POST, 'comment', ''))
			->save();

		return $this->ajaxSuccess(array('model' => $model->getById($id), 'budget' => $budget->getSummary()));
	}

	public function returnRemainder()
	{
		$logger = new Logger();

		$id = $_POST['id'];

		$model = new Categories();
		$category = $model->getById($id);

		if ($category['current_amount'] < 0.01)
		{
			return $this->ajaxError(array('error' => _t('/money_flow/validator/remainder_zero')));
		}

		if ($category['current_amount'] == $category['amount'])
		{
			return $this->ajaxError(array('error' => _t('/money_flow/validator/is_sync')));
		}

		$logger->fixBefore($id);

		$model->returnRemainder($id, $category['current_amount']);

		$logger
			->fixAfter($id)
			->setAction(Logger::AC_RETURN_REMAINDER)
			->setAmount($category['current_amount'])
			->setTitle($category['title'])
			->save();

		$budget = new Budgets();

		return $this->ajaxSuccess(array('model' => $model->getById($id), 'budget' => $budget->getSummary()));
	}

	private function _hasFail()
	{
		if (!isset($_POST['amount']))
		{
			return array('amount' => _t('/money_flow/validator/missing_field', array('field' => 'amount')));
		}

		if (floatval($_POST['amount']) < 0.01)
		{
			return array('amount' => _t('/money_flow/validator/wrong_amount'));
		}

		return false;
	}
}