#!/bin/sh

DIRNAME=`dirname $0`
REALPATH=`which realpath`
if [ ! -z "${REALPATH}" ]; then
  DIRNAME=`realpath ${DIRNAME}`
fi

#mongoimport --db test --collection cities --file test/cities.json --upsert
mongoimport --db test --collection students --file test/students.json --upsert

${HPHP_HOME}/hphp/hhvm/hhvm \
  -vDynamicExtensions.0=${DIRNAME}/mongo.so \
  /usr/local/bin/phpunit ${DIRNAME}/test

