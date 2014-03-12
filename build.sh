#!/bin/bash

if [ "$HPHP_HOME" = "" ]; then
    echo "HPHP_HOME environment variable must be set!"
    exit 1
fi

printf "<?hh\n" > mongo.php

files=( mongoClient.php mongoCursor.php mongo_types/MongoDate.php)
tail -q -n +2 ${files[@]} >> mongo.php

$HPHP_HOME/hphp/tools/hphpize/hphpize
cmake .
make
