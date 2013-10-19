<?php
namespace LightWork\Models;

use LightWork\Models\Archive\Interfaces\Resetable;
use LightWork\Db\Logs as LogsTable;

class Logs implements Resetable
{
	public function reset()
	{
		LogsTable::create()->where('user_id', user_id())->delete();
	}
}