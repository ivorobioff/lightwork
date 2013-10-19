<?php
namespace LightWork\Libs\DataBase\Adapters;

interface AdapterInterface
{
	public function query($sql);
	public function escape($srt);
	public function getAffectedRows();
	public function getInsertId();
	public function getError();
}