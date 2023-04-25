<?php

declare(strict_types=1);

namespace App\Router;

use Nette;
use Nette\Application\Routers\RouteList;


final class RouterFactory
{
	use Nette\StaticClass;

	public static function createRouter(): RouteList
	{
		$router = new RouteList;
		$router->addRoute('/', 'Home:default');
        $router->addRoute('/home','Home:default');
        $router->addRoute('/about','About:show');
        $router->addRoute('/news','News:news');
        $router->addRoute('/SIn', 'Sign:in');
        $router->addRoute('/SOut', 'Sign:out');
        $router->addRoute('edit', 'Edit:create');
		return $router;
	}
}
