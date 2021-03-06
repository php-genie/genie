<?php
namespace Genie\Config;
/**
* 
*/
class LoadConfig
{
	public static function load($path) {
		$configs = [];
		$baseName = glob($path . '/*.*');
		foreach ($baseName as $key => $value) {
			$info = pathinfo($value);
			$configs[$info['filename']] = require($value);
		}
		return $configs;
	}
}
