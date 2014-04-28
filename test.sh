#!/bin/sh

DIRNAME=`dirname $0`
REALPATH=`which realpath`
if [ ! -z "${REALPATH}" ]; then
  DIRNAME=`realpath ${DIRNAME}`
fi

#mongoimport --db test --collection cities --file test/cities.json --upsert
mongoimport --db test --collection students --file test/students.json --upsert
#to test the drop method in MongoCollection
mongoimport --db test --collection temp --file test/students.json --upsert

${HPHP_HOME}/hphp/hhvm/hhvm \
  -vDynamicExtensions.0=${DIRNAME}/mongo.so \
  `which phpunit` ${DIRNAME}/test/bson_encode_decode_Test.php

