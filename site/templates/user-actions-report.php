<?php
	include_once($modules->get('Mvc')->controllersPath().'vendor/autoload.php');

	use App\Activity\UserActions\Report;
	$routes = [
		['GET',  '', Report::class, 'index'],
		['GET',  'customer', Report\Customer::class, 'index'],
	];
	$router = new Mvc\Routers\Router();
	$router->setRoutes($routes);
	$router->setRoutePrefix($page->url);
	$page->body = $router->route();

	include $config->paths->content."common/echo-page.php";
