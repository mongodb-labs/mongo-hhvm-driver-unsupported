<?php
var_dump(extension_loaded("mongo"));
var_dump(function_exists("example_sum"));
var_dump(example_sum(1,2));
var_dump(hello_world("world"));
var_dump(sum_all(array(1,2,3,4,5)));

$cli = new MongoClient();
var_dump((string) $cli);
$cursor = new MongoCursor($cli, "db.collection");
//var_dump($cli->connect());
//var_dump($cli->getConnections());
//var_dump($cli->getHosts());
