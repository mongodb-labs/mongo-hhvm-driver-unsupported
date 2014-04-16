<?php

class MongoCollectionTest extends MongoTestCase {

	public function testMongoCollection() {
		printf("Running testMongoCollection\n");
		$cli = new MongoClient();
		$database_name = "test";
		$db = new MongoDB($cli, $database_name);
		$coll_name = "students";
		$coll = new MongoCollection($db, $coll_name);

		//Test case for insert and remove
		$new_doc = array("_id"=>"123456781234567812345678", "name" => "Dan"); //24-digit _id required
		$this->assertEquals(true, $coll->insert($new_doc));
		$cursor = $coll->find(array("name" => "Dan"));
		$cursor->rewind();
		while ($cursor->valid())
		{
		    //$key = $cursor->key();   //This should be tested after BSON-PHP decoder is finished
		    //var_dump($key);
		    $value = $cursor->current();
		    var_dump($value);
		    $cursor->next();
		}
		$this->assertEquals(true, $coll->remove(array("name"=>"Dan")));
		printf("Ended testMongoCollection\n\n");

		//Test case for drop
		$temp_coll = new MongoCollection($db, "temp");

		$this->assertEquals(true, $temp_coll->drop()["return"]);
	}
}