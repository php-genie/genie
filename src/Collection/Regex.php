<?php namespace PQuery;
/**
* 
*/
class Regex
{
	private $pattern;
	private $modifier;
	function __construct($pattern, $modifier) {
		$this->pattern = $pattern;
		$this->modifier = $modifier;
	}
	function __get($prop) {
		return $this->$prop;
	}
}