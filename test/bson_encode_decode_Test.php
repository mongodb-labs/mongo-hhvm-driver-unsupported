<?php

class DecodingTest extends PHPUnit_Framework_TestCase {

	public function testDecoding() {
		printf("Starting %s\n", __FUNCTION__);

		echo "\nAssering that two simple arrays are equal";
		$bson  = pack('C', 0x02);                      // byte: string type
		$bson .= pack('a*x', 'x');                     // cstring: field name
		$bson .= pack('V', 2);                         // int32: string length (valid)
		$bson .= pack('a*x', 'a');                      // cstring: string value
		$bson .= pack('x');                            // null byte: document terminator
		$bson  = pack('V', 4 + strlen($bson)) . $bson; // int32: document length

		$this->assertEquals(Encoding::bson_decode($bson), array("x" => "a"));
		
		echo "\nTesting string type with valid length prefix\n";
		$bson  = pack('C', 0x02);                      // byte: string type
		$bson .= pack('a*x', 'x');                     // cstring: field name
		$bson .= pack('V', 1);                         // int32: string length (valid)
		$bson .= pack('a*x', '');                      // cstring: string value
		$bson .= pack('x');                            // null byte: document terminator
		$bson  = pack('V', 4 + strlen($bson)) . $bson; // int32: document length
		var_dump(Encoding::bson_decode($bson));
		//assert(Encoding::bson_decode($bson) == var_dump(array(["x"] => "")));
		$this->assertEquals(Encoding::bson_decode($bson), array("x" => ""));

		echo "\nTesting string type\n";
		$bson = pack('C', 0x02); // byte: string type
		$bson .= pack('a*x', 'x'); // cstring: field name
		$bson .= pack('V', 0); // int32: string length (invalid)
		$bson .= pack('a*x', ''); // cstring: string value
		$bson .= pack('x'); // null byte: document terminator
		$bson = pack('V', 4 + strlen($bson)) . $bson; // int32: document length
		//var_dump(Encoding::bson_decode($bson));

		printf("Ending %s\n", __FUNCTION__);
	}

	public function testEncodeDecode() {
		printf("Starting %s\n", __FUNCTION__);
		$a1 = array("hello" => "world");
		//$this->assertTrue(bson_decode(bson_encode($a1)) == $a1);

		$id = new MongoId();
		$a2 = array("_id" => $id);
		//$this->assertTrue(bson_decode(bson_encode($a2)) == $a2);

		$int32 = new MongoInt32("32");
		$a2["a_int32"] = $int32;
		//$this->assertTrue(bson_decode(bson_encode($a2)) == $a2);

		$int64 = new MongoInt64("64");
		$a2["a_int64"] = $int64;
		//$this->assertTrue(bson_decode(bson_encode($a2)) == $a2);

		$a3 = array("nested" => $a1);
		//$this->assertTrue(bson_decode(bson_encode($a3)) == $a3);

		printf("Ending %s\n", __FUNCTION__);
	}
} 	