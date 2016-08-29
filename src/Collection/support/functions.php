<?php

use Genie\Collection\Arr;
use Genie\Collection\Str;

function str($str) {
	return new Str($str);
}

function arr($arr) {
	return new Arr($arr);
}

function regex($str) {
	return new Regex($str);
}
