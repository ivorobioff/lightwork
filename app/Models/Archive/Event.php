<?php
namespace LightWork\Models\Archive;

use LightWork\Models\Archive\Interfaces\Archivable;
use LightWork\Models\Archive\Interfaces\Resetable;
use LightWork\Models\Archive\Interfaces\Savable;
use LightWork\Models\Archive;
/**
 * @author Igor Vorobioff<igor.vorobioff@gmail.com>
 */
class Event
{
	private $_models = array();

	public function add(Archivable $model)
	{
		$this->_models[] = $model;
		return $this;
	}

	public function closeMonth()
	{
		$this->_saveAll();
		$this->_resetAll();
	}

	private function _saveAll()
	{
		foreach ($this->_models as $model)
		{
			if ($model instanceof Savable)
			{
				$data[$model->getArchiveAlias()] = json_encode($model->buildArchiveData());
			}
		}

		$archive = new Archive();
		$archive->save($data);
	}

	private function _resetAll()
	{
		foreach ($this->_models as $model)
		{
			if ($model instanceof Resetable)
			{
				$model->reset();
			}
		}
	}
}