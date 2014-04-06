#include "hphp/runtime/base/base-includes.h"
#include <bson.h>

namespace HPHP {
  void int32ToBSON(const int32_t value, const char* key, bson_t* bson) {
    bson_append_int32(bson, key, -1, value);
  }

  void int64TOBSON(const int64_t value, const char* key, bson_t) {
    bson_append_int64(bson, key, -1, value);
  }

  void boolTOBSON(const int64_t value, const char* key, bson_t) {

  void arrayTOBSON(const int64_t value, const char* key, bson_t) {

  void doubleTOBSON(const int64_t value, const char* key, bson_t) {

  void utf8TOBSON(const int64_t value, const char* key, bson_t) {

  void nullTOBSON(const int64_t value, const char* key, bson_t) {

  void oidTOBSON(const int64_t value, const char* key, bson_t) {

  void documentTOBSON(const int64_t value, const char* key, bson_t) {
} // end of HPHP namespace
