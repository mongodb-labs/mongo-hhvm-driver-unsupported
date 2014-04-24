<?php

// To test protected methods
class MongoCollectionStub extends MongoCollection {

	public function __construct() {}

	public function createIndexString($keys) {
		return $this->toIndexString($keys);
	}
}

class MongoCollectionTest extends MongoTestCase {

	public function testMongoCollection() {
		$cli = $this->getTestClient();
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
		    $value = $cursor->current();
		    $this->assertEquals("Dan", $value["name"]);
		    $cursor->next();
		}
		$this->assertEquals(true, $coll->remove(array("name"=>"Dan")));

		// //Test case for drop
		// $temp_coll = new MongoCollection($db, "temp");
		// //$this->assertEquals(true, $temp_coll->drop()["return"]);

		// //Test case for save
		// $new_doc = array("_id"=>"123456791234567912345679", "name" => "Eva"); //24-digit _id required
		// //$this->assertEquals(true, $coll->save($new_doc));
		// $cursor = $coll->find(array("name" => "Eva"));
		// $cursor->rewind();
		// while ($cursor->valid())
		// {
		//     $value = $cursor->current();
		//     if ((strpos($value["return"], "\"name\" : \"Eva\"")) == FALSE) {
		//     	throw new Exception("MongoCollection save for new document failed.");
		//     }
		//     $cursor->next();
		// }
		// $new_doc_modified = array("_id"=>"123456791234567912345679", "name" => "Frank"); //24-digit _id required
		// $this->assertEquals(true, $coll->save($new_doc_modified));
		// $cursor = $coll->find(array("name" => "Frank"));
		// $cursor->rewind();
		// while ($cursor->valid())
		// {
		//     $value = $cursor->current();
		//     if (strpos($value["return"], "\"name\" : \"Frank\"") == FALSE) {
		//     	throw new Exception("MongoCollection save for existing document failed.");
		//     }
		//     $cursor->next();
		// }

		$cli = $this->getTestClient();
		$db = $this->getTestDB();
		$coll_name = "students";
		$coll = $db->__get($coll_name);

		// Testing toIndexString
		$c = new MongoCollectionStub();
		$actual = $c->createIndexString("foo");
		$expected = "foo_1";
		$this->assertEquals($expected, $actual);

		$actual = $c->createIndexString(array('name' => 1, 'age' => -1, 'bool' => false));
		$expected = "name_1_age_-1_bool_1";
		$this->assertEquals($expected, $actual);

	}
}