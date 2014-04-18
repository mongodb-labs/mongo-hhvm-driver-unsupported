<?php

class MongoDBTest extends MongoTestCase {

	public function testMongoDB() {
		$cli = new MongoClient();
		$database_name = "Fake database";
		$db = new MongoDB($cli, $database_name);
	}
}