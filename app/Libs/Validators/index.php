<?php
namespace LightWork\Libs;

use LightWork\Libs\Validators\Plugins\DateFormat;
use LightWork\Libs\Validators\Plugins\Email;
use LightWork\Libs\Validators\Plugins\Password;
use LightWork\Libs\Validators\Plugins\Setness;
class Validators
{
	/**
	 * @return \LightWork\Libs\Validators\Plugins\Email;
	 */
	static public function getEmailValidator()
	{
		return new Email();
	}

	/**
	 * @return \LightWork\Libs\Validators\Plugins\Password
	 */
	static public function getPasswordValidator()
	{
		return new Password();
	}

	/**
	 * @return \LightWork\Libs\Validators\Plugins\Setness
	 */
	static public function getSetnessValidator()
	{
		return new Setness();
	}

	/**
	 * @return \LightWork\Libs\Validators\Plugins\DateFormat
	 */
	static public function getDateFormatValidator()
	{
		return new DateFormat();
	}
}