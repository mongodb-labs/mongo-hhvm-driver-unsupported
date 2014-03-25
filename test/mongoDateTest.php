<?php

class MongoDateTest extends PHPUnit_Framework_TestCase{

	public function testMongoDate() {
		printf("Running testMongoDate\n");
		$date = new MongoDate();
		var_dump((string) $date);
		printf("Ended testMongoDate\n\n");
	}
}