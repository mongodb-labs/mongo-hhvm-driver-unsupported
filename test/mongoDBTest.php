<?php

class MongoDBTest extends MongoTestCase {

	public function testMongoDB() {
		printf("Starting %s\n", __FUNCTION__);
		$cli = new MongoClient();
		$database_name = "Fake database";
		$db = new MongoDB($cli, $database_name);
		var_dump($db->setSlaveOkay());
		var_dump($db->getSlaveOkay());
		printf("Ending %s\n", __FUNCTION__);
	}
}