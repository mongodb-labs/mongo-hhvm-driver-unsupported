<?php

class MongoCursorTest extends MongoTestCase {
	
	public function testConstructCursor() {
		$cli = new MongoClient();
		$database_name = "test.students";
		$cursor = new MongoCursor(	$cli, 
									$database_name, 
									array("name" => "Bob"),
                                  	array()	);
		//var_dump($cursor->current());
		//var_dump($cursor->info());
		//$this->assertEquals(true, $cursor);
		//var_dump($cursor->batchSize(5));
		
		$cursor->rewind();

		//Expect to have an exception since the cursor has started iterating.
		$this->setExpectedException('MongoCursorException');
		$cursor->addOption("", "");
		
		while ($cursor->valid())
		{
		    //$key = $cursor->key();   //This should be tested after BSON-PHP decoder is finished
		    //var_dump($key);
		    $value = $cursor->current();
		    //var_dump($value);
		    $cursor->next();
		}
		
	}

}