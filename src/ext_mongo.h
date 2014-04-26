#ifndef incl_HPHP_EXT_MONGO_H_
#define incl_HPHP_EXT_MONGO_H_

#include "mongo_common.h"
namespace HPHP {

//////////////////////////////////////////////////////////////////////////////
// PHP Exceptions and Classes (adapted from Imagick core extension)
#define MONGO_DEFINE_CLASS(CLS) \
  class CLS { \
   public: \
    static Object allocObject() { \
      if (cls == nullptr) { \
        initClass(); \
      } \
      return ObjectData::newInstance(cls); \
    } \
    \
    static Object allocObject(const Variant& arg) { \
      Object ret = allocObject(); \
      TypedValue dummy; \
      g_context->invokeFunc(&dummy, \
                              cls->getCtor(), \
                              make_packed_array(arg), \
                              ret.get()); \
      return ret; \
    } \
    \
   private: \
    static void initClass() { \
      cls = Unit::lookupClass(StringData::Make(#CLS)); \
    } \
    \
    static HPHP::Class* cls; \
  };

MONGO_DEFINE_CLASS(MongoException)
MONGO_DEFINE_CLASS(MongoConnectionException)
MONGO_DEFINE_CLASS(MongoCursorTimeoutException)
MONGO_DEFINE_CLASS(MongoDuplicateKeyException)
MONGO_DEFINE_CLASS(MongoExecutionTimeoutException)
MONGO_DEFINE_CLASS(MongoGridFSException)
MONGO_DEFINE_CLASS(MongoProtocolException)
MONGO_DEFINE_CLASS(MongoResultException)
MONGO_DEFINE_CLASS(MongoWriteConcernException)

#undef MONGO_DEFINE_CLASS

template<typename T>
void mongoThrow(const char* message);

template<typename T>
void mongoThrow(const char* message) {
  std::string msg(message);
  throw T::allocObject(msg);
}

//////////////////////////////////////////////////////////////////////////////

static void mongoc_log_handler(mongoc_log_level_t log_level,
                               const char *log_domain, const char *message,
                               void *user_data) {
   if (log_level < MONGOC_LOG_LEVEL_INFO) {
      mongoc_log_default_handler(log_level, log_domain, message, NULL);
   }
}

class mongoExtension : public Extension {
public:
  mongoExtension() : Extension("mongo") {}
  virtual void moduleInit() {

    mongoc_log_set_handler(mongoc_log_handler, NULL);
    _initMongoClientClass();
    _initMongoCursorClass();
    _initMongoCollectionClass();
    _initBSON();
    loadSystemlib();
  }

private:
  void _initMongoClientClass();
  void _initMongoCursorClass();
  void _initMongoCollectionClass();
  void _initBSON();
};

} // namespace HPHP

#endif // incl_HPHP_EXT_MONGO_H_
