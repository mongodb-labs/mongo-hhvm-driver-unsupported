<?php

class MongoCollectionTest extends PHPUnit_Framework_TestCase{

	public function testMongoCollection() {
		printf("Running testMongoCollection\n");
		$cli = new MongoClient();
		$database_name = "Fake database";
		$db = new MongoDB($cli, $database_name);
		$coll_name = "Fake collection";
		$coll = new MongoCollection($db, $coll_name);
		printf("Ended testMongoCollection\n\n");
	}
}