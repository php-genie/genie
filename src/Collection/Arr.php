<?php
namespace Genie\Collection;
/**
* 
*/
class Arr extends Core implements \IteratorAggregate, \ArrayAccess, \Serializable, \Countable, \JsonSerializable
{
	protected $var;
	protected $allowed = ['json', 'length'];

	private $key;

	function __construct(array $array) {
		$this->var = $array;
	}

	// Custom methods

	public function join($delimeter) {
		return new Str(implode($delimeter, $this->var));
	}

	public function length() {
		return count($this->var);
	}

	public function each(\Closure $callback) {
		foreach($this->var as $key => $value) {
			if(is_array($value)) {
				$value = new Arr($value);
				$callback = $callback->bindTo($value);
			}
			if(is_string($value)) {
				$value = new Str($value);
				$callback = $callback->bindTo($value);
			}
			call_user_func($callback, is_int($key) ? $key : new Str($key), $value);
		}
		return $this;
	}

	public function map(\Closure $callback) {
		$mapped = new static([]);
		$callback = $callback->bindTo($mapped);
		foreach($this->var as $key => $value) {
			$key = is_int($key) ? $key : new Str($key);
			if(is_array($value)) {
				$value = new Arr($value);
			} elseif(is_string($value)) {
				$value = new Str($value);
			}
			$output = call_user_func($callback, $key, $value, $mapped);
		}
		return $mapped;
	}

	// Core functions
	public function json() {
		return new Str(json_encode($this->var));
	}
	
	public function change_case() {

	}

	public function chunk() {

	}

	public function column() {

	}

	public function combine() {

	}
	public function count_values() {

	}

	public function diff() {

	}

	public function diff_assoc() {

	}

	public function diff_key() {

	}

	public function diff_uassoc() {

	}

	public function diff_ukey() {

	}

	public function fill() {

	}
	public function fill_keys() {

	}

	public function filter() {

	}

	public function flip() {

	}

	public function intersect() {

	}

	public function intersect_assoc() {

	}

	public function intersect_key() {

	}

	public function intersect_uassoc() {

	}

	public function intersect_ukey() {

	}

	public function key_exists() {

	}
	
	public function keys() {

	}

	public function merge() {

	}
	public function merge_recursive() {

	}
	public function multisort() {

	}
	public function pad() {

	}
	public function pop() {

	}
	public function product() {

	}
	public function push() {

	}
	public function rand() {

	}
	public function reduce() {

	}
	public function replace() {

	}
	public function replace_recursive() {

	}
	public function reverse() {

	}
	public function search() {

	}
	public function shift() {

	}
	public function slice() {

	}
	public function splice() {

	}
	public function sum() {

	}
	public function udiff() {

	}
	public function udiff_assoc() {

	}
	public function udiff_uassoc() {

	}
	public function uintersect() {

	}
	public function uintersect_assoc() {

	}
	public function uintersect_uassoc() {

	}
	public function unique() {

	}
	public function unshift() {

	}
	public function values() {

	}
	public function walk() {

	}
	public function walk_recursive() {

	}
	public function arsort() {

	}
	public function asort() {

	}
	public function compact() {

	}

	
	public function end() {

	}
	public function extract() {

	}
	public function in_array() {

	}
	
	public function krsort() {

	}
	public function ksort() {

	}
	public function lists() {

	}
	public function natcasesort() {

	}
	public function natsort() {

	}
	
	public function pos() {

	}
	public function prev() {

	}
	public function range() {

	}
	public function reset() {

	}
	public function rsort() {

	}
	public function shuffle() {

	}
	public function sizeof() {

	}

	public function uasort() {

	}
	public function uksort() {

	}
	public function usort() {

	}

	// Inspired by JAVASCRIPT
	public function concat() {
		
	}
	public function indexOf() {
		
	}
	
	public function lastIndexOf() {
		
	}

	public function valueOf() {
		
	}

	// laravel helper functions

	/**
	 * Add an element to an array using "dot" notation if it doesn't exist.
	 *
	 * @param  array   $array
	 * @param  string  $key
	 * @param  mixed   $value
	 * @return array
	 */
	public function add($key, $value)
	{
		$array = $this->var;
		if (is_null($this->get($key)))
		{
			$this->set($key, $value);
		}

		return new static($array);
	}

