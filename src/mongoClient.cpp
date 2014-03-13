#include "ext_mongo.h"

namespace HPHP {

////////////////////////////////////////////////////////////////////////////////
// class MongoClient

static bool HHVM_METHOD(MongoClient, __construct, const String& connection, CArrRef options) {
  std::cout << "Client started" << std::endl;
  return false;
  //throw NotImplementedException("Not Implemented");
}

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

////////////////////////////////////////////////////////////////////////////////

void mongoExtension::_initMongoClientClass() {
    HHVM_ME(MongoClient, __construct);
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
}

} //namespace HPHP
