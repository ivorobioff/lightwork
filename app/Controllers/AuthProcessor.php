<?php
namespace LightWork\Controllers;

use LightWork\Libs\Controllers\Processor;
use LightWork\Libs\Validators;
use LightWork\Models\Auth;
use LightWork\Models\Budgets;
use LightWork\Models\CurrentUser;
use LightWork\Models\Groups;

class AuthProcessor extends Processor
{
	protected $_require_auth = false;
	protected $_ajax_exceptions = array('signout');

	public function signin()
	{
		if (!Validators::getEmailValidator()
				->setEmail($_POST['email'])
				->verify())
		{
			return $this->ajaxError(array('email' => _t('/auth/validator/wrong_email_format')));
		}

		if (!Validators::getPasswordValidator()
				->setPass($_POST['pass'])
				->checkLength())
		{
			return $this->ajaxError(array('pass' => _t('/auth/validator/wrong_pass_length')));
		}

		$model = new Auth();

		if (!$data = $model->getUserByCredentials($_POST['email'], $_POST['pass']))
		{
			return $this->ajaxError(array('email' => _t('/auth/validator/no_user')));
		}

		CurrentUser::getInstance()->login($data, isset($_POST['remember_me']));

		$this->ajaxSuccess();
	}

	public function signout()
	{
		CurrentUser::getInstance()->logout();
		redirect('/Auth/');
	}

	public function signup()
	{
		$missing_fields = Validators::getSetnessValidator()
			->setRequiredFields(array('fio', 'email', 'pass', 'conf_pass'))
			->setFields($_POST)
			->getMissingFields();

		if ($missing_fields)
		{
			$errors = array();

			foreach ($missing_fields as $item)
			{
				$errors[$item] = _t('/auth/validator/missing_field', array('field' => $item));
			}

			return $this->ajaxError($errors);
		}

		if (!Validators::getEmailValidator()
				->setEmail($_POST['email'])
				->verify())
		{
			return $this->ajaxError(array('email' => _t('/auth/validator/wrong_email_format')));
		}

		$pass_validator = Validators::getPasswordValidator()
			->setPass($_POST['pass'])
			->setConfirmPass($_POST['conf_pass']);

		if (!$pass_validator->checkLength())
		{
			return $this->ajaxError(array('pass' => _t('/auth/validator/wrong_pass_length')));
		}

		if (!$pass_validator->checkIfEqual())
		{
			return $this->ajaxError(array('pass' => _t('/auth/validator/diff_passes')));
		}

		$model = new Auth();

		if ($model->checkEmail($_POST['email']))
		{
			return $this->ajaxError(array('email' => _t('/auth/validator/user_exists')));
		}

		if (!$user_id = $model->addUser($_POST))
		{
			return $this->ajaxError(array('message' => _t('/auth/validator/cannot_register')));
		}

		$budget_model = new Budgets();
		$budget_model->addDefault($user_id);

		$group_model = new Groups();
		$group_model->addDefault($user_id);

		CurrentUser::getInstance()->login($model->getUserById($user_id));

		return $this->ajaxSuccess();
	}
}