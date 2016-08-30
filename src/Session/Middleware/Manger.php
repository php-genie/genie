<?php
namespace Genie\Session\Middleware;

use Pimple\Container;
/**
* 
*/
class Manager
{
	protected $session;
	protected $container;

	function __construct(Container $container, $session) {
		$this->container = $container;
		$this->session = $container->get($session);
	}

	public function startSession($req) {
		$this->session->start($req, $res);
		$parsedBody = $req->getParsedBody();
		$previusParsed = $this->session->get('__PARSED_BODY__')->toArray();
		if($previusParsed) {
			$req = $req->withParsedBody(array_merge($previusParsed, $parsedBody));
		}

		if($parsedBody) {
			$this->session->set('__PARSED_BODY__', $parsedBody);
		}
		
		return $req;
	}

	public function __invoke($req, $res, $next) {
		$req = $this->startSession($req);
		$res = $next($req, $res);
		return $this->session->terminate($req, $res);
	}
}
