#include "bson_decode.h"
#include "bson_encode.h"

namespace HPHP {

  // Global function for decoding bson
  static Array HHVM_FUNCTION(bson_decode, const String& bson) {
    return cbson_loads_from_string(bson);
  }

  static String HHVM_FUNCTION(bson_encode, const Variant& anything) {
    bson_t bson;
    bson_init(&bson);
    fillBSONWithArray(anything.toArray(), &bson);

    const char* output = (const char*) bson_get_data(&bson);        
    return String(output, bson.len, CopyString);
  }
}
