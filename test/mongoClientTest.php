<?php

class MongoClientTest extends PHPUnit_Framework_TestCase{

	public function testMongoClient() {
		printf("Running testMongoClient\n");
		$cli = new MongoClient();
		var_dump((string) $cli);
		printf("Ended testMongoClient\n\n");
	}
}