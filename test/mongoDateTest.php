<?php

class MongoDateTest extends MongoTestCase {

	public function testMongoDate() {
		printf("Running testMongoDate\n");
		$date = new MongoDate();
		//printf($new_date);
		var_dump((string) $date);
		printf("Ended testMongoDate\n\n");
	}
}