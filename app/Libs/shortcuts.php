<?php
use LightWork\Libs\JsComposer\Exceptions\NoStart;
use LightWork\Libs\JsComposer;
use LightWork\Libs\Config;
use LightWork\Libs\Views;
use LightWork\Models\CurrentUser;
use LightWork\Libs\Exceptions\Error404;
use LightWork\Controllers\Error404 as Error404Controller;

function pre($str)
{
	echo '<pre>';
	print_r($str);
	echo '</pre>';
}

function pred($str)
{
	pre($str);
	die();
}

function _t($alias, $placeholders = array())
{
	$alias = '/'.trim($alias, '/');

	include APP_DIR.'/i18n/ru.php';

	$result = always_set($i18n, $alias, $alias);

	foreach ($placeholders as $placeholder => $value)
	{
		$result = str_replace('{'.$placeholder.'}', $value, $result);
	}

	return $result;
}

function _url($path)
{
	return $path;
}

function always_set($array, $key, $default = null)
{
	return isset($array[$key]) ? $array[$key] : $default;
}

function is_location($url)
{
	$url = trim($url, '/');
	$url = explode('/', $url);

	if (!isset($url[0]) || !isset($url[1])) return false;

	if ($url[1] == '*')
	{
		return $url[0] == $_GET['controller'];
	}

	return $url[0] == $_GET['controller'] && $url[1] == $_GET['action'];
}

function redirect($path)
{
	header('location: '._url($path));
	exit();
}

function is_auth()
{
	return CurrentUser::getInstance()->isAuth();
}

function load_js($controller, $action)
{
	$bootstrap_name = strtolower($controller.($action != 'index' ? '_'.$action : ''));

	if ($bootstrap_name == 'common') return ;

	$bin = md5($bootstrap_name);

	if (Config::isProduction())
	{
		return '<script src="/front/js/app/bin/'.$bin.'.js"></script>';
	}

	$composer = new JsComposer(Config::getCustom('js_composer'));

	try
	{
		$composer
			->addBootstrap('common.js')
			->addBootstrap($bootstrap_name.'.js')
			->process()
			->save($bin.'.js');
	}
	catch (NoStart $ex)
	{
		return '';
	}

	return '<script src="/front/js/app/bin/'.$bin.'.js"></script>';
}

function user_id()
{
	return CurrentUser::getInstance()->id;
}

/**
 * @param string $template
 * @return \LightWork\Libs\Views
 */
function create_view($template)
{
	return Views::create($template);
}

function show_404()
{
	$_GET['controller'] = 'Error404';
	$_GET['action'] = 'show';
	
	throw new Error404(new Error404Controller());
}