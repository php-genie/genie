<?php
use Genie\Collection\Arr;
use Genie\Collection\Str;
/**
* 
*/
class StrTest extends \PHPUnit_Framework_TestCase {


	function testSplit() {
		$str = new Str("Hello World this is Adil");
		$this->assertInstanceOf(Arr::class, $str->split(' '));
		$this->assertEquals($str->split(' '), new Arr(explode(' ', "Hello World this is Adil")));
	}

	public function testLength() {
		$str = new Str("Hello World this is Adil");
		$this->assertEquals($str->length, 24);
	}
}