	/**
	 * Divide an array into two arrays. One with keys and the other with values.
	 *
	 * @param  array  $array
	 * @return array
	 */
	public function divide()
	{
		return new static(array(array_keys($this->var), array_values($this->var)));
	}

	/**
	 * Flatten a multi-dimensional associative array with dots.
	 *
	 * @param  array   $array
	 * @param  string  $prepend
	 * @return array
	 */
	public function dot($prepend = '')
	{
		$array = $this->var;
		$results = array();

		foreach ($array as $key => $value)
		{
			if (is_array($value))
			{
				$results = array_merge($results, $this->dot($prepend.$key.'.'));
			}
			else
			{
				$results[$prepend.$key] = $value;
			}
		}

		return new static($results);
	}

	/**
	 * Get all of the given array except for a specified array of items.
	 *
	 * @param  array  $array
	 * @param  array|string  $keys
	 * @return array
	 */
	public function except($keys)
	{
		return new static(array_diff_key($this->var, array_flip((array) $keys)));
	}

	/**
	 * Fetch a flattened array of a nested array element.
	 *
	 * @param  array   $array
	 * @param  string  $key
	 * @return array
	 */
	public function fetch($key)
	{
		$array = $this->var;
		foreach (explode('.', $key) as $segment)
		{
			$results = array();

			foreach ($array as $value)
			{
				if (array_key_exists($segment, $value = (array) $value))
				{
					$results[] = $value[$segment];
				}
			}

			$array = array_values($results);
		}

		return new static(array_values($results));
	}

	/**
	 * Return the first element in an array passing a given truth test.
	 *
	 * @param  array     $array
	 * @param  \Closure  $callback
	 * @param  mixed     $default
	 * @return mixed
	 */
	public function first($callback, $default = null)
	{
		$array = $this->var;
		foreach ($array as $key => $value)
		{
			if (call_user_func($callback, $key, $value)) return $this->parse($value);
		}

		return $default;
	}

	/**
	 * Return the last element in an array passing a given truth test.
	 *
	 * @param  array     $array
	 * @param  \Closure  $callback
	 * @param  mixed     $default
	 * @return mixed
	 */
	public function last($array, $callback, $default = null)
	{
		return static::first(array_reverse($array), $callback, $default);
	}

	/**
	 * Flatten a multi-dimensional array into a single level.
	 *
	 * @param  array  $array
	 * @return array
	 */
	public function flatten($array)
	{
		$return = array();

		array_walk_recursive($array, function($x) use (&$return) { $return[] = $x; });

		return $return;
	}

	/**
	 * Remove one or many array items from a given array using "dot" notation.
	 *
	 * @param  array  $array
	 * @param  array|string  $keys
	 * @return void
	 */
	public function forget(&$array, $keys)
	{
		$original =& $array;

		foreach ((array) $keys as $key)
		{
			$parts = explode('.', $key);

			while (count($parts) > 1)
			{
				$part = array_shift($parts);

				if (isset($array[$part]) && is_array($array[$part]))
				{
					$array =& $array[$part];
				}
			}

			unset($array[array_shift($parts)]);

			// clean up after each pass
			$array =& $original;
		}
	}

	/**
	 * Get an item from an array using "dot" notation.
	 *
	 * @param  array   $array
	 * @param  string  $key
	 * @param  mixed   $default
	 * @return mixed
	 */
	public function get($key, $default = null, $seprator = '.')
	{
		$array = $this->var;
		if (is_null($key)) return new static($array);

		if (isset($array[$key])) return $this->parse($array[$key]);

		foreach (explode($seprator, $key) as $segment)
		{
			if ( ! is_array($array) || ! array_key_exists($segment, $array))
			{
				return $this->parse($default);
			}

			$array = $array[$segment];
		}
		return $this->parse($array);
	}

