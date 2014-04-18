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
		$cli = $this->getTestClient();
		$db = $this->getTestDB();
		$coll_name = "students";
		$coll = $db->__get($coll_name);

		// Testing toIndexString
		$c = new MongoCollectionStub();
		$actual = $c->createIndexString("foo");
		$expected = "foo_1";
		$this->assertEquals($expected, $actual);

		$actual = $c->createIndexString(array('name' => 1, 'age' => -1, 'bool' => false));
		$expected = "name_1_age_-1_bool_1";
		$this->assertEquals($expected, $actual);
	}
}