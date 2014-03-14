#!/bin/sh

if [ "$HPHP_HOME" = "" ]; then
    echo "HPHP_HOME environment variable must be set!"
    exit 1
fi

printf "<?hh\n" > mongo.php
tail -q -n +2 src/*php src/types/*php >> mongo.php
#tail -q -n +2 src/types/*php >> mongo.php

$HPHP_HOME/hphp/tools/hphpize/hphpize
cmake .
make
