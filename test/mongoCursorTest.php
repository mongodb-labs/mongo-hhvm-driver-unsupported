<?php

class MongoCursorTest extends MongoTestCase {
	
	public function testConstructCursor() {
		printf("Starting %s\n", __FUNCTION__);
		$cli = new MongoClient();
		$database_name = "test.students";
		$cursor = new MongoCursor(	$cli, 
									$database_name, 
									array("name" => "Bob"),
                                  	array()	);
		//var_dump($cursor->info());
		//$this->assertEquals(true, $cursor);
		//var_dump($cursor->batchSize(5));
		
		$cursor->rewind();

		//Expect to have an exception since the cursor has started iterating.
		try {
		    $cursor->addOption("", "");
		} catch (Exception $e) {
		    echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
		
		while ($cursor->valid())
		{
		    //$key = $cursor->key();   //This should be tested after BSON-PHP decoder is finished
		    //var_dump($key);
		    $value = $cursor->current();
		    var_dump($value);
		    $cursor->next();
		}
		
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