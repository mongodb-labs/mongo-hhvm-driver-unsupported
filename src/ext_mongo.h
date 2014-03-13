#ifndef incl_HPHP_EXT_MONGO_H_
#define incl_HPHP_EXT_MONGO_H_

#include "hphp/runtime/base/base-includes.h"

namespace HPHP {

class mongoExtension : public Extension {
public:
  mongoExtension() : Extension("mongo") {}
  virtual void moduleInit() {
    _initMongoClientClass();
    _initMongoCursorClass();
    
    loadSystemlib();
  }

private:
  void _initMongoClientClass();
  void _initMongoCursorClass();
};

} // namespace HPHP

#endif // incl_HPHP_EXT_MONGO_H_
