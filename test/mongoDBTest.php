<?php

class MongoDBTest extends MongoTestCase {

	public function testProfilingLevel() {

		$db = $this->getTestDB();
		$db->setProfilingLevel(2);
		$res = $db->getProfilingLevel();
		$this->assertEquals(2, $res);
	}

	public function testCreateDropCollection() {
		$db = $this->getTestDB();
		$coll_name = "hello";
		$coll = $db->createCollection("hello");

		$db_response = $db->dropCollection($coll);
		$this->assertEquals(1, $db_response["ok"]);
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

	public function testErrors() {
		$db = $this->getTestDB();

		$res = $db->resetError();
		$this->assertEquals(1, $res["ok"]);

		$res = $db->prevError();
		$this->assertEquals(1, $res["ok"]);
		$this->assertEquals(-1, $res["nPrev"]);

		$res = $db->lastError();
		$this->assertEquals(1, $res["ok"]);
		$this->assertEquals(0, $res["n"]);

		$db->forceError();
		$res = $db->lastError();
		$this->assertEquals(1, $res["ok"]);
		$this->assertEquals("forced error", $res["err"]);
	}

	public function testOtherMethods() {
		$db = $this->getTestDB();

		$res = $db->repair();
		$this->assertEquals(1, $res["ok"]);

	}
}