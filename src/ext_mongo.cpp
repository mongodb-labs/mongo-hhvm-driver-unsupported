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

mongoExtension s_mongo_extension;
HHVM_GET_MODULE(mongo);

} // namespace HPHP
