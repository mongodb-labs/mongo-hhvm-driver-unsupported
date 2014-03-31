<?php

/*
Assuming that in test.test_collection we have the following three documents:
{ "_id" : ..., "test_field" : "1", "num" : "1" }
{ "_id" : ..., "test_field" : "1", "num" : "2" }
{ "_id" : ..., "test_field" : "1", "num" : "3" }
*/

$output = shell_exec('mongoimport --db test --collection test_collection --file test/test_collection.json --upsert');
echo $output;
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

printf("Ran through cursor");

$mcursor->rewind();
$mcursor->next();
$mcursor->next();
$mcursor->getNext(); //this should only print the third document from the query