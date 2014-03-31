<?php

class MongoDBTest extends MongoTestCase {

	public function testMongoDB() {
		printf("Running testMongoDB\n");
		$cli = new MongoClient();
		$database_name = "Fake database";
		$db = new MongoDB($cli, $database_name);
		var_dump($db->setSlaveOkay());
		var_dump($db->getSlaveOkay());
		printf("Ended testMongoDB\n\n");
	}
}