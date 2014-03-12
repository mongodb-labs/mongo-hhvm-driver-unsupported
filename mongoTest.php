<?php
var_dump(extension_loaded("mongo"));

class TestSuite {

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
}

$test = new TestSuite();
$test->MongoDateTest();
$test->MongoClientTest();
$test->MongoCursorTest();