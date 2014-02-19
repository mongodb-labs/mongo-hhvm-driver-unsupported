#! /bin/sh

if [[ "$HPHP_HOME" == "" ]]; then
    echo HPHP_HOME environment variable must be set!
    exit 1
fi

$HPHP_HOME/hphp/tools/hphpize/hphpize
cmake .
make

