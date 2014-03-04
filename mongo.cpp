#include "hphp/runtime/base/base-includes.h"
#include <iostream>

namespace HPHP {

static int64_t HHVM_FUNCTION(example_sum, int64_t a, int64_t b) {
  return a + b;
}

static String HHVM_FUNCTION(hello_world, const String& statement) {
  return String("Hello, " + statement + "!");
}

static int64_t HHVM_FUNCTION(sum_all, CArrRef numberArray) {
  int64_t sum = 0;
  for( int i = 0 ; i < numberArray.size() ; i++) {
    sum += toInt64(numberArray[i]);
  }
  return sum;
}

const StaticString s_MongoClient("MongoClient");
//////////////////////////////////////////////////////////////////////////////
// class MongoClient

static bool HHVM_METHOD(MongoClient, close, CVarRef connection) {
  throw NotImplementedException("Not Implemented");
}

static bool HHVM_METHOD(MongoClient, connect) {
  throw NotImplementedException("Not Implemented");
}

static Array HHVM_METHOD(MongoClient, dropDB, CVarRef db) {
  throw NotImplementedException("Not Implemented");
}

static Object HHVM_METHOD(MongoClient, __get, const String& dbname) {
  throw NotImplementedException("Not Implemented");
}

static Array HHVM_STATIC_METHOD(MongoClient, getConnections) {
  throw NotImplementedException("Not Implemented");
}

static Array HHVM_METHOD(MongoClient, getHosts) {
  throw NotImplementedException("Not Implemented");
}

static Array HHVM_METHOD(MongoClient, getReadPreference) {
  throw NotImplementedException("Not Implemented");
}

static bool HHVM_METHOD(MongoClient, killCursor, const String& server_hash, CVarRef id) {
  throw NotImplementedException("Not Implemented");
}

static Array HHVM_METHOD(MongoClient, listDBs) {
  throw NotImplementedException("Not Implemented");
}

static Object HHVM_METHOD(MongoClient, selectCollection, const String& db, const String& collection) {
  throw NotImplementedException("Not Implemented");
}

static Object HHVM_METHOD(MongoClient, selectDB, const String& name) {
  throw NotImplementedException("Not Implemented");
}

static bool HHVM_METHOD(MongoClient, setReadPreference, const String& read_preference, CArrRef tags) {
  throw NotImplementedException("Not Implemented");
}

static String HHVM_METHOD(MongoClient, __toString) {
  String s = "dummy toString";
  return s;
  //throw NotImplementedException("Not Implemented");
}

const StaticString s_MongoCursor("MongoCursor");
//////////////////////////////////////////////////////////////////////////////
// class MongoCursor

static Object HHVM_METHOD(MongoCursor, addOption, const String& key, CVarRef value) {
  throw NotImplementedException("Not Implemented");
}

static Object HHVM_METHOD(MongoCursor, awaitData, bool wait) {
  throw NotImplementedException("Not Implemented");
}

static Object HHVM_METHOD(MongoCursor, batchSize, int64_t batchSize) {
  throw NotImplementedException("Not Implemented");
}

static void HHVM_METHOD(MongoCursor, __construct, CObjRef connection, const String& ns, CArrRef query, CArrRef fields) {
  std::cout << "constructing dummy MongoCursor" << std::endl;
  //throw NotImplementedException("Not Implemented");
}

static int64_t HHVM_METHOD(MongoCursor, count, bool foundOnly) {
  throw NotImplementedException("Not Implemented");
}

static Array HHVM_METHOD(MongoCursor, current) {
  throw NotImplementedException("Not Implemented");
}

static bool HHVM_METHOD(MongoCursor, dead) {
  throw NotImplementedException("Not Implemented");
}

static void HHVM_METHOD(MongoCursor, doQuery) {
  throw NotImplementedException("Not Implemented");
}

static Array HHVM_METHOD(MongoCursor, explain) {
  throw NotImplementedException("Not Implemented");
}

static Object HHVM_METHOD(MongoCursor, fields, CArrRef f) {
  throw NotImplementedException("Not Implemented");
}

static Array HHVM_METHOD(MongoCursor, getNext) {
  throw NotImplementedException("Not Implemented");
}

static Array HHVM_METHOD(MongoCursor, getReadPreference) {
  throw NotImplementedException("Not Implemented");
}

static bool HHVM_METHOD(MongoCursor, hasNext) {
  throw NotImplementedException("Not Implemented");
}

static Object HHVM_METHOD(MongoCursor, hint, CVarRef index) {
  throw NotImplementedException("Not Implemented");
}

static Object HHVM_METHOD(MongoCursor, immortal, bool liveForever) {
  throw NotImplementedException("Not Implemented");
}

static Array HHVM_METHOD(MongoCursor, info) {
  throw NotImplementedException("Not Implemented");
}

static String HHVM_METHOD(MongoCursor, key) {
  throw NotImplementedException("Not Implemented");
}

static Object HHVM_METHOD(MongoCursor, limit, int64_t num) {
  throw NotImplementedException("Not Implemented");
}

static void HHVM_METHOD(MongoCursor, next) {
  throw NotImplementedException("Not Implemented");
}

static Object HHVM_METHOD(MongoCursor, partial, bool okay) {
  throw NotImplementedException("Not Implemented");
}

static void HHVM_METHOD(MongoCursor, reset) {
  throw NotImplementedException("Not Implemented");
}

static void HHVM_METHOD(MongoCursor, rewind) {
  throw NotImplementedException("Not Implemented");
}

static Object HHVM_METHOD(MongoCursor, setFlag, int64_t flag, bool set) {
  throw NotImplementedException("Not Implemented");
}

static Object HHVM_METHOD(MongoCursor, setReadPreference, const String& read_preference, CArrRef tags) {
  throw NotImplementedException("Not Implemented");
}

static Object HHVM_METHOD(MongoCursor, skip, int64_t num) {
  throw NotImplementedException("Not Implemented");
}

static Object HHVM_METHOD(MongoCursor, slaveOkay, bool okay) {
  throw NotImplementedException("Not Implemented");
}

static Object HHVM_METHOD(MongoCursor, snapshot) {
  throw NotImplementedException("Not Implemented");
}

static Object HHVM_METHOD(MongoCursor, sort, CArrRef fields) {
  throw NotImplementedException("Not Implemented");
}

static Object HHVM_METHOD(MongoCursor, tailable, bool tail) {
  throw NotImplementedException("Not Implemented");
}

static Object HHVM_METHOD(MongoCursor, timeout, int64_t ms) {
  throw NotImplementedException("Not Implemented");
}

static bool HHVM_METHOD(MongoCursor, valid) {
  throw NotImplementedException("Not Implemented");
}


//////////////////////////////////////////////////////////////////////////////

class mongoExtension : public Extension {
 public:
  mongoExtension() : Extension("mongo") {}
  virtual void moduleInit() {
    HHVM_FE(example_sum);
    HHVM_FE(hello_world);
    HHVM_FE(sum_all);

    HHVM_ME(MongoClient, close);
    HHVM_ME(MongoClient, connect);
    HHVM_ME(MongoClient, dropDB);
    HHVM_ME(MongoClient, __get);
    HHVM_STATIC_ME(MongoClient, getConnections);
    HHVM_ME(MongoClient, getHosts);
    HHVM_ME(MongoClient, getReadPreference);
    HHVM_ME(MongoClient, killCursor);
    HHVM_ME(MongoClient, listDBs);
    HHVM_ME(MongoClient, selectCollection);
    HHVM_ME(MongoClient, selectDB);
    HHVM_ME(MongoClient, setReadPreference);
    HHVM_ME(MongoClient, __toString);
    
    HHVM_ME(MongoCursor, addOption);
    HHVM_ME(MongoCursor, awaitData);
    HHVM_ME(MongoCursor, batchSize);
    HHVM_ME(MongoCursor, __construct);
    HHVM_ME(MongoCursor, count);
    HHVM_ME(MongoCursor, current);
    HHVM_ME(MongoCursor, dead);
    HHVM_ME(MongoCursor, doQuery);
    HHVM_ME(MongoCursor, explain);
    HHVM_ME(MongoCursor, fields);
    HHVM_ME(MongoCursor, getNext);
    HHVM_ME(MongoCursor, getReadPreference);
    HHVM_ME(MongoCursor, hasNext);
    HHVM_ME(MongoCursor, hint);
    HHVM_ME(MongoCursor, immortal);
    HHVM_ME(MongoCursor, info);
    HHVM_ME(MongoCursor, key);
    HHVM_ME(MongoCursor, limit);
    HHVM_ME(MongoCursor, next);
    HHVM_ME(MongoCursor, partial);
    HHVM_ME(MongoCursor, reset);
    HHVM_ME(MongoCursor, rewind);
    HHVM_ME(MongoCursor, setFlag);
    HHVM_ME(MongoCursor, setReadPreference);
    HHVM_ME(MongoCursor, skip);
    HHVM_ME(MongoCursor, slaveOkay);
    HHVM_ME(MongoCursor, snapshot);
    HHVM_ME(MongoCursor, sort);
    HHVM_ME(MongoCursor, tailable);
    HHVM_ME(MongoCursor, timeout);
    HHVM_ME(MongoCursor, valid);
    
    loadSystemlib();
  }
} s_mongo_extension;

// Uncomment for non-bundled module
HHVM_GET_MODULE(mongo);

//////////////////////////////////////////////////////////////////////////////
} // namespace HPHP
