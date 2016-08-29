<?php
namespace Genie\Collection;
use Doctrine\Common\Inflector\Inflector;
/**
* 
*/
class Str extends Core  implements \ArrayAccess, \IteratorAggregate, \JsonSerializable, \Serializable
{
	protected $var;
	protected $allowed = [
		'length', 'json', 'snake', 'camel', 'plural',
		'singular', 'upper', 'lower'
	];
	
	private static $snakeCache = [];

	public static $extends = [];

	public function __construct($str) {
		$this->var = (string)$str;
	}

	// Custom methods

	public function split() {
		$args = func_get_args();
		$pattern = array_shift($args);
		$callback = null;
		foreach($args as $key => $arg) {
			if(is_callable($arg)) {
				$callback = $arg;
				unset($args[$key]);
			}
		}
		$spliter = $this->isRegex($pattern) ? 'preg_split' : 'explode';
		$ret = call_user_func_array($spliter, array_merge([$pattern, $this->var], $args));
		$arr = [];
		if($callback) {
			foreach($ret as $key => $val) {
				$caller = $callback->bindTo(new static($val));
				$arr[] = $caller($key, new static($val));
			}
			return new Arr($arr);
		}
		return new Arr($ret);
	}

	public function html($element, $attrs = []) {
		$attribute = '';
		foreach($attrs as $attr => $val) {
			$attribute .= " {$attr}=\"{$val}\"";
		}
		return new static("<{$element}{$attribute}>{$this->var}</{$element}>");
	}

	public function fromJson($array = false) {
		if($array) {
			return new Arr(json_decode($this->var, true));
		}
		return json_decode($this->var);
	}
	// Core methods
	public function replace($pattern, $replace) {
		if($this->isRegex($pattern)) {
			$var = preg_replace($pattern, $replace, $this->var);
		} else {
			$var = str_replace($pattern, $replace, $this->replace);
		}
		return new static($var);
	}

	public function length() {
		return strlen($this->var);
	}

	public function equal($str) {
		return $this->var == (string)$str;
	}

	// Doctrine Inflector
	public function camel() {
		return new static(Inflector::camelize($this->var));
	}

	public function ucwords($delimiters = " \n\t\r\0\x0B-") {
		return new static(Inflector::ucwords($this->var, $delimiters));
	}
	public function plural() {
		return new static(Inflector::pluralize($this->var));
	}
	public function singular() {
		return new static(Inflector::singularize($this->var));
	}	
	// End Doctrine Inflector

	// Inspired by JAVASCRIPT

	public function concat() {
		return new static($this->var . implode('', func_get_args()));
	}

	public function concatWs() {
		$args = func_get_args();
		$concater = array_shift($args);
		return new static($this->var . $concater . implode($concater, $args));
	}
	
	public function indexOf($value) {
		return strpos($this->var, $value);
	}

	
	public function fixed() {
		
	}

	// laravel Helper functions
	public function ascii()
	{
		return ord($this->var);
	}

	/**
	 * Determine if a given string contains a given substring.
	 *
	 * @param  string  $haystack
	 * @param  string|array  $needles
	 * @return bool
	 */
	public function contains($needles)
	{
		foreach ((array) $needles as $needle)
		{
			if ($needle != '' && strpos($this->var, $needle) !== false) return true;
		}

		return false;
	}

	/**
	 * Determine if a given string ends with a given substring.
	 *
	 * @param  string  $haystack
	 * @param  string|array  $needles
	 * @return bool
	 */
	public static function endsWith($needles)
	{
		foreach ((array) $needles as $needle)
		{
			if ((string) $needle === substr($this->var, -strlen($needle))) return true;
		}

		return false;
	}

	/**
	 * Cap a string with a single instance of a given value.
	 *
	 * @param  string  $value
	 * @param  string  $cap
	 * @return string
	 */
	public function finish($cap)
	{
		$quoted = preg_quote($cap, '/');

		return new static(preg_replace('/(?:'.$quoted.')+$/', '', $this->var).$cap);
	}

	/**
	 * Determine if a given string matches a given pattern.
	 *
	 * @param  string  $pattern
	 * @param  string  $value
	 * @return bool
	 */
	public function is($pattern)
	{
		$value = $this->var;
		if ($pattern == $value) return true;

		$pattern = preg_quote($pattern, '#');

		// Asterisks are translated into zero-or-more regular expression wildcards
		// to make it convenient to check if the strings starts with the given
		// pattern such as "library/*", making any string check convenient.
		$pattern = str_replace('\*', '.*', $pattern).'\z';

		return (bool) preg_match('#^'.$pattern.'#', $value);
	}

