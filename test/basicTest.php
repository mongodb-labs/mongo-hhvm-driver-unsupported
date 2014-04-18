<?php

class BasicTest extends PHPUnit_Framework_TestCase{

	public function testLoaded() {
		$this->assertEquals(true, extension_loaded("mongo"));
	}
}
