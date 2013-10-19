<?php
namespace LightWork\Models;

use LightWork\Db\Users as UsersTable;

class Auth
{
	/**
	 * @var \LightWork\Libs\Database\QueryBuilder\Builder
	 */
	private $_table;

	public function __construct()
	{
		$this->_table = new UsersTable();
	}

	public function addUser($data)
	{
		$data = array(
			'email' => $data['email'],
			'pass' => md5($data['pass']),
			'fio' => $data['fio'],
			'active' => 1,
		);

		return $this->_table->insert($data);
	}

	public function getUserByCredentials($email, $password)
	{
		return $this->_table
			->where('pass', md5($password))
			->where('email', $email)
			->where('active', 1)
			->fetchOne();
	}

	public function checkEmail($email)
	{
		return $this->_table->where('email', $email)->check();
	}

	public function getUserByHashedId($id)
	{
		return $this->_table->where('MD5(id)', $id)->fetchOne();
	}

	public function getUserById($id)
	{
		return $this->_table
			->where('id', $id)
			->where('active', 1)
			->fetchOne();
	}
}