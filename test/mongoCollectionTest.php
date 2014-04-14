<?php

class MongoCollectionTest extends MongoTestCase {

	public function testMongoCollection() {
		printf("Starting %s\n", __FUNCTION__);
		$cli = $this->getTestClient();
		$db = $this->getTestDB();
		$coll_name = "students";
		$coll = $db->__get($coll_name);
		printf("Ending %s\n", __FUNCTION__);
	}
}