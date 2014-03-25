<?php

class MongoCursorTest extends PHPUnit_Framework_TestCase{
	
	public function testMongoCursor() {
		printf("Running testMongoCursor\n");
		$cli = new MongoClient();
		$database_name = "Fake database";
		$cursor = new MongoCursor($cli, $database_name);
		//var_dump($cursor->batchSize(5));
		printf("Ended testMongoCursor\n\n");
	}
}