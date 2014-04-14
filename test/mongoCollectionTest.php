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
		printf("Starting %s\n", __FUNCTION__);
		$cli = $this->getTestClient();
		$db = $this->getTestDB();
		$coll_name = "students";
		$coll = $db->__get($coll_name);

		// Testing toIndexString
		$c = new MongoCollectionStub();
		var_dump($c->createIndexString("foo"));
		var_dump($c->createIndexString(array('name' => 1, 'age' => -1, 'bool' => false)));

		printf("Ending %s\n", __FUNCTION__);
	}
}