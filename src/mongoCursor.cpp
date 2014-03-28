#include <iostream>
#include "ext_mongo.h"

namespace HPHP {

////////////////////////////////////////////////////////////////////////////////
// class MongoCursor

static Array HHVM_METHOD(MongoCursor, current) {
  throw NotImplementedException("Not Implemented");
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
  bson_error_t error;
  const bson_t *doc;
  mongoc_cursor_t *cursor = get_cursor(this_)->get();
  if (!mongoc_cursor_next (cursor, &doc)) {
    if (mongoc_cursor_error (cursor, &error)) {
      throw FatalErrorException(error.message);
    }
  }
}

static void HHVM_METHOD(MongoCursor, reset) {
  throw NotImplementedException("Not Implemented");
}

static void HHVM_METHOD(MongoCursor, rewind) {
  mongoc_cursor_t *cursor = get_cursor(this_)->get();
  mongoc_cursor_t *rewinded_cursor = mongoc_cursor_clone(cursor);
  get_cursor(this_)->set(rewinded_cursor);
}

static bool HHVM_METHOD(MongoCursor, valid) {
  throw NotImplementedException("Not Implemented");
}

////////////////////////////////////////////////////////////////////////////////

void mongoExtension::_initMongoCursorClass() {
    HHVM_ME(MongoCursor, __construct);
    HHVM_ME(MongoCursor, current);
    HHVM_ME(MongoCursor, hasNext);
    HHVM_ME(MongoCursor, next);
    HHVM_ME(MongoCursor, reset);
    HHVM_ME(MongoCursor, rewind);
    HHVM_ME(MongoCursor, valid);
}

} // namespace HPHP
