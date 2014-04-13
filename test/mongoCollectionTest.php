<?php

// To test protected methods
class MongoCollectionStub extends MongoCollection {

	public function __construct() {}

	public function createIndexString($keys) {
		return $this->toIndexString($keys);
	}
}

class MongoCollectionTest extends MongoTestCase {

	public function testMongoCollection() {
		printf("Running testMongoCollection\n");
		$cli = new MongoClient();
		$database_name = "Fake database";
		$db = new MongoDB($cli, $database_name);
		$coll_name = "Fake collection";
		$coll = new MongoCollection($db, $coll_name);

		// Testing toIndexString
		$c = new MongoCollectionStub();
		var_dump($c->createIndexString("foo"));
		var_dump($c->createIndexString(array('name' => 1, 'age' => -1, 'bool' => false)));

		printf("Ended testMongoCollection\n\n");
	}
}