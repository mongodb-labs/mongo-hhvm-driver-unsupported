<?php

class MongoDateTest extends MongoTestCase {

	public function testMongoDate() {
		printf("Starting %s\n", __FUNCTION__);
		$date = new MongoDate();
		//printf($new_date);
		var_dump((string) $date);
		printf("Ending %s\n", __FUNCTION__);
	}
}