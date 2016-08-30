<?php
use Genie\Collection\Arr;
use Genie\Config\Config;
/**
* 
*/
class ConfigTest extends \PHPUnit_Framework_TestCase
{
	protected $config;
	public function setUp() {
		$this->config = new Config(__DIR__ . '/configs');
	}
	public function testLoad() {
		$this->assertInstanceOf(Arr::class, $this->config);
		$this->assertTrue($this->config->get('app.debug'));
		$this->assertTrue($this->config->get('app.session.storage')->length == 4);
	}

	public function testCamelCase() {
		$this->assertEquals((string)$this->config->get('app.session.table')->studly, 'EdunutsSession');
	}
}
