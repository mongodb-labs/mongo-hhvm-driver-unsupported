#include "bson_decode.h"

namespace HPHP {

  // Global function for decoding bson
  static Array HHVM_FUNCTION(bson_decode, const String& bson) {
    return cbson_loads_from_string(bson);
  }


}