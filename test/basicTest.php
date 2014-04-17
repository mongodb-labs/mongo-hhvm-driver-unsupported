<?php

class BasicTest extends PHPUnit_Framework_TestCase{

	public function testLoaded() {
		printf("Running testLoaded\n");
		$this->assertEquals(true, extension_loaded("mongo"));
		printf("Ended testLoaded\n\n");
	}
}
