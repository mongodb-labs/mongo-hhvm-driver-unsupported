<?php

class DecodingTest extends PHPUnit_Framework_TestCase {

	public function testDecoding() {

		//echo "\nTesting string type with valid length prefix";
		$bson  = pack('C', 0x02);                      // byte: string type
		$bson .= pack('a*x', 'x');                     // cstring: field name
		$bson .= pack('V', 2);                         // int32: string length (valid)
		$bson .= pack('a*x', 'a');                      // cstring: string value
		$bson .= pack('x');                            // null byte: document terminator
		$bson  = pack('V', 4 + strlen($bson)) . $bson; // int32: document length

		$this->assertEquals(array("x" => "a"), bson_decode($bson));

		//echo "\nTesting oid\n";
		$bson  = pack('C', 0x07);                      // byte: oid type
		$bson .= pack('a*x', 'id');                     // cstring: field name
		$bson .= pack('H24', "507f191e810c19729de860ea");               // byte*12: oid value
		$bson .= pack('x');                            // null byte: document terminator
		$bson  = pack('V', 4 + strlen($bson)) . $bson; // int32: document length

		$expected = array("id" => new MongoId("507f191e810c19729de860ea"));
		$this->assertEquals($expected, bson_decode($bson));

		//echo "\nTesting nested docs\n";
		$inner_doc = $bson;
		$inner_expected = $expected;
		$bson  = pack('C', 0x03);                      // byte: document type
		$bson .= pack('a*x', 'doc');                   // cstring: field name
		$bson .= $inner_doc;						   // an inner document
		$bson .= pack('x');                            // null byte: document terminator
		$bson  = pack('V', 4 + strlen($bson)) . $bson; // int32: document length
		//assert(bson_decode($bson) == var_dump(array(["x"] => "")));
		$expected = array("doc" => $inner_expected);
		$this->assertEquals($expected, bson_decode($bson));

		/*echo "\nTesting datetime\n";
		$bson  = pack('C', 0x09);                      // byte: datetime type
		$bson .= pack('a*x', 'date');                   // cstring: field name
		$bson .= pack('H16', "0123456789abcde0");
		$bson .= pack('x');                            // null byte: document terminator
		$bson  = pack('V', 4 + strlen($bson)) . $bson; // int32: document length
		//assert(bson_decode($bson) == var_dump(array(["x"] => "")));
		$date = new MongoDate();
		printf("Date: %s", $date);
		$expected = array("date" => $date);
		$this->assertEquals($expected, bson_decode($bson));*/


		//echo "\nTesting string type\n";
		$bson = pack('C', 0x02); // byte: string type
		$bson .= pack('a*x', 'x'); // cstring: field name
		$bson .= pack('V', 0); // int32: string length (invalid)
		$bson .= pack('a*x', ''); // cstring: string value
		$bson .= pack('x'); // null byte: document terminator
		$bson = pack('V', 4 + strlen($bson)) . $bson; // int32: document length
		//var_dump(bson_decode($bson));

	}

	public function testEncodeDecode() {
		$a1 = array("hello" => "world");
    	//var_dump(bson_decode(bson_encode($a1)));
		$this->assertEquals($a1, bson_decode(bson_encode($a1)));

		$id = new MongoId();
		$a2 = array("_id" => $id);

	    $en_result = bson_encode($a2);
	    $result = bson_decode($en_result);
	    $this->assertEquals($a2, $result);
    
		$int32 = 2;
		$a2["a_int32"] = $int32;
	    $result = bson_decode(bson_encode($a2));
		$this->assertEquals($a2, $result);
	/*
			$int64 = new MongoInt64("64");
			$a2["a_int64"] = $int64;
	    //var_dump(bson_decode(bson_encode($a2["a_int32"])));
			//$this->assertTrue(bson_decode(bson_encode($a2["a_int64"])) == $a2["a_int64"]);
	*/
	    $bool = true;
	    $a2["boolean"] = $bool;
		$this->assertEquals($a2, bson_decode(bson_encode($a2)));
	    
	    $null = null;
	    $a2["null"] = $null;
		$this->assertEquals($a2, bson_decode(bson_encode($a2)));
	    
	    $double = 3.2;
	    $a2["double"] = $double;
		$this->assertEquals($a2, bson_decode(bson_encode($a2))); 
	}

	public function testEncodeDecodeNestedDocument() {
		$id1 = new MongoId();

		$bool = true;
		$string = "Top level doc";
		$int64 = 9001;
		$date = new MongoDate();
		$timestamp = new MongoTimestamp();
		$minkey = new MongoMinKey();
		$maxkey = new MongoMaxKey();
		$bindata = new MongoBinData("1111");

		$id2 = new MongoId();
		$string2 = "Inner doc";
		$array = [0, 1, 2 , 3];

		$inner_doc = array("_id" => $id2, "name" => $string2, "list" => $array);

		$this->assertEquals($inner_doc, bson_decode(bson_encode($inner_doc)));

		$out_doc = array("_id" => $id1, 
										 "name" => $string, 
										 "val" => $int64,
										 "date" => $date,
										 "timestamp" => $timestamp,
										 "minkey" => $minkey,
										 "maxkey" => $maxkey,
										 "bindata" => $bindata,
										 "inner_doc" => $inner_doc);

		// var_dump(bson_decode(bson_encode($out_doc)));

		$this->assertEquals($out_doc, bson_decode(bson_encode($out_doc)));
	}

	public function testDecodeCorruptException() {
		$id1 = new MongoId();

		$bool = true;
		$string = "Top level doc";
		$int64 = 9001;
		$date = new MongoDate();
		$timestamp = new MongoTimestamp();
		$minkey = new MongoMinKey();
		$maxkey = new MongoMaxKey();
		$bindata = new MongoBinData("1111");

		$out_doc = array("_id" => $id1, 
										 "name" => $string, 
										 "val" => $int64,
										 "date" => $date,
										 "timestamp" => $timestamp,
										 "minkey" => $minkey,
										 "maxkey" => $maxkey,
										 "bindata" => $bindata);

		$encoded_bson = bson_encode($out_doc);

		$corrupted_bson = substr($encoded_bson, 0, strlen($encoded_bson) - 4);

		$message = "Unexpected end of BSON. Input document is likely corrupted!";

		$this->setExpectedException('MongoException', $message);

		bson_decode($corrupted_bson);
	}
} 	