	/**
	 * Limit the number of characters in a string.
	 *
	 * @param  string  $value
	 * @param  int     $limit
	 * @param  string  $end
	 * @return string
	 */
	public static function limit($limit = 100, $end = '...')
	{
		$value = $this->var;
		if (mb_strlen($value) <= $limit) return new static($value);
		return new static(rtrim(mb_substr($value, 0, $limit, 'UTF-8')).$end);
	}

	/**
	 * Convert the given string to lower-case.
	 *
	 * @param  string  $value
	 * @return string
	 */
	public function lower()
	{
		return new static(mb_strtolower($this->var));
	}

	/**
	 * Limit the number of words in a string.
	 *
	 * @param  string  $value
	 * @param  int     $words
	 * @param  string  $end
	 * @return string
	 */
	public static function words($words = 100, $end = '...')
	{
		$value = $this->var;
		preg_match('/^\s*+(?:\S++\s*+){1,'.$words.'}/u', $value, $matches);

		if ( ! isset($matches[0]) || strlen($value) === strlen($matches[0])) return new static($value);

		return new static(rtrim($matches[0]).$end);
	}

	/**
	 * Convert the given string to upper-case.
	 *
	 * @param  string  $value
	 * @return string
	 */
	public function upper()
	{
		return new static(mb_strtoupper($this->var));
	}

	/**
	 * Convert the given string to title case.
	 *
	 * @param  string  $value
	 * @return string
	 */
	public static function title()
	{
		return new static(mb_convert_case($this->var, MB_CASE_TITLE, 'UTF-8'));
	}

	/**
	 * Convert a string to snake case.
	 *
	 * @param  string  $value
	 * @param  string  $delimiter
	 * @return string
	 */
	public function snake($delimiter = '_')
	{
		$value = $this->var;
		$key = $value.$delimiter;

		if (isset(static::$snakeCache[$key]))
		{
			return new static(static::$snakeCache[$key]);
		}

		if ( ! ctype_lower($value))
		{
			$replace = '$1'.$delimiter.'$2';

			$value = strtolower(preg_replace('/(.)([A-Z])/', $replace, $value));
		}
		return new static(static::$snakeCache[$key] = $value);
	}

	/**
	 * Determine if a given string starts with a given substring.
	 *
	 * @param  string  $haystack
	 * @param  string|array  $needles
	 * @return bool
	 */
	public function startsWith($needles)
	{
		foreach ((array) $needles as $needle)
		{
			if ($needle != '' && strpos($this->var, $needle) === 0) return true;
		}

		return false;
	}

	/**
	 * Convert a value to studly caps case.
	 *
	 * @param  string  $value
	 * @return string
	 */
	public function studly()
	{
		$key = $value = $this->var;

		if (isset(static::$studlyCache[$key]))
		{
			return new static(static::$studlyCache[$key]);
		}

		$value = ucwords(str_replace(array('-', '_'), ' ', $value));

		return new static(static::$studlyCache[$key] = str_replace(' ', '', $value));
	}

	// Magic methods
	
	public function __toString() {
		return (string)$this->var;
	}

	public function __isset($prop) {
		if(isset($this->allowed[$prop])) {
			return true;
		}

		return false;
	}

	public function jsonSerialize() {
		return $this->var;
	}

	public function serialize() {
		return serialize($this->var);
	}

	public function unserialize($str) {
		return unserialize($this->var);
	}
	// Array 
	public function getIterator() {
		return new ArrayIterator($this->var);
	}

	public function offsetGet($key) {
		return $this->var[$key];
	}

	public function offsetSet($key, $val) {
		$this->var[$key] = $val;
	}

	public function offsetExists($key) {
		return isset($this->var[$key]);
	}

	public function offsetUnset($key) {
		unset($this->var[$key]);
	}

	public function __debugInfo() {
		return ['string' => $this->var, 'length' => strlen($this->var)];
	}

	public function __call($fn, $args) {
		if(isset(self::$extends[$fn])) {
			return $this->parse(call_user_func_array(self::$extends[$fn], $args));
		}
		if(function_exists($fn)) {
			$args[] = $this->var;
			return $this->parse(call_user_func_array($fn, $args));
		}
		throw new Exception\UndefinedException("Call to undefined function", 1);
	}
}
