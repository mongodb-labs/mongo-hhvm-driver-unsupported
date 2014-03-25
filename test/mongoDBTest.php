<?php

class MongoDBTest extends PHPUnit_Framework_TestCase{

	public function testMongoDB() {
		printf("Running testMongoDB\n");
		$cli = new MongoClient();
		$database_name = "Fake database";
		$db = new MongoDB($cli, $database_name);
		printf("Ended testMongoDB\n\n");
	}
}