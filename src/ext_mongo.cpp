#include "ext_mongo.h"

namespace HPHP {

// PHP Exceptions and Classes
HPHP::Class* MongoException::cls = nullptr;
HPHP::Class* MongoConnectionException::cls = nullptr;
HPHP::Class* MongoCursorException::cls = nullptr;
HPHP::Class* MongoCursorTimeoutException::cls = nullptr;
HPHP::Class* MongoDuplicateKeyException::cls = nullptr;
HPHP::Class* MongoExecutionTimeoutException::cls = nullptr;
HPHP::Class* MongoGridFSException::cls = nullptr;
HPHP::Class* MongoProtocolException::cls = nullptr;
HPHP::Class* MongoResultException::cls = nullptr;
HPHP::Class* MongoWriteConcernException::cls = nullptr;

HPHP::Class* MongoClient::cls = nullptr;
HPHP::Class* MongoCursor::cls = nullptr;
HPHP::Class* MongoCollection::cls = nullptr;

static void mongoc_log_handler(mongoc_log_level_t log_level,
                               const char *log_domain, const char *message,
                               void *user_data) {
   if (log_level < MONGOC_LOG_LEVEL_INFO) {
      mongoc_log_default_handler(log_level, log_domain, message, NULL);
   }
}

MongoExtension::MongoExtension() :
  Extension("mongo") {
}

void MongoExtension::moduleInit() {
  mongoc_log_set_handler(mongoc_log_handler, NULL);
  _initMongoClientClass();
  _initMongoCursorClass();
  _initMongoCollectionClass();
  _initBSON();
  loadSystemlib();
}

MongoExtension s_mongo_extension;
HHVM_GET_MODULE(mongo);

} // namespace HPHP
