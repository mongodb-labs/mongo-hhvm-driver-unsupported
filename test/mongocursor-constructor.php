<?php

$mc = new MongoClient();
printf("Server version: %s\n", $mc->getServerVersion());

$mcursor = new MongoCursor( $mc,
                              "test.test_collection",
                              array("test_field" => "1"),
                              array());
