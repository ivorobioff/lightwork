<?php
namespace LightWork\Models\Archive\Interfaces;

use LightWork\Models\Archive\Interfaces\Archivable;

interface Resetable extends Archivable
{
	public function reset();
}