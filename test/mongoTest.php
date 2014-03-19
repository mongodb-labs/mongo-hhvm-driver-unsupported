<?php
var_dump(extension_loaded("mongo"));

class CoreClassesBasicTest {

	public function MongoDateTest() {
		printf("Running MongoDateTest\n");
		$date = new MongoDate();
		var_dump((string) $date);
		printf("Ended MongoDateTest\n\n");
	}

	public function MongoClientTest() {
		printf("Running MongoClientTest\n");
		$cli = new MongoClient();
		var_dump((string) $cli);
		printf("Ended MongoClientTest\n\n");
	}

	public function MongoCursorTest() {
		printf("Running MongoCursorTest\n");
		$cli = new MongoClient();
		$database_name = "Fake database";
		$cursor = new MongoCursor($cli, $database_name);
		//var_dump($cursor->batchSize(5));
		printf("Ended MongoCursorTest\n\n");
	}

	public function MongoDBTest() {
		printf("Running MongoDBTest\n");
		$cli = new MongoClient();
		$database_name = "Fake database";
		$db = new MongoDB($cli, $database_name);
		printf("Ended MongoDBTest\n\n");
	}

	public function MongoCollectionTest() {
		printf("Running MongoCollectionTest\n");
		$cli = new MongoClient();
		$database_name = "Fake database";
		$db = new MongoDB($cli, $database_name);
		$coll_name = "Fake collection";
		$coll = new MongoCollection($db, $coll_name);
		printf("Ended MongoCollectionTest\n\n");
	}
}

$test = new CoreClassesBasicTest();
$test->MongoDateTest();
$test->MongoClientTest();
$test->MongoCursorTest();
$test->MongoDBTest();
$test->MongoCollectionTest();