#include "hphp/runtime/base/exceptions.h"

namespace HPHP {

class MongoException : public ExtendedException {
  public:
    MongoException(const std::string &msg) : ExtendedException(msg) {}

    virtual ~MongoException() throw() {}

    EXCEPTION_COMMON_IMPL(MongoException);
};

}