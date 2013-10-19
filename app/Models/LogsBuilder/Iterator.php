<?php
namespace LightWork\Models\LogsBuilder;

use LightWork\Libs\Logger;
use LightWork\Libs\Utils;

class Iterator implements \Iterator
{
	private $_data;

	public  function __construct(array $data)
	{
		$this->_data = $data;
	}

	public function current()
	{
		$row = current($this->_data);
		return $this->_prepareRow($row);
	}

	private function _prepareRow($row)
	{
		$row['insert_date'] = date('Y-m-d H:s', strtotime($row['insert_date']));
		$row['fixation'] = json_decode($row['fixation']);
		$row['amount'] = Utils::toMomey($row['amount']);
		$row['action'] = $this->_getActionLabel($row['action']);

		return $row;
	}

	private function _getActionLabel($action)
	{
		$map = array(
			Logger::AC_ADD_AMOUNT => _t('/logs/actions/add_mount'),
			Logger::AC_BUDGET_DEPOSIT => _t('/logs/actions/budget_deposit'),
			Logger::AC_BUDGET_WITHDRAWAL => _t('/logs/actions/budget_withdrawal'),
			Logger::AC_CATEGORY_REFUND => _t('/logs/actions/category_refund'),
			Logger::AC_CATEGORY_WITHDRAWAL => _t('/logs/actions/category_withdrawal'),
			Logger::AC_CREATE_CATEGORY => _t('/logs/actions/create_category'),
			Logger::AC_DELETE_CATEGORY => _t('/logs/actions/delete_category'),
			Logger::AC_REQUEST_AMOUNT => _t('/logs/actions/request_amount'),
			Logger::AC_RETURN_REMAINDER => _t('/logs/actions/return_remainder'),
			Logger::AC_SUBTRACT_AMOUNT => _t('/logs/actions/subtract_amount'),
		);

		return $map[$action];
	}

	public function next()
	{
		next($this->_data);
	}

	public function key()
	{
		return key($this->_data);
	}

	public function valid()
	{
		return key($this->_data) !== null;
	}

	public function rewind()
	{
		reset($this->_data);
	}
}