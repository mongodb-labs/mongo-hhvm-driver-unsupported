#!/bin/sh

if [ "$HPHP_HOME" = "" ]; then
    echo "HPHP_HOME environment variable must be set!"
    exit 1
fi

printf "<?hh\n\n// AUTO-GENERATED FILE. DO NOT MODIFY.\n" > src/ext_mongo.php
find src/ -name "*.php" \! -name ext_mongo.php | xargs tail -q -n +2 >> src/ext_mongo.php

$HPHP_HOME/hphp/tools/hphpize/hphpize
cmake .
make -j5
