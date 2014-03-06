<?php
var_dump(extension_loaded("mongo"));

$cli = new MongoClient();
var_dump((string) $cli);
$cursor = new MongoCursor($cli, "db.collection");
var_dump($cursor->batchSize(5));
//var_dump($cli->connect());
//var_dump($cli->getConnections());
//var_dump($cli->getHosts());
