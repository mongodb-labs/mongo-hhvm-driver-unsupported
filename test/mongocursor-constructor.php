<?php

/*
Assuming that in test.test_collection we have the following three documents:
{ "_id" : ..., "test_field" : "1", "num" : "1" }
{ "_id" : ..., "test_field" : "1", "num" : "2" }
{ "_id" : ..., "test_field" : "1", "num" : "3" }
*/

$mc = new MongoClient();
printf("Server version: %s\n", $mc->getServerVersion());

$mcursor = new MongoCursor( $mc,
                              "test.test_collection",
                              array("test_field" => "1"),
                              array());
while ($mcursor->hasNext()) {
	printf("hasNext?: Yes\n");
	$mcursor->getNext();
}

$mcursor->rewind();
$mcursor->next();
$mcursor->next();
$mcursor->getNext(); //this should only print the third document from the query