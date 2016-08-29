<?php
namespace Genie\Collection;

use ArrayAccess;
use SeekableIterator;
use Countable;
use Serializable;
/**
* 
*/
class Iterator extends Core implements \Iterator {

	// Array iterator
	protected $var;

	function __construct($var) {
		$this->var = $var;
	}

	public function current() {
		return $this->parse(current($this->var));
	}
	
	public function key() {
		$key = key($this->var);
		return is_int($key) ? $key : new Str($key);
	}

	public function next() {
		next($this->var);
	}

	public function rewind() {
		reset($this->var);
	}

	public function valid() {
		return key($this->var) !== null;
	}
}
