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
	public function toJson() {
		return new Str(json_encode($this->var));
	}
	
	public function toArray() {
		return $this->var;
	}

	public function pop() {
		return $this->parse(array_pop($this->var));
	}

	public function push($var) {
		array_push($this->var, $var);
		return $this->parse($var);
	}

	public function rand() {
		$array = $this->var;
		return new static(array_rand($array));
	}

	public function indexOf($index) {
		return array_search($index, $this->var);
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
	 * Get all of the given array except for a specified array of items.
	 *
	 * @param  array  $array
	 * @param  array|string  $keys
	 * @return array
	 */
	public function except(array $keys)
	{
		return new static(array_diff_key($this->var, array_flip((array) $keys)));
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

		return $this->parse($default);
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
		$array = &$this->var;
		if (is_null($key)) {
            return $array = $value;
        }

        $keys = explode('.', $key);
        while (count($keys) > 1) {

            $key = array_shift($keys);

            // If the key doesn't exist at this depth, we will just create an empty array
            // to hold the next value, allowing us to create the arrays to hold final
            // values at the correct depth. Then we'll keep digging into the array.
            if (! isset($array[$key]) || ! is_array($array[$key])) {
                $array[$key] = [];
            }

            $array = &$array[$key];
        }
        $array[array_shift($keys)] = $value;
        
        return new static($this->var);
	}

	/**
	 * Sort the array using the given Closure.
	 *
	 * @param  array     $array
	 * @param  \Closure  $callback
	 * @return array
	 */
	public function sort(Closure $callback)
	{
		$array = $this->var;
		return new static(uasort($array, $callback));
	}

	// Magic Methods
	public function __toString() {
		return json_encode($this->var);
	}
	
	public function serialize() {
		return serialize($this->var);
	}

	public function unserialize($serialized) {
		return unserialize($this->var);
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
		return new Iterator($this->var);
	}

	public function __call($fn, $args) {
		if(isset(self::$extends[$fn])) {
			return $this->parse(call_user_func_array(self::$extends[$fn], $args));
		}
		if(function_exists('array_' . $fn)) {
			$args[] = $this->var;
			return $this->parse(call_user_func_array('array_' . $fn, $args));
		}
		throw new Exception\UndefinedException("Call to undefined function {$fn}()", 404);
	}
	// End Magic Methods
}
