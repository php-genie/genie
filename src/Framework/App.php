<?php
namespace Genie;
use ReflectionClass;
use ReflectionMethod;
/**
* 
*/
class App extends \Slim\App
{
	protected $container;

	public function controller($route, $controller = null) {
		if(!$controller) {
			$controller = $route;
			$route = null;
		}

		$this->container[$controller] = function($c) use($controller) {
			return new $controller;
		};

		$this->bindRoutes($controller, $route);
	}

	public function bindRoutes($controller, $route) {
		$reflector = new ReflectionClass($controller);
		$classDoc = new DocParser($reflector->getDocComment());
		
		foreach($reflector->getMethods(ReflectionMethod::IS_PUBLIC) as $methods) {
			if(strpos($methods->name, '__') === 0) continue;
			$methodDoc = new DocParser($method->getDocComment());
			$methods = $methodDoc->getMethods() ?: $classDoc->getMethods() ?: ['GET'];
			$pattern = implode('/', array_filter([$route, $classDoc->getRoute(), $methodDoc->getRoute()]));
			$router = $this->map($methods, $pattern, "{$controller}:{$meth}");
			$this->addMiddleware($router, $classDoc, $methodDoc);
		}
	}

	public function addMiddlewares($route, $classDoc, $methodDoc) {
		// $route->add()
	}
}