	/**
	 * Check if an item exists in an array using "dot" notation.
	 *
	 * @param  array   $array
	 * @param  string  $key
	 * @return bool
	 */
	public function has($key, $seprator = '.')
	{
		$array = $this->var;
		if (empty($array) || is_null($key)) return false;

		if (array_key_exists($key, $array)) return true;

		foreach (explode($seprator, $key) as $segment)
		{
			if ( ! is_array($array) || ! array_key_exists($segment, $array))
			{
				return false;
			}

			$array = $array[$segment];
		}

		return true;
	}

	/**
	 * Get a subset of the items from the given array.
	 *
	 * @param  array  $array
	 * @param  array|string  $keys
	 * @return array
	 */
	public function only($keys)
	{
		return new static(array_intersect_key($this->var, array_flip((array) $keys)));
	}

	/**
	 * Pluck an array of values from an array.
	 *
	 * @param  array   $array
	 * @param  string  $value
	 * @param  string  $key
	 * @return array
	 */
	public function pluck($value, $key = null)
	{
		$array = $this->var;
		$results = array();

		foreach ($array as $item)
		{
			$itemValue = is_object($item) ? $item->{$value} : $item[$value];

			// If the key is "null", we will just append the value to the array and keep
			// looping. Otherwise we will key the array using the value of the key we
			// received from the developer. Then we'll return the final array form.
			if (is_null($key))
			{
				$results[] = $itemValue;
			}
			else
			{
				$itemKey = is_object($item) ? $item->{$key} : $item[$key];

				$results[$itemKey] = $itemValue;
			}
		}

		return new static($results);
	}

	/**
	 * Get a value from the array, and remove it.
	 *
	 * @param  array   $array
	 * @param  string  $key
	 * @param  mixed   $default
	 * @return mixed
	 */
	public function pull($key, $default = null, $seprator = '.')
	{
		$value = $this->get($key, $default, $seprator);
		$this->forget($key, $seprator);
		return $this->parse($value);
	}

	/**
	 * Set an array item to a given value using "dot" notation.
	 *
	 * If no key is given to the method, the entire array will be replaced.
	 *
	 * @param  array   $array
	 * @param  string  $key
	 * @param  mixed   $value
	 * @return array
	 */
	public function set($key, $value)
	{
		if (is_null($key)) return $this->var = $value;

		$keys = explode('.', $key);

		while (count($keys) > 1)
		{
			$key = array_shift($keys);

			// If the key doesn't exist at this depth, we will just create an empty array
			// to hold the next value, allowing us to create the arrays to hold final
			// values at the correct depth. Then we'll keep digging into the array.
			if ( ! isset($this->var[$key]) || ! is_array($this->var[$key]))
			{
				$this->var[$key] = array();
			}

			$this->var = $this->var[$key];
		}

		$array[array_shift($keys)] = $value;

		return $this;
	}

	/**
	 * Sort the array using the given Closure.
	 *
	 * @param  array     $array
	 * @param  \Closure  $callback
	 * @return array
	 */
	public function sort($array, Closure $callback)
	{
		return Collection::make($array)->sortBy($callback)->all();
	}

	// Magic Methods

	public function __toString() {
		return new Str(json_encode($this->var));
	}
	
	public function serialize() {
		
	}

	public function unserialize($serialized) {

	}
	public function offsetGet($key) {
		return $this->parse($this->var[$key]);
	}

	public function offsetSet($key, $val) {
		if($key) {
			$this->var[$key] = $val;
		} else {
			$this->var[] = $val;
		}
		return $this;
	}

	public function offsetUnset($key) {
		unset($this->var[$key]);
	}

	public function offsetExists($key) {
		return isset($this->var[$key]);
	}

	public function count() {
		return count($this->var);
	}

	public function jsonSerialize() {
		return $this->var;
	}

	public function __debugInfo() {
		return $this->var;
	}

	public function getIterator() {
		return new Iterator(new static($this->var));
	}

	public function __call($fn, $args) {
		if(isset(self::$extends[$fn])) {
			return $this->parse(call_user_func_array(self::$extends[$fn], $args));
		}
		if(function_exists('array_' . $fn)) {
			$args[] = $this->var;
			return $this->parse(call_user_func_array('array_' . $fn, $args));
		}
		throw new PQueryException("Call to undefined function {$fn}()", 404);
	}
	// End Magic Methods
}
