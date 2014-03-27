<?php

class MongoClientTest extends MongoTestCase {

	public function testMongoClient() {
		printf("Running testMongoClient\n");
		$cli = $this->getTestClient();
		var_dump((string) $cli);
		printf("Ended testMongoClient\n\n");
	}
} 	