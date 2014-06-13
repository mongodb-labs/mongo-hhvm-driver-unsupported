#include "bson_decode.h"
#include "contrib/encode.h"
#include "ext_mongo.h"
#include <iostream>
namespace HPHP {

  static String encode(const Variant& mixture) {
    bson_t bson = encodeToBSON(mixture);

    const char* output = (const char*) bson_get_data(&bson);        
    String s = String(output, bson.len, CopyString);
    return s;
  }

    static Array HHVM_FUNCTION(bson_decode, const String& bson) {
        return cbson_loads_from_string(bson);
    }

    static String HHVM_FUNCTION(bson_encode, const Variant& value) {
        return encode(value);
    }

    void MongoExtension::_initBSON() {
        HHVM_FE(bson_decode);
        HHVM_FE(bson_encode);
    }
}
