<?php 
//based off of MongoFill

//namespace mongo_hhvm_driver\tests;

abstract class MongoTestCase extends PHPUnit_Framework_TestCase {
	const TEST_DB = 'test';

	private $testClient;

	protected function setUp() {
		parent::setUp();
		//$this->getTestDB()->drop();
	}

	protected function tearDown() {
		$this->testClient = null;
		parent::tearDown();
	}

	public function getTestClient() {
		if(!$this->testClient) {
			$this->testClient = new MongoClient();
		}

		return $this->testClient;
	}

	public function getTestDB() {
		return $this->getTestClient()->selectDB(self::TEST_DB);
	}
}