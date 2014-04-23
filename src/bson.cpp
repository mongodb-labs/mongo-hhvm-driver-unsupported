#include "bson_decode.h"
#include "contrib/encode.h"
#include "ext_mongo.h"
#include <iostream>
namespace HPHP {

  // Global function for decoding bson
  static Array HHVM_STATIC_METHOD(Encoding, bson_decode, const String& bson) {
    return cbson_loads_from_string(bson);
  }

  static String encode(const Variant& mixture) {
    bson_t bson;
    bson_init(&bson);
    fillBSONWithArray(mixture.toArray(), &bson);

    const char* output = (const char*) bson_get_data(&bson);        
    String s = String(output, bson.len, CopyString);
    return s;
  }

  static String HHVM_STATIC_METHOD(Encoding, bson_encode, const Variant& mixture) {
    return encode(mixture);
  }

  void mongoExtension::_initBSON() {
    HHVM_STATIC_ME(Encoding, bson_decode);
    HHVM_STATIC_ME(Encoding, bson_encode);
  }

}
