<?php

class MongoDBTest extends MongoTestCase {

	public function testMongoDB() {
		$cli = $this->getTestClient();

		//echo "Going to selectDB";
		$db = $this->getTestDB();
		//var_dump($db->getProfilingLevel());
		//$db = new MongoDB($cli, $database_name);
	}

	public function testCreateDropCollection() {
		$db = $this->getTestDB();
		$coll_name = "hello";
		$coll = $db->createCollection("hello");
		//$res = $db->dropCollection($coll);
		//$res = $db->createCollection("hello");
	}

	public function testGetCollectionNames() {
		$cli = $this->getTestClient();
		$new_colls = array("t1","t2","t3");
		$db = $cli->selectDB("testCollectionNames");
		foreach ($new_colls as $coll) {
		//	$db->createCollection($coll);
		}

		//$res = $db->getCollectionNames();
		//$this->assertEquals($new_colls, $res);
	}
}