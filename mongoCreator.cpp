#include "mongoCreator.h"

namespace HPHP {

const StaticString s_MongoClient("MongoClient");
const StaticString s_MongoCursor("MongoCursor");

mongoExtension s_mongo_extension;
// Uncomment for non-bundled module
HHVM_GET_MODULE(mongo);

//////////////////////////////////////////////////////////////////////////////
} // namespace HPHP
