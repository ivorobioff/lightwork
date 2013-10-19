<?php
namespace LightWork\Models\Archive\Interfaces;

use LightWork\Models\Archive\Interfaces\Archivable;

interface Savable extends Archivable
{
	public function getArchiveAlias();
	public function buildArchiveData();
}