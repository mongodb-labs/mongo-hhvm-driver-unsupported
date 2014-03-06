#ifndef incl_HPHP_EXT_MONGO_H
#define incl_HPHP_EXT_MONGO_H

#include "hphp/runtime/base/base-includes.h"
#include <iostream>

namespace HPHP {

extern const StaticString s_MongoClient;
extern const StaticString s_MongoCursor;

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

//////////////////////////////////////////////////////////////////////////////
} // namespace HPHP

#endif