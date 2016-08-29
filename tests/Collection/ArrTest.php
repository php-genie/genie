<?php
use Genie\Collection\Arr;
use Genie\Collection\Str;
/**
* 
*/
class ArrTest extends \PHPUnit_Framework_TestCase
{
	public function testJoin() {
		$arr = new Arr(['Hello', 'World']);
		$this->assertEquals($arr->join(' '), 'Hello World');
	}

	public function testLength() {
		$arr = new Arr(['Hello', 'World']);
		$this->assertEquals($arr->length, 2);
	}

	public function testToJson() {
		$arr = ['Hello', 'World'];
		$arrObj = new Arr($arr);

		$this->assertEquals($arrObj->toJson(), json_encode($arr));
	}

	public function testToArray() {
		$arr = ['Hello', 'World'];
		$arrObj = new Arr($arr);
		$this->assertTrue($arrObj->toArray() === $arr);
	}

	public function testIndexOf() {
		$arr = new Arr(['Hello', 'World']);
		$this->assertSame($arr->indexOf('Hello w'), false);
	}

	public function testIterable() {
		$arr = new Arr(['Hello', 'World']);
		foreach($arr as $key => $val) {
			$this->assertInstanceOf(Str::class, $val);
		}
	}

	public function testSet() {
		$arr = new Arr([]);
		$newArr = $arr->set('name.firstname', 'adil');
		$this->assertEquals($arr->toArray(), ['name' => ['firstname' => 'adil']]);
	}

	public function testGet() {
		$arr = new Arr([]);
		$arr->set('name.firstname', 'adil');
		$this->assertEquals($arr->get('name.firstname'), 'adil');
	}

	public function testAdd() {
		$arr = new Arr([]);
		$newArr = $arr->add('name.firstname', 'adil');
		$this->assertEquals($arr->toArray(), ['name' => ['firstname' => 'adil']]);
	}
}
