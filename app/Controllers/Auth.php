<?php
namespace LightWork\Controllers;

use LightWork\Libs\Controllers;
use LightWork\Libs\Views;
use LightWork\Models\Auth as AuthModel;
use LightWork\Models\CurrentUser;

class Auth extends Controllers
{
	protected $_require_auth = false;

	public function index()
	{
		if (is_auth())
		{
			redirect('/MoneyFlow/');
		}

		if ($hashed_id = always_set($_COOKIE, 'remember_me', false))
		{
			$model = new AuthModel();

			if ($data = $model->getUserByHashedId($hashed_id))
			{
				CurrentUser::getInstance()->login($data);
				redirect(isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/');
			}
		}

		$view = Views::create('/auth/index.phtml')
			->assign('title', _t('/auth/title'))
			->render();
	}
}