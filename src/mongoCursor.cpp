#include <iostream>
#include "ext_mongo.h"

namespace HPHP {

////////////////////////////////////////////////////////////////////////////////
// class MongoCursor

static Array HHVM_METHOD(MongoCursor, current) {
  mongoc_cursor_t *cursor = get_cursor(this_)->get();
  const bson_t *doc;
  char *str;

  doc = mongoc_cursor_current (cursor);
  if (doc) {
    str = bson_as_json (doc, NULL);
    auto ret = Array::Create("return", str); //TODO: We should return the translated PHP Array here  
    bson_free (str);
    return ret;   
  } else {
    return Array::Create(); //Empty array means no valid document left
  }
}

static bool HHVM_METHOD(MongoCursor, hasNext) {
  bson_error_t error;
  mongoc_cursor_t *cursor = get_cursor(this_)->get();

  bool ret = mongoc_cursor_more(cursor);
  if (!mongoc_cursor_error (cursor, &error)) {
    return ret;
  } else {
    throw FatalErrorException(error.message);
  }
}

static void HHVM_METHOD(MongoCursor, next) {
  const bson_t *doc;
  mongoc_cursor_t *cursor = get_cursor(this_)->get();
  // if (!mongoc_cursor_next (cursor, &doc)) {
  //   if (mongoc_cursor_error (cursor, &error)) {
  //     throw FatalErrorException(error.message);
  //   }
  // }
  mongoc_cursor_next (cursor, &doc);   //Note: error would be catched by valid()
}

static void HHVM_METHOD(MongoCursor, reset) {
  if (get_cursor(this_)) {
    get_cursor(this_)->~MongocCursor();
  }
}

static void HHVM_METHOD(MongoCursor, rewind) {
  HHVM_MN(MongoCursor, reset)(this_);

  //TODO: need to test with null value
  auto connection = this_->o_realProp("connection", ObjectData::RealPropUnchecked, "MongoCursor")->toObject();
  auto ns = this_->o_realProp("ns", ObjectData::RealPropUnchecked, "MongoCursor")->toString();
  auto query = this_->o_realProp("query", ObjectData::RealPropUnchecked, "MongoCursor")->toArray();

  bson_t query_bs;
  bson_init(&query_bs);
  if (!query->empty()) {
    //Currently only supports "name" query
    bson_append_utf8(&query_bs, "name", 4, query[String("name")].toString().c_str(), query[String("name")].toString().length());
  }

//Parameters and their types:
//static void HHVM_METHOD(MongoCursor, __construct, const Object& connection, const String& ns, const Array& query, const Array& fields)
/*
MongocCursor(mongoc_client_t           *client,
                const char                *db_and_collection,
                mongoc_query_flags_t       flags,
                uint32_t                   skip,
                uint32_t                   limit,
                uint32_t                   batch_size,
                bool                       is_command,
                const bson_t              *query,
                const bson_t              *fields,
                const mongoc_read_prefs_t *read_prefs);
                */

  MongocCursor *cursor = new MongocCursor(get_client(connection)->get(), ns.c_str(), MONGOC_QUERY_NONE, 0, 0, 0, false, &query_bs, NULL, NULL);

  this_->o_set(s_mongoc_cursor, cursor, s_mongocursor);
  bson_destroy(&query_bs);

  this_->o_set("started_iterating", Variant(true), "MongoCursor");

  HHVM_MN(MongoCursor, next)(this_);
}

static bool HHVM_METHOD(MongoCursor, valid) {
  auto cur = HHVM_MN(MongoCursor, current)(this_);
  return !(cur->empty());
}

////////////////////////////////////////////////////////////////////////////////

void mongoExtension::_initMongoCursorClass() {
    HHVM_ME(MongoCursor, current);
    HHVM_ME(MongoCursor, hasNext);
    HHVM_ME(MongoCursor, next);
    HHVM_ME(MongoCursor, reset);
    HHVM_ME(MongoCursor, rewind);
    HHVM_ME(MongoCursor, valid);
}

} // namespace HPHP
