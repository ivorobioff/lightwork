<?php
namespace LightWork\Models;

use LightWork\Db\Archives as ArchivesTable;
use LightWork\Models\Archive\Item as ArchiveItem;

class Archive
{
	private $_table;

	public function __construct()
	{
		$this->_table = new ArchivesTable();
	}

	public function save(array $data)
	{
		$data['user_id'] = user_id();
		$data['insert_date'] = date('Y-m-d H:i:s');

		$this->_table->insert($data);
	}

	public function getDates()
	{
		return $this->_table
			->where('user_id', user_id())
			->orderBy('id', 'DESC')
			->createResultFormat()
			->getHash('id', 'insert_date');
	}

	/**
	 * @param int $id
	 * @return \LightWork\Models\Archive\Item
	 */
	public function getById($id)
	{
		$data = $this->_table
			->where('user_id', user_id())
			->where('id', $id)
			->fetchOne();

		if (!$data) return false;

		return new ArchiveItem($data);
	}
}