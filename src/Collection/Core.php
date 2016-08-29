<?php
namespace Genie\Collection;
/**
* 
*/
class Core
{
	
	protected $var;
	protected $allowed;
	public static $extends;

	protected function isRegex($pattern) {
		return $pattern instanceof Regex;
	}

	protected function parse($var) {
		if(is_string($var)) {
			return new Str($var);
		} elseif(is_array($var)) {
			return new Arr($var);
		}
		return $var;
	}

	public function __clone() {
		return new static($this->var);
	}

	public function __get($var) {
		if(in_array($var, $this->allowed)) {
			return $this->{$var}();
		}
	}
}
