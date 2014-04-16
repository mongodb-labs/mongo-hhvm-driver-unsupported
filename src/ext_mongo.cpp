#include "ext_mongo.h"
#include "bson_decode.h"
#include "contrib/encode.h"

namespace HPHP {

mongoExtension s_mongo_extension;
HHVM_GET_MODULE(mongo);

} // namespace HPHP
