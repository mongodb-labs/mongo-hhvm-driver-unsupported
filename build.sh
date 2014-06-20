#!/bin/sh

if [ "$HPHP_HOME" = "" ]; then
    echo "HPHP_HOME environment variable must be set!"
    exit 1
fi

printf "<?hh\n\n// AUTO-GENERATED FILE. DO NOT MODIFY.\n" > src/ext_mongo.php

# Base classes must be concatenated/declared first
tail -q -n +2 src/exceptions/MongoException.php >> src/ext_mongo.php
tail -q -n +2 src/exceptions/MongoConnectionException.php >> src/ext_mongo.php
tail -q -n +2 src/exceptions/MongoCursorException.php >> src/ext_mongo.php
tail -q -n +2 src/exceptions/MongoDuplicateKeyException.php >> src/ext_mongo.php
tail -q -n +2 src/exceptions/MongoExecutionTimeoutException.php >> src/ext_mongo.php
tail -q -n +2 src/exceptions/MongoGridFSException.php >> src/ext_mongo.php
tail -q -n +2 src/exceptions/MongoProtocolException.php >> src/ext_mongo.php
tail -q -n +2 src/exceptions/MongoResultException.php >> src/ext_mongo.php
tail -q -n +2 src/exceptions/MongoCursorTimeoutException.php >> src/ext_mongo.php
tail -q -n +2 src/exceptions/MongoWriteConcernException.php >> src/ext_mongo.php

# Type and base classes have no inheritance hierarchy
tail -q -n +2 src/types/*.php >> src/ext_mongo.php
find src/ -maxdepth 1 -name "*.php" \! -name ext_mongo.php | xargs tail -q -n +2 >> src/ext_mongo.php

$HPHP_HOME/hphp/tools/hphpize/hphpize
cmake .
make -j5
