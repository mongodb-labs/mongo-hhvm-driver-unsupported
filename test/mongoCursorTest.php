<?php

class MongoCursorTest extends MongoTestCase {
	
	public function testConstructCursor() {
		printf("Starting %s\n", __FUNCTION__);
		$cli = new MongoClient();
		$database_name = "test.students";
		$cursor = new MongoCursor(	$cli, 
									$database_name, 
									array("test_field" => "1"),
                                  	array()	);
		var_dump($cursor->info());
		//$this->assertEquals(true, $cursor);
		//var_dump($cursor->batchSize(5));
		printf("Ending %s\n\n", __FUNCTION__);
	}

	/*public function testCurrent() {
		printf("Starting %s\n", __FUNCTION__);
		$cli = new MongoClient();
		$db = "test.test_collection";
		$cursor = new MongoCursor($cli, $db);
		$output = shell_exec("mongo test js/testCursor.js");
		printf($output);
		printf("Ending %s\n\n", __FUNCTION__);
	}*/
}