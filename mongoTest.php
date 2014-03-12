<?php
var_dump(extension_loaded("mongo"));

class TestSuite {

	public function MongoDateTest() {
		printf("Running MongoDateTest\n");
		$date = new MongoDate();
		var_dump((string) $date);
	}

	public function MongoClientTest() {
		printf("Running MongoClientTest\n");
		$cli = new MongoClient();
		var_dump((string) $cli);
	}

	public function MongoCursorTest() {
		printf("Running MongoCursorTest\n");
		$cursor = new MongoCursor();
		var_dump($cursor->batchSize(5));
	}
}

$test = new TestSuite();
$test->MongoDateTest();

