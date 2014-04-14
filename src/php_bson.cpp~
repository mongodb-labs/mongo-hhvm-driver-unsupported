#include "hphp/runtime/base/base-includes.h"
#include <bson.h>

namespace HPHP {
  void int32ToBSON(const int32_t value, const char* key, bson_t* bson) {
    bson_append_int32(bson, key, -1, value);
  }

  void int64TOBSON(const int64_t value, const char* key, bson_t) {
    bson_append_int64(bson, key, -1, value);
  }

  void boolTOBSON(const bool value, const char* key, bson_t bson) {
    bson_append_bool(bson, key, -1, value);
  }

  void doubleTOBSON(const double value, const char* key, bson_t bson) {
    bson_append_double(bson, key, -1, value);
  }

  void stringTOBSON(const String& value, const char* key, bson_t bson) {
    bson_append_utf8(bson, key, strlen(key), value.c_str(), -1);
  }

  void nullTOBSON(const char* value, const char* key, bson_t bson) {
    bson_append_null(bson, key, -1, value);
  }

  void oidTOBSON(const Object& value, const char* key, bson_t bson) {
    bson_oid_t oid;
    bson_oid_init_from_string(&oid, value->o_get("$id").toString().c_str());
    bson_append_oid(bson, key, -1, &oid);
  }

  void documentTOBSON(const int64_t value, const char* key, bson_t bson) {
    bson_t child;
    bson_append_document_begin(bson, key, -1, &child);
    fillBSONWithArray(value, &child);
    bson_append_document_end(bson, &child);
  }

  void arrayTOBSON(const Array& value, const char* key, bson_t bson) {
    bson_t child;
    bson_append_array_begin(bson, key, -1, &child);
    fillBSONWithArray(value, &child);
    bson_append_array_end(bson, &child);
  }
} // end of HPHP namespace
