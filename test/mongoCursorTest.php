<?php

class MongoCursorTest extends MongoTestCase {
	
	public function testConstructCursor() {
		$cli = new MongoClient();
		$database_name = "test.students";
		$cursor = new MongoCursor(	$cli, 
									$database_name);
		
		$cursor->rewind();
		while ($cursor->valid())
		{
		    //$key = $cursor->key();   //This should be tested after BSON-PHP decoder is finished
		    //var_dump($key);
		    $value = $cursor->current();
		    //var_dump($value);
		    $cursor->next();
		}
	}

	public function testStartedIterating() {
		$cli = $this->getTestClient();
		$database_name = "test.students";
		$cursor = new MongoCursor(	$cli, 
									$database_name, 
									array("name" => "Bob"),
                                  	array()	);

		$cursor->rewind();

		/* TODO: add assertEquals to ensure that the document retrieved is what we want
		while ($cursor->valid())
		{
		    //$key = $cursor->key();   //This should be tested after BSON-PHP decoder is finished
		    //var_dump($key);
		    $value = $cursor->current();
		    var_dump($value);
		    $cursor->next();
		}
		*/

		//Expect to have an exception since the cursor has started iterating.
		$this->setExpectedException('MongoCursorException');
		$cursor->addOption("", "");
	}

}