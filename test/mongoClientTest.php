<?php

class MongoClientTest extends MongoTestCase {

	public function testMongoClient() {
		$cli = $this->getTestClient();
		//var_dump((string) $cli);
		//var_dump($cli->listDBs());
	}
} 	