<?php
namespace LightWork\Controllers;

use LightWork\Libs\Controllers\Processor;
use LightWork\Models\Budgets;
use LightWork\Models\Categories;
use LightWork\Models\Groups;
use LightWork\Models\Logs;
use LightWork\Models\Archive\Event;

class ArchiveProcessor extends Processor
{
	public function closeMonth()
	{
		$categories = new Categories();

		if ($categories->hasRemainders())
		{
			return $this->ajaxError(array(_t('/archive/validator/has_remainders')));
		}

		$event = new Event();

		$event
			->add(new Budgets())
			->add(new Categories())
			->add(new Groups())
			->add(new Logs())
			->closeMonth();

		$this->ajaxSuccess();
	}
}