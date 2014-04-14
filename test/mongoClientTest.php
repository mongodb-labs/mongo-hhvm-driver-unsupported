<?php

class MongoClientTest extends MongoTestCase {

	public function testMongoClient() {
		printf("Starting %s\n", __FUNCTION__);
		$cli = $this->getTestClient();
		var_dump((string) $cli);
		//var_dump($cli->listDBs());
		printf("Ending %s\n", __FUNCTION__);
	}
} 	