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
		return new static("<{$element}{$attribute}>$this->var</{$element}>");
	}

	public function json($array = false) {
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
	
	
	public function camelCase() {
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

	// Regex functions
	public function filter() {

	}

	public function grep() {

	}

	public function preg_last_error() {

	}
	
	public function preg_match_all($pattern, $subject, $matches) {

	}
	
	public function match($pattern, $subject) {

	}
	
	public function quote($str) {

	}
	
	public function preg_replace_callback($pattern, $callback, $subject) {
		
	}
	
	public function preg_replace_callback_array($pattern, $callback, $subject) {

	}
	
	public function callback() {

	}
	
	public function split($pattern, $subject) {

	}

	/*
	public function addslashes($char = null) {
		$this->var = addcslashes($this->var, $char);
		return $this;
	}

	public function bin2hex() {

	}

	public function chop() {

	}

	public function chr() {

	}
	public function chunk_split() {

	}
	public function convert_cyr_string() {

	}
	public function convert_uudecode() {

	}
	public function convert_uuencode() {

	}
	public function count_chars() {

	}
	public function crc32() {

	}
	public function crypt() {

	}

	public function fprintf() {

	}

	public function get_html_translation_table() {

	}

	public function hebrev() {

	}
	public function hebrevc() {

	}
	public function hex2bin() {

	}
	public function html_entity_decode() {

	}
	public function htmlentities() {

	}
	public function htmlspecialchars_decode() {

	}
	public function htmlspecialchars() {

	}

	public function lcfirst() {

	}
	public function levenshtein() {

	}
	public function localeconv() {

	}
	public function ltrim() {

	}
	
	
	public function metaphone() {

	}
	public function money_format() {

	}
	public function nl_langinfo() {

	}
	public function nl2br() {

	}
	public function number_format() {

	}
	public function ord() {

	}
	public function parse_str() {

	}
	public function printf() {

	}
	public function quoted_printable_decode() {

	}
	public function quoted_printable_encode() {

	}
	public function quotemeta() {

	}
	public function rtrim() {

	}
	public function setlocale() {

	}
	public function sha1() {

	}
	public function sha1_file() {

	}
	public function similar_text() {

	}
	public function soundex() {

	}
	public function sprintf() {

	}
	public function sscanf() {

	}
	public function str_getcsv() {

	}
	public function str_ireplace() {

	}
	public function str_pad() {

	}
	public function str_repeat() {

	}
	public function str_replace() {

	}
	public function str_rot13() {

	}
	public function str_shuffle() {

	}
	public function str_split() {

	}
	public function str_word_count() {

	}
	public function strcasecmp() {

	}
	public function strchr() {

	}
	public function strcmp() {

	}
	public function strcoll() {

	}
	public function strcspn() {

	}
	public function strip_tags() {

	}
	public function stripcslashes() {

	}
	public function stripslashes() {

	}
	public function stripos() {

	}
	public function stristr() {

	}
	
	public function strnatcasecmp() {

	}
	public function strnatcmp() {

	}
	public function strncasecmp() {

	}
	public function strncmp() {

	}
	public function strpbrk() {

	}
	public function strpos() {

	}
	public function strrchr() {

	}
	public function strrev() {

	}
	public function strripos() {

	}
	public function strrpos() {

	}
	public function strspn() {

	}
	public function strstr() {

	}
	public function strtok() {

	}
	public function strtolower() {

	}
	public function strtoupper() {

	}
	public function strtr() {

	}
	public function substr() {

	}
	public function substr_compare() {

	}
	public function substr_count() {

	}
	public function substr_replace() {

	}
	public function trim() {
		$this->var = trim($this->var);
		return $this;
	}

	public function ucfirst() {
		$this->var = ucfirst($this->var);
		return $this;
	}

	
	public function vfprintf() {

	}
	public function vprintf() {

	}
	public function vsprintf() {

	}
	public function wordwrap() {

	}
	*/

	// Inspired by JAVASCRIPT

	public function concat() {
		return new static($this->var . implode('', func_get_args()));
	}

	public function concatWs() {
		$args = func_get_args();
		$concater = array_shift($args);
		return new static($this->var . $concater . implode($concater, $args));
	}
	
	public function indexOf() {
		
	}

	public function lastIndexOf() {
		
	}

	public function search() {
		
	}

	public function slice() {
		
	}

	public function substring() {
		
	}

	public function toLocaleLowerCase() {
		
	}

	public function toLocaleUpperCase() {
		
	}

	public function toLowerCase() {
		
	}
	
	public function toUpperCase() {
		
	}

	public function valueOf() {
		
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
	public function snakeCase($delimiter = '_')
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
	public function studlyCase()
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

	}

	public function unserialize($str) {

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

	public function get() {
		return self::$extends;
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
		throw new UndefinedFunctionException("Call to undefined function", 1);
	}
}
