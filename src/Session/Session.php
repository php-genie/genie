<?php
namespace Genie\Session;
use Genie\Collection\Arr;
use Pimple\Container;
use Carbon\Carbon;

class Session extends Arr {

	protected $handler;
	protected $var;

	public function __construct(Container $app, Handler\HandlerInterface $handler = null) {
		$this->container = $container;
		if(!$handler) {
			$handler = new Handler\NativeHandler($container);
		}
		$this->handler = $handler;
	}

	public function flash($data, $val) {
		$this->set('__FLASH_DATA__.' . $data, $val);
	}

	public function reflash() {

	}

	public function destroy() {
		$this->var = null;
	}

	public function start($req, $res) {
		$this->handler->gc($this->container['session.expires']);
		$this->var = $this->handler->read();
	}

	public function terminate($req, $res) {
		$this->handler->write($this->var);
		return $res;
	}
}
