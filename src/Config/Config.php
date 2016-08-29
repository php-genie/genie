<?php
namespace Genie\Config;
use Genie\Collection\Arr;

/**
* 
*/
class Config extends Arr
{
	protected $var;
	
	function __construct($path)
	{
		$this->var = LoadConfig::load($path);
	}
}
