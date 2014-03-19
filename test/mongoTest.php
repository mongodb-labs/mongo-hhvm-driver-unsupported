<?php

class CoreClassesBasicTest extends PHPUnit_Framework_TestCase{

	public function testLoaded() {
		printf("Running testLoaded\n");
		$this->assertEquals(true, extension_loaded("mongo"));
		printf("Ended testLoaded\n\n");
	}

	public function testMongoDate() {
		printf("Running testMongoDate\n");
		$date = new MongoDate();
		var_dump((string) $date);
		printf("Ended testMongoDate\n\n");
	}

	public function testMongoClient() {
		printf("Running testMongoClient\n");
		$cli = new MongoClient();
		var_dump((string) $cli);
		printf("Ended testMongoClient\n\n");
	}
	
	public function testMongoCursor() {
		printf("Running testMongoCursor\n");
		$cli = new MongoClient();
		$database_name = "Fake database";
		$cursor = new MongoCursor($cli, $database_name);
		//var_dump($cursor->batchSize(5));
		printf("Ended testMongoCursor\n\n");
	}

	public function testMongoDB() {
		printf("Running testMongoDB\n");
		$cli = new MongoClient();
		$database_name = "Fake database";
		$db = new MongoDB($cli, $database_name);
		printf("Ended testMongoDB\n\n");
	}

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