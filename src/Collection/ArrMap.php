<?php
namespace Genie;
/**
* 
*/
class ArrMap implements \ArrayAccess
{
	public $key;
	public $value;

	public function __construct($key, $value) {
		$this->key = $key;
		$this->value = $value;
	}
	public function __set($key, $value) {
		$this->key = $key;
		$this->value = $value;
	}

	public function offsetGet($key) {
	}

	public function offsetSet($key, $value) {
		$this->key = $key;
		$this->value = $value;
	}

	public function offsetUnset($key) {
	}

	public function offsetExists($key) {
	}
}