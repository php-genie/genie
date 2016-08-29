<?php
namespace Genie\Controller;
/**
* @method /
*/
class DocParser
{
	protected $doc;
	
	public function __construct(string $doc) {
		$this->doc = $this->parse($doc);
	}

	protected function parse($doc) {
		$array = [];
		foreach (explode(PHP_EOL, $doc) as $line) {
			preg_match('/\@(\w+)(\s+)?(.*)?/', $line, $matches);
			if($matches) {
				$array[$matches[1]][] = $matches[3];
			}
		}
		return $array;
	}

	public function getMiddlewares() {
		if(isset($this->doc['middleware'])) {
			return $this->doc['middleware'];
		}
		return null;
	}

	public function getMethods() {
		if(isset($this->doc['method'])) {
			return array_pop($this->doc['method']);
		}
		return null;	
	}

	public function getRoute() {
		if(isset($this->doc['route'])) {
			return array_pop($this->doc['route']);
		}
	}

}
