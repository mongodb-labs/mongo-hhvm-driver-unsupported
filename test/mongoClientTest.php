<?php

class MongoClientTest extends MongoTestCase {

	public function testListDBs() {
		$cli = $this->getTestClient();
		
		$res = $cli->listDBs();
		$this->assertEquals(1, $res["ok"]);
	}

} 	