#include "ext_mongo.h"

namespace HPHP {

const StaticString
  s_mongoclient("MongoClient"),
  s_mongoc_client("__mongoc_client");

////////////////////////////////////////////////////////////////////////////////

static Resource get_client_resource(Object obj) {
  auto res = obj->o_realProp(s_mongoc_client, ObjectData::RealPropUnchecked, s_mongoclient);

  if (!res || !res->isResource()) {
    return null_resource;
  }

  return res->toResource();
}

static MongocClient *get_client(Object obj) {
  auto res = get_client_resource(obj);

  return res.getTyped<MongocClient>(true, false);
}

////////////////////////////////////////////////////////////////////////////////
// class MongoClient

static void HHVM_METHOD(MongoClient, __construct, const String& uri, CArrRef options) {
  MongocClient *client = MongocClient::GetPersistent(uri);

  if (client == nullptr) {
    client = new MongocClient(uri);
  }

  if (client->isInvalid()) {
    throw Exception("Unable to connect: %s", uri.c_str());
  }

  MongocClient::SetPersistent(uri, client);
  this_->o_set(s_mongoc_client, client, s_mongoclient);
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

/* Test method that returns the server's version string */
static String HHVM_METHOD(MongoClient, getServerVersion) {
  bool result;
  bson_t buildInfo, reply;
  bson_error_t error;
  bson_iter_t iter;
  String retval;

  auto client = get_client(this_);

  bson_init(&buildInfo);
  bson_append_int32(&buildInfo, "buildInfo", 9, 1);

  result = mongoc_client_command_simple(client->get(), "test", &buildInfo, nullptr, &reply, &error);

  bson_destroy(&buildInfo);

  if ( ! result) {
    throw Exception("Command error: %s", error.message);
  }

  if (bson_iter_init_find(&iter, &reply, "version")) {
    retval = String(bson_iter_utf8(&iter, nullptr), CopyString);
  }

  bson_destroy(&reply);

  return retval;
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
    HHVM_ME(MongoClient, getServerVersion);
}

} //namespace HPHP
