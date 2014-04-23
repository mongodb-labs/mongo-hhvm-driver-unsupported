<?php

class MongoDBTest extends MongoTestCase {

	public function testMongoDB() {
		$cli = $this->getTestClient();

		//echo "Going to selectDB";
		$db = $cli->selectDB('test');
		//var_dump($db->getProfilingLevel());
		//$db = new MongoDB($cli, $database_name);
	}

	public function testCommand() {

	}
}